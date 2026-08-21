@extends('layouts.app')

@section('title', 'Edit Surat Keluar')

@section('content')
<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard') }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/surat-keluar') }}" class="text-decoration-none text-success">Surat Keluar</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Surat Keluar</li>
        </ol>
    </nav>

    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex gap-3 align-items-start">
                <span class="module-icon fs-3"><i class="bi bi-pencil-square"></i></span>
                <div>
                    <span class="badge text-bg-warning mb-2">Surat Keluar</span>
                    <h4 class="fw-bold text-success mb-1">Edit Surat Keluar</h4>
                    <p class="text-muted mb-0">Perbarui arsip, ganti template, atau upload file manual baru.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card form-card shadow-sm border-0">
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4">
                            <div class="fw-semibold mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Periksa kembali input berikut:</div>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="suratKeluarForm" action="{{ url('/surat-keluar/update/' . $suratKeluar->id_surat_keluar) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nomor_surat" class="form-label fw-semibold">Nomor Surat <span class="required-dot">*</span></label>
                                <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $suratKeluar->nomor_surat) }}" maxlength="100" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_surat" class="form-label fw-semibold">Tanggal Surat <span class="required-dot">*</span></label>
                                <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', optional($suratKeluar->tanggal_surat)->format('Y-m-d') ?? $suratKeluar->tanggal_surat) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tujuan" class="form-label fw-semibold">Tujuan / Penerima <span class="required-dot">*</span></label>
                                <input type="text" class="form-control" id="tujuan" name="tujuan" value="{{ old('tujuan', $suratKeluar->tujuan) }}" maxlength="100" required>
                            </div>
                            <div class="col-md-6">
                                <label for="perihal" class="form-label fw-semibold">Perihal Surat <span class="required-dot">*</span></label>
                                <input type="text" class="form-control" id="perihal" name="perihal" value="{{ old('perihal', $suratKeluar->perihal) }}" maxlength="255" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="border rounded-4 p-3 h-100">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="module-icon"><i class="bi bi-file-earmark-richtext"></i></span>
                                        <div>
                                            <h6 class="fw-bold text-success mb-0">Generate dari Template</h6>
                                            <small class="text-muted">Pilih template untuk membuat ulang file PDF.</small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="id_template" class="form-label fw-semibold">Template Surat</label>
                                        <select class="form-select" id="id_template" name="id_template">
                                            <option value="">Tidak menggunakan template</option>
                                            @foreach($templates as $template)
                                                <option value="{{ $template->id_template }}" @selected((string) old('id_template', $suratKeluar->id_template) === (string) $template->id_template)>{{ $template->nama_template }} - {{ $template->jenis_surat }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @php
                                        $nikPenerima = old('nik_penerima', data_get($suratKeluar->snapshot_identitas, 'nik', data_get($suratKeluar->data_template, 'nik')));
                                    @endphp
                                    <div class="mb-3" id="templateNikContainer">
                                        <label for="nik_penerima" class="form-label fw-semibold">NIK Penerima <span class="required-dot">*</span></label>
                                        <input type="text" class="form-control @error('nik_penerima') is-invalid @enderror" id="nik_penerima" name="nik_penerima" value="{{ $nikPenerima }}" maxlength="20" placeholder="Masukkan NIK penerima">
                                        <small class="text-muted">Wajib diisi saat menggunakan template surat.</small>
                                        @error('nik_penerima')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div id="templatePlaceholderContainer" class="d-grid gap-3"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="border rounded-4 p-3 h-100">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="module-icon"><i class="bi bi-upload"></i></span>
                                        <div>
                                            <h6 class="fw-bold text-success mb-0">Upload File Manual</h6>
                                            <small class="text-muted">Upload file baru akan mengganti hasil template sebelumnya.</small>
                                        </div>
                                    </div>

                                    <label for="file_surat" class="form-label fw-semibold">Upload Berkas Dokumen Baru</label>
                                    <input class="form-control" type="file" id="file_surat" name="file_surat" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                                    <small class="text-muted d-block mt-2">Biarkan kosong jika tidak ingin mengganti file secara manual.</small>

                                    @if($suratKeluar->file_surat)
                                        <div class="mt-3">
                                            <span class="badge current-file-badge">
                                                File saat ini:
                                                <a href="{{ asset('uploads/surat_keluar/'.$suratKeluar->file_surat) }}" target="_blank" class="text-decoration-none text-reset">
                                                    {{ $suratKeluar->file_surat }}
                                                </a>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mt-4">
                            <a href="{{ url('/surat-keluar') }}" class="btn btn-light border px-4 rounded-pill"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <a href="{{ url('/surat-keluar/' . $suratKeluar->id_surat_keluar . '/preview') }}" target="_blank" class="btn btn-info px-4 rounded-pill"><i class="bi bi-eye me-1"></i>Preview Saat Ini</a>
                                <a href="{{ url('/surat-keluar/' . $suratKeluar->id_surat_keluar . '/download') }}" class="btn btn-outline-success px-4 rounded-pill"><i class="bi bi-download me-1"></i>Download Saat Ini</a>
                                <button type="submit" class="btn btn-warning px-5 rounded-pill"><i class="bi bi-save me-1"></i>Update Surat</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const templates = @json($templatesForJs);
        const oldValues = @json(old('data_template', $suratKeluar->data_template ?? []));
        const select = document.getElementById('id_template');
        const container = document.getElementById('templatePlaceholderContainer');
        const coreFields = ['nomor_surat', 'tanggal_surat', 'tujuan', 'perihal', 'nik'];

        function labelize(value) {
            return value.replace(/_/g, ' ').replace(/\b\w/g, function (letter) { return letter.toUpperCase(); });
        }

        function renderFields() {
            const template = templates[select.value];
            container.innerHTML = '';

            if (!template) {
                container.innerHTML = '<div class="empty-state py-3"><i class="bi bi-file-earmark-text d-block mb-2"></i><strong>Pilih template untuk mengisi data tambahan.</strong></div>';
                return;
            }

            const customPlaceholders = (template.placeholder || []).filter(function (placeholder) {
                return !coreFields.includes(placeholder);
            });

            if (customPlaceholders.length === 0) {
                container.innerHTML = '<div class="alert alert-success rounded-4 mb-0"><i class="bi bi-check-circle me-1"></i>Template ini hanya memakai data utama surat.</div>';
                return;
            }

            customPlaceholders.forEach(function (placeholder) {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = '<label class="form-label fw-semibold" for="data_' + placeholder + '">' + labelize(placeholder) + '</label>' +
                    '<input type="text" class="form-control" id="data_' + placeholder + '" name="data_template[' + placeholder + ']" value="' + (oldValues[placeholder] || '') + '" maxlength="1000">' +
                    '<small class="text-muted">Placeholder: ${' + placeholder + '}</small>';
                container.appendChild(wrapper);
            });
        }

        select.addEventListener('change', renderFields);
        renderFields();
    });
</script>
@endsection