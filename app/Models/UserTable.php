<?php

namespace App\Models;

// Kita gunakan Authenticatable supaya Laravel tahu ini tabel untuk login
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserTable extends Authenticatable
{
    use Notifiable;

    protected $table = 'tb_user';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'username', 
        'password', 
        'level', 
        'nama_lengkap'
    ];

    // Menyembunyikan password agar tidak tampil di log atau API
    protected $hidden = [
        'password',
    ];
}