<?php

namespace App\Http\Controllers;

use App\Models\ArsipDigital;
use App\Models\AuditLog;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class ArsipDigitalController extends Controller
{
    private array $kategoriList = [
        'Surat Masuk',
        'Surat Keluar',
        'Kegiatan Desa',
        'Bantuan Sosial',
        'Laporan',
        'Template Surat',
        'Lainnya',
    ];

    public function __construct(private GoogleDriveService $googleDriveService)
    {
    }

    public function index(Request $request)
    {
        $this->authorizeAccess();

        $filters = $request->validate([
            'search' => 'nullable|string|max:100',
            'kategori' => 'nullable|string|max:100',
        ]);

        $query = ArsipDigital::with('user')->orderBy('id_arsip', 'desc');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nomor_arsip', 'like', '%' . $search . '%')
                    ->orWhere('nama_arsip', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%')
                    ->orWhere('uploader', 'like', '%' . $search . '%');
            });
        }

        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }

        $arsipDigital = $query->paginate(10)->withQueryString();
        $kategoriList = $this->kategoriList;
        $totalArsip = ArsipDigital::count();

        return view('arsip-digital', compact('arsipDigital', 'kategoriList', 'filters', 'totalArsip'));
    }

    public function create()
    {
        $this->authorizeAccess();
        $kategoriList = $this->kategoriList;

        return view('arsip-digital-create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $this->authorizeAccess();

        $validated = $request->validate([
            'nomor_arsip' => 'required|string|max:100',
            'nama_arsip' => 'required|string|max:150',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'file_arsip' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ]);

        try {
            $upload = $this->googleDriveService->upload($request->file('file_arsip'), $validated['nama_arsip']);

            $arsip = ArsipDigital::create([
                'nomor_arsip' => $validated['nomor_arsip'],
                'nama_arsip' => $validated['nama_arsip'],
                'kategori' => $validated['kategori'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'google_drive_file_id' => $upload['id'],
                'mime_type' => $upload['mime_type'],
                'ukuran' => $upload['size'],
                'original_name' => $request->file('file_arsip')->getClientOriginalName(),
                'uploader' => auth()->user()->nama ?? auth()->user()->username,
                'id_user' => auth()->user()->id_user,
            ]);

            AuditLog::catat($request, 'Upload Arsip', 'Arsip Digital', 'Upload arsip ' . $arsip->nama_arsip . ' ke Google Drive private');

            return redirect('/arsip-digital')->with('success', 'Arsip Digital berhasil diupload ke Google Drive!');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withInput()->with('error', 'Upload arsip ke Google Drive gagal: ' . $exception->getMessage());
        }
    }

    public function edit($id)
    {
        $this->authorizeAccess();
        $arsip = ArsipDigital::where('id_arsip', $id)->firstOrFail();
        $kategoriList = $this->kategoriList;

        return view('arsip-digital-edit', compact('arsip', 'kategoriList'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAccess();
        $arsip = ArsipDigital::where('id_arsip', $id)->firstOrFail();

        $validated = $request->validate([
            'nomor_arsip' => 'required|string|max:100',
            'nama_arsip' => 'required|string|max:150',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'file_arsip' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ]);

        $dataUpdate = [
            'nomor_arsip' => $validated['nomor_arsip'],
            'nama_arsip' => $validated['nama_arsip'],
            'kategori' => $validated['kategori'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'uploader' => auth()->user()->nama ?? auth()->user()->username,
            'id_user' => auth()->user()->id_user,
        ];

        try {
            if ($request->hasFile('file_arsip')) {
                $upload = $this->googleDriveService->upload($request->file('file_arsip'), $validated['nama_arsip']);
                $oldFileId = $arsip->google_drive_file_id;

                $dataUpdate['google_drive_file_id'] = $upload['id'];
                $dataUpdate['mime_type'] = $upload['mime_type'];
                $dataUpdate['ukuran'] = $upload['size'];
                $dataUpdate['original_name'] = $request->file('file_arsip')->getClientOriginalName();

                try {
                    $this->googleDriveService->delete($oldFileId);
                } catch (Throwable $exception) {
                    report($exception);
                }
            }

            $arsip->update($dataUpdate);
            AuditLog::catat($request, 'Edit data', 'Arsip Digital', 'Memperbarui metadata arsip ' . $arsip->nama_arsip);

            return redirect('/arsip-digital')->with('success', 'Arsip Digital berhasil diperbarui!');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withInput()->with('error', 'Update arsip gagal: ' . $exception->getMessage());
        }
    }

    public function preview(Request $request, $id)
    {
        $this->authorizeAccess();
        $arsip = ArsipDigital::where('id_arsip', $id)->firstOrFail();

        try {
            $file = $this->googleDriveService->read($arsip->google_drive_file_id);
            AuditLog::catat($request, 'Preview Arsip', 'Arsip Digital', 'Preview arsip ' . $arsip->nama_arsip);

            return $this->streamFile($file['content'], $arsip, 'inline');
        } catch (Throwable $exception) {
            report($exception);

            return redirect('/arsip-digital')->with('error', 'Preview arsip gagal: ' . $exception->getMessage());
        }
    }

    public function download(Request $request, $id)
    {
        $this->authorizeAccess();
        $arsip = ArsipDigital::where('id_arsip', $id)->firstOrFail();

        try {
            $file = $this->googleDriveService->read($arsip->google_drive_file_id);
            AuditLog::catat($request, 'Download Arsip', 'Arsip Digital', 'Download arsip ' . $arsip->nama_arsip);

            return $this->streamFile($file['content'], $arsip, 'attachment');
        } catch (Throwable $exception) {
            report($exception);

            return redirect('/arsip-digital')->with('error', 'Download arsip gagal: ' . $exception->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeAccess();
        $arsip = ArsipDigital::where('id_arsip', $id)->firstOrFail();

        try {
            $this->googleDriveService->delete($arsip->google_drive_file_id);
            $namaArsip = $arsip->nama_arsip;
            $arsip->delete();
            AuditLog::catat($request, 'Delete Arsip', 'Arsip Digital', 'Menghapus arsip ' . $namaArsip . ' dari Google Drive private');

            return redirect('/arsip-digital')->with('success', 'Arsip Digital berhasil dihapus!');
        } catch (Throwable $exception) {
            report($exception);

            return redirect('/arsip-digital')->with('error', 'Hapus arsip gagal: ' . $exception->getMessage());
        }
    }

    private function authorizeAccess(): void
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Kepala Desa'], true)) {
            abort(403);
        }
    }

    private function streamFile(string $content, ArsipDigital $arsip, string $disposition)
    {
        $fileName = $this->downloadName($arsip);

        return response($content, 200, [
            'Content-Type' => $arsip->mime_type ?: 'application/octet-stream',
            'Content-Length' => strlen($content),
            'Content-Disposition' => $disposition . '; filename="' . $fileName . '"',
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
        ]);
    }

    private function downloadName(ArsipDigital $arsip): string
    {
        $name = $arsip->original_name ?: $arsip->nama_arsip;
        $name = str_replace(['"', '\\', '/'], '', $name);

        if (!Str::contains($name, '.') && $arsip->mime_type) {
            $extension = match ($arsip->mime_type) {
                'application/pdf' => 'pdf',
                'application/msword' => 'doc',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                'application/vnd.ms-excel' => 'xls',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                default => null,
            };

            if ($extension) {
                $name .= '.' . $extension;
            }
        }

        return $name ?: 'arsip-digital';
    }
}