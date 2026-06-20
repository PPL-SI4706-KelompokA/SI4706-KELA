<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pemberitahuans', function (Blueprint $table) {
            $table->id('id_pemberitahuan');
            $table->string('judul', 100);
            $table->text('pesan');
            $table->string('tipe', 50)->default('Maintenance'); // Maintenance, Informasi
            $table->dateTime('tanggal_mulai')->nullable();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pemberitahuans');
    }
};
