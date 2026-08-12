<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PendudukTemporal;
use App\Models\SuratKeluar;
use App\Models\TemplateSurat;
use App\Services\TemplateSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SuratKeluarController extends Controller
{
    public function __construct(private TemplateSuratService $templateService)
    {
    }

    public function index()
    {
        $suratKeluar = SuratKeluar::with('templateSurat')->orderBy('id_surat_keluar', 'desc')->get();

        return view('surat-keluar', compact('suratKeluar'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        return view('surat-keluar-create');
    }

    public function createManual()
    {
        $this->authorizeAdmin();

        return view('surat-keluar-create-manual');
    }

    public function createTemplate(Request $request)
    {
        $this->authorizeAdmin();
        $templates = $this->activeTemplates();
        $templatesForJs = $this->templatesForJs($templates);
        $selectedTemplateId = $request->query('id_template');
        $nikPenerima = trim((string) $request->query('nik_penerima', ''));
        $pendudukPenerima = null;
        $pendudukNotFound = false;

        if ($nikPenerima !== '') {
            $request->validate([
                'nik_penerima' => 'string|max:20',
                'id_template' => 'nullable|exists:tb_template_surat,id_template',
            ], [
                'nik_penerima.max' => 'NIK maksimal 20 karakter.',
                'id_template.exists' => 'Template surat tidak ditemukan.',
            ]);

            $pendudukPenerima = PendudukTemporal::where('nik', $nikPenerima)->first();
            $pendudukNotFound = !$pendudukPenerima;
        }

        return view('surat-keluar-create-template', compact(
            'templates',
            'templatesForJs',
            'selectedTemplateId',
            'nikPenerima',
            'pendudukPenerima',
            'pendudukNotFound'
        ));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $validated = $request->validate($this->rules(true), $this->templateMessages());
        $usesUpload = $request->hasFile('file_surat');
        $template = $usesUpload ? null : $this->templateFromRequest($validated['id_template'] ?? null);
        $pendudukPenerima = $template ? $this->pendudukFromRequest($request) : null;
        $templateData = $template ? $this->templateData($request, $template, $pendudukPenerima) : null;
        $namaFile = $usesUpload ? $this->simpanFileManual($request) : '';

        $suratKeluar = SuratKeluar::create([
            'id_template' => $template?->id_template,
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'tujuan' => $pendudukPenerima?->nama ?? $validated['tujuan'],
            'perihal' => $validated['perihal'],
            'file_surat' => $namaFile,
            'status_persetujuan' => 'Menunggu',
            'snapshot_identitas' => $pendudukPenerima ? $this->snapshotIdentitas($pendudukPenerima) : null,
            'data_template' => $templateData,
            'metode_pembuatan' => $usesUpload ? 'Upload' : 'Template',
            'id_user' => auth()->user()->id_user,
        ]);

        if ($template) {
            $namaFile = $this->generateFileSurat($suratKeluar, $template, $templateData);
            $suratKeluar->update(['file_surat' => $namaFile]);
            $pendudukPenerima->refreshLastUsedAt();
        }

        AuditLog::catat($request, 'Tambah data', 'Surat Keluar', 'Menambah surat keluar ' . $suratKeluar->nomor_surat);

        return redirect('/surat-keluar')->with('success', 'Data Surat Keluar berhasil disimpan!');
    }

    public function edit($id)
    {
        $this->authorizeAdmin();
        $suratKeluar = SuratKeluar::with('templateSurat')->where('id_surat_keluar', $id)->firstOrFail();
        $templates = $this->activeTemplates($suratKeluar->id_template);
        $templatesForJs = $this->templatesForJs($templates);

        return view('surat-keluar-edit', compact('suratKeluar', 'templates', 'templatesForJs'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();
        $suratKeluar = SuratKeluar::where('id_surat_keluar', $id)->firstOrFail();
        $validated = $request->validate($this->rules(false));
        $usesUpload = $request->hasFile('file_surat');
        $template = (!$usesUpload && !empty($validated['id_template'])) ? $this->templateFromRequest($validated['id_template']) : null;
        $templateData = $template ? $this->templateData($request, $template) : null;

        $dataUpdate = [
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'tujuan' => $validated['tujuan'],
            'perihal' => $validated['perihal'],
            'id_user' => auth()->user()->id_user,
        ];

        if ($usesUpload) {
            $this->hapusFileSurat($suratKeluar->file_surat);
            $dataUpdate['file_surat'] = $this->simpanFileManual($request);
            $dataUpdate['id_template'] = null;
            $dataUpdate['data_template'] = null;
            $dataUpdate['metode_pembuatan'] = 'Upload';
        } elseif ($template) {
            $dataUpdate['id_template'] = $template->id_template;
            $dataUpdate['data_template'] = $templateData;
            $dataUpdate['metode_pembuatan'] = 'Template';
        }

        $suratKeluar->update($dataUpdate);

        if ($template) {
            $namaFile = $this->generateFileSurat($suratKeluar->fresh(), $template, $templateData);
            $suratKeluar->update(['file_surat' => $namaFile]);
        }

        AuditLog::catat($request, 'Edit data', 'Surat Keluar', 'Memperbarui surat keluar ' . $suratKeluar->nomor_surat);

        return redirect('/surat-keluar')->with('success', 'Data Surat Keluar berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeAdmin();
        $suratKeluar = SuratKeluar::where('id_surat_keluar', $id)->firstOrFail();
        $this->hapusFileSurat($suratKeluar->file_surat);

        $nomorSurat = $suratKeluar->nomor_surat;
        $suratKeluar->delete();
        AuditLog::catat($request, 'Hapus data', 'Surat Keluar', 'Menghapus surat keluar ' . $nomorSurat);

        return redirect('/surat-keluar')->with('success', 'Data Surat Keluar berhasil dihapus!');
    }

    public function previewTemplate(Request $request)
    {
        $this->authorizeAdmin();
        [$template, $data] = $this->validatedTemplatePreview($request);
        AuditLog::catat($request, 'Preview PDF', 'Surat Keluar', 'Preview PDF dari template ' . $template->nama_template);

        return $this->templateService->renderPdf($template, $data)->stream($this->pdfName($request->nomor_surat ?: 'preview'));
    }

    public function downloadTemplate(Request $request)
    {
        $this->authorizeAdmin();
        [$template, $data] = $this->validatedTemplatePreview($request);
        AuditLog::catat($request, 'Download PDF', 'Surat Keluar', 'Download PDF dari template ' . $template->nama_template);

        return $this->templateService->renderPdf($template, $data)->download($this->pdfName($request->nomor_surat ?: 'template'));
    }

    public function generate(Request $request, $id)
    {
        $this->authorizeAdmin();
        $suratKeluar = SuratKeluar::with('templateSurat')->where('id_surat_keluar', $id)->firstOrFail();

        if (!$suratKeluar->templateSurat || !$suratKeluar->data_template) {
            return redirect('/surat-keluar')->with('error', 'Surat ini tidak memiliki template untuk digenerate ulang.');
        }

        $namaFile = $this->generateFileSurat($suratKeluar, $suratKeluar->templateSurat, $suratKeluar->data_template);
        $suratKeluar->update(['file_surat' => $namaFile]);
        AuditLog::catat($request, 'Generate PDF', 'Surat Keluar', 'Generate ulang PDF surat keluar ' . $suratKeluar->nomor_surat);

        return redirect('/surat-keluar')->with('success', 'PDF Surat Keluar berhasil digenerate ulang!');
    }

    public function preview(Request $request, $id)
    {
        $suratKeluar = SuratKeluar::with('templateSurat')->where('id_surat_keluar', $id)->firstOrFail();
        AuditLog::catat($request, 'Preview PDF', 'Surat Keluar', 'Preview PDF surat keluar ' . $suratKeluar->nomor_surat);

        if ($this->isPdfFile($suratKeluar->file_surat)) {
            return response()->file(public_path('uploads/surat_keluar/' . $suratKeluar->file_surat));
        }

        if ($suratKeluar->templateSurat && $suratKeluar->data_template) {
            return $this->templateService->renderPdf($suratKeluar->templateSurat, $suratKeluar->data_template)->stream($this->pdfName($suratKeluar->nomor_surat));
        }

        return redirect('/surat-keluar')->with('error', 'Preview PDF tidak tersedia untuk surat ini.');
    }

    public function download(Request $request, $id)
    {
        $suratKeluar = SuratKeluar::with('templateSurat')->where('id_surat_keluar', $id)->firstOrFail();
        AuditLog::catat($request, 'Download PDF', 'Surat Keluar', 'Download PDF surat keluar ' . $suratKeluar->nomor_surat);

        if ($this->fileExists($suratKeluar->file_surat)) {
            return response()->download(public_path('uploads/surat_keluar/' . $suratKeluar->file_surat));
        }

        if ($suratKeluar->templateSurat && $suratKeluar->data_template) {
            return $this->templateService->renderPdf($suratKeluar->templateSurat, $suratKeluar->data_template)->download($this->pdfName($suratKeluar->nomor_surat));
        }

        return redirect('/surat-keluar')->with('error', 'File surat tidak tersedia.');
    }

    private function rules(bool $create): array
    {
        $fileRule = $create ? 'nullable|required_without:id_template' : 'nullable';
        $templateRule = $create ? 'nullable|required_without:file_surat' : 'nullable';
        $tujuanRule = $create ? 'nullable|required_without:id_template|string|max:100' : 'required|string|max:100';

        return [
            'id_template' => $templateRule . '|exists:tb_template_surat,id_template',
            'nik_penerima' => 'nullable|required_with:id_template|string|max:20',
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tujuan' => $tujuanRule,
            'perihal' => 'required|string|max:255',
            'data_template' => 'nullable|array',
            'data_template.*' => 'nullable|string|max:1000',
            'file_surat' => $fileRule . '|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx|max:5120',
        ];
    }

    private function validatedTemplatePreview(Request $request): array
    {
        $validated = $request->validate([
            'id_template' => 'required|exists:tb_template_surat,id_template',
            'nik_penerima' => 'required|string|max:20|exists:penduduk_temporal,nik',
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|string|max:255',
            'data_template' => 'nullable|array',
            'data_template.*' => 'nullable|string|max:1000',
        ], $this->templateMessages());

        $template = $this->templateFromRequest($validated['id_template']);
        $pendudukPenerima = $this->pendudukFromRequest($request);

        return [$template, $this->templateData($request, $template, $pendudukPenerima)];
    }

    private function activeTemplates(?int $includeId = null)
    {
        return TemplateSurat::query()
            ->where(function ($query) use ($includeId) {
                $query->where('status', 'Aktif');
                if ($includeId) {
                    $query->orWhere('id_template', $includeId);
                }
            })
            ->orderBy('nama_template')
            ->get();
    }

    private function templatesForJs($templates): array
    {
        return $templates->mapWithKeys(fn ($template) => [
            $template->id_template => [
                'nama_template' => $template->nama_template,
                'jenis_surat' => $template->jenis_surat,
                'placeholder' => array_values($template->placeholder ?? []),
            ],
        ])->all();
    }

    private function templateFromRequest(?int $idTemplate): TemplateSurat
    {
        return TemplateSurat::where('id_template', $idTemplate)->firstOrFail();
    }

    private function templateData(Request $request, TemplateSurat $template, ?PendudukTemporal $pendudukPenerima = null): array
    {
        $manualData = $request->input('data_template', []);
        $pendudukData = $pendudukPenerima ? $this->pendudukPlaceholderData($pendudukPenerima) : [];
        $coreData = [
            'nomor_surat' => $request->nomor_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'tujuan' => $pendudukPenerima?->nama ?? $request->tujuan,
            'perihal' => $request->perihal,
        ];

        $data = [];
        foreach ($template->placeholder ?? [] as $placeholder) {
            $data[$placeholder] = $coreData[$placeholder]
                ?? $pendudukData[$placeholder]
                ?? ($manualData[$placeholder] ?? '');
        }

        return $data;
    }

    private function pendudukFromRequest(Request $request): PendudukTemporal
    {
        $validated = $request->validate([
            'nik_penerima' => 'required|string|max:20|exists:penduduk_temporal,nik',
        ], $this->templateMessages());

        return PendudukTemporal::where('nik', $validated['nik_penerima'])->firstOrFail();
    }

    private function pendudukPlaceholderData(PendudukTemporal $penduduk): array
    {
        $tanggalLahir = $penduduk->tanggal_lahir?->format('d-m-Y');
        $tempatTanggalLahir = trim(implode(', ', array_filter([
            $penduduk->tempat_lahir,
            $tanggalLahir,
        ])));

        return [
            'nik' => $penduduk->nik,
            'nama' => $penduduk->nama,
            'tempat_tanggal_lahir' => $tempatTanggalLahir,
            'pekerjaan' => $penduduk->pekerjaan,
            'jenis_kelamin' => $penduduk->jenis_kelamin,
            'kewarganegaraan' => $penduduk->kewarganegaraan,
            'agama' => $penduduk->agama,
            'alamat' => $penduduk->alamat,
        ];
    }

    private function snapshotIdentitas(PendudukTemporal $penduduk): array
    {
        return [
            'nik' => $penduduk->nik,
            'nama' => $penduduk->nama,
            'jenis_kelamin' => $penduduk->jenis_kelamin,
            'bin_binti' => $penduduk->bin_binti,
            'tempat_lahir' => $penduduk->tempat_lahir,
            'tanggal_lahir' => $penduduk->tanggal_lahir?->toDateString(),
            'kewarganegaraan' => $penduduk->kewarganegaraan,
            'agama' => $penduduk->agama,
            'pekerjaan' => $penduduk->pekerjaan,
            'alamat' => $penduduk->alamat,
        ];
    }

    private function templateMessages(): array
    {
        return [
            'nik_penerima.required' => 'NIK penerima wajib dicari terlebih dahulu.',
            'nik_penerima.required_with' => 'NIK penerima wajib dicari terlebih dahulu.',
            'nik_penerima.exists' => 'Data penduduk dengan NIK tersebut tidak ditemukan.',
            'nik_penerima.max' => 'NIK maksimal 20 karakter.',
        ];
    }
    private function simpanFileManual(Request $request): ?string
    {
        if (!$request->hasFile('file_surat')) {
            return null;
        }

        File::ensureDirectoryExists(public_path('uploads/surat_keluar'));
        $file = $request->file('file_surat');
        $namaFile = 'SK_UPLOAD_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/surat_keluar'), $namaFile);

        return $namaFile;
    }

    private function generateFileSurat(SuratKeluar $suratKeluar, TemplateSurat $template, array $data): string
    {
        if ($suratKeluar->file_surat && str_starts_with($suratKeluar->file_surat, 'SK_TEMPLATE_')) {
            $this->hapusFileSurat($suratKeluar->file_surat);
        }

        $namaFile = 'SK_TEMPLATE_' . $suratKeluar->id_surat_keluar . '_' . time() . '.pdf';

        return $this->templateService->savePdf($template, $data, $namaFile);
    }

    private function hapusFileSurat(?string $namaFile): void
    {
        if (!$namaFile) {
            return;
        }

        $path = public_path('uploads/surat_keluar/' . $namaFile);
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    private function isPdfFile(?string $namaFile): bool
    {
        return $this->fileExists($namaFile) && strtolower(pathinfo($namaFile, PATHINFO_EXTENSION)) === 'pdf';
    }

    private function fileExists(?string $namaFile): bool
    {
        return $namaFile && File::exists(public_path('uploads/surat_keluar/' . $namaFile));
    }

    private function pdfName(string $nomorSurat): string
    {
        $nomorSurat = preg_replace('/[^A-Za-z0-9_-]+/', '-', $nomorSurat) ?: 'surat-keluar';

        return 'Surat-Keluar-' . trim($nomorSurat, '-') . '.pdf';
    }
}