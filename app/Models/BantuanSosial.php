<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BantuanSosial extends Model
{
    protected $table = 'tb_bantuan_sosial';

    protected $primaryKey = 'id_bantuan';

    protected $fillable = [
        'jenis_bantuan',
        'penerima_bantuan',
        'tanggal_penyaluran',
        'keterangan',
        'file_dokumen',
        'id_user'
    ];
}
