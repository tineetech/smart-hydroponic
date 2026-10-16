// ============================================================
// MQTT CONFIG — Edit bagian ini sesuai kebutuhan
// ============================================================
#define MQTT_SERVER    "76.13.21.108"
#define MQTT_PORT      1883
#define MQTT_USER      "parkify"
#define MQTT_PASS      "parkify09"
#define MQTT_CLIENT_ID "ESP32_Gdronic_01"
// ============================================================

#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <WiFi.h>
#include <PubSubClient.h>

#include <NewPing.h>
#include <Wire.h>
#include <Adafruit_ADS1X15.h>
#include <DHT.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <LiquidCrystal_I2C.h>
#include <EEPROM.h>
#include <WebServer.h>

struct ConfigData {
  uint32_t magic;
  char ssid[32];
  char password[64];
  char url[256];
  char mqttBroker[128];   // alamat MQTT broker
  int  mqttPort;          // port MQTT
  char mqttUser[64];      // username MQTT (kosongkan jika tidak ada auth)
  char mqttPass[64];      // password MQTT
  char mqttClientId[64];  // client ID unik untuk broker
};
#define EEPROM_SIZE sizeof(ConfigData)
#define MAGIC_NUMBER 0xABCD1237  // dinaikkan agar EEPROM lama di-reset dengan field baru
#define LONG_PRESS_MS 3000

ConfigData config;
String url;

// =======================
// TIMER JADWAL PENGIRIMAN
// =======================
unsigned long lastSend = 0;
// Interval HTTP POST ditingkatkan ke 10 detik agar tidak memicu memory fragmentation TLS
const long intervalSend = 10000; 

// =======================
// LCD I2C 20x4
// =======================
LiquidCrystal_I2C lcd(0x27, 20, 4); // alamat umum 0x27
unsigned long lastTextChange = 0;
const long intervalText = 3000;
unsigned long lastLCD = 0;
const long intervalLCD = 1000; // Update LCD tiap 1 detik (stabil & hemat I2C traffic)
byte textIndex = 0;

// Timer auto-recovery LCD (re-sync 4-bit nibble jika terkena noise/glitch)
unsigned long lastLCDRecover = 0;
const long intervalLCDRecover = 30000; // setiap 30 detik auto refresh/init LCD

String statusText[] = {
  "System OK",
  "Gdronic",
  "Smart Hydroponic"
};

// =======================
// ADS1115
// =======================
Adafruit_ADS1115 ads;

// =======================
// HC-SR04
// =======================
#define TRIG_PIN 5
#define ECHO_PIN 18
#define MAX_DISTANCE 400 // cm
#define JARAK_KOSONG 22.0
#define JARAK_PENUH 11.0
#define TDS_K_VALUE 1.0

NewPing sonar(TRIG_PIN, ECHO_PIN, MAX_DISTANCE);

// =======================
// DHT22
// =======================
#define DHT_PIN 15
#define DHT_TYPE DHT22
DHT dht(DHT_PIN, DHT_TYPE);

// =======================
// DS18B20
// =======================
#define ONE_WIRE_BUS 4
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

// =======================
// RELAY SSR DAN MOTOR
// =======================
#define SSR_PIN    23
#define MOTOR1_PIN 25
#define MOTOR2_PIN 26

// =======================
// PUSH BUTTON RESET
// =======================
#define BUTTON_RESET_PIN 35
unsigned long lastButtonCheck = 0;
unsigned long buttonPressStart = 0;
bool buttonWasPressed = false;
const long intervalButton = 50;

// =======================
// MQTT
// =======================
// Topic command (subscribe) — server -> ESP32
#define MQTT_TOPIC_CMD_POMPA   "gdronic/aktuator/pompa_utama"
#define MQTT_TOPIC_CMD_PHDOWN  "gdronic/aktuator/ph_down"
#define MQTT_TOPIC_CMD_PHUP    "gdronic/aktuator/ph_up"

// Topic status (publish) — ESP32 -> server/dashboard
#define MQTT_TOPIC_STATUS_POMPA   "gdronic/status/pompa_utama"
#define MQTT_TOPIC_STATUS_PHDOWN  "gdronic/status/ph_down"
#define MQTT_TOPIC_STATUS_PHUP    "gdronic/status/ph_up"

WiFiClient   wifiClient;
PubSubClient mqttClient(wifiClient);

unsigned long lastMqttReconnect = 0;
const long    intervalMqttReconnect = 5000; // coba reconnect setiap 5 detik

// =======================
// KONTROL AKTUATOR (MQTT + Safety Fail-Safe Timer)
// =======================
// Proteksi fail-safe durasi (ms): jika perintah ON datang tanpa durasi,
// pompa/motor maksimal menyala selama durasi ini lalu auto-OFF agar tidak
// terjadi overheating, banjir, ataupun spike EMI terus-menerus.
#define FAILSAFE_MAX_DURASI_MS 180000UL // 3 menit maksimal fail-safe

struct AktuatorKontrol {
  const char* nama;
  uint8_t pin;
  const char* topicStatus;
  bool aktif;
  unsigned long matiPada; // millis() saat harus dimatikan otomatis
};

AktuatorKontrol aktSSR   = {"SSR Pompa Utama", SSR_PIN,    MQTT_TOPIC_STATUS_POMPA,  false, 0};
AktuatorKontrol aktMotor1 = {"Motor pH Down",  MOTOR1_PIN, MQTT_TOPIC_STATUS_PHDOWN, false, 0};
AktuatorKontrol aktMotor2 = {"Motor pH Up",    MOTOR2_PIN, MQTT_TOPIC_STATUS_PHUP,   false, 0};

// Publish status aktual GPIO ke broker (raw string ON/OFF)
void publishStatus(const char* topic, bool isOn) {
  if (mqttClient.connected()) {
    mqttClient.publish(topic, isOn ? "ON" : "OFF", true); // retain=true
    Serial.print("[MQTT] Publish -> ");
    Serial.print(topic);
    Serial.println(isOn ? " = ON" : " = OFF");
  }
}

// Nyalakan aktuator dengan durasi tertentu (atau fail-safe)
void nyalakanAktuator(AktuatorKontrol& a, unsigned long durasiMs = FAILSAFE_MAX_DURASI_MS) {
  a.aktif = true;
  a.matiPada = millis() + (durasiMs > 0 ? durasiMs : FAILSAFE_MAX_DURASI_MS);
  digitalWrite(a.pin, HIGH);
  publishStatus(a.topicStatus, true);

  Serial.print("[AKTUATOR] ");
  Serial.print(a.nama);
  Serial.print(" ON (durasi fail-safe: ");
  Serial.print((a.matiPada - millis()) / 1000);
  Serial.println(" detik)");
}

// Matikan aktuator dan update status
void matikanAktuator(AktuatorKontrol& a) {
  a.aktif = false;
  a.matiPada = 0;
  digitalWrite(a.pin, LOW);
  publishStatus(a.topicStatus, false);

  Serial.print("[AKTUATOR] ");
  Serial.print(a.nama);
  Serial.println(" OFF");
}

// Cek timer auto-off di setiap loop
void updateAktuatorKontrol() {
  AktuatorKontrol* daftar[] = { &aktSSR, &aktMotor1, &aktMotor2 };
  for (int i = 0; i < 3; i++) {
    AktuatorKontrol& a = *daftar[i];
    if (a.aktif && a.matiPada > 0 && (long)(millis() - a.matiPada) >= 0) {
      Serial.print("[AKTUATOR] Durasi habis -> ");
      matikanAktuator(a);
    }
  }
}

// =======================
// MQTT CALLBACK
// =======================
void mqttCallback(char* topic, byte* payload, unsigned int length) {
  char payloadStr[64];
  unsigned int copyLen = (length < sizeof(payloadStr) - 1) ? length : sizeof(payloadStr) - 1;
  memcpy(payloadStr, payload, copyLen);
  payloadStr[copyLen] = '\0';

  String topicStr = String(topic);

  Serial.print("[MQTT] Terima <- ");
  Serial.print(topicStr);
  Serial.print(" | Payload: ");
  Serial.println(payloadStr);

  String statusStr = "";
  long durasiDetik = 0;

  JsonDocument doc;
  DeserializationError err = deserializeJson(doc, payloadStr);

  if (!err && doc.containsKey("status")) {
    statusStr = doc["status"].as<String>();
    durasiDetik = doc["durasi"] | doc["durasi_detik"] | 0L;
    Serial.print("[MQTT] Mode JSON -> status: ");
    Serial.print(statusStr);
    Serial.print(", durasi: ");
    Serial.println(durasiDetik);
  } else {
    statusStr = String(payloadStr);
    Serial.print("[MQTT] Mode plain text -> status: ");
    Serial.println(statusStr);
  }

  statusStr.toUpperCase();
  bool nyala = statusStr.startsWith("ON");
  unsigned long durasiMs = durasiDetik > 0 ? (unsigned long)durasiDetik * 1000UL : FAILSAFE_MAX_DURASI_MS;

  if (topicStr == MQTT_TOPIC_CMD_POMPA) {
    if (nyala) nyalakanAktuator(aktSSR, durasiMs);
    else        matikanAktuator(aktSSR);

  } else if (topicStr == MQTT_TOPIC_CMD_PHDOWN) {
    if (nyala) nyalakanAktuator(aktMotor1, durasiMs);
    else        matikanAktuator(aktMotor1);

  } else if (topicStr == MQTT_TOPIC_CMD_PHUP) {
    if (nyala) nyalakanAktuator(aktMotor2, durasiMs);
    else        matikanAktuator(aktMotor2);
  }
}

// Hubungkan (atau reconnect) ke MQTT broker
bool mqttConnect() {
  if (!mqttClient.connected()) {
    Serial.print("[MQTT] Menghubungkan ke broker ");
    Serial.print(config.mqttBroker);
    Serial.print(":");
    Serial.println(config.mqttPort);

    const char* user = (strlen(config.mqttUser) > 0) ? config.mqttUser : nullptr;
    const char* pass = (strlen(config.mqttPass) > 0) ? config.mqttPass : nullptr;

    bool ok = mqttClient.connect(config.mqttClientId, user, pass);

    if (ok) {
      Serial.println("[MQTT] Connected!");

      mqttClient.subscribe(MQTT_TOPIC_CMD_POMPA);
      mqttClient.subscribe(MQTT_TOPIC_CMD_PHDOWN);
      mqttClient.subscribe(MQTT_TOPIC_CMD_PHUP);

      // Publish status awal semua aktuator saat baru connect
      publishStatus(aktSSR.topicStatus,    digitalRead(aktSSR.pin)    == HIGH);
      publishStatus(aktMotor1.topicStatus, digitalRead(aktMotor1.pin) == HIGH);
      publishStatus(aktMotor2.topicStatus, digitalRead(aktMotor2.pin) == HIGH);

    } else {
      Serial.print("[MQTT] Gagal connect, rc=");
      Serial.println(mqttClient.state());
    }

    return ok;
  }
  return true;
}

// Panggil di loop() — jaga koneksi MQTT tetap hidup tanpa blocking lama
void handleMqtt() {
  if (WiFi.status() != WL_CONNECTED) return;

  if (!mqttClient.connected()) {
    unsigned long now = millis();
    if (now - lastMqttReconnect >= intervalMqttReconnect) {
      lastMqttReconnect = now;
      mqttConnect();
    }
  } else {
    mqttClient.loop();
  }
}

// =======================
// KIRIM DATA KE HTTP API (Batch Log)
// =======================
void kirimDataAPI(float suhuAir,
                  float suhuUdara,
                  float kelembaban,
                  float jarak,
                  float levelAir,
                  float ph,
                  float tds) {

  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("[HTTP] WiFi disconnected, skip kirim");
    return;
  }

  // Heap Guard: Cek sisa RAM sebelum inisialisasi TLS HTTPS.
  // Ini mencegah crash dan brownout jika RAM menipis akibat koneksi ganda.
  uint32_t freeMem = ESP.getFreeHeap();
  if (freeMem < 25000) {
    Serial.print("[HTTP] Heap kritis (");
    Serial.print(freeMem);
    Serial.println(" B). Skip pengiriman siklus ini agar tidak crash.");
    return;
  }

  HTTPClient http;
  http.begin(url);
  http.setTimeout(2500); // 2.5 detik cukup untuk TLS tanpa membuat loop macet
  http.setReuse(false);  // Pastikan resource TLS dibebaskan secara bersih

  http.addHeader("Content-Type", "application/json");

  JsonDocument doc;

  // SENSOR
  JsonObject sensor = doc["sensor"].to<JsonObject>();
  JsonObject sensorData = sensor["data"].to<JsonObject>();

  // DS18B20
  JsonObject ds = sensorData["DS18B20 Suhu Air"].to<JsonObject>();
  ds["nilai"] = suhuAir;
  ds["kualitas_data"] = "normal";
  ds["sudah_diproses"] = true;

  // DHT22
  JsonObject dht = sensorData["DHT22 Suhu Udara"].to<JsonObject>();
  JsonObject dhtNilai = dht["nilai"].to<JsonObject>();
  dhtNilai["suhu"] = round(suhuUdara * 10.0) / 10.0;
  dhtNilai["kelembapan"] = round(kelembaban * 10.0) / 10.0;
  dht["kualitas_data"] = "normal";
  dht["sudah_diproses"] = true;

  // HC-SR04
  JsonObject hc = sensorData["HC-SR04 Level Air"].to<JsonObject>();
  JsonObject hcNilai = hc["nilai"].to<JsonObject>();
  hcNilai["level"] = (int)levelAir;
  hcNilai["jarak_cm"] = jarak;
  hc["kualitas_data"] = "normal";
  hc["sudah_diproses"] = true;

  // pH
  JsonObject phObj = sensorData["Probe pH Larutan"].to<JsonObject>();
  phObj["nilai"] = round(ph * 10.0) / 10.0;
  phObj["kualitas_data"] = "normal";
  phObj["sudah_diproses"] = true;

  // TDS
  JsonObject tdsObj = sensorData["EC/TDS Probe"].to<JsonObject>();
  JsonObject tdsNilai = tdsObj["nilai"].to<JsonObject>();
  float ecCalc = tds / 500.0;
  tdsNilai["tds"] = (int)tds;
  tdsNilai["ec"] = round(ecCalc * 10000.0) / 10000.0;
  tdsObj["kualitas_data"] = "normal";
  tdsObj["sudah_diproses"] = true;

  String body;
  serializeJson(doc, body);

  int httpCode = http.POST(body);

  if (httpCode > 0) {
    String response = http.getString();
    Serial.print("[HTTP] POST Sukses, Code: ");
    Serial.println(httpCode);
    Serial.println(response);
  } else {
    Serial.print("[HTTP] POST Error: ");
    Serial.println(http.errorToString(httpCode));
  }

  http.end();
}

// =======================
// EEPROM CONFIG
// =======================
void saveConfig() {
  EEPROM.put(0, config);
  EEPROM.commit();
}

void loadConfig() {
  EEPROM.get(0, config);
  if (config.magic != MAGIC_NUMBER) {
    config.magic = MAGIC_NUMBER;
    strlcpy(config.ssid,        "z",                                                      sizeof(config.ssid));
    strlcpy(config.password,    "00000000",                                               sizeof(config.password));
    strlcpy(config.url,         "https://gdronic.christoperbale.com/api/batch-log/store", sizeof(config.url));
    strlcpy(config.mqttBroker,  MQTT_SERVER,                                              sizeof(config.mqttBroker));
    config.mqttPort = MQTT_PORT;
    strlcpy(config.mqttUser,    MQTT_USER,                                                sizeof(config.mqttUser));
    strlcpy(config.mqttPass,    MQTT_PASS,                                                sizeof(config.mqttPass));
    strlcpy(config.mqttClientId, MQTT_CLIENT_ID,                                          sizeof(config.mqttClientId));
    saveConfig();
  }
}

WebServer server(80);

void handleRoot() {
  String html = "<!DOCTYPE html><html><head><meta name='viewport' content='width=device-width,initial-scale=1'>";
  html += "<style>body{font-family:sans-serif;padding:20px;max-width:400px;margin:auto}";
  html += "input{width:100%;padding:8px;margin:8px 0;box-sizing:border-box}";
  html += "input[type=submit]{background:#4CAF50;color:#fff;border:0;padding:10px;cursor:pointer}";
  html += "hr{margin:16px 0}";
  html += "h3{margin:12px 0 4px}</style>";
  html += "</head><body><h2>Smart Hydroponic Config</h2>";
  html += "<form action='/save' method='POST'>";

  html += "<h3>WiFi</h3>";
  html += "SSID:<br><input type='text'     name='ssid'        value='" + String(config.ssid)        + "'><br>";
  html += "Password:<br><input type='password' name='password' value='" + String(config.password)   + "'><br>";

  html += "<hr><h3>API</h3>";
  html += "URL API:<br><input type='url'   name='url'         value='" + String(config.url)         + "'><br>";

  html += "<hr><h3>MQTT Broker</h3>";
  html += "Server (IP/hostname):<br><input type='text'   name='mqttBroker'   value='" + String(config.mqttBroker)   + "'><br>";
  html += "Port:<br><input           type='number' name='mqttPort'     value='" + String(config.mqttPort)     + "'><br>";
  html += "Username:<br><input       type='text'   name='mqttUser'     value='" + String(config.mqttUser)     + "'><br>";
  html += "Password:<br><input       type='password' name='mqttPass'   value='" + String(config.mqttPass)     + "'><br>";
  html += "Client ID:<br><input      type='text'   name='mqttClientId' value='" + String(config.mqttClientId) + "'><br>";

  html += "<br><input type='submit' value='Save &amp; Restart'>";
  html += "</form></body></html>";
  server.send(200, "text/html", html);
}

void handleSave() {
  strlcpy(config.ssid,         server.arg("ssid").c_str(),         sizeof(config.ssid));
  strlcpy(config.password,     server.arg("password").c_str(),     sizeof(config.password));
  strlcpy(config.url,          server.arg("url").c_str(),          sizeof(config.url));
  strlcpy(config.mqttBroker,   server.arg("mqttBroker").c_str(),   sizeof(config.mqttBroker));
  strlcpy(config.mqttUser,     server.arg("mqttUser").c_str(),     sizeof(config.mqttUser));
  strlcpy(config.mqttPass,     server.arg("mqttPass").c_str(),     sizeof(config.mqttPass));
  strlcpy(config.mqttClientId, server.arg("mqttClientId").c_str(), sizeof(config.mqttClientId));
  config.mqttPort = server.arg("mqttPort").toInt();
  if (config.mqttPort <= 0) config.mqttPort = 1883;
  config.magic = MAGIC_NUMBER;
  saveConfig();

  String html = "<!DOCTYPE html><html><head><meta name='viewport' content='width=device-width,initial-scale=1'>";
  html += "<style>body{font-family:sans-serif;padding:20px;text-align:center}</style>";
  html += "</head><body><h2>Saved!</h2><p>Restarting...</p></body></html>";
  server.send(200, "text/html", html);
  delay(1000);
  ESP.restart();
}

void enterAPMode() {
  WiFi.mode(WIFI_AP);
  WiFi.softAPConfig(IPAddress(192,168,4,1), IPAddress(192,168,4,1), IPAddress(255,255,255,0));
  WiFi.softAP("Smart-Hydroponic");

  server.on("/", handleRoot);
  server.on("/save", HTTP_POST, handleSave);
  server.begin();

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("AP: Smart-Hydroponic");
  lcd.setCursor(0, 1);
  lcd.print("IP: 192.168.4.1");

  Serial.println("===== AP CONFIG MODE =====");
  Serial.println("SSID: Smart-Hydroponic");
  Serial.println("IP  : 192.168.4.1");

  while (true) {
    server.handleClient();
    delay(10);
  }
}

// =======================
// WIFI INIT
// =======================
void connectWiFi() {
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Connecting...");

  WiFi.mode(WIFI_STA);
  WiFi.begin(config.ssid, config.password);

  Serial.print("Connecting to WiFi");

  int retry = 0;
  while (WiFi.status() != WL_CONNECTED && retry < 30) {
    delay(500);
    Serial.print(".");
    lcd.setCursor(retry % 20, 1);
    lcd.print(".");
    retry++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nWiFi Connected!");
    Serial.print("IP Address: ");
    Serial.println(WiFi.localIP());

    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("WiFi Connected");
    lcd.setCursor(0, 1);
    lcd.print(WiFi.localIP());

    delay(2000);
  } else {
    Serial.println("\nFailed to connect!");
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("WiFi Failed");
    lcd.setCursor(0, 1);
    lcd.print("Entering AP Mode");
    delay(2000);
    enterAPMode();
  }
}

// =======================
// SETUP
// =======================
void setup() {
  Serial.begin(115200);

  // Inisialisasi Bus I2C dengan proteksi kebal noise & timeout
  Wire.begin();
  Wire.setClock(100000); // 100 kHz standard mode (jauh lebih stabil dari 400kHz terhadap noise kabel/relay)
  Wire.setTimeOut(100);  // Cegah bus I2C freeze selamanya jika terjadi glitch

  // LCD
  lcd.init();
  lcd.backlight();
  lcd.setCursor(0, 0);
  lcd.print("System Starting...");

  pinMode(BUTTON_RESET_PIN, INPUT);

  // EEPROM
  EEPROM.begin(EEPROM_SIZE);
  loadConfig();
  url = String(config.url);

  // Button check at boot → AP config mode
  if (digitalRead(BUTTON_RESET_PIN) == HIGH) {
    delay(100);
    if (digitalRead(BUTTON_RESET_PIN) == HIGH) {
      lcd.clear();
      lcd.print("AP Config Mode");
      delay(1000);
      enterAPMode();
    }
  }

  pinMode(SSR_PIN,    OUTPUT);
  pinMode(MOTOR1_PIN, OUTPUT);
  pinMode(MOTOR2_PIN, OUTPUT);

  // Kondisi awal mati
  digitalWrite(SSR_PIN,    LOW);
  digitalWrite(MOTOR1_PIN, LOW);
  digitalWrite(MOTOR2_PIN, LOW);

  // Ultrasonik
  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);

  // DHT22
  dht.begin();

  // DS18B20
  sensors.begin();

  // WiFi Connect
  connectWiFi();

  // Inisialisasi ADS1115 (setelah WiFi connect agar bus I2C tidak terganggu)
  if (!ads.begin()) {
    Serial.println("ERROR: ADS1115 tidak ditemukan pada bus I2C!");
    lcd.setCursor(0, 1);
    lcd.print("ADS1115 ERROR!");
  } else {
    ads.setGain(GAIN_ONE);
    Serial.println("ADS1115 OK");
  }

  // =======================
  // MQTT INIT
  // =======================
  mqttClient.setServer(config.mqttBroker, config.mqttPort);
  mqttClient.setCallback(mqttCallback);
  mqttClient.setSocketTimeout(2); // Timeout 2 detik (bukan 15s) agar loop tidak freeze saat disconnect
  mqttClient.setKeepAlive(15);
  mqttClient.setBufferSize(512);
  mqttConnect();

  // Staggering timer: Offset awal LCD 500ms agar tidak pernah bentrok dengan ADC pada I2C
  lastLCD = millis() + 500;
  lastLCDRecover = millis();
}

// =======================
// FUNGSI ULTRASONIK
// =======================
float bacaJarak() {
  unsigned int jarak = sonar.ping_median(5);
  if (jarak == 0) return -1;
  return (float)jarak / US_ROUNDTRIP_CM;
}

// =======================
// VARIABEL SENSOR
// =======================
float suhuUdara = 0;
float kelembaban = 0;
float suhuAir = 0;
float phVoltage = 0;
float phValue = 0;
float tdsVoltage = 0;
float tdsValue = 0;
float ecValue = 0;
float jarak = 0;
float levelAir = 0;
float phValueFiltered = 7.0;

int16_t phRaw = 0;
int16_t tdsRaw = 0;

// =======================
// TIMER SENSOR (Diberi interval stabil & tidak bertumpuk)
// =======================
unsigned long lastADC        = 0;
unsigned long lastUltrasonik = 0;
unsigned long lastSerial     = 0;
unsigned long lastDHT        = 0;
unsigned long lastDS18B20    = 0;

const long intervalADC        = 1000; // 1 detik
const long intervalUltrasonik = 1500; // 1.5 detik
const long intervalSerial     = 2000; // 2 detik
const long intervalDHT        = 2500; // 2.5 detik (DHT22 butuh minimal 2 detik jeda baca)
const long intervalDS         = 2000; // 2 detik

// =======================
// LOOP UTAMA
// =======================
void loop() {

  // 1. Update aktuator fail-safe timer (matikan aktuator jika durasi habis)
  updateAktuatorKontrol();

  // 2. MQTT — jaga koneksi & proses pesan masuk
  handleMqtt();

  // 3. AUTO-RECOVERY LCD (Setiap 30 detik)
  // Menjaga LCD dari fenomena "Blank / Tulisan Random dan Aneh"
  // Jika HD44780 kehilangan sinkronisasi 4-bit akibat lonjakan tegangan/noise,
  // rutin ini secara otomatis mengembalikan LCD ke kondisi normal tanpa merestart ESP32.
  if (millis() - lastLCDRecover >= intervalLCDRecover) {
    lastLCDRecover = millis();
    lcd.init();
    lcd.backlight();
  }

  // 4. ADS1115 (Setiap 1000ms)
  // A0 = PH, A1 = TDS
  if (millis() - lastADC >= intervalADC) {
    lastADC = millis();

    tdsRaw     = ads.readADC_SingleEnded(1);
    tdsVoltage = ads.computeVolts(tdsRaw);
    tdsValue   = (133.42 * pow(tdsVoltage, 3) - 255.86 * pow(tdsVoltage, 2) + 857.39 * tdsVoltage) * TDS_K_VALUE;
    if (tdsValue < 0.0) tdsValue = 0.0;
    ecValue    = tdsValue / 500.0;

    phRaw     = ads.readADC_SingleEnded(0);
    phVoltage = ads.computeVolts(phRaw);
    phValue   = 3.5 * phVoltage;
    if (phValue < 0.0) phValue = 0.0;
    if (phValue > 14.0) phValue = 14.0;

    // Exponential moving average untuk meredam noise
    phValueFiltered += 0.3 * (phValue - phValueFiltered);
  }

  // 5. ULTRASONIK (Setiap 1500ms)
  if (millis() - lastUltrasonik >= intervalUltrasonik) {
    lastUltrasonik = millis();

    jarak    = bacaJarak();
    if (jarak < 0) jarak = 0;
    levelAir = ((JARAK_KOSONG - jarak) / (JARAK_KOSONG - JARAK_PENUH)) * 100.0;
    if (levelAir <   0.0) levelAir = 0.0;
    if (levelAir > 100.0) levelAir = 100.0;
  }

  // 6. DHT22 (Setiap 2500ms)
  if (millis() - lastDHT >= intervalDHT) {
    lastDHT = millis();

    float t = dht.readTemperature();
    float h = dht.readHumidity();

    if (!isnan(t) && !isnan(h)) {
      suhuUdara = t;
      kelembaban = h;
    }
  }

  // 7. DS18B20 (Setiap 2000ms)
  if (millis() - lastDS18B20 >= intervalDS) {
    lastDS18B20 = millis();

    sensors.requestTemperatures();
    float temp = sensors.getTempCByIndex(0);

    if (temp != -127.0 && temp > -50.0 && temp < 100.0) {
      suhuAir = round(temp * 10.0) / 10.0;
    }
  }

  // 8. LCD 20x4 (Setiap 1000ms, berselang-seling dengan ADC agar bus I2C tidak tabrakan)
  if (millis() - lastLCD >= intervalLCD) {
    lastLCD = millis();

    char buf[21];

    // Baris 1: pH & TDS
    snprintf(buf, sizeof(buf), "pH: %4.1f   TDS:%4d ", phValueFiltered, (int)tdsValue);
    lcd.setCursor(0, 0);
    lcd.print(buf);

    // Baris 2: Suhu Udara & Kelembaban
    snprintf(buf, sizeof(buf), "TA: %4.1fC   RH:%3d%% ", suhuUdara, (int)kelembaban);
    lcd.setCursor(0, 1);
    lcd.print(buf);

    // Baris 3: Suhu Air & Level Air
    snprintf(buf, sizeof(buf), "TW: %4.1fC   LV:%3.0f%%", suhuAir, levelAir);
    lcd.setCursor(0, 2);
    lcd.print(buf);

    // Ganti tulisan banner bawah setiap 3 detik
    if (millis() - lastTextChange >= intervalText) {
      lastTextChange = millis();
      textIndex++;
      if (textIndex >= 3) textIndex = 0;
    }

    // Baris 4 (center)
    char buf2[21];
    int centerPos = (20 - statusText[textIndex].length()) / 2;
    if (centerPos < 0) centerPos = 0;
    snprintf(buf2, sizeof(buf2), "%*s%-*s", centerPos, "", 20 - centerPos, statusText[textIndex].c_str());
    lcd.setCursor(0, 3);
    lcd.print(buf2);
  }

  // 9. KIRIM DATA SENSOR via HTTP API (Setiap 10000ms)
  if (millis() - lastSend >= intervalSend) {
    lastSend = millis();

    kirimDataAPI(
      suhuAir,
      suhuUdara,
      kelembaban,
      jarak,
      levelAir,
      phValueFiltered,
      tdsValue
    );
  }

  // 10. PUSH BUTTON RESET
  if (millis() - lastButtonCheck >= intervalButton) {
    lastButtonCheck = millis();
    bool btnState = digitalRead(BUTTON_RESET_PIN) == HIGH;

    if (btnState && !buttonWasPressed) {
      buttonPressStart = millis();
      buttonWasPressed = true;
    }

    if (!btnState && buttonWasPressed) {
      unsigned long pressDuration = millis() - buttonPressStart;
      buttonWasPressed = false;

      if (pressDuration >= LONG_PRESS_MS) {
        Serial.println("Long press - entering AP config mode...");
        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("AP Config Mode");
        delay(1000);
        enterAPMode();
      } else {
        Serial.println("Short press - restarting...");
        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("Manual Reset...");
        delay(500);
        ESP.restart();
      }
    }
  }

  // 11. SERIAL MONITOR (Setiap 2000ms)
  if (millis() - lastSerial >= intervalSerial) {
    lastSerial = millis();

    Serial.println("===== DATA SENSOR =====");
    Serial.print("pH Raw      : "); Serial.println(phRaw);
    Serial.print("pH Voltage  : "); Serial.print(phVoltage, 3); Serial.println(" V");
    Serial.print("pH Filtered : "); Serial.println(phValueFiltered, 2);
    Serial.print("TDS Raw     : "); Serial.println(tdsRaw);
    Serial.print("TDS Voltage : "); Serial.print(tdsVoltage, 3); Serial.println(" V");
    Serial.print("TDS Value   : "); Serial.print(tdsValue); Serial.println(" ppm");
    Serial.print("Jarak       : "); Serial.print(jarak); Serial.println(" cm");
    Serial.print("Suhu Udara  : "); Serial.print(suhuUdara); Serial.println(" C");
    Serial.print("Kelembaban  : "); Serial.print(kelembaban); Serial.println(" %");
    Serial.print("Suhu Air    : "); Serial.print(suhuAir); Serial.println(" C");
    Serial.print("Level Air   : "); Serial.print(levelAir, 1); Serial.println(" %");

    Serial.println("----- AKTUATOR -----");
    Serial.print("Pompa Utama : "); Serial.println(aktSSR.aktif ? "ON" : "OFF");
    Serial.print("Motor pH Down: "); Serial.println(aktMotor1.aktif ? "ON" : "OFF");
    Serial.print("Motor pH Up : "); Serial.println(aktMotor2.aktif ? "ON" : "OFF");

    Serial.print("Free Heap   : "); Serial.print(ESP.getFreeHeap()); Serial.println(" Bytes");
    Serial.println("=======================\n");
  }

}