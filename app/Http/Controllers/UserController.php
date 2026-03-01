<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// Kita tetap butuh Model UserTable jika nanti ingin mencatat log ke database
use App\Models\UserTable;

class UserController extends Controller
{
    // Menampilkan halaman login dengan deteksi IP
    public function index(Request $request)
    {
        // Menangkap IP Address pengakses
        $ipAddress = $request->ip();
        
        // Mengirim data IP ke view 'login'
        return view('login', compact('ipAddress'));
    }

    // Proses autentikasi user
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Deteksi IP saat tombol login ditekan
        $ip = $request->ip();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Logika: User berhasil masuk, IP dicatat (bisa dikembangkan ke tabel log)
            if (Auth::user()->level == 'Admin') {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/kades/dashboard');
            }
        }

        // Jika gagal, tampilkan pesan error beserta IP-nya (sebagai peringatan)
        return back()->with('loginError', "Login gagal! Akses Anda dari IP: $ip telah terekam sistem.");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}