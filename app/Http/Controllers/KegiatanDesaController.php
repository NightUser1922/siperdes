<?php

namespace App\Http\Controllers;

use App\Models\KegiatanDesa;
use App\Models\AuditLog;
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

        $kegiatan = KegiatanDesa::create([
            'nama_kegiatan'    => $request->nama_kegiatan,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'lokasi'           => $request->lokasi,
            'keterangan'       => $request->keterangan,
            'id_user'          => auth()->user()->id_user,
        ]);

        AuditLog::catat($request, 'Tambah data', 'Kegiatan Desa', 'Menambah kegiatan ' . $kegiatan->nama_kegiatan);

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

        AuditLog::catat($request, 'Edit data', 'Kegiatan Desa', 'Memperbarui kegiatan ' . $kegiatan->nama_kegiatan);

        return redirect('/kegiatan-desa')->with('success', 'Data Kegiatan Desa berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $kegiatan = KegiatanDesa::where('id_kegiatan', $id)->firstOrFail();
        $namaKegiatan = $kegiatan->nama_kegiatan;
        $kegiatan->delete();
        AuditLog::catat($request, 'Hapus data', 'Kegiatan Desa', 'Menghapus kegiatan ' . $namaKegiatan);

        return redirect('/kegiatan-desa')->with('success', 'Data Kegiatan Desa berhasil dihapus!');
    }
}