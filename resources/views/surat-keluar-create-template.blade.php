@extends('layouts.app')

@section('title', 'Buat Surat dari Template')

@section('content')
@php
    $selectedTemplateId = old('id_template', $selectedTemplateId ?? request('id_template'));
    $nikPenerima = old('nik_penerima', $nikPenerima ?? request('nik_penerima'));
    $selectedTemplate = $templates->firstWhere('id_template', (int) $selectedTemplateId);
    $canGenerate = $selectedTemplate && $pendudukPenerima;
@endphp

<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard') }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/surat-keluar') }}" class="text-decoration-none text-success">Surat Keluar</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/surat-keluar/create') }}" class="text-decoration-none text-success">Pilih Jenis Input</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat dari Template</li>
        </ol>
    </nav>

    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex gap-3 align-items-start">
                <span class="module-icon fs-3"><i class="bi bi-file-earmark-richtext"></i></span>
                <div>
                    <span class="badge text-bg-success mb-2">Surat Keluar</span>
                    <h4 class="fw-bold text-success mb-1">Buat Surat dari Template</h4>
                    <p class="text-muted mb-0">Pilih template, cari penerima dari Data Penduduk Temporal, lalu isi placeholder tambahan.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card form-card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="module-icon"><i class="bi bi-search"></i></span>
                        <div>
                            <p class="form-section-title mb-1">Template dan Penerima</p>
                            <h5 class="mb-0 fw-bold text-success">Cari Data Penerima</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ url('/surat-keluar/create/template') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-lg-6">
                            <label for="id_template_search" class="form-label fw-semibold">Template Surat <span class="required-dot">*</span></label>
                            <select class="form-select @error('id_template') is-invalid @enderror" id="id_template_search" name="id_template" required>
                                <option value="">Pilih template surat</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id_template }}" @selected((string) $selectedTemplateId === (string) $template->id_template)>{{ $template->nama_template }} - {{ $template->jenis_surat }}</option>
                                @endforeach
                            </select>
                            @error('id_template')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-lg-4">
                            <label for="nik_penerima_search" class="form-label fw-semibold">NIK Penerima <span class="required-dot">*</span></label>
                            <input type="text" class="form-control @error('nik_penerima') is-invalid @enderror" id="nik_penerima_search" name="nik_penerima" value="{{ $nikPenerima }}" maxlength="20" placeholder="Masukkan NIK" required>
                            @error('nik_penerima')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-lg-2">
                            <button type="submit" class="btn btn-success rounded-pill px-4 w-100"><i class="bi bi-search me-1"></i>Cari</button>
                        </div>
                    </form>

                    @if($nikPenerima && $pendudukNotFound)
                        <div class="alert alert-warning border-0 rounded-4 mt-4 mb-0" role="alert">
                            <i class="bi bi-info-circle me-2"></i>Data penduduk dengan NIK tersebut tidak ditemukan.
                        </div>
                    @endif

                    @if($pendudukPenerima)
                        <div class="alert alert-success border-0 rounded-4 mt-4" role="alert">
                            <i class="bi bi-check-circle me-2"></i>Data Penduduk Ditemukan
                        </div>
                        <div class="border rounded-4 p-3">
                            <div class="row g-3">
                                <div class="col-md-6"><small class="text-muted d-block">Nama Lengkap</small><strong>{{ $pendudukPenerima->nama }}</strong></div>
                                <div class="col-md-6"><small class="text-muted d-block">Tempat/Tgl Lahir</small><strong>{{ $pendudukPenerima->tempat_lahir ?: '-' }}{{ $pendudukPenerima->tanggal_lahir ? ', ' . $pendudukPenerima->tanggal_lahir->format('d-m-Y') : '' }}</strong></div>
                                <div class="col-md-6"><small class="text-muted d-block">Jenis Kelamin</small><strong>{{ $pendudukPenerima->jenis_kelamin ?: '-' }}</strong></div>
                                <div class="col-md-6"><small class="text-muted d-block">Pekerjaan</small><strong>{{ $pendudukPenerima->pekerjaan ?: '-' }}</strong></div>
                                <div class="col-md-6"><small class="text-muted d-block">Agama</small><strong>{{ $pendudukPenerima->agama ?: '-' }}</strong></div>
                                <div class="col-md-6"><small class="text-muted d-block">Kewarganegaraan</small><strong>{{ $pendudukPenerima->kewarganegaraan ?: '-' }}</strong></div>
                                <div class="col-12"><small class="text-muted d-block">Alamat</small><strong>{{ $pendudukPenerima->alamat ?: '-' }}</strong></div>
                                <div class="col-12"><small class="text-muted d-block">NIK</small><strong>{{ $pendudukPenerima->nik }}</strong></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card form-card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="module-icon"><i class="bi bi-file-earmark-plus"></i></span>
                        <div>
                            <p class="form-section-title mb-1">Form Template</p>
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

                    @if(!$canGenerate)
                        <div class="alert alert-info border-0 rounded-4">
                            <i class="bi bi-info-circle me-2"></i>Pilih template dan cari NIK penerima terlebih dahulu sebelum membuat surat.
                        </div>
                    @endif

                    <form id="suratKeluarTemplateForm" action="{{ url('/surat-keluar/store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_template" value="{{ $selectedTemplateId }}">
                        <input type="hidden" name="nik_penerima" value="{{ $pendudukPenerima?->nik }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nomor_surat" class="form-label fw-semibold">Nomor Surat <span class="required-dot">*</span></label>
                                <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat') }}" maxlength="100" placeholder="Isi nomor surat secara manual" required @disabled(!$canGenerate)>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_surat" class="form-label fw-semibold">Tanggal Surat <span class="required-dot">*</span></label>
                                <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required @disabled(!$canGenerate)>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tujuan / Penerima</label>
                                <input type="text" class="form-control" value="{{ $pendudukPenerima?->nama ?? '' }}" placeholder="Terisi otomatis dari data NIK" disabled>
                            </div>
                            <div class="col-md-6">
                                <label for="perihal" class="form-label fw-semibold">Perihal Surat <span class="required-dot">*</span></label>
                                <input type="text" class="form-control" id="perihal" name="perihal" value="{{ old('perihal') }}" maxlength="255" required @disabled(!$canGenerate)>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="border rounded-4 p-3">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="module-icon"><i class="bi bi-file-earmark-richtext"></i></span>
                                <div>
                                    <h6 class="fw-bold text-success mb-0">Placeholder Tambahan</h6>
                                    <small class="text-muted">Data penduduk dari NIK akan diisi otomatis. Field lain tetap diisi manual.</small>
                                </div>
                            </div>

                            @if($selectedTemplate)
                                <div class="alert alert-light border rounded-4">
                                    <i class="bi bi-file-earmark-text me-1"></i>Template dipilih: <strong>{{ $selectedTemplate->nama_template }}</strong>
                                </div>
                            @endif

                            <div id="templatePlaceholderContainer" class="d-grid gap-3"></div>
                        </div>

                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mt-4">
                            <a href="{{ url('/surat-keluar/create') }}" class="btn btn-light border px-4 rounded-pill"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <button type="submit" formaction="{{ url('/surat-keluar/preview-template') }}" formtarget="_blank" class="btn btn-info px-4 rounded-pill" @disabled(!$canGenerate)><i class="bi bi-eye me-1"></i>Preview PDF</button>
                                <button type="submit" formaction="{{ url('/surat-keluar/download-template') }}" formtarget="_blank" class="btn btn-outline-success px-4 rounded-pill" @disabled(!$canGenerate)><i class="bi bi-download me-1"></i>Download PDF</button>
                                <button type="submit" formaction="{{ url('/surat-keluar/store') }}" class="btn btn-success px-5 rounded-pill" @disabled(!$canGenerate)><i class="bi bi-save me-1"></i>Simpan Surat</button>
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
        const selectedTemplateId = @json((string) $selectedTemplateId);
        const container = document.getElementById('templatePlaceholderContainer');
        const coreFields = ['nomor_surat', 'tanggal_surat', 'tujuan', 'perihal'];
        const pendudukFields = ['nik', 'nama', 'tempat_tanggal_lahir', 'pekerjaan', 'jenis_kelamin', 'kewarganegaraan', 'agama', 'alamat'];

        function labelize(value) {
            return value.replace(/_/g, ' ').replace(/\b\w/g, function (letter) { return letter.toUpperCase(); });
        }

        function renderFields() {
            const template = templates[selectedTemplateId];
            container.innerHTML = '';

            if (!template) {
                container.innerHTML = '<div class="empty-state py-3"><i class="bi bi-file-earmark-text d-block mb-2"></i><strong>Pilih template untuk melihat data tambahan.</strong></div>';
                return;
            }

            const customPlaceholders = (template.placeholder || []).filter(function (placeholder) {
                return !coreFields.includes(placeholder) && !pendudukFields.includes(placeholder);
            });

            if (customPlaceholders.length === 0) {
                container.innerHTML = '<div class="alert alert-success rounded-4 mb-0"><i class="bi bi-check-circle me-1"></i>Semua placeholder template ini dapat diisi dari data utama surat dan Data Penduduk Temporal.</div>';
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

        renderFields();
    });
</script>
@endsection