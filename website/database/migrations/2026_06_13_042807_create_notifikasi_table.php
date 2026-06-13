<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();

            $table->enum('tipe', [
                'alert',        // nilai sensor melebihi batas
                'info',         // informasi umum sistem
                'ai_insight',   // rekomendasi dari AI
                'jadwal',       // notifikasi eksekusi jadwal
                'error'         // error sistem/sensor
            ]);

            $table->enum('level', ['low', 'medium', 'high', 'critical'])
                  ->default('low');

            $table->string('judul');
            $table->text('pesan');

            $table->foreignId('id_komponen')
                  ->nullable()
                  ->constrained('komponen')
                  ->onDelete('set null');

            $table->json('data_tambahan')->nullable();
            // data konteks tambahan, misal nilai sensor saat alert terjadi

            $table->boolean('sudah_dibaca')->default(false);
            $table->timestamp('dibaca_at')->nullable();

            $table->enum('channel', ['database', 'telegram', 'email', 'whatsapp'])
                  ->default('database');

            $table->boolean('terkirim')->default(false);
            $table->timestamp('terkirim_at')->nullable();

            $table->timestamps();

            $table->index(['sudah_dibaca', 'created_at']);
            $table->index('level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};