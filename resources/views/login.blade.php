<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SIPERDES - UNISKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #e8f5e9; /* Hijau sangat muda (Mint) */
        }
        .login-container { 
            margin-top: 100px; 
            max-width: 400px; 
            border: none;
            border-radius: 15px;
        }
        .btn-primary {
            background-color: #66bb6a; /* Hijau kalem */
            border-color: #66bb6a;
        }
        .btn-primary:hover {
            background-color: #43a047; /* Hijau agak gelap saat hover */
            border-color: #43a047;
        }
        .ip-box { 
            font-size: 0.8rem; 
            color: #2e7d32; /* Hijau tua untuk teks IP */
        }
        .card-title {
            color: #2e7d32;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="card login-container shadow p-4">
            <h3 class="text-center mb-4 card-title">SIPERDES</h3>
            
            @if(session('loginError'))
                <div class="alert alert-danger p-2 small">
                    {{ session('loginError') }}
                </div>
            @endif

            <form action="/login" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 shadow-sm">Masuk Sistem</button>
            </form>

            <div class="mt-4 text-center ip-box">
                <p>Alamat IP Anda Terdeteksi: <strong>{{ $ipAddress }}</strong></p>
                <p class="text-muted small">Akses masuk dipantau oleh sistem keamanan desa.</p>
            </div>
        </div>
    </div>
</body>
</html>