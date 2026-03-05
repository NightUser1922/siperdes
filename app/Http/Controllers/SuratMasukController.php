<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    // 1. MENAMPILKAN TABEL DATA (Read)
    public function index()
    {
        // Gunakan orderBy id_surat_masuk agar yang terbaru tampil di atas
        $suratMasuk = SuratMasuk::orderBy('id_surat_masuk', 'desc')->get();
        return view('surat-masuk', compact('suratMasuk'));
    }

    // 2. MENAMPILKAN FORM TAMBAH (UI)
    public function create()
    {
        return view('surat-masuk-create');
    }

    // 3. PROSES SIMPAN (Create)
    public function store(Request $request)
    {
        $request->validate([
            'no_surat'       => 'required',
            'pengirim'       => 'required',
            'tanggal_masuk'  => 'required|date',
            'perihal'        => 'required',
            // PERBAIKAN: Hapus image, tambahkan ekstensi office, ubah max ke 5120
            'file_surat'     => 'nullable|mimes:jpg,png,jpeg,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $namaFile = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $namaFile = 'SM_' . time() . '.' . $file->getClientOriginalExtension();
            // Simpan di folder public/uploads/surat_masuk
            $file->move(public_path('uploads/surat_masuk'), $namaFile);
        }

        // Mapping ke database sesuai acuan
        SuratMasuk::create([
            'no_surat'       => $request->no_surat,
            'pengirim'       => $request->pengirim,
            'tanggal_masuk'  => $request->tanggal_masuk,
            'perihal'        => $request->perihal,
            'file_surat'     => $namaFile,
        ]);

        return redirect('/surat-masuk')->with('success', 'Arsip Surat Masuk Berhasil Disimpan!');
    }

    // 4. MENAMPILKAN FORM EDIT
    public function edit($id)
    {
        $suratMasuk = SuratMasuk::where('id_surat_masuk', $id)->firstOrFail();
        return view('surat-masuk-edit', compact('suratMasuk'));
    }

    // 5. PROSES UPDATE DATA
    public function update(Request $request, $id)
    {
        $request->validate([
            'no_surat'       => 'required',
            'pengirim'       => 'required',
            'tanggal_masuk'  => 'required|date',
            'perihal'        => 'required',
            // PERBAIKAN: Hapus image, tambahkan ekstensi office, ubah max ke 5120
            'file_surat'     => 'nullable|mimes:jpg,png,jpeg,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $suratMasuk = SuratMasuk::where('id_surat_masuk', $id)->firstOrFail();
        
        $dataUpdate = [
            'no_surat'       => $request->no_surat,
            'pengirim'       => $request->pengirim,
            'tanggal_masuk'  => $request->tanggal_masuk,
            'perihal'        => $request->perihal,
        ];

        // Cek jika ada file baru yang diunggah
        if ($request->hasFile('file_surat')) {
            // Hapus file lama jika ada
            if ($suratMasuk->file_surat && file_exists(public_path('uploads/surat_masuk/' . $suratMasuk->file_surat))) {
                unlink(public_path('uploads/surat_masuk/' . $suratMasuk->file_surat));
            }

            // Simpan file baru
            $file = $request->file('file_surat');
            $namaFile = 'SM_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/surat_masuk'), $namaFile);
            
            $dataUpdate['file_surat'] = $namaFile;
        }

        $suratMasuk->update($dataUpdate);

        return redirect('/surat-masuk')->with('success', 'Data Surat Masuk berhasil diperbarui!');
    }

    // 6. PROSES HAPUS DATA
    public function destroy($id)
    {
        $suratMasuk = SuratMasuk::where('id_surat_masuk', $id)->firstOrFail();

        // Hapus file fisik jika ada
        if ($suratMasuk->file_surat && file_exists(public_path('uploads/surat_masuk/' . $suratMasuk->file_surat))) {
            unlink(public_path('uploads/surat_masuk/' . $suratMasuk->file_surat));
        }

        $suratMasuk->delete();

        return redirect('/surat-masuk')->with('success', 'Arsip Surat Masuk berhasil dihapus!');
    }
}