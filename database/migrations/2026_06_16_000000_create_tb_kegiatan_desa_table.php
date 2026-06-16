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
        Schema::create('tb_kegiatan_desa', function (Blueprint $table) {
            $table->bigIncrements('id_kegiatan');
            $table->string('nama_kegiatan', 150);
            $table->date('tanggal_kegiatan');
            $table->string('lokasi', 100);
            $table->text('keterangan');
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
        Schema::dropIfExists('tb_kegiatan_desa');
    }
};
