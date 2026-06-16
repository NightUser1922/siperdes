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
        Schema::create('tb_audit_log', function (Blueprint $table) {
            $table->bigIncrements('id_log');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->string('aktivitas');
            $table->string('ip_address', 50);
            $table->timestamp('waktu_akses');
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
        Schema::dropIfExists('tb_audit_log');
    }
};
