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
        Schema::create('tb_surat_keluar', function (Blueprint $table) {
            // Menggunakan id_surat_keluar sebagai Primary Key
            $table->bigIncrements('id_surat_keluar');
            $table->string('no_surat');
            $table->date('tanggal_keluar');
            $table->string('tujuan_surat');
            $table->text('perihal');
            $table->string('file_surat')->nullable(); // Boleh kosong
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_surat_keluar');
    }
};