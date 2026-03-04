<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    // Menunjuk ke tabel yang benar di database
    protected $table = 'tb_surat_masuk';
    
    // Primary key disesuaikan dengan acuan tabel
    protected $primaryKey = 'id_surat_masuk';
    
    // Kolom-kolom yang diizinkan untuk diisi (wajib sama persis dengan Controller dan Database)
    protected $fillable = [
        'no_surat', 
        'pengirim', 
        'tanggal_masuk', 
        'perihal', 
        'file_surat'
    ];
}