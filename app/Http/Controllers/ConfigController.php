<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Http\Requests\StoreConfigRequest;
use App\Http\Requests\UpdateConfigRequest;
use App\Models\ConfiguracionMeta;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ConfigController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!can(Usuario::PERMISO_CONFIGURACION_META_LISTADO) &&
            !can(Usuario::PERMISO_CONFIGURACION_META_CREAR) &&
            !can(Usuario::PERMISO_CONFIGURACION_META_EDITAR) &&
            !can(Usuario::PERMISO_CONFIGURACION_META_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $info['demo'] = $this->demo;
        $info['existeConfig'] = ConfiguracionMeta::where('uuid', $this->uuid)
            ->where('estado', ConfiguracionMeta::ACTIVO)
            ->exists();
        $info['plan'] = $this->plan;

        return view('configs.index', $info);
    }

    public function listado(Request $request, $proyecto = null)
    {
        if (!can(Usuario::PERMISO_CONFIGURACION_META_LISTADO) &&
            !can(Usuario::PERMISO_CONFIGURACION_META_EDITAR) &&
            !can(Usuario::PERMISO_CONFIGURACION_META_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $configs = ConfiguracionMeta::where('estado', '!=', ConfiguracionMeta::ELIMINADO)
            ->where('uuid', $this->uuid);

        return DataTables::eloquent($configs)
            ->addColumn("token", function ($model) {

                return $model?->token ? acortarCadena($model?->token, 20) : '';
            })
            ->addColumn("estado", function ($model) {
                $info['concepto'] = $model?->infoEstado;
                return view("sistema.estado", $info);
            })
            ->addColumn("webhook", function($model){
                $info['model'] = $model;
                return view("configs.columnas.btn-webhook", $info);
            })
            ->addColumn("action", function($model){
                $info['model'] = $model;
                $info['puedeEditar'] = can(Usuario::PERMISO_CONFIGURACION_META_EDITAR);
                $info['puedeEliminar'] = can(Usuario::PERMISO_CONFIGURACION_META_ELIMINAR);
                return view("configs.columnas.acciones", $info);
            })
            ->rawColumns(["action"])
            ->make(true);
    }

    public function store(StoreConfigRequest $request)
    {
        $datos = $request->all();
        $datos['uuid'] = auth()->user()->uuid;
        $datos['estado'] = ConfiguracionMeta::ACTIVO;
        $config = ConfiguracionMeta::create($datos);

        if (!$config) {
            throw new ErrorException('Error al intentar crear la nueva configuracion.');
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se creo correctamente la configuracion.',
        ];
    }

    public function edit(Request $request, ConfiguracionMeta $config)
    {
        if (!can(Usuario::PERMISO_CONFIGURACION_META_EDITAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $info["config"] = $config;

        $respuesta["estado"] = "success";
        $respuesta["mensaje"] = "Datos cargados correctamente";
        $respuesta['html'] = view("configs.modals.editar", $info)->render();

        return response()->json($respuesta);
    }

    public function update(UpdateConfigRequest $request, ConfiguracionMeta $config)
    {
        $datos = $request->all();
        $actualizar = $config->update($datos);
        if (!$actualizar) {
            throw new ErrorException('Error al intentar actualizar la configuracion.');
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se actualizo correctamente la configuracion.',
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(ConfiguracionMeta $config)
    {
        $eliminar = $config->eliminar();

        if (!$eliminar) {
            throw new ErrorException('A ocurrido un error al intentar eliminar el configuracion.');
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se eliminado correctamente el configuracion.',
        ];
    }
}
