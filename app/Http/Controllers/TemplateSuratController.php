<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\TemplateSurat;
use App\Services\TemplateSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateSuratController extends Controller
{
    public function __construct(private TemplateSuratService $templateService)
    {
    }

    public function index()
    {
        $templates = TemplateSurat::orderBy('id_template', 'desc')->get();

        return view('template-surat.index', compact('templates'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('template-surat.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'nama_template' => 'required|string|max:150',
            'jenis_surat' => 'required|string|max:100',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'file_template' => 'required|file|mimes:docx|max:5120',
        ]);

        $fileTemplate = $this->storeTemplateFile($request);
        $placeholders = $this->templateService->extractPlaceholders($fileTemplate);

        $template = TemplateSurat::create([
            'nama_template' => $validated['nama_template'],
            'jenis_surat' => $validated['jenis_surat'],
            'status' => $validated['status'],
            'file_template' => $fileTemplate,
            'placeholder' => $placeholders,
            'id_user' => auth()->user()->id_user,
        ]);

        AuditLog::catat($request, 'Tambah data', 'Template Surat', 'Menambah template surat ' . $template->nama_template);

        return redirect('/template-surat')->with('success', 'Template Surat berhasil disimpan!');
    }

    public function edit($id)
    {
        $this->authorizeAdmin();
        $template = TemplateSurat::where('id_template', $id)->firstOrFail();

        return view('template-surat.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();
        $template = TemplateSurat::where('id_template', $id)->firstOrFail();

        $validated = $request->validate([
            'nama_template' => 'required|string|max:150',
            'jenis_surat' => 'required|string|max:100',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'file_template' => 'nullable|file|mimes:docx|max:5120',
        ]);

        $data = [
            'nama_template' => $validated['nama_template'],
            'jenis_surat' => $validated['jenis_surat'],
            'status' => $validated['status'],
            'id_user' => auth()->user()->id_user,
        ];

        if ($request->hasFile('file_template')) {
            $this->deleteTemplateFile($template->file_template);
            $data['file_template'] = $this->storeTemplateFile($request);
            $data['placeholder'] = $this->templateService->extractPlaceholders($data['file_template']);
        }

        $template->update($data);
        AuditLog::catat($request, 'Edit data', 'Template Surat', 'Memperbarui template surat ' . $template->nama_template);

        return redirect('/template-surat')->with('success', 'Template Surat berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeAdmin();
        $template = TemplateSurat::where('id_template', $id)->firstOrFail();
        $namaTemplate = $template->nama_template;

        $this->deleteTemplateFile($template->file_template);
        $template->delete();
        AuditLog::catat($request, 'Hapus data', 'Template Surat', 'Menghapus template surat ' . $namaTemplate);

        return redirect('/template-surat')->with('success', 'Template Surat berhasil dihapus!');
    }

    public function download($id)
    {
        $template = TemplateSurat::where('id_template', $id)->firstOrFail();

        return Storage::download($template->file_template, basename($template->file_template));
    }

    private function storeTemplateFile(Request $request): string
    {
        $file = $request->file('file_template');
        $namaFile = 'template_' . time() . '_' . uniqid() . '.docx';

        return $file->storeAs('templates', $namaFile);
    }

    private function deleteTemplateFile(?string $fileTemplate): void
    {
        if ($fileTemplate && Storage::exists($fileTemplate)) {
            Storage::delete($fileTemplate);
        }
    }
}