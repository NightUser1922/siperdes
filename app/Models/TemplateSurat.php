<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateSurat extends Model
{
    protected $table = 'tb_template_surat';

    protected $primaryKey = 'id_template';

    protected $fillable = [
        'nama_template',
        'jenis_surat',
        'file_template',
        'placeholder',
        'status',
        'id_user'
    ];

    protected $casts = [
        'placeholder' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class, 'id_template', 'id_template');
    }

    public function getIsAktifAttribute(): bool
    {
        return $this->status === 'Aktif';
    }
}