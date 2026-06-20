<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesans', function (Blueprint $table) {
            $table->id('id_pesan');
            $table->foreignId('id_pengirim')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreignId('id_penerima')->references('id_user')->on('users')->onDelete('cascade');
            $table->text('pesan');
            $table->boolean('status_baca')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesans');
    }
};
