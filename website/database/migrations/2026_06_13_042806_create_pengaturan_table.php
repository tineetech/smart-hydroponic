<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            // contoh: 'ai_config', 'lokasi', 'tanaman', 'notifikasi', 'sistem'

            $table->string('kunci')->unique();
            // contoh: 'gemini_api_key', 'openai_api_key', 'lokasi_kota', dll

            $table->text('nilai')->nullable();
            // nilai dalam bentuk string/json string

            $table->string('tipe_data')->default('string');
            // string, integer, float, boolean, json
            // untuk keperluan casting saat dibaca

            $table->boolean('is_encrypted')->default(false);
            // untuk field sensitif seperti API key

            $table->boolean('is_public')->default(false);
            // false = hanya backend yang boleh akses

            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->index('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};