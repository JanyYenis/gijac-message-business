<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\ApiKey;
use App\Models\ApiKeyLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ApiKeyController extends Controller
{
    public function listado(Request $request)
    {
        $apis = ApiKey::whereNot('estado', ApiKey::ELIMINADO)
            ->where('uuid', auth()->user()->uuid);

        return DataTables::eloquent($apis)
            ->addColumn("estado", function ($model) {
                $info['concepto'] = $model?->infoEstado;
                return view("sistema.estado", $info);
            })
            ->editColumn('key', fn ($model) => ($model->prefijo ?? 'GMB-') . '••••••••••••••••')
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
        $apis = ApiKeyLog::whereHas('apiKey', function($query) {
                $query->where('uuid', auth()->user()->uuid);
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

        $datos = $request->validate([
            'etiqueta' => 'required|string|max:100',
            'scopes' => 'nullable|array',
            'scopes.*' => ['string', Rule::in(array_keys(ApiKey::SCOPES))],
        ]);

        [$apiKey, $llave] = ApiKey::crearConLlave([
            'etiqueta' => $datos['etiqueta'],
            'uuid' => $user->uuid,
            'cod_empresa' => $user->empresa?->id,
            'scopes' => array_values($datos['scopes'] ?? []),
        ]);

        return response()->json([
            'estado' => 'success',
            'mensaje' => __('Se creo correctamente la API Key. Cópiala ahora: no se volverá a mostrar.'),
            'api_key' => $llave,
        ]);
    }

    public function update(Request $request, ApiKey $key)
    {
        $this->verificarDueno($key);

        $datos = $request->validate([
            'estado' => ['sometimes', Rule::in([ApiKey::ACTIVO, ApiKey::INACTIVO])],
            'etiqueta' => 'sometimes|string|max:100',
        ]);

        if (!$key->update($datos)) {
            throw new ErrorException(__('Error al intentar actualizar la API Key.'));
        }

        return [
            'estado' => 'success',
            'mensaje' => __('Se actualizo correctamente la API Key.')
        ];
    }

    public function delete(Request $request, ApiKey $key)
    {
        $this->verificarDueno($key);

        if (!$key->delete()) {
            throw new ErrorException(__('Error al intentar eliminar la API Key.'));
        }

        return [
            'estado' => 'success',
            'message' => __('Se elimino correctamente la API Keys.')
        ];
    }

    /** Una llave solo la puede modificar o eliminar su dueño. */
    private function verificarDueno(ApiKey $key): void
    {
        if ($key->uuid !== auth()->user()->uuid) {
            throw new ErrorException(__('No tienes permisos para modificar esta API Key.'));
        }
    }
}
