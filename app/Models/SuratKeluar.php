<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'tb_surat_keluar';

    protected $primaryKey = 'id_surat_keluar';

    protected $fillable = [
        'id_template',
        'nomor_surat',
        'tanggal_surat',
        'tujuan',
        'perihal',
        'file_surat',
        'status_persetujuan',
        'data_template',
        'metode_pembuatan',
        'id_user'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'data_template' => 'array',
    ];

    public function templateSurat()
    {
        return $this->belongsTo(TemplateSurat::class, 'id_template', 'id_template');
    }
}