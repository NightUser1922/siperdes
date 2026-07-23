<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanDesa extends Model
{
    protected $table = 'tb_kegiatan_desa';

    protected $primaryKey = 'id_kegiatan';

    protected $fillable = [
        'nama_kegiatan',
        'tanggal_kegiatan',
        'lokasi',
        'keterangan',
        'id_user'
    ];
}