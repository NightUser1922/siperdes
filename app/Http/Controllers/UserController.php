<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Menampilkan halaman login dengan deteksi IP
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect($this->dashboardPath(Auth::user()->role));
        }

        // Menangkap IP Address pengakses
        $ipAddress = $request->ip();
        
        // Mengirim data IP ke view 'login'
        return view('login', compact('ipAddress'));
    }

    // Proses autentikasi user
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string|max:50',
            'password' => 'required|string|max:255',
        ]);

        // Deteksi IP saat tombol login ditekan
        $ip = $request->ip();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            AuditLog::catat(
                $request,
                'Login',
                'Authentication',
                'Login berhasil ke sistem. User Agent: ' . $request->userAgent()
            );

            return redirect($this->dashboardPath(Auth::user()->role));
        }

        // Jika gagal, tampilkan pesan error beserta IP-nya (sebagai peringatan)
        return back()->with('loginError', "Login gagal! Akses Anda dari IP: $ip telah terekam sistem.");
    }

    public function logout(Request $request)
    {
        AuditLog::catat($request, 'Logout', 'Autentikasi', 'Logout dari sistem', Auth::user());
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    private function dashboardPath(string $role): string
    {
        return $role === 'Admin' ? '/admin/dashboard' : '/kades/dashboard';
    }
}
