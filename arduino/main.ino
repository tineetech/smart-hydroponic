#include <WiFi.h>
#include <WiFiManager.h>
#include <Wire.h>
#include <Adafruit_ADS1X15.h>
#include <DHT.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <LiquidCrystal_I2C.h>

// =======================
// LCD I2C 20x4
// =======================
LiquidCrystal_I2C lcd(0x27, 20, 4); // alamat umum 0x27

// =======================
// ADS1115
// =======================
Adafruit_ADS1115 ads;

// =======================
// HC-SR04
// =======================
#define TRIG_PIN 5
#define ECHO_PIN 18

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
// TIMER
// =======================
unsigned long lastDHT = 0;
unsigned long lastDS18B20 = 0;

const long intervalDHT = 2000;
const long intervalDS = 2000;

// =======================
// VARIABEL
// =======================
float suhuUdara = 0;
float kelembaban = 0;
float suhuAir = 0;
float phVoltage = 0;
float tdsVoltage = 0;

int16_t phRaw = 0;
int16_t tdsRaw = 0;

// =======================
// WIFI INIT
// =======================
void connectWiFi() {

  WiFiManager wm;

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Setup WiFi...");

  bool res = wm.autoConnect("Hydroponic-ESP32");

  if (!res) {
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("WiFi Failed");
    delay(3000);
    ESP.restart();
  }

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("WiFi Connected");

  lcd.setCursor(0, 1);
  lcd.print(WiFi.localIP());

  delay(3000);
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

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Semua Sensor OK");
  delay(2000);
}

// =======================
// FUNGSI ULTRASONIK
// =======================
float bacaJarak() {
  digitalWrite(TRIG_PIN, LOW);
  delayMicroseconds(2);

  digitalWrite(TRIG_PIN, HIGH);
  delayMicroseconds(10);

  digitalWrite(TRIG_PIN, LOW);

  long durasi = pulseIn(ECHO_PIN, HIGH, 30000);
  if (durasi == 0) return -1;

  return durasi / 58.3;
}

// =======================
// LOOP
// =======================
void loop() {

  // =======================
  // ADS1115
  // A0 = TDS
  // A1 = PH
  // =======================
  tdsRaw = ads.readADC_SingleEnded(0);
  tdsVoltage = ads.computeVolts(tdsRaw);

  phRaw = ads.readADC_SingleEnded(1);
  phVoltage = ads.computeVolts(phRaw);

  // =======================
  // ULTRASONIK
  // =======================
  float jarak = bacaJarak();

  // =======================
  // DHT22
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

    // if (temp != -127.0) {
      suhuAir = temp;
    // }
  }

  // =======================
  // SERIAL MONITOR
  // =======================
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

  Serial.println("=======================\n");

  // =======================
  // LCD 20x4
  // =======================
  lcd.clear();

  // -----------------------
  // BARIS 1
  // pH & TDS
  // -----------------------
  String line1Left = "pH:" + String(phVoltage, 2);
  String line1Right = "TDS:" + String(tdsVoltage, 2);

  lcd.setCursor(0, 0);
  lcd.print(line1Left);

  lcd.setCursor(20 - line1Right.length(), 0);
  lcd.print(line1Right);

  // -----------------------
  // BARIS 2
  // Suhu Udara & Humidity
  // -----------------------
  String line2Left = "T:" + String(suhuUdara, 1) + "C";
  String line2Right = "H:" + String(kelembaban, 0) + "%";

  lcd.setCursor(0, 1);
  lcd.print(line2Left);

  lcd.setCursor(20 - line2Right.length(), 1);
  lcd.print(line2Right);

  // -----------------------
  // BARIS 3
  // Suhu Air & Jarak
  // -----------------------
  String line3Left = "Air:" + String(suhuAir, 1) + "C";
  String line3Right = "J:" + String(jarak, 0) + "cm";

  lcd.setCursor(0, 2);
  lcd.print(line3Left);

  lcd.setCursor(20 - line3Right.length(), 2);
  lcd.print(line3Right);

  // -----------------------
  // BARIS 4 CENTER
  // -----------------------
  String line4 = "System OK";

  int centerPos = (20 - line4.length()) / 2;

  lcd.setCursor(centerPos, 3);
  lcd.print(line4);

  delay(1000);
}