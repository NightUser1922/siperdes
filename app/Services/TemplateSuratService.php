<?php

namespace App\Services;

use App\Models\TemplateSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\Process\Process;

class TemplateSuratService
{
    public function extractPlaceholders(string $fileTemplate): array
    {
        $processor = $this->templateProcessor(Storage::path($fileTemplate));

        try {
            $variables = array_values(array_unique($processor->getVariables()));
            sort($variables);

            return $variables;
        } finally {
            $this->resetTemplateProcessorMacroChars();
        }
    }

    public function placeholders(TemplateSurat $templateSurat): array
    {
        $storedPlaceholders = array_values(array_filter($templateSurat->placeholder ?? []));
        if ($storedPlaceholders !== []) {
            return $storedPlaceholders;
        }

        return $this->extractPlaceholders($templateSurat->file_template);
    }

    public function streamPdf(TemplateSurat $templateSurat, array $data, string $fileName)
    {
        $pdfPath = $this->renderPdfPath($templateSurat, $data);

        return response()
            ->file($pdfPath, ['Content-Type' => 'application/pdf'])
            ->deleteFileAfterSend(true);
    }

    public function downloadPdf(TemplateSurat $templateSurat, array $data, string $fileName)
    {
        $pdfPath = $this->renderPdfPath($templateSurat, $data);

        return response()
            ->download($pdfPath, $fileName, ['Content-Type' => 'application/pdf'])
            ->deleteFileAfterSend(true);
    }

    public function renderPdf(TemplateSurat $templateSurat, array $data)
    {
        $docxPath = $this->renderDocx($templateSurat, $data);
        $htmlPath = storage_path('app/generated/surat_keluar/' . pathinfo($docxPath, PATHINFO_FILENAME) . '.html');

        try {
            $phpWord = IOFactory::load($docxPath);
            $writer = IOFactory::createWriter($phpWord, 'HTML');
            $writer->save($htmlPath);

            $html = File::get($htmlPath);

            return Pdf::loadHTML($html)->setPaper('a4', 'portrait');
        } finally {
            File::delete([$docxPath, $htmlPath]);
        }
    }

    public function savePdf(TemplateSurat $templateSurat, array $data, string $namaFile): string
    {
        File::ensureDirectoryExists(public_path('uploads/surat_keluar'));
        $pdfPath = $this->renderPdfPath($templateSurat, $data);
        File::copy($pdfPath, public_path('uploads/surat_keluar/' . $namaFile));
        File::delete($pdfPath);

        return $namaFile;
    }

    private function renderDocx(TemplateSurat $templateSurat, array $data): string
    {
        File::ensureDirectoryExists(storage_path('app/generated/surat_keluar'));

        $placeholders = $this->placeholders($templateSurat);
        $processor = $this->templateProcessor(Storage::path($templateSurat->file_template));
        $previousEscaping = Settings::isOutputEscapingEnabled();
        Settings::setOutputEscapingEnabled(true);

        try {
            foreach ($placeholders as $placeholder) {
                $processor->setValue($placeholder, $this->value($data[$placeholder] ?? ''));
            }

            $docxPath = storage_path('app/generated/surat_keluar/template_' . $templateSurat->id_template . '_' . uniqid() . '.docx');
            $processor->saveAs($docxPath);
        } finally {
            Settings::setOutputEscapingEnabled($previousEscaping);
            $this->resetTemplateProcessorMacroChars();
        }

        return $docxPath;
    }

    private function renderPdfPath(TemplateSurat $templateSurat, array $data): string
    {
        File::ensureDirectoryExists(storage_path('app/generated/surat_keluar'));

        $docxPath = $this->renderDocx($templateSurat, $data);
        $this->ensureDocxReadable($docxPath);
        $pdfPath = storage_path('app/generated/surat_keluar/' . pathinfo($docxPath, PATHINFO_FILENAME) . '.pdf');

        try {
            if ($this->convertDocxToPdfWithWord($docxPath, $pdfPath)) {
                return $pdfPath;
            }
        } catch (\Throwable $exception) {
            Log::warning('Konversi DOCX ke PDF via Microsoft Word gagal. Menggunakan fallback DomPDF.', [
                'template_id' => $templateSurat->id_template,
                'message' => $exception->getMessage(),
            ]);
        }

        $this->ensureDocxReadable($docxPath);

        try {
            return $this->convertDocxToPdfWithDompdf($docxPath, $pdfPath);
        } finally {
            File::delete($docxPath);
        }
    }

    private function convertDocxToPdfWithWord(string $docxPath, string $pdfPath): bool
    {
        if (PHP_OS_FAMILY !== 'Windows' || !File::exists('C:\\Program Files\\Microsoft Office\\root\\Office16\\WINWORD.EXE')) {
            return false;
        }

        $scriptPath = str_replace('/', DIRECTORY_SEPARATOR, storage_path('app/generated/surat_keluar/convert_' . uniqid() . '.ps1'));
        $inputPath = str_replace('/', DIRECTORY_SEPARATOR, realpath($docxPath) ?: $docxPath);
        $outputPath = str_replace('/', DIRECTORY_SEPARATOR, $pdfPath);
        File::delete($outputPath);

        File::put($scriptPath, <<<'POWERSHELL'
param(
    [Parameter(Mandatory = $true)] [string] $InputPath,
    [Parameter(Mandatory = $true)] [string] $OutputPath
)

$ErrorActionPreference = 'Stop'
$word = $null
$document = $null

try {
    $word = New-Object -ComObject Word.Application
    $word.Visible = $false
    $word.DisplayAlerts = 0
    $document = $word.Documents.Open($InputPath, $false, $true)
    $document.ExportAsFixedFormat($OutputPath, 17)
} finally {
    if ($document -ne $null) {
        $document.Close($false)
    }
    if ($word -ne $null) {
        $word.Quit()
    }
    if ($document -ne $null) {
        [System.Runtime.InteropServices.Marshal]::ReleaseComObject($document) | Out-Null
    }
    if ($word -ne $null) {
        [System.Runtime.InteropServices.Marshal]::ReleaseComObject($word) | Out-Null
    }
    [GC]::Collect()
    [GC]::WaitForPendingFinalizers()
}
POWERSHELL);

        $converted = false;

        try {
            $process = new Process([
                'powershell.exe',
                '-NoProfile',
                '-ExecutionPolicy',
                'Bypass',
                '-File',
                $scriptPath,
                '-InputPath',
                $inputPath,
                '-OutputPath',
                $outputPath,
            ]);
            $process->setTimeout(120);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \RuntimeException(trim($process->getErrorOutput() . PHP_EOL . $process->getOutput()));
            }

            $converted = File::exists($outputPath) && File::size($outputPath) > 0;

            return $converted;
        } finally {
            File::delete($scriptPath);
            if ($converted) {
                File::delete($docxPath);
            }
        }
    }

    private function convertDocxToPdfWithDompdf(string $docxPath, string $pdfPath): string
    {
        $htmlPath = storage_path('app/generated/surat_keluar/' . pathinfo($docxPath, PATHINFO_FILENAME) . '.html');

        try {
            $phpWord = IOFactory::load($docxPath);
            $writer = IOFactory::createWriter($phpWord, 'HTML');
            $writer->save($htmlPath);

            Pdf::loadHTML(File::get($htmlPath))
                ->setPaper('a4', 'portrait')
                ->save($pdfPath);

            return $pdfPath;
        } finally {
            File::delete($htmlPath);
        }
    }

    private function ensureDocxReadable(string $docxPath): void
    {
        IOFactory::load($docxPath);
    }

    private function templateProcessor(string $path): TemplateProcessor
    {
        $this->resetTemplateProcessorMacroChars();

        $processor = new TemplateProcessor($path);
        $processor->setMacroChars('{{', '}}');

        return $processor;
    }

    private function resetTemplateProcessorMacroChars(): void
    {
        $openingChars = new \ReflectionProperty(TemplateProcessor::class, 'macroOpeningChars');
        $closingChars = new \ReflectionProperty(TemplateProcessor::class, 'macroClosingChars');

        $openingChars->setAccessible(true);
        $closingChars->setAccessible(true);
        $openingChars->setValue(null, '${');
        $closingChars->setValue(null, '}');
    }

    private function value(mixed $value): string
    {
        if (is_array($value)) {
            return implode(', ', array_filter($value));
        }

        $text = trim((string) $value);
        $xmlSafeText = preg_replace('/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}]/u', '', $text);

        return is_string($xmlSafeText) ? $xmlSafeText : $text;
    }
}
