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
}