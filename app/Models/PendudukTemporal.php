<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendudukTemporal extends Model
{
    protected $table = 'penduduk_temporal';

    protected $fillable = [
        'nik',
        'nama',
        'jenis_kelamin',
        'bin_binti',
        'tempat_lahir',
        'tanggal_lahir',
        'kewarganegaraan',
        'agama',
        'pekerjaan',
        'alamat',
        'last_used_at',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'last_used_at' => 'datetime',
    ];

    public function refreshLastUsedAt(): bool
    {
        return $this->forceFill([
            'last_used_at' => now(),
        ])->save();
    }
}