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
        Schema::create('tb_log_akses', function (Blueprint $table) {
            $table->id('id_log');
            $table->string('username', 50)->nullable();
            $table->string('ip_address', 45); // Mencatat IP Address
            $table->text('user_agent'); // Mencatat Kridensial Perangkat (PC/Mobile)
            $table->enum('status', ['Berhasil', 'Gagal']);
            $table->timestamps(); // Waktu akses otomatis dicatat di sini
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_log_akses');
    }
};