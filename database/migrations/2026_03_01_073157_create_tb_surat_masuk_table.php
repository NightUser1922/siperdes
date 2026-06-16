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
            $table->bigIncrements('id_surat_masuk');
            $table->string('nomor_surat', 100);
            $table->date('tanggal_surat');
            $table->string('pengirim', 100);
            $table->string('perihal', 255);
            $table->string('file_surat');
            $table->enum('status_verifikasi', ['Menunggu', 'Disetujui', 'Ditolak']);
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->foreign('id_user')
                ->references('id_user')
                ->on('tb_user');
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
