<?php

namespace App\Http\Controllers;

use App\Services\GoogleDriveService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GoogleDriveOAuthController extends Controller
{
    public function __construct(private GoogleDriveService $googleDriveService)
    {
    }

    public function connect(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $state = Str::random(40);
        $request->session()->put('google_drive_oauth_state', $state);

        return redirect()->away($this->googleDriveService->oauthAuthUrl($state));
    }

    public function callback(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        if ($request->query('state') !== $request->session()->pull('google_drive_oauth_state')) {
            return redirect('/arsip-digital')->with('error', 'State OAuth Google Drive tidak valid.');
        }

        if ($request->filled('error')) {
            return redirect('/arsip-digital')->with('error', 'Google Drive gagal dihubungkan: ' . $request->query('error'));
        }

        if (!$request->filled('code')) {
            return redirect('/arsip-digital')->with('error', 'Kode OAuth Google Drive tidak diterima.');
        }

        $this->googleDriveService->storeOAuthToken($request->query('code'));

        return redirect('/arsip-digital')->with('success', 'Google Drive berhasil dihubungkan.');
    }

    protected function authorizeAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'Admin') {
            abort(403);
        }
    }
}
