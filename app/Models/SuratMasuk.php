<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $table = 'tb_surat_masuk';
    protected $primaryKey = 'id_surat';
    
    // Izin kolom yang bisa dimasukkan data melalui form
    protected $fillable = [
        'no_agenda', 
        'no_surat', 
        'tgl_surat', 
        'tgl_terima', 
        'pengirim', 
        'perihal', 
        'file_scan'
    ];
}