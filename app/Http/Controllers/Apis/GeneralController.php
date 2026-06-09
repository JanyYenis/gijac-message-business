<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Campana;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function campanas(Request $request)
    {
        $fecha_inicio = $request->input('fecha_inicio') ?? null;
        $fecha_fin = $request->input('fecha_fin') ?? null;

        if (!$fecha_fin || !$fecha_inicio) {
            return response()->json([
                'estado' => 400,
                'mensaje' => 'La fecha de inicio y la fecha de finalizacion son requeridas.'
            ]);
        }

        $campanas = Campana::selectRaw('id as id, nombre_evento as descripcion, material, fecha_wpp as fecha_envio, tipo_wpp as tipo_envio, contenido_multimedia, estado')
            ->whereBetween('fecha_wpp', [$fecha_inicio, $fecha_fin])
            ->whereNotIn('estado', [Campana::CANCELADO, Campana::ELIMINADO])
            ->where('uuid', $request->input('user_id'))
            ->get();

        return response()->json([
            'campanas' => $campanas,
            'estado' => 200
        ]);
    }

    public function campana(Request $request)
    {
        $id = $request->input('id') ?? null;

        if (!$id) {
            return response()->json([
                'estado' => 400,
                'mensaje' => 'El campo id es requerido.'
            ]);
        }

        $campana = Campana::selectRaw('id as id, nombre_evento as descripcion, material, fecha_wpp as fecha_envio, tipo_wpp as tipo_envio, contenido_multimedia, estado')
            ->where('id', $id)
            ->whereNotIn('estado', [Campana::CANCELADO, Campana::ELIMINADO])
            ->where('uuid', $request->input('user_id'))
            ->first();

        return response()->json([
            'campana' => $campana,
            'estado' => 200
        ]);
    }

    public function detalleCampanas(Request $request)
    {

    }

    public function enviarMensaje(Request $request)
    {

    }
}
