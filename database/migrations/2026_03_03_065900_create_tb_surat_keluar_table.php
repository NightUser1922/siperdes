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
            $table->id('id_surat_keluar');
            $table->string('no_surat', 100);
            $table->string('tujuan_surat', 150);
            $table->date('tanggal_keluar');
            $table->string('perihal', 255);
            $table->string('file_surat')->nullable(); // Disiapkan untuk link Google Drive
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