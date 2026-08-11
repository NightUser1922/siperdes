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

        if (!Schema::hasColumn('tb_surat_keluar', 'status_dokumen')) {
            Schema::table('tb_surat_keluar', function (Blueprint $table) {
                $table->enum('status_dokumen', ['DRAFT', 'FINAL', 'LOCKED'])
                    ->default('DRAFT')
                    ->after('status_persetujuan');
            });
        }

        if (!Schema::hasColumn('tb_surat_keluar', 'snapshot_identitas')) {
            Schema::table('tb_surat_keluar', function (Blueprint $table) {
                $table->json('snapshot_identitas')->nullable()->after('status_dokumen');
            });
        }

        if (!Schema::hasColumn('tb_surat_keluar', 'finalized_at')) {
            Schema::table('tb_surat_keluar', function (Blueprint $table) {
                $table->timestamp('finalized_at')->nullable()->after('snapshot_identitas');
            });
        }

        if (!Schema::hasColumn('tb_surat_keluar', 'locked_at')) {
            Schema::table('tb_surat_keluar', function (Blueprint $table) {
                $table->timestamp('locked_at')->nullable()->after('finalized_at');
            });
        }

        if (!Schema::hasColumn('tb_surat_keluar', 'google_drive_file_id')) {
            Schema::table('tb_surat_keluar', function (Blueprint $table) {
                $table->string('google_drive_file_id', 255)->nullable()->after('locked_at');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('tb_surat_keluar')) {
            return;
        }

        $columns = [];
        foreach ([
            'status_dokumen',
            'snapshot_identitas',
            'finalized_at',
            'locked_at',
            'google_drive_file_id',
        ] as $column) {
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
