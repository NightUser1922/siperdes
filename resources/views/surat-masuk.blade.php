@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
<div class="container-fluid px-0">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                <div class="d-flex gap-3 align-items-start">
                    <span class="module-icon fs-3"><i class="bi bi-inbox"></i></span>
                    <div>
                        <span class="badge text-bg-success mb-2">Menu Utama</span>
                        <h4 class="fw-bold text-success mb-1">Arsip Surat Masuk</h4>
                        <p class="text-muted mb-0">Kelola dan pantau dokumen surat masuk yang diterima oleh desa.</p>
                    </div>
                </div>
                <a href="/surat-masuk/create" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Surat Masuk
                </a>
            </div>
        </div>
    </div>

    <div class="card module-card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="module-toolbar mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Surat Masuk</h5>
                    <p class="text-muted small mb-0" id="suratMasukTableInfo">Menampilkan {{ $suratMasuk->count() }} data surat masuk</p>
                </div>
                <div class="module-search">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search" class="form-control" id="suratMasukSearch" placeholder="Cari nomor, pengirim, perihal...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table data-table align-middle">
                    <thead>
                        <tr>
                            <th>Aksi</th>
                            <th>Nomor Surat</th>
                            <th>Tanggal Surat</th>
                            <th>Pengirim</th>
                            <th>Perihal</th>
                            <th>Status</th>
                            <th>Dokumen</th>
                        </tr>
                    </thead>
                    <tbody id="suratMasukTableBody">
                        @forelse($suratMasuk as $surat)
                            <tr data-search-row="{{ strtolower($surat->nomor_surat.' '.$surat->pengirim.' '.$surat->perihal.' '.$surat->status_verifikasi) }}">
                                <td>
                                    <div class="d-inline-flex gap-1">
                                        <a href="/surat-masuk/edit/{{ $surat->id_surat_masuk }}" class="btn btn-warning btn-sm action-btn" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="/surat-masuk/delete/{{ $surat->id_surat_masuk }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus arsip surat masuk {{ $surat->nomor_surat }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm action-btn" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="fw-bold text-success">{{ $surat->nomor_surat }}</td>
                                <td>{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d-m-Y') }}</td>
                                <td>{{ $surat->pengirim }}</td>
                                <td class="text-start">{{ $surat->perihal }}</td>
                                <td>
                                    @php
                                        $statusClass = match($surat->status_verifikasi) {
                                            'Disetujui' => 'text-bg-success',
                                            'Ditolak' => 'text-bg-danger',
                                            default => 'text-bg-warning',
                                        };
                                    @endphp
                                    <span class="badge status-badge {{ $statusClass }}">{{ $surat->status_verifikasi ?? 'Menunggu' }}</span>
                                </td>
                                <td>
                                    @if($surat->file_surat)
                                        <a href="{{ asset('uploads/surat_masuk/'.$surat->file_surat) }}" target="_blank" class="btn btn-info btn-sm file-link-btn">
                                            <i class="bi bi-eye me-1"></i>Lihat
                                        </a>
                                    @else
                                        <span class="text-muted small">Tidak ada file</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox d-block mb-2"></i>
                                        <strong>Belum ada data surat masuk</strong>
                                        <p class="mb-0 small">Silakan tambah data baru untuk mulai mengarsipkan surat masuk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="suratMasukSearchEmpty" class="d-none">
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-search d-block mb-2"></i>
                                    <strong>Data tidak ditemukan</strong>
                                    <p class="mb-0 small">Coba gunakan kata kunci pencarian yang berbeda.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3">
                <small class="text-muted">Data ditampilkan langsung dari arsip Surat Masuk.</small>
                <nav aria-label="Navigasi data surat masuk">
                    <ul class="pagination pagination-sm mb-0" id="suratMasukPagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('suratMasukSearch');
        const rows = Array.from(document.querySelectorAll('[data-search-row]'));
        const pagination = document.getElementById('suratMasukPagination');
        const tableInfo = document.getElementById('suratMasukTableInfo');
        const emptySearchRow = document.getElementById('suratMasukSearchEmpty');
        const pageSize = 10;
        let currentPage = 1;

        function filteredRows() {
            const keyword = (searchInput?.value || '').trim().toLowerCase();
            return rows.filter(row => row.dataset.searchRow.includes(keyword));
        }

        function render() {
            const visibleRows = filteredRows();
            const totalPages = Math.max(1, Math.ceil(visibleRows.length / pageSize));
            currentPage = Math.min(currentPage, totalPages);
            const start = (currentPage - 1) * pageSize;
            const end = start + pageSize;

            rows.forEach(row => row.classList.add('d-none'));
            visibleRows.slice(start, end).forEach(row => row.classList.remove('d-none'));

            if (emptySearchRow) {
                emptySearchRow.classList.toggle('d-none', visibleRows.length !== 0 || rows.length === 0);
            }

            if (tableInfo) {
                tableInfo.textContent = `Menampilkan ${visibleRows.length} dari ${rows.length} data surat masuk`;
            }

            if (!pagination) return;
            pagination.innerHTML = '';
            if (visibleRows.length <= pageSize) return;

            for (let page = 1; page <= totalPages; page++) {
                const item = document.createElement('li');
                item.className = `page-item ${page === currentPage ? 'active' : ''}`;
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'page-link';
                button.textContent = page;
                button.addEventListener('click', function () {
                    currentPage = page;
                    render();
                });
                item.appendChild(button);
                pagination.appendChild(item);
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                currentPage = 1;
                render();
            });
        }

        render();
    });
</script>
@endsection
