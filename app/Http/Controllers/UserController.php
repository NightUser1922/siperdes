<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UserTable;

class UserController extends Controller
{
    // Menampilkan halaman login
    public function index()
    {
        return view('login');
    }

    // Proses autentikasi user
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Cek level user untuk redirect ke dashboard yang tepat
            if (Auth::user()->level == 'Admin') {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/kades/dashboard');
            }
        }

        return back()->with('loginError', 'Username atau Password salah!');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}