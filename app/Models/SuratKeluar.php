<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    public const STATUS_DOKUMEN_DRAFT = 'DRAFT';
    public const STATUS_DOKUMEN_FINAL = 'FINAL';
    public const STATUS_DOKUMEN_LOCKED = 'LOCKED';

    protected $table = 'tb_surat_keluar';

    protected $primaryKey = 'id_surat_keluar';

    protected $attributes = [
        'status_dokumen' => self::STATUS_DOKUMEN_DRAFT,
    ];

    protected $fillable = [
        'id_template',
        'nomor_surat',
        'tanggal_surat',
        'tujuan',
        'perihal',
        'file_surat',
        'status_persetujuan',
        'status_dokumen',
        'snapshot_identitas',
        'finalized_at',
        'locked_at',
        'google_drive_file_id',
        'data_template',
        'metode_pembuatan',
        'id_user'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'snapshot_identitas' => 'array',
        'finalized_at' => 'datetime',
        'locked_at' => 'datetime',
        'data_template' => 'array',
    ];

    public function templateSurat()
    {
        return $this->belongsTo(TemplateSurat::class, 'id_template', 'id_template');
    }
}