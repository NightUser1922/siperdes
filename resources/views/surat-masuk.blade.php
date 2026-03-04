@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
<div class="container-fluid mt-4 mb-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Arsip Surat Masuk</h3>
        <div>
            <a href="/surat-masuk/create" class="btn btn-primary">+ Tambah Surat Masuk Baru</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Aksi</th>
                        <th>No Surat</th>
                        <th>Tanggal Masuk</th>
                        <th>Pengirim</th>
                        <th>Perihal</th>
                        <th>Dokumen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratMasuk as $surat)
                        <tr class="align-middle">
                            <td>
                                {{-- PERBAIKAN: Gunakan id_surat_masuk --}}
                                <a href="/surat-masuk/edit/{{ $surat->id_surat_masuk }}" class="btn btn-warning btn-sm mb-1">Edit</a>
                                <form action="/surat-masuk/delete/{{ $surat->id_surat_masuk }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus arsip surat masuk {{ $surat->no_surat }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mb-1">Hapus</button>
                                </form>
                            </td>
                            <td class="fw-bold">{{ $surat->no_surat }}</td>
                            
                            {{-- PERBAIKAN: Gunakan tanggal_masuk --}}
                            <td>{{ \Carbon\Carbon::parse($surat->tanggal_masuk)->format('d-m-Y') }}</td>
                            
                            {{-- PERBAIKAN: Gunakan pengirim --}}
                            <td>{{ $surat->pengirim }}</td>
                            
                            <td class="text-start">{{ $surat->perihal }}</td>
                            
                            {{-- PERBAIKAN: Tambahkan tombol lihat dokumen file_surat --}}
                            <td>
                                @if($surat->file_surat)
                                    <a href="{{ asset('uploads/surat_masuk/'.$surat->file_surat) }}" target="_blank" class="btn btn-info btn-sm">Lihat</a>
                                @else
                                    <span class="text-muted small">Tidak ada file</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted p-4">Belum ada data surat masuk. Silakan tambah data baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection