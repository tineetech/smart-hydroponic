<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komponen', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komponen');
            $table->enum('jenis_komponen', ['sensor', 'aktuator']);
            $table->string('tipe_komponen')->nullable();
            // contoh tipe: DHT22, DS18B20, TDS Meter, pH Sensor,
            //              Water Pump, LED Grow Light, Solenoid Valve, Fan

            $table->json('pin_data')->nullable();
            // contoh isi:
            // sensor  -> {"sda": 21, "scl": 22}  atau  {"pin": 4}
            // aktuator-> {"pin": 26, "active_low": true}

            $table->string('satuan')->nullable();
            // contoh: °C, %, ppm, pH, lux — khusus sensor

            $table->json('batas_nilai')->nullable();
            // contoh: {"min": 5.5, "max": 6.5, "optimal_min": 5.8, "optimal_max": 6.2}
            // berguna untuk trigger alert otomatis

            $table->string('lokasi')->nullable();
            // contoh: "Nutrient Tank A", "Growing Bed 1"

            $table->string('protokol')->nullable();
            // contoh: I2C, OneWire, Analog, Digital, PWM, UART

            $table->text('deskripsi')->nullable();
            $table->enum('status', ['active', 'non-active'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komponen');
    }
};