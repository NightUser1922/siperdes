<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_user', function (Blueprint $table) {
            $table->id('id_user'); // Menggunakan id_user sesuai rancangan PK
            $table->string('username', 50);
            $table->string('password', 255);
            $table->enum('level', ['Admin', 'Kades']); // Ini kolom vital yang tadi hilang
            $table->string('nama_lengkap', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_user');
    }
};