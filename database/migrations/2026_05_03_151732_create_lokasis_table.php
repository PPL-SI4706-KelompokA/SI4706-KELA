<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lokasis', function (Blueprint $table) {
            $table->id('id_lokasi'); // Harus ada sebelum dirujuk tabel Donasi[cite: 2]
            $table->string('alamat', 255);
            $table->string('kota', 255);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('lokasis'); }
};