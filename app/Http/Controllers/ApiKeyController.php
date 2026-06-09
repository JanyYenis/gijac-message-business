<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\ApiKey;
use App\Models\ApiKeyLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiKeyController extends Controller
{
    public function listado(Request $request)
    {
        // if (!can(Usuario::PERMISO_LISTADO)) {
        //     throw new ErrorException("No tienes permisos para acceder a esta sección.");
        // }

        $apis = ApiKey::whereNot('estado', ApiKey::ELIMINADO)
            ->where('id_usuario', auth()->user()->id);

        return DataTables::eloquent($apis)
            ->addColumn("estado", function ($model) {
                $info['concepto'] = $model?->infoEstado;
                return view("sistema.estado", $info);
            })
            ->addColumn("action", function ($model) {
                $info['model'] = $model;
                $info['puedeEditar'] = true;
                $info['puedeEliminar'] = true;
                return view("perfil.componentes.apis.columnas.acciones", $info);
            })
            ->rawColumns(["action", "estado"])
            ->make(true);
    }

    public function listadoLog(Request $request)
    {
        // if (!can(Usuario::PERMISO_LISTADO)) {
        //     throw new ErrorException("No tienes permisos para acceder a esta sección.");
        // }

        $apis = ApiKeyLog::whereHas('apiKey', function($query) {
                $query->where('id_usuario', auth()->user()->id);
            })
            ->orderByDesc('fecha');

        return DataTables::eloquent($apis)
            ->addColumn("estado", function ($model) {
                $info['concepto'] = $model?->infoEstado;
                return view("sistema.estado", $info);
            })
            ->rawColumns(["estado"])
            ->make(true);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $apiKey = ApiKey::create([
            'etiqueta' => $request->input('etiqueta') ?? null,
            'id_usuario' => $user->id,
            'key' => ApiKey::generate(),
        ]);

        return response()->json([
            'estado' => 'success',
            'mensaje' => 'Se creo correctamente la API Key',
            'api_key' => $apiKey->key
        ]);
    }

    public function update(Request $request, ApiKey $key)
    {
        $datos = $request->all();
        $actualizar = $key->update($datos);

        if (!$actualizar) {
            throw new ErrorException('Error al intentar actualizar la API Key.');
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se actualizo correctamente la API Key.'
        ];
    }

    public function delete(Request $request, ApiKey $key)
    {
        $eliminar = $key->delete();

        if (!$eliminar) {
            throw new ErrorException('Error al intentar eliminar la API Key.');
        }

        return [
            'estado' => 'success',
            'message' => 'Se elimino correctamente la API Keys.'
        ];
    }
}
