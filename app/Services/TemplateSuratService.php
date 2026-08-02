<?php

namespace App\Services;

use App\Models\TemplateSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;

class TemplateSuratService
{
    public function extractPlaceholders(string $fileTemplate): array
    {
        $processor = new TemplateProcessor(Storage::path($fileTemplate));
        $variables = array_values(array_unique($processor->getVariables()));
        sort($variables);

        return $variables;
    }

    public function renderPdf(TemplateSurat $templateSurat, array $data)
    {
        $docxPath = $this->renderDocx($templateSurat, $data);
        $htmlPath = storage_path('app/generated/surat_keluar/' . pathinfo($docxPath, PATHINFO_FILENAME) . '.html');

        $phpWord = IOFactory::load($docxPath);
        $writer = IOFactory::createWriter($phpWord, 'HTML');
        $writer->save($htmlPath);

        $html = File::get($htmlPath);
        File::delete([$docxPath, $htmlPath]);

        return Pdf::loadHTML($html)->setPaper('a4', 'portrait');
    }

    public function savePdf(TemplateSurat $templateSurat, array $data, string $namaFile): string
    {
        File::ensureDirectoryExists(public_path('uploads/surat_keluar'));
        $this->renderPdf($templateSurat, $data)->save(public_path('uploads/surat_keluar/' . $namaFile));

        return $namaFile;
    }

    private function renderDocx(TemplateSurat $templateSurat, array $data): string
    {
        File::ensureDirectoryExists(storage_path('app/generated/surat_keluar'));

        $processor = new TemplateProcessor(Storage::path($templateSurat->file_template));
        foreach ($templateSurat->placeholder ?? [] as $placeholder) {
            $processor->setValue($placeholder, $this->value($data[$placeholder] ?? ''));
        }

        $docxPath = storage_path('app/generated/surat_keluar/template_' . $templateSurat->id_template . '_' . uniqid() . '.docx');
        $processor->saveAs($docxPath);

        return $docxPath;
    }

    private function value(mixed $value): string
    {
        if (is_array($value)) {
            return implode(', ', array_filter($value));
        }

        return trim((string) $value);
    }
}