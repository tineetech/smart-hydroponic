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
  delay(1000);
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

  // ===== ADS1115 =====
  int16_t raw = ads.readADC_SingleEnded(0);
  float tegangan = raw * 0.125 / 1000.0;

  // ===== ULTRASONIK =====
  float jarak = bacaJarak();

  // ===== DHT22 =====
  if (millis() - lastDHT >= intervalDHT) {
    lastDHT = millis();

    float t = dht.readTemperature();
    float h = dht.readHumidity();

    if (!isnan(t) && !isnan(h)) {
      suhuUdara = t;
      kelembaban = h;
    }
  }

  // ===== DS18B20 =====
  if (millis() - lastDS18B20 >= intervalDS) {
    lastDS18B20 = millis();

    sensors.requestTemperatures();
    float temp = sensors.getTempCByIndex(0);

    if (temp != -127.0) {
      suhuAir = temp;
    }
  }

  // ===== SERIAL OUTPUT =====
  Serial.println("===== DATA SENSOR =====");
  Serial.print("Tegangan: "); Serial.print(tegangan, 3); Serial.println(" V");
  Serial.print("Jarak: "); Serial.print(jarak); Serial.println(" cm");
  Serial.print("Suhu Udara: "); Serial.print(suhuUdara); Serial.println(" C");
  Serial.print("Kelembaban: "); Serial.print(kelembaban); Serial.println(" %");
  Serial.print("Suhu Air: "); Serial.print(suhuAir); Serial.println(" C");
  Serial.println("=======================\n");

  // ===== LCD OUTPUT (20x4) =====
  lcd.clear();

  // Baris 1
  lcd.setCursor(0, 0);
  lcd.print("V:");
  lcd.print(tegangan, 2);
  lcd.print(" J:");
  lcd.print(jarak, 0);

  // Baris 2
  lcd.setCursor(0, 1);
  lcd.print("T:");
  lcd.print(suhuUdara, 1);
  lcd.print("C H:");
  lcd.print(kelembaban, 0);
  lcd.print("%");

  // Baris 3
  lcd.setCursor(0, 2);
  lcd.print("Air:");
  lcd.print(suhuAir, 1);
  lcd.print("C");

  // Baris 4
  lcd.setCursor(0, 3);
  lcd.print("System OK");

  delay(1000);
}