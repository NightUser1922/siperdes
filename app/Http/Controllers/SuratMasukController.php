<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk; // Memanggil kamus Model
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    // 1. Menampilkan halaman form (UI)
    public function create()
    {
        return view('surat-masuk-create');
    }

    // 2. Menangkap dan menyimpan data
    public function store(Request $request)
    {
        // Validasi ketat
        $request->validate([
            'no_surat'   => 'required',
            'tgl_surat'  => 'required|date',
            'tgl_terima' => 'required|date',
            'pengirim'   => 'required',
            'perihal'    => 'required',
        ], [
            'no_surat.required'   => 'Nomor Surat wajib diisi.',
            'tgl_surat.required'  => 'Tanggal Surat wajib diisi.',
            'tgl_terima.required' => 'Tanggal Terima wajib diisi.',
            'pengirim.required'   => 'Instansi Pengirim wajib diisi.',
            'perihal.required'    => 'Perihal surat wajib diisi.',
        ]);

        // Proses penyimpanan ke tabel
        SuratMasuk::create([
            'no_agenda'  => $request->no_agenda,
            'no_surat'   => $request->no_surat,
            'tgl_surat'  => $request->tgl_surat,
            'tgl_terima' => $request->tgl_terima,
            'pengirim'   => $request->pengirim,
            'perihal'    => $request->perihal,
        ]);

        return redirect('/surat-masuk')->with('success', 'Data Surat Masuk berhasil ditambahkan!');
    }
}