<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds tim_pelaksana, penanggung_jawab, dokumentasi to tb_kegiatan_desa
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_kegiatan_desa', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_kegiatan_desa', 'tim_pelaksana')) {
                $table->text('tim_pelaksana')->nullable()->after('keterangan');
            }

            if (!Schema::hasColumn('tb_kegiatan_desa', 'penanggung_jawab')) {
                $table->string('penanggung_jawab', 150)->nullable()->after('tim_pelaksana');
            }

            if (!Schema::hasColumn('tb_kegiatan_desa', 'dokumentasi')) {
                $table->string('dokumentasi')->nullable()->after('penanggung_jawab');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_kegiatan_desa', function (Blueprint $table) {
            if (Schema::hasColumn('tb_kegiatan_desa', 'dokumentasi')) {
                $table->dropColumn('dokumentasi');
            }

            if (Schema::hasColumn('tb_kegiatan_desa', 'penanggung_jawab')) {
                $table->dropColumn('penanggung_jawab');
            }

            if (Schema::hasColumn('tb_kegiatan_desa', 'tim_pelaksana')) {
                $table->dropColumn('tim_pelaksana');
            }
        });
    }
};