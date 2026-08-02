<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_arsip_digital', function (Blueprint $table) {
            $table->bigIncrements('id_arsip');
            $table->string('nomor_arsip', 100);
            $table->string('nama_arsip', 150);
            $table->string('kategori', 100);
            $table->text('deskripsi')->nullable();
            $table->string('google_drive_file_id', 255);
            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('ukuran')->default(0);
            $table->string('original_name', 255)->nullable();
            $table->string('uploader', 100);
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->foreign('id_user')
                ->references('id_user')
                ->on('tb_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_arsip_digital');
    }
};