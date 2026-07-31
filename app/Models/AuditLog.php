<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuditLog extends Model
{
    protected $table = 'tb_audit_log';

    protected $primaryKey = 'id_log';

    protected $fillable = [
        'id_user',
        'aktivitas',
        'ip_address',
        'waktu_akses'
    ];

    protected $casts = [
        'waktu_akses' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public static function catat(Request $request, string $aktivitas, string $modul, string $keterangan = '', ?User $user = null): void
    {
        $user = $user ?: auth()->user();

        if (!$user) {
            return;
        }

        try {
            static::create([
                'id_user' => $user->id_user,
                'aktivitas' => static::formatAktivitas($aktivitas, $modul, $keterangan),
                'ip_address' => $request->ip(),
                'waktu_akses' => now(),
            ]);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    public function getAktivitasLabelAttribute(): string
    {
        return $this->parts()[0] ?? $this->aktivitas;
    }

    public function getModulAttribute(): string
    {
        return $this->parts()[1] ?? '-';
    }

    public function getKeteranganAttribute(): string
    {
        return $this->parts()[2] ?? $this->aktivitas;
    }

    private static function formatAktivitas(string $aktivitas, string $modul, string $keterangan): string
    {
        return Str::limit($aktivitas . '||' . $modul . '||' . $keterangan, 255, '');
    }

    private function parts(): array
    {
        return explode('||', $this->aktivitas, 3);
    }
}