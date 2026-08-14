<?php

namespace App\Exports\AnalisisPredictivo;

use App\Exports\AnalisisPredictivo\ContactosSheet;
use App\Exports\AnalisisPredictivo\RecomendacionesSheet;
use App\Exports\AnalisisPredictivo\ResumenGeneralSheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class GenerarExcelReporteCampana implements WithMultipleSheets
{
    public function __construct(
        private Collection $contactos,     // Colección de modelos Contacto
        private array $predicciones,       // Salida de predecirCampana()
        private ?array $analisisIa,        // Salida de CampanaAiService::analizarLote()
        private string $contenidoMensaje
    ) {}

    public function sheets(): array
    {
        $predicciones = collect($this->predicciones)->keyBy('contacto_id');

        return [
            'Resumen' => new ResumenGeneralSheet(
                $predicciones,
                $this->analisisIa,
                $this->contenidoMensaje
            ),
            'Contactos' => new ContactosSheet(
                $this->contactos,
                $predicciones
            ),
            'Recomendaciones IA' => new RecomendacionesSheet(
                $this->analisisIa
            ),
        ];
    }
}
