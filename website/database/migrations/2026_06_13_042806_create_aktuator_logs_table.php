<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aktuator_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_komponen')
                  ->constrained('komponen')
                  ->onDelete('cascade');

            $table->enum('status', ['on', 'off'])->default('off');

            $table->integer('durasi_detik')->nullable();
            // durasi aktuator aktif (null = manual/tidak terbatas)

            $table->integer('nilai_pwm')->nullable();
            // 0-255, untuk aktuator yang support PWM (fan, dimmer LED)

            $table->enum('sumber_perintah', [
                'manual',       // dari user dashboard
                'otomatis',     // dari jadwal
                'ai',           // dari rekomendasi AI
                'threshold'     // dari trigger batas nilai sensor
            ])->default('manual');

            $table->text('catatan')->nullable();
            // alasan kenapa diaktifkan, misal: "pH terlalu tinggi, pompa nutrisi dinyalakan"

            $table->foreignId('triggered_by_log_id')
                  ->nullable()
                  ->constrained('sensor_logs')
                  ->onDelete('set null');
            // relasi ke sensor_log yang memicu aktuator ini (jika otomatis)

            $table->timestamps();

            $table->index(['id_komponen', 'created_at']);
            $table->index('sumber_perintah');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aktuator_logs');
    }
};