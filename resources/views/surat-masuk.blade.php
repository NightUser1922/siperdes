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
        <h2>Halaman Surat Masuk</h2>
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
                        <th>No Agenda</th>
                        <th>No Surat</th>
                        <th>Tanggal Surat</th>
                        <th>Tanggal Terima</th>
                        <th>Pengirim</th>
                        <th>Perihal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratMasuk as $index => $surat)
                        <tr class="align-middle">
                            <td>
                                <a href="/surat-masuk/edit/{{ $surat->id_surat }}" class="btn btn-warning btn-sm mb-1">Edit</a>
                                <form action="/surat-masuk/delete/{{ $surat->id_surat }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus arsip surat {{ $surat->no_surat }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mb-1">Hapus</button>
                                </form>
                            </td>
                            <td>{{ $surat->no_agenda ?? '-' }}</td>
                            <td class="fw-bold">{{ $surat->no_surat }}</td>
                            <td>{{ \Carbon\Carbon::parse($surat->tgl_surat)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($surat->tgl_terima)->format('d-m-Y') }}</td>
                            <td>{{ $surat->pengirim }}</td>
                            <td class="text-start">{{ $surat->perihal }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted p-4">Belum ada data surat masuk. Silakan tambah data baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection