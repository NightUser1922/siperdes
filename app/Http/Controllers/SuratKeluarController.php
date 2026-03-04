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
        $suratKeluar = SuratKeluar::orderBy('id_keluar', 'desc')->get();
        return view('surat-keluar', compact('suratKeluar'));
    }

    // Menampilkan Form dengan Nomor Otomatis
    public function create()
    {
        // Hitung total surat keluar tahun ini untuk nomor urut
        $tahunSekarang = date('Y');
        $jumlahSurat = SuratKeluar::whereYear('tgl_surat', $tahunSekarang)->count();
        
        // Format: 001/SK/AMD/2026
        $noOtomatis = sprintf("%03d", $jumlahSurat + 1) . "/SK/AMD/" . $tahunSekarang;

        return view('surat-keluar-create', compact('noOtomatis'));
    }

    // Proses Simpan Data
    public function store(Request $request)
    {
        $request->validate([
            'no_surat'  => 'required',
            'tgl_surat' => 'required|date',
            'tujuan'    => 'required',
            'perihal'   => 'required',
            'file_scan' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $namaFile = null;
        if ($request->hasFile('file_scan')) {
            $file = $request->file('file_scan');
            // Simpan dengan nama unik di folder public/uploads/surat_keluar
            $namaFile = 'SK_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/surat_keluar'), $namaFile);
        }

        SuratKeluar::create([
            'no_surat'  => $request->no_surat,
            'tgl_surat' => $request->tgl_surat,
            'tujuan'    => $request->tujuan,
            'perihal'   => $request->perihal,
            'file_scan' => $namaFile,
        ]);

        return redirect('/surat-keluar')->with('success', 'Surat Keluar Berhasil Diarsipkan!');
    }

    // ----------------------------------------------------
    // FITUR BARU: EDIT, UPDATE, & DELETE
    // ----------------------------------------------------

    // Menampilkan Form Edit
    public function edit($id)
    {
        $suratKeluar = SuratKeluar::where('id_keluar', $id)->firstOrFail();
        return view('surat-keluar-edit', compact('suratKeluar'));
    }

    // Proses Update Data
    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl_surat' => 'required|date',
            'tujuan'    => 'required',
            'perihal'   => 'required',
            'file_scan' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $suratKeluar = SuratKeluar::where('id_keluar', $id)->firstOrFail();
        
        $dataUpdate = [
            'tgl_surat' => $request->tgl_surat,
            'tujuan'    => $request->tujuan,
            'perihal'   => $request->perihal,
        ];

        // Cek jika ada file baru yang diupload
        if ($request->hasFile('file_scan')) {
            // Hapus file lama jika ada
            if ($suratKeluar->file_scan && file_exists(public_path('uploads/surat_keluar/' . $suratKeluar->file_scan))) {
                unlink(public_path('uploads/surat_keluar/' . $suratKeluar->file_scan));
            }

            // Simpan file baru
            $file = $request->file('file_scan');
            $namaFile = 'SK_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/surat_keluar'), $namaFile);
            
            $dataUpdate['file_scan'] = $namaFile;
        }

        $suratKeluar->update($dataUpdate);

        return redirect('/surat-keluar')->with('success', 'Data Surat Keluar berhasil diperbarui!');
    }

    // Proses Hapus Data
    public function destroy($id)
    {
        $suratKeluar = SuratKeluar::where('id_keluar', $id)->firstOrFail();

        // Hapus file fisik jika ada
        if ($suratKeluar->file_scan && file_exists(public_path('uploads/surat_keluar/' . $suratKeluar->file_scan))) {
            unlink(public_path('uploads/surat_keluar/' . $suratKeluar->file_scan));
        }

        $suratKeluar->delete();

        return redirect('/surat-keluar')->with('success', 'Arsip Surat Keluar berhasil dihapus!');
    }
}