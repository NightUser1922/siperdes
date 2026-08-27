<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    public function index()
    {
        $suratMasuk = SuratMasuk::orderBy('id_surat_masuk', 'desc')->get();
        return view('surat-masuk', compact('suratMasuk'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('surat-masuk-create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'nomor_surat'    => 'required|string|max:100',
            'pengirim'       => 'required|string|max:100',
            'tanggal_surat'  => 'required|date',
            'perihal'        => 'required|string|max:255',
            'file_surat'     => 'required|mimes:jpg,png,jpeg,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $namaFile = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $namaFile = 'SM_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/surat_masuk'), $namaFile);
        }

        $suratMasuk = SuratMasuk::create([
            'nomor_surat'       => $request->nomor_surat,
            'pengirim'          => $request->pengirim,
            'tanggal_surat'     => $request->tanggal_surat,
            'perihal'           => $request->perihal,
            'file_surat'        => $namaFile,
            'status_verifikasi' => 'Menunggu',
            'id_user'           => auth()->user()->id_user,
        ]);

        AuditLog::catat($request, 'Tambah data', 'Surat Masuk', 'Menambah surat masuk ' . $suratMasuk->nomor_surat);

        return redirect('/surat-masuk')->with('success', 'Data Surat Masuk berhasil disimpan!');
    }

    public function edit($id)
    {
        $this->authorizeAdmin();
        $suratMasuk = SuratMasuk::where('id_surat_masuk', $id)->firstOrFail();
        return view('surat-masuk-edit', compact('suratMasuk'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        $request->validate([
            'nomor_surat'    => 'required|string|max:100',
            'pengirim'       => 'required|string|max:100',
            'tanggal_surat'  => 'required|date',
            'perihal'        => 'required|string|max:255',
            'file_surat'     => 'nullable|mimes:jpg,png,jpeg,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $suratMasuk = SuratMasuk::where('id_surat_masuk', $id)->firstOrFail();

        $dataUpdate = [
            'nomor_surat'   => $request->nomor_surat,
            'pengirim'      => $request->pengirim,
            'tanggal_surat' => $request->tanggal_surat,
            'perihal'       => $request->perihal,
            'id_user'       => auth()->user()->id_user,
        ];

        if ($request->hasFile('file_surat')) {
            if ($suratMasuk->file_surat && file_exists(public_path('uploads/surat_masuk/' . $suratMasuk->file_surat))) {
                unlink(public_path('uploads/surat_masuk/' . $suratMasuk->file_surat));
            }

            $file = $request->file('file_surat');
            $namaFile = 'SM_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/surat_masuk'), $namaFile);
            $dataUpdate['file_surat'] = $namaFile;
        }

        $suratMasuk->update($dataUpdate);
        AuditLog::catat($request, 'Edit data', 'Surat Masuk', 'Memperbarui surat masuk ' . $suratMasuk->nomor_surat);

        return redirect('/surat-masuk')->with('success', 'Data Surat Masuk berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeAdmin();

        $suratMasuk = SuratMasuk::where('id_surat_masuk', $id)->firstOrFail();

        if ($suratMasuk->file_surat && file_exists(public_path('uploads/surat_masuk/' . $suratMasuk->file_surat))) {
            unlink(public_path('uploads/surat_masuk/' . $suratMasuk->file_surat));
        }

        $nomorSurat = $suratMasuk->nomor_surat;
        $suratMasuk->delete();
        AuditLog::catat($request, 'Hapus data', 'Surat Masuk', 'Menghapus surat masuk ' . $nomorSurat);

        return redirect('/surat-masuk')->with('success', 'Data Surat Masuk berhasil dihapus!');
    }
    public function approve(Request $request)
    {
        return $this->recordApproval($request, 'disetujui');
    }

    public function reject(Request $request)
    {
        return $this->recordApproval($request, 'ditolak');
    }

    private function recordApproval(Request $request, string $status)
    {
        if (!auth()->check() || auth()->user()->role !== 'Kepala Desa') {
            abort(403);
        }

        $suratMasuk = SuratMasuk::where('id_surat_masuk', $request->route('id'))->firstOrFail();

        if ($suratMasuk->status !== 'menunggu') {
            return redirect('/surat-masuk')->with('error', 'Status persetujuan surat ini sudah diproses.');
        }

        $suratMasuk->update([
            'status' => $status,
            'approved_by' => auth()->user()->id_user,
            'approved_at' => now(),
        ]);

        AuditLog::catat(
            $request,
            $status === 'disetujui' ? 'Setujui Surat Masuk' : 'Tolak Surat Masuk',
            'Surat Masuk',
            ucfirst($status) . ' surat masuk ' . $suratMasuk->nomor_surat
        );

        return redirect('/surat-masuk')->with('success', 'Surat masuk berhasil ' . $status . '.');
    }
}