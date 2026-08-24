<?php

namespace App\Services\AI;

use Illuminate\Http\UploadedFile;
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser as PdfParser;
use RuntimeException;

class DocumentTextExtractor
{
    public function extraer(UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension());

        $texto = match ($ext) {
            'pdf'   => $this->extraerPdf($file),
            'docx'  => $this->extraerDocx($file),
            'txt'   => $file->get(),
            default => throw new RuntimeException("Formato de archivo no soportado: {$ext}"),
        };

        return $this->limpiarUtf8($texto);
    }

    private function limpiarUtf8(string $texto): string
    {
        // mb_convert_encoding con origen/destino UTF-8 descarta secuencias de bytes inválidas
        $texto = mb_convert_encoding($texto, 'UTF-8', 'UTF-8');

        // Elimina caracteres de control que a veces quedan de la extracción de PDF
        $texto = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $texto);

        return trim($texto);
    }

    private function extraerPdf(UploadedFile $file): string
    {
        $parser = new PdfParser();
        $pdf    = $parser->parseFile($file->getRealPath());

        return trim($pdf->getText());
    }

    private function extraerDocx(UploadedFile $file): string
    {
        $phpWord = IOFactory::load($file->getRealPath());
        $texto   = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $texto .= $element->getText() . "\n";
                } elseif (method_exists($element, 'getElements')) {
                    foreach ($element->getElements() as $sub) {
                        if (method_exists($sub, 'getText')) {
                            $texto .= $sub->getText() . "\n";
                        }
                    }
                }
            }
        }

        return trim($texto);
    }
}
