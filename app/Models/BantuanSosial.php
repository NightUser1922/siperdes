<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BantuanSosial extends Model
{
    protected $table = 'tb_bantuan_sosial';

    protected $primaryKey = 'id_bantuan';

    protected $fillable = [
        'nama_bantuan',
        'instansi_pemberi',
        'tanggal_bantuan',
        'jumlah_penerima',
        'id_user'
    ];
}