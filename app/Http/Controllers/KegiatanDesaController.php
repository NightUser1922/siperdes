<?php

namespace App\Http\Controllers;

use App\Models\KegiatanDesa;
use Illuminate\Http\Request;

class KegiatanDesaController extends Controller
{
    public function index()
    {
        $kegiatanDesa = KegiatanDesa::orderBy('id_kegiatan', 'desc')->get();
        return view('kegiatan-desa', compact('kegiatanDesa'));
    }

    public function create()
    {
        return view('kegiatan-desa-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan'    => 'required|string|max:150',
            'tanggal_kegiatan' => 'required|date',
            'lokasi'           => 'required|string|max:100',
            'keterangan'       => 'required|string',
        ]);

        KegiatanDesa::create([
            'nama_kegiatan'    => $request->nama_kegiatan,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'lokasi'           => $request->lokasi,
            'keterangan'       => $request->keterangan,
            'id_user'          => auth()->user()->id_user,
        ]);

        return redirect('/kegiatan-desa')->with('success', 'Data Kegiatan Desa berhasil disimpan!');
    }

    public function edit($id)
    {
        $kegiatan = KegiatanDesa::where('id_kegiatan', $id)->firstOrFail();
        return view('kegiatan-desa-edit', compact('kegiatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan'    => 'required|string|max:150',
            'tanggal_kegiatan' => 'required|date',
            'lokasi'           => 'required|string|max:100',
            'keterangan'       => 'required|string',
        ]);

        $kegiatan = KegiatanDesa::where('id_kegiatan', $id)->firstOrFail();
        $kegiatan->update([
            'nama_kegiatan'    => $request->nama_kegiatan,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'lokasi'           => $request->lokasi,
            'keterangan'       => $request->keterangan,
            'id_user'          => auth()->user()->id_user,
        ]);

        return redirect('/kegiatan-desa')->with('success', 'Data Kegiatan Desa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kegiatan = KegiatanDesa::where('id_kegiatan', $id)->firstOrFail();
        $kegiatan->delete();

        return redirect('/kegiatan-desa')->with('success', 'Data Kegiatan Desa berhasil dihapus!');
    }
}