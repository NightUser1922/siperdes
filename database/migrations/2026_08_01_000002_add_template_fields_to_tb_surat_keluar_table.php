<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tb_surat_keluar')) {
            return;
        }

        if (!Schema::hasColumn('tb_surat_keluar', 'id_template')) {
            Schema::table('tb_surat_keluar', function (Blueprint $table) {
                $table->unsignedBigInteger('id_template')->nullable()->after('id_surat_keluar');
            });
        }

        if (!Schema::hasColumn('tb_surat_keluar', 'data_template')) {
            Schema::table('tb_surat_keluar', function (Blueprint $table) {
                $table->json('data_template')->nullable()->after('status_persetujuan');
            });
        }

        if (!Schema::hasColumn('tb_surat_keluar', 'metode_pembuatan')) {
            Schema::table('tb_surat_keluar', function (Blueprint $table) {
                $table->enum('metode_pembuatan', ['Template', 'Upload'])->default('Upload')->after('data_template');
            });
        }

        if (Schema::hasTable('tb_template_surat')) {
            Schema::table('tb_surat_keluar', function (Blueprint $table) {
                $table->foreign('id_template')
                    ->references('id_template')
                    ->on('tb_template_surat')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('tb_surat_keluar')) {
            return;
        }

        Schema::table('tb_surat_keluar', function (Blueprint $table) {
            try {
                $table->dropForeign(['id_template']);
            } catch (Throwable $exception) {
                // Foreign key may not exist on older databases.
            }
        });

        $columns = [];
        foreach (['id_template', 'data_template', 'metode_pembuatan'] as $column) {
            if (Schema::hasColumn('tb_surat_keluar', $column)) {
                $columns[] = $column;
            }
        }

        if ($columns !== []) {
            Schema::table('tb_surat_keluar', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};