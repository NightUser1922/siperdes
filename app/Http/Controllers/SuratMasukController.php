<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    // MENAMPILKAN TABEL DATA (Read)
    public function index()
    {
        $suratMasuk = SuratMasuk::all();
        return view('surat-masuk', compact('suratMasuk'));
    }

    // MENAMPILKAN FORM TAMBAH (UI)
    public function create()
    {
        return view('surat-masuk-create');
    }

    // PROSES SIMPAN (Create)
    public function store(Request $request)
    {
        $request->validate([
            'no_surat'   => 'required',
            'tgl_surat'  => 'required|date',
            'tgl_terima' => 'required|date',
            'pengirim'   => 'required',
            'perihal'    => 'required',
        ]);

        SuratMasuk::create([
            'no_agenda'  => $request->no_agenda,
            'no_surat'   => $request->no_surat,
            'tgl_surat'  => $request->tgl_surat,
            'tgl_terima' => $request->tgl_terima,
            'pengirim'   => $request->pengirim,
            'perihal'    => $request->perihal,
        ]);

        return redirect('/surat-masuk')->with('success', 'Arsip Berhasil Disimpan!');
    }
}