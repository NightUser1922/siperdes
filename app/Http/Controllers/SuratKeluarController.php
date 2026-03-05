<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    // Menampilkan Tabel Surat Keluar
    public function index()
    {
        $suratKeluar = SuratKeluar::orderBy('id_surat_keluar', 'desc')->get();
        return view('surat-keluar', compact('suratKeluar'));
    }

    // Menampilkan Form dengan Nomor Otomatis
    public function create()
    {
        // Hitung total surat keluar tahun ini untuk nomor urut
        $tahunSekarang = date('Y');
        $jumlahSurat = SuratKeluar::whereYear('tanggal_keluar', $tahunSekarang)->count();
        
        // Format: 001/SK/AMD/2026
        $noOtomatis = sprintf("%03d", $jumlahSurat + 1) . "/SK/AMD/" . $tahunSekarang;

        return view('surat-keluar-create', compact('noOtomatis'));
    }

    // Proses Simpan Data
    public function store(Request $request)
    {
        $request->validate([
            'no_surat'       => 'required',
            'tanggal_keluar' => 'required|date',
            'tujuan_surat'   => 'required',
            'perihal'        => 'required',
            // PERBAIKAN: Hapus image, tambahkan ekstensi office, ubah max ke 5120
            'file_surat'     => 'nullable|mimes:jpg,png,jpeg,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $namaFile = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            // Simpan dengan nama unik di folder public/uploads/surat_keluar
            $namaFile = 'SK_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/surat_keluar'), $namaFile);
        }

        SuratKeluar::create([
            'no_surat'       => $request->no_surat,
            'tanggal_keluar' => $request->tanggal_keluar,
            'tujuan_surat'   => $request->tujuan_surat,
            'perihal'        => $request->perihal,
            'file_surat'     => $namaFile,
        ]);

        return redirect('/surat-keluar')->with('success', 'Surat Keluar Berhasil Diarsipkan!');
    }

    // ----------------------------------------------------
    // FITUR BARU: EDIT, UPDATE, & DELETE
    // ----------------------------------------------------

    // Menampilkan Form Edit
    public function edit($id)
    {
        $suratKeluar = SuratKeluar::where('id_surat_keluar', $id)->firstOrFail();
        return view('surat-keluar-edit', compact('suratKeluar'));
    }

    // Proses Update Data
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_keluar' => 'required|date',
            'tujuan_surat'   => 'required',
            'perihal'        => 'required',
            // PERBAIKAN: Hapus image, tambahkan ekstensi office, ubah max ke 5120
            'file_surat'     => 'nullable|mimes:jpg,png,jpeg,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $suratKeluar = SuratKeluar::where('id_surat_keluar', $id)->firstOrFail();
        
        $dataUpdate = [
            'tanggal_keluar' => $request->tanggal_keluar,
            'tujuan_surat'   => $request->tujuan_surat,
            'perihal'        => $request->perihal,
        ];

        // Cek jika ada file baru yang diupload
        if ($request->hasFile('file_surat')) {
            // Hapus file lama jika ada
            if ($suratKeluar->file_surat && file_exists(public_path('uploads/surat_keluar/' . $suratKeluar->file_surat))) {
                unlink(public_path('uploads/surat_keluar/' . $suratKeluar->file_surat));
            }

            // Simpan file baru
            $file = $request->file('file_surat');
            $namaFile = 'SK_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/surat_keluar'), $namaFile);
            
            $dataUpdate['file_surat'] = $namaFile;
        }

        $suratKeluar->update($dataUpdate);

        return redirect('/surat-keluar')->with('success', 'Data Surat Keluar berhasil diperbarui!');
    }

    // Proses Hapus Data
    public function destroy($id)
    {
        $suratKeluar = SuratKeluar::where('id_surat_keluar', $id)->firstOrFail();

        // Hapus file fisik jika ada
        if ($suratKeluar->file_surat && file_exists(public_path('uploads/surat_keluar/' . $suratKeluar->file_surat))) {
            unlink(public_path('uploads/surat_keluar/' . $suratKeluar->file_surat));
        }

        $suratKeluar->delete();

        return redirect('/surat-keluar')->with('success', 'Arsip Surat Keluar berhasil dihapus!');
    }
}