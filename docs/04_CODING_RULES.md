# \# CODING RULES

# 

# Seluruh implementasi wajib mengikuti Bab 3.

# 

# Migration adalah sumber kebenaran.

# 

# Urutan implementasi:

# 

# Migration

# 

# ↓

# 

# Model

# 

# ↓

# 

# Controller

# 

# ↓

# 

# Route

# 

# ↓

# 

# Blade

# 

# ↓

# 

# UI

# 

# Jangan membalik urutan tersebut.

# 

# \---

# 

# Sebelum melakukan perubahan:

# 

# Audit Migration

# 

# Audit Model

# 

# Audit Controller

# 

# Audit Route

# 

# Audit Blade

# 

# Audit Dashboard

# 

# \---

# 

# Jika ada ketidaksesuaian:

# 

# Tampilkan:

# 

# \- lokasi file

# 

# \- penyebab

# 

# \- roadmap

# 

# \- diff

# 

# Jangan menerapkan perubahan.

# 

# Tunggu persetujuan.

# 

# \---

# 

# Jika modul belum dibuat:

# 

# Jangan hanya mengatakan:

# 

# Controller belum ada

# 

# View belum ada

# 

# Tetapi tampilkan:

# 

# \- File yang harus dibuat

# 

# \- Route yang diperlukan

# 

# \- Controller yang diperlukan

# 

# \- Blade yang diperlukan

# 

# \- Roadmap implementasi

# 

# \- Diff usulan

# 

# \---

# 

# Jangan membuat field baru.

# 

# Jangan menghapus field.

# 

# Jangan mengubah migration.

# 

# Kecuali memang Bab 3 mengharuskan demikian.

# 

# \---

# 

# Setelah implementasi:

# 

# Audit ulang.

# 

# Pastikan:

# 

# Migration

# 

# Model

# 

# Controller

# 

# Route

# 

# Blade

# 

# UI

# 

# seluruhnya sinkron.

# 

# Jangan commit kecuali diminta.

