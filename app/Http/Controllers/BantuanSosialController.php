<?php

namespace App\Http\Controllers;

use App\Models\BantuanSosial;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class BantuanSosialController extends Controller
{
    public function index()
    {
        $bantuanSosial = BantuanSosial::orderBy('id_bantuan', 'desc')->get();
        return view('bantuan-sosial', compact('bantuanSosial'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('bantuan-sosial-create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $request->validate([
            'nama_bantuan'     => 'required|string|max:100',
            'instansi_pemberi' => 'required|string|max:100',
            'tanggal_bantuan'  => 'required|date',
            'jumlah_penerima'  => 'required|integer|min:0',
        ]);

        $bantuan = BantuanSosial::create([
            'nama_bantuan'     => $request->nama_bantuan,
            'instansi_pemberi' => $request->instansi_pemberi,
            'tanggal_bantuan'  => $request->tanggal_bantuan,
            'jumlah_penerima'  => $request->jumlah_penerima,
            'id_user'          => auth()->user()->id_user,
        ]);

        AuditLog::catat($request, 'Tambah data', 'Bantuan Sosial', 'Menambah bantuan ' . $bantuan->nama_bantuan);

        return redirect('/bantuan-sosial')->with('success', 'Data Bantuan Sosial berhasil disimpan!');
    }

    public function edit($id)
    {
        $this->authorizeAdmin();
        $bantuan = BantuanSosial::where('id_bantuan', $id)->firstOrFail();
        return view('bantuan-sosial-edit', compact('bantuan'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();
        $request->validate([
            'nama_bantuan'     => 'required|string|max:100',
            'instansi_pemberi' => 'required|string|max:100',
            'tanggal_bantuan'  => 'required|date',
            'jumlah_penerima'  => 'required|integer|min:0',
        ]);

        $bantuan = BantuanSosial::where('id_bantuan', $id)->firstOrFail();
        $bantuan->update([
            'nama_bantuan'     => $request->nama_bantuan,
            'instansi_pemberi' => $request->instansi_pemberi,
            'tanggal_bantuan'  => $request->tanggal_bantuan,
            'jumlah_penerima'  => $request->jumlah_penerima,
            'id_user'          => auth()->user()->id_user,
        ]);

        AuditLog::catat($request, 'Edit data', 'Bantuan Sosial', 'Memperbarui bantuan ' . $bantuan->nama_bantuan);

        return redirect('/bantuan-sosial')->with('success', 'Data Bantuan Sosial berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeAdmin();
        $bantuan = BantuanSosial::where('id_bantuan', $id)->firstOrFail();
        $namaBantuan = $bantuan->nama_bantuan;
        $bantuan->delete();
        AuditLog::catat($request, 'Hapus data', 'Bantuan Sosial', 'Menghapus bantuan ' . $namaBantuan);

        return redirect('/bantuan-sosial')->with('success', 'Data Bantuan Sosial berhasil dihapus!');
    }
}