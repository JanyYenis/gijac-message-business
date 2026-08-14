<?php

namespace App\Jobs;

use App\Exceptions\ErrorException;
use App\Exports\AnalisisPredictivo\GenerarExcelReporteCampana;
use App\Mail\ReporteCampanaMail;
use App\Models\Contacto;
use App\Services\AI\CampanaAiService;
use App\Services\Campanas\PrediccionContenidoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class GenerarReporteCampanaJob implements ShouldQueue
{
    public function __construct(
        private array $contactosIds,
        private string $contenidoMensaje,
        private string $emailDestino
    ) {}

    public function handle(
        PrediccionContenidoService $service,
        CampanaAiService $ai
    ) {
        $contactos = Contacto::whereIn('id', $this->contactosIds)->get();
        $predicciones = $service->predecirCampana($contactos->all(), $this->contenidoMensaje);

        // Un solo prompt batch, no uno por contacto
        $analisisIa = $ai->analizar($this->contenidoMensaje, $predicciones);

        $rutaExcel = 'reportes-campanas/' . uniqid('reporte_') . '.xlsx';

        Excel::store(
            new GenerarExcelReporteCampana($contactos, $predicciones, $analisisIa, $this->contenidoMensaje),
            $rutaExcel,
            'local'
        );

        $promedioApertura = round(collect($predicciones)->avg('tasa_historica'), 2);
        $nivelGeneral = match (true) {
            $promedioApertura >= 70 => 'Alta',
            $promedioApertura >= 40 => 'Media',
            default => 'Baja',
        };

        Mail::to($this->emailDestino)->send(new ReporteCampanaMail(
            $rutaExcel,
            count($predicciones),
            $promedioApertura,
            $nivelGeneral,
            $analisisIa
        ));
    }
}
