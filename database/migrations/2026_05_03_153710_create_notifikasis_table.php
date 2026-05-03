<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->foreignId('id_user')->references('id_user')->on('users');
            $table->foreignId('id_permintaan')->references('id_permintaan')->on('permintaans');
            $table->string('pesan', 255);
            $table->date('tanggal_notifikasi');
            $table->integer('status_baca')->default(0);
            $table->string('tipe_notifikasi', 50);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('notifikasis'); }
};