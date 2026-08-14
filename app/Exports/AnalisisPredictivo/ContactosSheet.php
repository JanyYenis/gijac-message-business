<?php

namespace App\Exports\AnalisisPredictivo;


use App\Exports\Concerns\EstiloCorporativo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ContactosSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    use EstiloCorporativo;

    public function __construct(
        private Collection $contactos,
        private Collection $predicciones
    ) {}

    public function collection(): Collection
    {
        return $this->contactos->sortByDesc(
            fn ($contacto) => $this->predicciones->get($contacto->id)['probabilidad'] ?? 0
        )->values();
    }

    public function headings(): array
    {
        return ['Nombre', 'Teléfono', 'Probabilidad de apertura', 'Nivel', 'Tasa histórica (%)', 'Mejor hora de envío'];
    }

    public function map($contacto): array
    {
        $prediccion = $this->predicciones->get($contacto->id);

        return [
            $contacto->nombre ?? '—',
            $contacto->telefono ?? '—',
            $prediccion['probabilidad'] ?? 0,
            $prediccion['nivel'] ?? '—',
            ($prediccion['tasa_historica'] ?? 0) . '%',
            $prediccion['mejor_hora'] ?? '—',
        ];
    }

    public function title(): string
    {
        return 'Contactos';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->applyFromArray($this->estiloEncabezado());
        $sheet->getRowDimension(1)->setRowHeight(22);

        $ultimaFila = $this->collection()->count() + 1;
        $sheet->getStyle("A1:F{$ultimaFila}")->applyFromArray($this->bordeCompleto());

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $ultimaFila = $this->collection()->count() + 1;

                // Filas alternadas + color por nivel de confianza
                for ($fila = 2; $fila <= $ultimaFila; $fila++) {
                    $nivel = $sheet->getCell("D{$fila}")->getValue();

                    $color = match ($nivel) {
                        'Alta' => 'D9F2E3',
                        'Media' => 'FFF4CC',
                        'Baja' => 'FDE2E1',
                        default => $fila % 2 === 0 ? $this->verdeClaro : 'FFFFFF',
                    };

                    $sheet->getStyle("A{$fila}:F{$fila}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB($color);
                }

                $sheet->freezePane('A2');
            },
        ];
    }
}
