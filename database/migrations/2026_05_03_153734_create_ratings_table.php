<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id('id_rating');
            $table->foreignId('id_user')->references('id_user')->on('users');
            $table->foreignId('id_permintaan')->references('id_permintaan')->on('permintaans');
            $table->integer('nilai_rating');
            $table->string('komentar', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('ratings'); }
};