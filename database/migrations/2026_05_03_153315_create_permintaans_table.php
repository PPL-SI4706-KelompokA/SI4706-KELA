<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('permintaans', function (Blueprint $table) {
            $table->id('id_permintaan');
            $table->foreignId('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreignId('id_donasi')->references('id_donasi')->on('donasis')->onDelete('cascade');
            $table->integer('jumlah_permintaan');
            $table->string('catatan', 255)->nullable();
            $table->string('status', 20)->default('Pending');
            $table->date('tanggal_acc')->nullable();
            $table->date('tanggal_tolak')->nullable();
            $table->integer('id_permintaan_parent')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('permintaans'); }
};