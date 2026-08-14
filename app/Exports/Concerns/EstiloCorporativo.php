<?php

namespace App\Exports\Concerns;

trait EstiloCorporativo
{
    protected string $verdeOscuro = '1F5C30';
    protected string $verdePrincipal = '2E8B47';
    protected string $verdeClaro = 'EAF7EE';
    protected string $grisTexto = '333333';

    protected function bordeCompleto(): array
    {
        return [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ];
    }

    protected function estiloEncabezado(): array
    {
        return [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => $this->verdePrincipal],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
    }
}
