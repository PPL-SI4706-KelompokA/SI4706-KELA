<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('riwayat_donasis', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->foreignId('id_donasi')->references('id_donasi')->on('donasis');
            $table->foreignId('id_permintaan')->references('id_permintaan')->on('permintaans');
            $table->foreignId('id_user')->references('id_user')->on('users');
            $table->string('status_pengambilan', 20);
            $table->date('tanggal_pembelian');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('riwayat_donasis'); }
};