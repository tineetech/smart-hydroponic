<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensor_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_komponen')
                  ->constrained('komponen')
                  ->onDelete('cascade');

            $table->json('nilai');
            // contoh isi berdasarkan jenis sensor:
            // DHT22      -> {"suhu": 28.5, "kelembapan": 65.2}
            // pH Sensor  -> {"ph": 6.1}
            // TDS Meter  -> {"tds": 850, "ec": 1.7}
            // DS18B20    -> {"suhu_air": 24.3}
            // Water Level-> {"level": 75.5, "jarak_cm": 10.2}
            // LDR/BH1750 -> {"cahaya_lux": 12500}
            // DO Sensor  -> {"dissolved_oxygen": 7.2}

            $table->enum('kualitas_data', ['normal', 'warning', 'critical', 'error'])
                  ->default('normal');
            // otomatis dihitung berdasarkan batas_nilai di tabel komponen

            $table->boolean('sudah_diproses')->default(false);
            // flag untuk AI processing (Gemini/GPT)

            $table->timestamps();

            // Index untuk query monitoring & grafik lebih cepat
            $table->index(['id_komponen', 'created_at']);
            $table->index('kualitas_data');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_logs');
    }
};