<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('donasis', function (Blueprint $table) {
            $table->id('id_donasi');
            $table->foreignId('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreignId('id_lokasi')->references('id_lokasi')->on('lokasis');
            $table->string('nama_makanan', 100);
            $table->string('kategori', 50);
            $table->integer('jumlah');
            $table->date('tanggal_kadaluarsa');
            $table->string('deskripsi', 255);
            $table->string('foto_url', 255)->nullable();
            $table->string('status_donasi', 20)->default('Available'); // Sesuai Mockup[cite: 2]
            $table->string('status_verifikasi', 20)->default('Pending');
            $table->integer('verified_by')->nullable();
            $table->date('tanggal_verifikasi')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('donasis'); }
};