@extends('layouts.app') {{-- Atau copas header/navbar dari surat-masuk --}}

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Arsip Surat Keluar</h3>
        <a href="/surat-keluar/create" class="btn btn-primary">+ Tambah Surat Keluar</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No Surat</th>
                        <th>Tanggal Surat</th>
                        <th>Tujuan</th>
                        <th>Perihal</th>
                        <th>Dokumen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratKeluar as $sk)
                    <tr>
                        <td>{{ $sk->no_surat }}</td>
                        <td>{{ \Carbon\Carbon::parse($sk->tgl_surat)->format('d-m-Y') }}</td>
                        <td>{{ $sk->tujuan }}</td>
                        <td>{{ $sk->perihal }}</td>
                        <td class="text-center">
                            @if($sk->file_scan)
                                <a href="{{ asset('uploads/surat_keluar/'.$sk->file_scan) }}" target="_blank" class="btn btn-info btn-sm">Lihat</a>
                            @else
                                <span class="text-muted small">Tidak ada file</span>
                            @endif
                        </td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data surat keluar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>