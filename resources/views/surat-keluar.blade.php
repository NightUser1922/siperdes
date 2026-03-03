@extends('layouts.app')

@section('title', 'Surat Keluar')

@section('content')
<div class="container-fluid mt-4 mb-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Arsip Surat Keluar</h3>
        <a href="/surat-keluar/create" class="btn btn-primary">+ Tambah Surat Keluar</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Aksi</th>
                        <th>No Surat</th>
                        <th>Tanggal Surat</th>
                        <th>Tujuan</th>
                        <th>Perihal</th>
                        <th>Dokumen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratKeluar as $sk)
                    <tr class="align-middle">
                        <td>
                            <a href="/surat-keluar/edit/{{ $sk->id_keluar }}" class="btn btn-warning btn-sm mb-1">Edit</a>
                            <form action="/surat-keluar/delete/{{ $sk->id_keluar }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus arsip surat {{ $sk->no_surat }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mb-1">Hapus</button>
                            </form>
                        </td>
                        <td class="fw-bold">{{ $sk->no_surat }}</td>
                        <td>{{ \Carbon\Carbon::parse($sk->tgl_surat)->format('d-m-Y') }}</td>
                        <td>{{ $sk->tujuan }}</td>
                        <td class="text-start">{{ $sk->perihal }}</td>
                        <td>
                            @if($sk->file_scan)
                                <a href="{{ asset('uploads/surat_keluar/'.$sk->file_scan) }}" target="_blank" class="btn btn-info btn-sm">Lihat</a>
                            @else
                                <span class="text-muted small">Tidak ada file</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted p-4">Belum ada data surat keluar. Silakan klik "+ Tambah".</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection