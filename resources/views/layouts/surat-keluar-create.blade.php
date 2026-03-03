<div class="container mt-4">
    <div class="card shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-header bg-primary text-white">Input Surat Keluar</div>
        <div class="card-body">
            <form action="/surat-keluar/store" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nomor Surat (Otomatis)</label>
                    <input type="text" name="no_surat" class="form-control" value="{{ $noOtomatis }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Surat</label>
                    <input type="date" name="tgl_surat" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tujuan</label>
                    <input type="text" name="tujuan" class="form-control" placeholder="Contoh: Camat Amawang" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Perihal</label>
                    <textarea name="perihal" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload Scan Surat (Foto/Gambar)</label>
                    <input type="file" name="file_scan" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-success w-100">Simpan Arsip Keluar</button>
            </form>
        </div>
    </div>
</div>