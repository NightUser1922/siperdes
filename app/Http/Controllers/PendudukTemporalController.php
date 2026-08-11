<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PendudukTemporal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PendudukTemporalController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdminOrKades();

        $nik = trim((string) $request->query('nik', ''));
        $penduduk = null;
        $notFound = false;

        if ($nik !== '') {
            $penduduk = PendudukTemporal::where('nik', $nik)->first();
            $notFound = !$penduduk;
        }

        return view('penduduk-temporal', compact('nik', 'penduduk', 'notFound'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdminOrKades();

        $validated = $request->validate($this->rules(), $this->messages());

        $penduduk = PendudukTemporal::create([
            ...$validated,
            'last_used_at' => now(),
        ]);

        AuditLog::catat($request, 'Tambah data', 'Data Penduduk Temporal', 'Menambah data penduduk temporal ' . $penduduk->nik);

        return redirect('/penduduk-temporal?nik=' . urlencode($penduduk->nik))
            ->with('success', 'Data penduduk temporal berhasil disimpan.')
            ->with('active_tab', 'cek');
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdminOrKades();

        $penduduk = PendudukTemporal::findOrFail($id);
        $validated = $request->validate($this->rules($penduduk), $this->messages());

        $penduduk->update($validated);
        $penduduk->refreshLastUsedAt();

        AuditLog::catat($request, 'Edit data', 'Data Penduduk Temporal', 'Memperbarui data penduduk temporal ' . $penduduk->nik);

        return redirect('/penduduk-temporal?nik=' . urlencode($penduduk->nik))
            ->with('success', 'Data penduduk temporal berhasil diperbarui.')
            ->with('active_tab', 'cek');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeAdminOrKades();

        $penduduk = PendudukTemporal::findOrFail($id);
        $nik = $penduduk->nik;

        $penduduk->delete();

        AuditLog::catat($request, 'Hapus data', 'Data Penduduk Temporal', 'Menghapus data penduduk temporal ' . $nik);

        return redirect('/penduduk-temporal')->with('success', 'Data penduduk temporal berhasil dihapus.');
    }

    private function rules(?PendudukTemporal $penduduk = null): array
    {
        return [
            'nik' => ['required', 'string', 'max:20', Rule::unique('penduduk_temporal', 'nik')->ignore($penduduk?->id)],
            'nama' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['nullable', 'string', 'max:20'],
            'bin_binti' => ['nullable', 'string', 'max:150'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'kewarganegaraan' => ['nullable', 'string', 'max:100'],
            'agama' => ['nullable', 'string', 'max:50'],
            'pekerjaan' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
        ];
    }

    private function messages(): array
    {
        return [
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'Data dengan NIK tersebut sudah tersedia.',
            'nama.required' => 'Nama lengkap wajib diisi.',
        ];
    }
}
