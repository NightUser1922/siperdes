<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_user', function (Blueprint $table) {
            $table->bigIncrements('id_user');
            $table->string('nama', 100);
            $table->string('username', 50);
            $table->string('password', 255);
            $table->enum('role', ['Admin', 'Kepala Desa']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_user');
    }
};
