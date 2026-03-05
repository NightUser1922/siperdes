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
        Schema::create('tb_surat_masuk', function (Blueprint $table) {
            // Primary Key sesuai acuan
            $table->bigIncrements('id_surat_masuk');
            
            // Kolom data surat
            $table->string('no_surat');
            $table->string('pengirim'); // Sesuai acuan pengirim/asal_surat
            $table->date('tanggal_masuk'); // Sesuai acuan tanggal_masuk
            $table->text('perihal');
            
            // Kolom file scan (boleh kosong)
            $table->string('file_surat')->nullable();
            
            // Pencatat waktu otomatis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_surat_masuk');
    }
};