# Tahap 1

# ========

# 

# Sinkronisasi seluruh modul yang sudah ada.

# 

# \- Login

# \- Dashboard

# \- Surat Masuk

# \- Surat Keluar

# 

# Tahap 2

# ========

# 

# Implementasi modul baru:

# 

# \- Kegiatan Desa

# \- Bantuan Sosial

# 

# Tahap 3

# ========

# 

# Implementasi:

# 

# \- Audit Log

# \- Laporan

# 

# Tahap 4

# ========

# 

# Workflow Persetujuan Kepala Desa

# 

# Tahap 5

# ========

# 

# Modernisasi UI sesuai mockup

# 

# Tahap 6

# ========

# 

Testing seluruh CRUD

## Implementasi Arsip Digital
===

# 

# Modul Arsip Digital menggunakan Google Drive sebagai backend storage.

# 

# Prinsip implementasi:

# 

# \- Google Drive hanya sebagai media penyimpanan.

# \- Hak akses tetap dikontrol Laravel.

# \- File tidak boleh dibuat public.

# \- Tidak menggunakan permission "Anyone with the link".

# \- Preview dan download harus melalui controller Laravel.

# \- Hanya Admin dan Kepala Desa yang boleh mengakses Arsip Digital.

# \- Database menyimpan metadata file, bukan isi file.

