<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'tb_surat_keluar';
    protected $primaryKey = 'id_keluar';
    protected $fillable = ['no_surat', 'tgl_surat', 'tujuan', 'perihal', 'file_scan'];
}