<?php

namespace App\Exports\AnalisisPredictivo;

use App\Exports\Concerns\EstiloCorporativo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ResumenGeneralSheet implements FromArray, WithTitle, WithStyles, WithDrawings, WithEvents
{
    use EstiloCorporativo;

    public function __construct(
        private Collection $predicciones,
        private ?array $analisisIa,
        private string $contenidoMensaje
    ) {}

    public function array(): array
    {
        $total = $this->predicciones->count();
        $promedioApertura = $total ? round($this->predicciones->avg('tasa_historica'), 2) : 0;

        $nivelGeneral = match (true) {
            $promedioApertura >= 70 => 'Alta',
            $promedioApertura >= 40 => 'Media',
            default => 'Baja',
        };

        $porNivel = $this->predicciones->countBy('nivel');

        return [
            ['Reporte de Predicción de Campaña', ''],
            ['', ''],
            ['', ''],
            ['Mensaje analizado', $this->contenidoMensaje],
            ['Total de contactos', $total],
            ['Tasa histórica promedio de apertura', $promedioApertura . '%'],
            ['Nivel de apertura general', $nivelGeneral],
            ['', ''],
            ['Distribución por nivel de confianza', ''],
            ['Alta', $porNivel->get('Alta', 0)],
            ['Media', $porNivel->get('Media', 0)],
            ['Baja', $porNivel->get('Baja', 0)],
            ['', ''],
            ['Explicación IA', $this->analisisIa['explicacion'] ?? 'No disponible'],
            ['Mensaje mejorado sugerido', $this->analisisIa['mensaje_mejorado'] ?? 'No disponible'],
        ];
    }

    public function drawings()
    {
        $logo = new Drawing();
        $logo->setName('Logo GMB');
        $logo->setPath(public_path('img/logo_gmb.png'));
        $logo->setHeight(70);
        $logo->setCoordinates('A1');

        return $logo;
    }

    public function title(): string
    {
        return 'Resumen';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getColumnDimension('A')->setWidth(35);
        $sheet->getColumnDimension('B')->setWidth(60);
        $sheet->getRowDimension(1)->setRowHeight(20);
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(3)->setRowHeight(20);

        // Título principal
        $sheet->mergeCells('A1:B3');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => $this->verdeOscuro]],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Subtítulo de sección "Distribución"
        $sheet->getStyle('A9')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $this->verdePrincipal],
            ],
        ]);
        $sheet->mergeCells('A9:B9');

        // Etiquetas en negrita
        foreach (['A4', 'A5', 'A6', 'A7', 'A9', 'A10', 'A11', 'A12', 'A14', 'A15'] as $celda) {
            $sheet->getStyle($celda)->getFont()->setBold(true);
        }

        // Borde alrededor del bloque de datos
        $sheet->getStyle('A1:B15')->applyFromArray($this->bordeCompleto());

        $sheet->getStyle('B1:B15')->getAlignment()->setWrapText(true);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getSheetView()
                    ->setZoomScale(100);
            },
        ];
    }
}
