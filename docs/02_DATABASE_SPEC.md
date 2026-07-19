# \# DATABASE SPECIFICATION

# 

# Database merupakan source of truth implementasi.

# 

# Semua Model, Controller, Route, Blade harus mengikuti struktur ini.

# 

# \---

# 

# \## tb\_user

# 

# id\_user

# 

# nama

# 

# username

# 

# password

# 

# role

# 

# created\_at

# 

# updated\_at

# 

# \---

# 

# \## tb\_surat\_masuk

# 

# id\_surat\_masuk

# 

# nomor\_surat

# 

# tanggal\_surat

# 

# pengirim

# 

# perihal

# 

# file\_surat

# 

# status\_verifikasi

# 

# id\_user

# 

# created\_at

# 

# updated\_at

# 

# \---

# 

# \## tb\_surat\_keluar

# 

# ...

# 

# \---

# 

# \## tb\_kegiatan\_desa

# 

# id\_kegiatan

# 

# nama\_kegiatan

# 

# tanggal\_kegiatan

# 

# lokasi

# 

# keterangan

# 

# id\_user

# 

# created\_at

# 

# updated\_at

# 

# \---

# 

# \## tb\_bantuan\_sosial

# 

# ...

# 

# \---

# 

# \## tb\_audit\_log

# 

# ...

