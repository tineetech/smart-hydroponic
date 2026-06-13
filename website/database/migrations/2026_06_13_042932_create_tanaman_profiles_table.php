<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanaman_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tanaman');
            $table->string('varietas')->nullable();
            $table->string('lokasi')->nullable();
            $table->integer('jumlah')->default(1);

            $table->date('tanggal_tanam')->nullable();
            $table->integer('estimasi_panen_hari')->nullable();

            $table->enum('fase_pertumbuhan', [
                'semai',
                'vegetatif',
                'pembungaan',
                'pembuahan',
                'panen'
            ])->default('semai');

            $table->json('parameter_ideal')->nullable();
            // contoh:
            // {
            //   "ph": {"min": 5.5, "max": 6.5},
            //   "tds": {"min": 800, "max": 1200},
            //   "suhu": {"min": 22, "max": 28},
            //   "suhu_air": {"min": 18, "max": 24},
            //   "kelembapan": {"min": 60, "max": 80},
            //   "cahaya_jam_per_hari": 16
            // }

            $table->boolean('is_aktif')->default(true);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanaman_profiles');
    }
};