<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTable extends Model
{
    // Mengarahkan model ke nama tabel yang benar di database Laragon
    protected $table = 'tb_user';

    // Mendefinisikan Primary Key sesuai rancangan skripsi
    protected $primaryKey = 'id_user';

    // Daftar kolom yang boleh diisi secara massal (keamanan)
    protected $fillable = ['username', 'password', 'level', 'nama_lengkap'];
}