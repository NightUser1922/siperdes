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
        Schema::create('tb_bantuan_sosial', function (Blueprint $table) {
            $table->bigIncrements('id_bantuan');
            $table->string('jenis_bantuan');
            $table->string('penerima_bantuan');
            $table->date('tanggal_penyaluran');
            $table->text('keterangan')->nullable();
            $table->string('file_dokumen')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->timestamps();

            $table->foreign('id_user')
                ->references('id_user')
                ->on('tb_user')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_bantuan_sosial');
    }
};
