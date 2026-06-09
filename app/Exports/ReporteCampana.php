<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteCampana implements FromView, ShouldAutoSize, WithStyles //, WithDrawings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    // public function drawings()
    // {
    //     $campana = $this->data['campana'];

    //     if (filter_var($campana->contenido_multimedia, FILTER_VALIDATE_URL)) {
    //         $imgContent = @file_get_contents($campana->contenido_multimedia);
    //         if ($imgContent) {
    //             $fileName = 'campanas/' . uniqid() . '.png';
    //             Storage::disk('public')->put($fileName, $imgContent);
    //             $campana->contenido_multimedia = 'storage/' . $fileName;
    //             $campana->save();
    //         }
    //     }

    //     return [];
    // }

    public function styles(Worksheet $sheet)
    {
        $info = $this->data['campana']->enviosActivos;
        // Obtener el rango de celdas de la tabla
        // $range = 'A1:H'.(count($info)+1);
        // // Agregar bordes a la tabla
        // $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        // $sheet->setAutoFilter('A1:H1');
        // $sheet->getColumnDimension('A')->setAutoSize(true);
        // $sheet->getColumnDimension('B')->setAutoSize(true);
        // $sheet->getColumnDimension('C')->setAutoSize(true);
        // $sheet->getColumnDimension('D')->setAutoSize(true);
        // $sheet->getColumnDimension('E')->setAutoSize(true);
        // $sheet->getColumnDimension('F')->setAutoSize(true);
        // $sheet->getColumnDimension('G')->setAutoSize(true);
        // $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->setTitle('Campaña');

        // Configura el estilo de las celdas, por ejemplo, agregar bordes
        // return [
        //     // Estilo para toda la hoja
        //     1 => ['border' => 'thin'],
        //     'A1:H1' => [
        //         'alignment' => [
        //             'horizontal' => 'center'
        //         ],
        //         'font' => [
        //             'bold' => true
        //         ],
        //         'border' => [
        //             'outline' => [
        //                 'style' => 'thin',
        //                 'color' => [
        //                     'rgb' => '000000'
        //                 ]
        //             ]
        //         ]
        //     ],
        //     'A1:H'.(count($info)+1) => ['border' => 'thin'],
        // ];
    }

    public function view(): View
    {
        return view('excels.reporte-campana', ['campana' => $this->data['campana']]);
    }
}
