#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <WiFi.h>

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
  char urlAkt[256];
};
#define EEPROM_SIZE sizeof(ConfigData)
#define MAGIC_NUMBER 0xABCD1234
#define LONG_PRESS_MS 3000

ConfigData config;
String url;
unsigned long lastSend = 0;
const long intervalSend = 3000;
// =======================
// LCD I2C 20x4
// =======================
LiquidCrystal_I2C lcd(0x27, 20, 4); // alamat umum 0x27
unsigned long lastTextChange = 0;
const long intervalText = 3000;
unsigned long lastLCD = 0;
const long intervalLCD = 500;
byte textIndex = 0;

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
#define SSR_PIN 23
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

unsigned long lastAktuator = 0;
const long intervalAktuator = 2000;
bool statusOutput = false;
String urlAktuator;

// =======================
// TIMER
// =======================
unsigned long lastADC = 0;
unsigned long lastUltrasonik = 0;
unsigned long lastSerial = 0;
unsigned long lastDHT = 0;
unsigned long lastDS18B20 = 0;

const long intervalADC = 500;
const long intervalUltrasonik = 1000;
const long intervalSerial = 2000;
const long intervalDHT = 2000;
const long intervalDS = 2000;

// =======================
// VARIABEL
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

    delay(3000);
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

void bacaAktuator() {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi disconnected");
    return;
  }

  HTTPClient http;
  http.begin(urlAktuator);
  http.setTimeout(5000);

  int httpCode = http.GET();

  if (httpCode > 0) {
    String response = http.getString();

    Serial.println("===== AKTUATOR =====");
    Serial.println(response);

    JsonDocument doc;
    DeserializationError error =
      deserializeJson(doc, response);

    if (!error) {
      String ssr =
        doc["aktuator"]["data"]["SSR Pompa Utama"] | "off";

      String phDown =
        doc["aktuator"]["data"]["Motor pH Down"] | "off";

      String phUp =
        doc["aktuator"]["data"]["Motor pH Up"] | "off";

      // SSR Pompa Utama
      digitalWrite(
        SSR_PIN,
        ssr.equalsIgnoreCase("on") ? HIGH : LOW
      );

      // Motor pH Down
      digitalWrite(
        MOTOR1_PIN,
        phDown.equalsIgnoreCase("on") ? HIGH : LOW
      );

      // Motor pH Up
      digitalWrite(
        MOTOR2_PIN,
        phUp.equalsIgnoreCase("on") ? HIGH : LOW
      );

      Serial.print("SSR       : ");
      Serial.println(ssr);

      Serial.print("pH Down   : ");
      Serial.println(phDown);

      Serial.print("pH Up     : ");
      Serial.println(phUp);
    } else {
      Serial.print("JSON Error: ");
      Serial.println(error.c_str());
    }
  } else {
    Serial.print("HTTP Error: ");
    Serial.println(http.errorToString(httpCode));
  }

  http.end();
}

void kirimDataAPI(float suhuAir,
                  float suhuUdara,
                  float kelembaban,
                  float jarak,
                  float levelAir,
                  float ph,
                  float tds) {

  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi disconnected");
    return;
  }

  HTTPClient http;
  http.begin(url);
  http.setTimeout(5000);

  http.addHeader("Content-Type", "application/json");

  JsonDocument doc;

  // ======================
  // SENSOR
  // ======================
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
  // hcNilai["level"] = 32;
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

  // Serial.println("===== JSON =====");
  // Serial.println(body);

  int httpCode = http.POST(body);

  // Serial.print("HTTP Code : ");
  // Serial.println(httpCode);

  if (httpCode > 0) {
    String response = http.getString();

    Serial.println("Response:");
    Serial.println(response);
  } else {
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
    strlcpy(config.ssid, "z", sizeof(config.ssid));
    strlcpy(config.password, "00000000", sizeof(config.password));
    strlcpy(config.url, "https://gdronic.cibunarhiap.id/api/batch-log/store", sizeof(config.url));
    strlcpy(config.urlAkt, "https://gdronic.cibunarhiap.id/api/aktuator/status", sizeof(config.urlAkt));
    saveConfig();
  }
}

WebServer server(80);

void handleRoot() {
  String html = "<!DOCTYPE html><html><head><meta name='viewport' content='width=device-width,initial-scale=1'>";
  html += "<style>body{font-family:sans-serif;padding:20px;max-width:400px;margin:auto}";
  html += "input{width:100%;padding:8px;margin:8px 0;box-sizing:border-box}";
  html += "input[type=submit]{background:#4CAF50;color:#fff;border:0;padding:10px;cursor:pointer}</style>";
  html += "</head><body><h2>Smart Hydroponic Config</h2>";
  html += "<form action='/save' method='POST'>";
  html += "SSID:<br><input type='text' name='ssid' value='" + String(config.ssid) + "'><br>";
  html += "Password:<br><input type='password' name='password' value='" + String(config.password) + "'><br>";
  html += "URL API:<br><input type='url' name='url' value='" + String(config.url) + "'><br>";
  html += "URL Aktuator:<br><input type='url' name='urlAkt' value='" + String(config.urlAkt) + "'><br>";
  html += "<input type='submit' value='Save & Restart'>";
  html += "</form></body></html>";
  server.send(200, "text/html", html);
}

void handleSave() {
  strlcpy(config.ssid, server.arg("ssid").c_str(), sizeof(config.ssid));
  strlcpy(config.password, server.arg("password").c_str(), sizeof(config.password));
  strlcpy(config.url, server.arg("url").c_str(), sizeof(config.url));
  strlcpy(config.urlAkt, server.arg("urlAkt").c_str(), sizeof(config.urlAkt));
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
// SETUP
// =======================
void setup() {
  Serial.begin(115200);

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
  urlAktuator = String(config.urlAkt);

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

  pinMode(SSR_PIN, OUTPUT);
  pinMode(MOTOR1_PIN, OUTPUT);
  pinMode(MOTOR2_PIN, OUTPUT);

  // Kondisi awal mati
  digitalWrite(SSR_PIN, LOW);
  digitalWrite(MOTOR1_PIN, LOW);
  digitalWrite(MOTOR2_PIN, LOW);

  connectWiFi();

  // ADS1115
  if (!ads.begin()) {
    Serial.println("ERROR: ADS1115 tidak ditemukan!");
    lcd.setCursor(0, 1);
    lcd.print("ADS1115 ERROR!");
    while (1);
  }
  ads.setGain(GAIN_ONE);

  // Ultrasonik
  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);

  // DHT22
  dht.begin();

  // DS18B20
  sensors.begin();

}

// =======================
// FUNGSI ULTRASONIK
// =======================
float bacaJarak() {
  unsigned int jarak = sonar.ping_median(5);

  if (jarak == 0) {
    return -1;
  }

  return jarak / US_ROUNDTRIP_CM;
}

// =======================
// LOOP
// =======================
void loop() {

  // =======================
  // ADS1115 (setiap 500ms)
  // A0 = PH
  // A1 = TDS
  // =======================
  if (millis() - lastADC >= intervalADC) {
    lastADC = millis();

    tdsRaw = ads.readADC_SingleEnded(1);
    tdsVoltage = ads.computeVolts(tdsRaw);
    tdsValue = (133.42 * pow(tdsVoltage, 3) - 255.86 * pow(tdsVoltage, 2) + 857.39 * tdsVoltage) * TDS_K_VALUE;
    ecValue = tdsValue / 500.0;

    phRaw = ads.readADC_SingleEnded(0);
    phVoltage = ads.computeVolts(phRaw);
    phValue = 3.5 * phVoltage;
    if (phValue < 0.0) phValue = 0.0;
    if (phValue > 9.0) phValue = 9.0;

    // Exponential moving average to dampen noise
    phValueFiltered += 0.3 * (phValue - phValueFiltered);
  }

  // =======================
  // ULTRASONIK (setiap 1000ms)
  // =======================
  if (millis() - lastUltrasonik >= intervalUltrasonik) {
    lastUltrasonik = millis();

    jarak = bacaJarak();
    if (jarak < 0) jarak = 0;
    levelAir = ((JARAK_KOSONG - jarak) / (JARAK_KOSONG - JARAK_PENUH)) * 100.0;
    if (levelAir < 0.0) levelAir = 0.0;
    if (levelAir > 100.0) levelAir = 100.0;
  }

  // =======================
  // DHT22 (setiap 2000ms)
  // =======================
  if (millis() - lastDHT >= intervalDHT) {
    lastDHT = millis();

    float t = dht.readTemperature();
    float h = dht.readHumidity();

    if (!isnan(t) && !isnan(h)) {
      suhuUdara = t;
      kelembaban = h;
    }
  }

  // =======================
  // DS18B20
  // =======================
  if (millis() - lastDS18B20 >= intervalDS) {
    lastDS18B20 = millis();

    sensors.requestTemperatures();
    float temp = sensors.getTempCByIndex(0);

    if (temp != -127.0) {
      // suhuAir = temp;
      suhuAir = round(temp * 10.0) / 10.0;
    }
  }

  // =======================
  // LCD 20x4 (setiap 500ms) - update sebelum kirim API
  // =======================
  if (millis() - lastLCD >= intervalLCD) {
    lastLCD = millis();

    char buf[21];

    // Baris 1 — all 20 chars exactly
    snprintf(buf, sizeof(buf), "pH: %4.1f   TDS:%4d ", phValueFiltered, (int)tdsValue);
    lcd.setCursor(0, 0);
    lcd.print(buf);

    // Baris 2
    snprintf(buf, sizeof(buf), "TA: %4.1fC   RH:%3d%% ", suhuUdara, (int)kelembaban);
    lcd.setCursor(0, 1);
    lcd.print(buf);

    // Baris 3
    snprintf(buf, sizeof(buf), "TW: %4.1fC   LV:%3.0f%%", suhuAir, levelAir);
    lcd.setCursor(0, 2);
    lcd.print(buf);

    // Ganti tulisan bawah setiap 3 detik
    if (millis() - lastTextChange >= intervalText) {
      lastTextChange = millis();
      textIndex++;
      if (textIndex >= 3) textIndex = 0;
    }

    // Baris 4 (center) - pad with spaces to clear old chars
    char buf2[21];
    int centerPos = (20 - statusText[textIndex].length()) / 2;
    snprintf(buf2, sizeof(buf2), "%*s%-*s", centerPos, "", 20 - centerPos, statusText[textIndex].c_str());
    lcd.setCursor(0, 3);
    lcd.print(buf2);
  }

  // =======================
  // KIRIM DATA API (setiap 3000ms)
  // =======================
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

    // kirimDataAPI(
    //   27.8, // suhuAir
    //   30.2, // suhuUdara
    //   65,   // kelembaban
    //   5.7,  // jarak
    //   4.4,  // ph
    //   4480  // tds
    // );
  }
  
  if (millis() - lastAktuator >= intervalAktuator) {
    lastAktuator = millis();
    bacaAktuator();
  }

  // =======================
  // PUSH BUTTON RESET
  // =======================
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

  // =======================
  // SERIAL MONITOR (setiap 2000ms)
  // =======================
  if (millis() - lastSerial >= intervalSerial) {
    lastSerial = millis();

    Serial.println("===== DATA SENSOR =====");

    Serial.print("pH Raw      : ");
    Serial.println(phRaw);

    Serial.print("pH Voltage  : ");
    Serial.print(phVoltage, 3);
    Serial.println(" V");

    Serial.print("TDS Raw     : ");
    Serial.println(tdsRaw);

    Serial.print("TDS Voltage : ");
    Serial.print(tdsVoltage, 3);
    Serial.println(" V");

    Serial.print("Jarak       : ");
    Serial.print(jarak);
    Serial.println(" cm");

    Serial.print("Suhu Udara  : ");
    Serial.print(suhuUdara);
    Serial.println(" C");

    Serial.print("Kelembaban  : ");
    Serial.print(kelembaban);
    Serial.println(" %");

    Serial.print("Suhu Air    : ");
    Serial.print(suhuAir);
    Serial.println(" C");

    Serial.print("Level Air   : ");
    Serial.print(levelAir, 1);
    Serial.println(" %");

    Serial.println("=======================\n");
  }

}