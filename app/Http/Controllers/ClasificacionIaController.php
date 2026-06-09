<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\ClasificacionIa;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ClasificacionIaController extends Controller
{
    public function index(Request $request)
    {
        if (!can(Usuario::PERMISO_CLASIFICACION_IA_LISTADO) && !can(Usuario::PERMISO_CLASIFICACION_IA_CREAR) &&
            !can(Usuario::PERMISO_CLASIFICACION_IA_EDITAR) && !can(Usuario::PERMISO_CLASIFICACION_IA_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $info['permisos'] = (!can(Usuario::PERMISO_CLASIFICACION_IA_CREAR) &&
            !can(Usuario::PERMISO_CLASIFICACION_IA_EDITAR) &&
            !can(Usuario::PERMISO_CLASIFICACION_IA_ELIMINAR));

        return view('clasificacion-ia.index', $info);
    }

    public function store(Request $request)
    {
        $datos['prompt'] = $request->input('prompt_usuario');

        $clasificacion = ClasificacionIa::updateOrCreate([
            'cod_usuario' => auth()->user()->uuid
        ], [
            'prompt' => $datos['prompt'],
            'estado' => ClasificacionIa::ACTIVO,
        ]);

        if (!$clasificacion) {
            throw new ErrorException('Error al intentar crear el prompt de clasificación.');
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se creo correctamente el prompt de la clasificación.',
        ];
    }
}
