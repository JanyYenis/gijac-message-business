<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EtiquetaController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('etiquetas.index');
    }

    public function listado(Request $request)
    {
        // if (!can(Usuario::PERMISO_LISTADO)) {
        //     throw new ErrorException("No tienes permisos para acceder a esta sección.");
        // }

        $etiquetas = Etiqueta::with(
            'infoEstado',
        )->where('estado', '!=', Etiqueta::ELIMINADO)
        ->where('uuid', $this->uuid)
        ->orderByDesc('created_at');

        return DataTables::eloquent($etiquetas)
            ->addColumn("estado", function ($model) {
                $info['concepto'] = $model?->infoEstado;
                return view("sistema.estado", $info);
            })
            ->addColumn("action", "etiquetas.columnas.acciones")
            ->addColumn("nombre", "etiquetas.columnas.nombre")
            ->addColumn("color", "etiquetas.columnas.color")
            ->rawColumns(["action", "nombre", "color"])
            ->make(true);
    }

    public function store(Request $request)
    {
        $datos = $request->all();
        $datos['uuid'] = auth()->user()->uuid;
        $etiqueta = Etiqueta::create($datos);

        if (!$etiqueta) {
            throw new ErrorException('Error al intentar crear la nueva etiqueta.');
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se creo correctamente la etiqueta.',
        ];
    }

    public function edit(Request $request, Etiqueta $etiqueta)
    {
        $info["etiqueta"] = $etiqueta;

        $respuesta["estado"] = "success";
        $respuesta["mensaje"] = "Datos cargados correctamente";
        $respuesta['html'] = view("etiquetas.modals.editar", $info)->render();

        return response()->json($respuesta);
    }

    public function update(Request $request, Etiqueta $etiqueta)
    {
        $datos = $request->all();
        $actualizar = $etiqueta->update($datos);

        if (!$actualizar) {
            throw new ErrorException('Error al intentar actualizar la etiqueta.');
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se actualizo correctamente la etiqueta.',
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Etiqueta $etiqueta)
    {
        $eliminar = $etiqueta->eliminar();

        if (!$eliminar) {
            throw new ErrorException('A ocurrido un error al intentar eliminar la etiqueta.');
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se eliminado correctamente la etiqueta.',
        ];
    }

    public function buscar(Request $request)
    {
        $nombre = $request->get("busqueda");
        $filtro = "%$nombre%";

        $etiquetas = Etiqueta::selectRaw('id, nombre as text')
            ->where(function($query) use($filtro){
                $query->whereRaw("LOWER(nombre) LIKE LOWER(?)", $filtro);
            })
            ->where('estado', Etiqueta::ACTIVO)
            ->where('uuid', $this->uuid)
            ->orderBy('text')
            ->get();

        return response()->json($etiquetas);
    }
}
