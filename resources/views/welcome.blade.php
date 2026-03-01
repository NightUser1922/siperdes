<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di SIPERDES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #e8f5e9; color: #2e7d32; }
        .hero-section { margin-top: 150px; }
        .btn-green { background-color: #66bb6a; color: white; border-radius: 25px; padding: 10px 30px; }
        .btn-green:hover { background-color: #43a047; color: white; }
    </style>
</head>
<body>
    <div class="container text-center hero-section">
        <h1 class="display-4 fw-bold">SIPERDES</h1>
        <p class="lead">Sistem Informasi Pengarsipan Surat Desa</p>
        <hr class="my-4" style="width: 100px; margin: auto; border-top: 3px solid #66bb6a;">
        <p>Silakan masuk untuk mengelola data surat masuk dan keluar secara digital.</p>
        <div class="mt-4">
            <a href="{{ route('login') }}" class="btn btn-green shadow-sm">Buka Halaman Login</a>
        </div>
    </div>
</body>
</html>