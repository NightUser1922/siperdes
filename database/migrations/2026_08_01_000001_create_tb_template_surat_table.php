<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tb_template_surat')) {
            Schema::create('tb_template_surat', function (Blueprint $table) {
                $table->bigIncrements('id_template');
                $table->string('nama_template', 150);
                $table->string('jenis_surat', 100);
                $table->string('file_template');
                $table->json('placeholder')->nullable();
                $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
                $table->unsignedBigInteger('id_user');
                $table->timestamps();

                $table->foreign('id_user')
                    ->references('id_user')
                    ->on('tb_user');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_template_surat');
    }
};