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
            $table->bigIncrements('id_surat_keluar');
            $table->string('nomor_surat', 100);
            $table->date('tanggal_surat');
            $table->string('tujuan', 100);
            $table->string('perihal', 255);
            $table->string('file_surat');
            $table->enum('status_persetujuan', ['Menunggu', 'Disetujui', 'Ditolak']);
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
        Schema::dropIfExists('tb_surat_keluar');
    }
};
