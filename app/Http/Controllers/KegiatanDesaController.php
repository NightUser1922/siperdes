<?php

namespace App\Http\Controllers;

use App\Models\KegiatanDesa;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class KegiatanDesaController extends Controller
{
    public function index()
    {
        $kegiatanDesa = KegiatanDesa::orderBy('id_kegiatan', 'desc')->get();
        return view('kegiatan-desa', compact('kegiatanDesa'));
    }

    public function create()
    {
        $this->authorizeAdminOrKades();
        return view('kegiatan-desa-create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdminOrKades();
        $request->validate([
            'nama_kegiatan'    => 'required|string|max:150',
            'tanggal_kegiatan' => 'required|date',
            'lokasi'           => 'required|string|max:100',
            'keterangan'       => 'required|string',
            'tim_pelaksana'    => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:150',
            'dokumentasi'      => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $dokumentasiFile = null;
        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            File::ensureDirectoryExists(public_path('uploads/kegiatan_dokumentasi'));
            $fileName = 'KDOC_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/kegiatan_dokumentasi'), $fileName);
            $dokumentasiFile = $fileName;
        }

        $kegiatan = KegiatanDesa::create([
            'nama_kegiatan'    => $request->nama_kegiatan,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'lokasi'           => $request->lokasi,
            'keterangan'       => $request->keterangan,
            'tim_pelaksana'    => $request->tim_pelaksana ?? null,
            'penanggung_jawab' => $request->penanggung_jawab ?? null,
            'dokumentasi'      => $dokumentasiFile,
            'id_user'          => auth()->user()->id_user,
        ]);

        AuditLog::catat($request, 'Tambah data', 'Kegiatan Desa', 'Menambah kegiatan ' . $kegiatan->nama_kegiatan);

        return redirect('/kegiatan-desa')->with('success', 'Data Kegiatan Desa berhasil disimpan!');
    }

    public function edit($id)
    {
        $this->authorizeAdminOrKades();
        $kegiatan = KegiatanDesa::where('id_kegiatan', $id)->firstOrFail();
        return view('kegiatan-desa-edit', compact('kegiatan'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdminOrKades();
        $request->validate([
            'nama_kegiatan'    => 'required|string|max:150',
            'tanggal_kegiatan' => 'required|date',
            'lokasi'           => 'required|string|max:100',
            'keterangan'       => 'required|string',
            'tim_pelaksana'    => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:150',
            'dokumentasi'      => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $kegiatan = KegiatanDesa::where('id_kegiatan', $id)->firstOrFail();

        $dataUpdate = [
            'nama_kegiatan'    => $request->nama_kegiatan,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'lokasi'           => $request->lokasi,
            'keterangan'       => $request->keterangan,
            'tim_pelaksana'    => $request->tim_pelaksana ?? null,
            'penanggung_jawab' => $request->penanggung_jawab ?? null,
            'id_user'          => auth()->user()->id_user,
        ];

        if ($request->hasFile('dokumentasi')) {
            if ($kegiatan->dokumentasi && file_exists(public_path('uploads/kegiatan_dokumentasi/' . $kegiatan->dokumentasi))) {
                unlink(public_path('uploads/kegiatan_dokumentasi/' . $kegiatan->dokumentasi));
            }

            $file = $request->file('dokumentasi');
            File::ensureDirectoryExists(public_path('uploads/kegiatan_dokumentasi'));
            $fileName = 'KDOC_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/kegiatan_dokumentasi'), $fileName);
            $dataUpdate['dokumentasi'] = $fileName;
        }

        $kegiatan->update($dataUpdate);

        AuditLog::catat($request, 'Edit data', 'Kegiatan Desa', 'Memperbarui kegiatan ' . $kegiatan->nama_kegiatan);

        return redirect('/kegiatan-desa')->with('success', 'Data Kegiatan Desa berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeAdminOrKades();
        $kegiatan = KegiatanDesa::where('id_kegiatan', $id)->firstOrFail();

        if ($kegiatan->dokumentasi && file_exists(public_path('uploads/kegiatan_dokumentasi/' . $kegiatan->dokumentasi))) {
            unlink(public_path('uploads/kegiatan_dokumentasi/' . $kegiatan->dokumentasi));
        }

        $namaKegiatan = $kegiatan->nama_kegiatan;
        $kegiatan->delete();
        AuditLog::catat($request, 'Hapus data', 'Kegiatan Desa', 'Menghapus kegiatan ' . $namaKegiatan);

        return redirect('/kegiatan-desa')->with('success', 'Data Kegiatan Desa berhasil dihapus!');
    }
}