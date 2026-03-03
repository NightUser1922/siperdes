<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPERDES - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; overflow-x: hidden; }
        .sidebar {
            height: 100vh;
            background-color: #1b5e20; /* Hijau Tua ala Pemerintahan */
            color: white;
            position: fixed;
            width: 250px;
            transition: all 0.3s;
        }
        .sidebar a {
            color: #c8e6c9;
            text-decoration: none;
            padding: 15px 20px;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #2e7d32;
            color: white;
            border-left: 4px solid #a5d6a7;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }
        .topbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h4 class="text-center py-4 fw-bold mb-0 border-bottom border-success">SIPERDES</h4>
        
        <small class="text-uppercase px-3 mt-4 d-block text-success fw-bold">Menu Utama</small>
        <a href="#" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        <a href="#"><i class="bi bi-inbox me-2"></i> Surat Masuk</a>
        <a href="#"><i class="bi bi-send me-2"></i> Surat Keluar</a>

        <small class="text-uppercase px-3 mt-4 d-block text-success fw-bold">Ekspansi (Fitur Baru)</small>
        <a href="#"><i class="bi bi-folder2-open me-2"></i> Arsip Dokumen</a>
        <a href="#"><i class="bi bi-bar-chart-line me-2"></i> Laporan Analitik</a>

        <small class="text-uppercase px-3 mt-4 d-block text-success fw-bold">Sistem</small>
        <a href="#"><i class="bi bi-shield-lock me-2"></i> Log Akses</a>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h5 class="mb-0 text-success fw-bold">@yield('title')</h5>
            <div>
                <span class="me-3 text-muted"><i class="bi bi-person-circle"></i> Halo, {{ Auth::user()->username ?? 'User' }}</span>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>