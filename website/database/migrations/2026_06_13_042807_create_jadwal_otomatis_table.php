<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_otomatis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jadwal');

            $table->foreignId('id_komponen')
                  ->constrained('komponen')
                  ->onDelete('cascade');

            $table->string('aksi');
            // contoh: 'on', 'off', 'toggle'

            $table->integer('nilai_pwm')->nullable();

            $table->string('cron_expression');
            // format cron: "0 6 * * *" = setiap hari jam 06:00
            // atau gunakan field terpisah ↓

            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->json('hari_aktif')->nullable();
            // contoh: ["senin","rabu","jumat"] atau ["*"] untuk setiap hari

            $table->integer('durasi_menit')->nullable();

            $table->boolean('is_aktif')->default(true);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_otomatis');
    }
};