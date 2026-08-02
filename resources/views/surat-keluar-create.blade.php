@extends('layouts.app')

@section('title', 'Tambah Surat Keluar')

@section('content')
<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard') }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/surat-keluar') }}" class="text-decoration-none text-success">Surat Keluar</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Surat Keluar</li>
        </ol>
    </nav>

    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex gap-3 align-items-start">
                <span class="module-icon fs-3"><i class="bi bi-send"></i></span>
                <div>
                    <span class="badge text-bg-success mb-2">Surat Keluar</span>
                    <h4 class="fw-bold text-success mb-1">Tambah Surat Keluar</h4>
                    <p class="text-muted mb-0">Buat surat dari template DOCX atau upload PDF/dokumen yang sudah jadi.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card form-card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="module-icon"><i class="bi bi-file-earmark-plus"></i></span>
                        <div>
                            <p class="form-section-title mb-1">Form Input</p>
                            <h5 class="mb-0 fw-bold text-success">Data Surat Keluar Baru</h5>
                        </div>
                    </div>
                </div>
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

                    <form id="suratKeluarForm" action="{{ url('/surat-keluar/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nomor_surat" class="form-label fw-semibold">Nomor Surat <span class="required-dot">*</span></label>
                                <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat') }}" maxlength="100" placeholder="Isi nomor surat secara manual" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_surat" class="form-label fw-semibold">Tanggal Surat <span class="required-dot">*</span></label>
                                <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tujuan" class="form-label fw-semibold">Tujuan / Penerima <span class="required-dot">*</span></label>
                                <input type="text" class="form-control" id="tujuan" name="tujuan" value="{{ old('tujuan') }}" maxlength="100" required>
                            </div>
                            <div class="col-md-6">
                                <label for="perihal" class="form-label fw-semibold">Perihal Surat <span class="required-dot">*</span></label>
                                <input type="text" class="form-control" id="perihal" name="perihal" value="{{ old('perihal') }}" maxlength="255" required>
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
                                            <small class="text-muted">Pilih template aktif lalu isi placeholder yang muncul.</small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="id_template" class="form-label fw-semibold">Template Surat</label>
                                        <select class="form-select" id="id_template" name="id_template">
                                            <option value="">Pilih template surat</option>
                                            @foreach($templates as $template)
                                                <option value="{{ $template->id_template }}" @selected((string) old('id_template') === (string) $template->id_template)>{{ $template->nama_template }} - {{ $template->jenis_surat }}</option>
                                            @endforeach
                                        </select>
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
                                            <small class="text-muted">Gunakan jika dokumen sudah jadi di luar sistem.</small>
                                        </div>
                                    </div>

                                    <label for="file_surat" class="form-label fw-semibold">Upload Berkas Dokumen</label>
                                    <input class="form-control" type="file" id="file_surat" name="file_surat" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                                    <small class="text-muted d-block mt-2">Minimal pilih template atau upload file manual. Format: PDF, JPG, PNG, DOC/DOCX, XLS/XLSX. Maksimal 5MB.</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mt-4">
                            <a href="{{ url('/surat-keluar') }}" class="btn btn-light border px-4 rounded-pill"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <button type="submit" formaction="{{ url('/surat-keluar/preview-template') }}" formtarget="_blank" class="btn btn-info px-4 rounded-pill"><i class="bi bi-eye me-1"></i>Preview PDF</button>
                                <button type="submit" formaction="{{ url('/surat-keluar/download-template') }}" formtarget="_blank" class="btn btn-outline-success px-4 rounded-pill"><i class="bi bi-download me-1"></i>Download PDF</button>
                                <button type="submit" formaction="{{ url('/surat-keluar/store') }}" class="btn btn-success px-5 rounded-pill"><i class="bi bi-save me-1"></i>Simpan Surat</button>
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
        const oldValues = @json(old('data_template', []));
        const select = document.getElementById('id_template');
        const container = document.getElementById('templatePlaceholderContainer');
        const coreFields = ['nomor_surat', 'tanggal_surat', 'tujuan', 'perihal'];

        function labelize(value) {
            return value.replace(/_/g, ' ').replace(/\b\w/g, function (letter) { return letter.toUpperCase(); });
        }

        function renderFields() {
            const template = templates[select.value];
            container.innerHTML = '';

            if (!template) {
                container.innerHTML = '<div class="empty-state py-3"><i class="bi bi-file-earmark-text d-block mb-2"></i><strong>Pilih template untuk melihat data tambahan.</strong></div>';
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