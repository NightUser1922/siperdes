<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    // Menunjuk ke tabel yang benar di database
    protected $table = 'tb_surat_keluar';
    
    // Primary key wajib disesuaikan dengan HeidiSQL
    protected $primaryKey = 'id_surat_keluar';
    
    // Kolom-kolom yang diizinkan untuk diisi (wajib sama persis dengan nama kolom database)
    protected $fillable = [
        'no_surat', 
        'tanggal_keluar', 
        'tujuan_surat', 
        'perihal', 
        'file_surat'
    ];
}