<?php

namespace App\Exports\AnalisisPredictivo;

use App\Exports\Concerns\EstiloCorporativo;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RecomendacionesSheet implements FromArray, WithTitle, WithStyles
{
    use EstiloCorporativo;

    public function __construct(private ?array $analisisIa) {}

    public function array(): array
    {
        if (!$this->analisisIa) {
            return [['No se pudo generar el análisis de IA para esta campaña.']];
        }

        $filas = [['Sugerencias generales']];

        foreach ($this->analisisIa['sugerencias'] ?? [] as $sugerencia) {
            $filas[] = ['• ' . $sugerencia];
        }

        $filas[] = [''];
        $filas[] = ['Recomendaciones por nivel de confianza'];

        foreach ($this->analisisIa['recomendaciones_por_nivel'] ?? [] as $texto) {
            $filas[] = [$texto];
        }

        return $filas;
    }

    public function title(): string
    {
        return 'Recomendaciones IA';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getColumnDimension('A')->setWidth(22);
        $sheet->getColumnDimension('B')->setWidth(85);

        foreach (['A1', 'A' . ($this->filaSeccionNivel())] as $celda) {
            // se aplica abajo con coordenadas fijas también, ver nota
        }

        $sheet->getStyle('A1')->applyFromArray($this->estiloEncabezado());
        $sheet->mergeCells('A1:B1');

        $sheet->getStyle('B1:B' . $sheet->getHighestRow())->getAlignment()->setWrapText(true);

        return [];
    }

    private function filaSeccionNivel(): int
    {
        return 1; // ajusta si quieres estilizar dinámicamente la 2da sección
    }
}
