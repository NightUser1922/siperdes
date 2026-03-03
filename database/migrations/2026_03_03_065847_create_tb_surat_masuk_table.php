<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_surat_masuk', function (Blueprint $table) {
            $table->id('id_surat'); // Sesuai dengan Model
            $table->string('no_agenda', 50)->nullable(); 
            $table->string('no_surat', 100);
            $table->date('tgl_surat'); 
            $table->date('tgl_terima'); 
            $table->string('pengirim', 150); 
            $table->string('perihal', 255);
            $table->string('file_scan')->nullable(); // Siap untuk integrasi Google Drive
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_surat_masuk');
    }
};