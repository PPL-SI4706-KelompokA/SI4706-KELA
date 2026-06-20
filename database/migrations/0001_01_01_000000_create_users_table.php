<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user'); // Primary Key sesuai ERD
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('role', 20); // Donatur, Penerima, Admin
            $table->string('no_telp', 15);
            $table->string('alamat', 255);
            $table->string('status_verifikasi', 20)->default('Belum Verifikasi');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('users'); }
};