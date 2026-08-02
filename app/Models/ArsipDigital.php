<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipDigital extends Model
{
    protected $table = 'tb_arsip_digital';

    protected $primaryKey = 'id_arsip';

    protected $fillable = [
        'nomor_arsip',
        'nama_arsip',
        'kategori',
        'deskripsi',
        'google_drive_file_id',
        'mime_type',
        'ukuran',
        'original_name',
        'uploader',
        'id_user'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function getUkuranFormatAttribute(): string
    {
        $bytes = (int) $this->ukuran;

        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        }

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }
}