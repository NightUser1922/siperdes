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
            $table->string('nama_bantuan', 100);
            $table->string('instansi_pemberi', 100);
            $table->date('tanggal_bantuan');
            $table->unsignedInteger('jumlah_penerima');
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
        Schema::dropIfExists('tb_bantuan_sosial');
    }
};
