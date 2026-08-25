<?php

namespace App\Http\Controllers;

use App\Models\AutomatizacionN8n;
use App\Models\AutomatizacionN8nEjecucion;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AutomatizacionN8nEjecucionController extends Controller
{
    public function listado(Request $request, string $automatizacion = '')
    {
        $versiones = AutomatizacionN8nEjecucion::where('cod_automatizacion', $automatizacion)
            ->orderByDesc('fecha_ejecucion');

        return DataTables::eloquent($versiones)
            ->addColumn("fecha", fn($model) => $model->fecha_ejecucion->formatLocalized('%d de %B del %Y a las %H:%M'))
            ->addColumn("cliente", fn($model) => $model?->contacto?->nombre_completo ?? 'N/A')
            ->addColumn("evento", fn($model) => $model?->infoEvento?->nombre ?? 'N/A')
            ->addColumn("estado", function ($model) {
                $info['concepto'] = $model?->infoEstado;
                return view("sistema.estado", $info);
            })
            ->addColumn("duracion", fn($model) => $model?->duracion_ms ? $model?->duracion_ms.' ms' : 'N/A')
            ->addColumn("respuesta", fn($model) => $model?->respuesta ?? 'N/A')
            ->rawColumns(["estado"])
            ->make(true);
    }
}
