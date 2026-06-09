<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\Comentario;
use App\Models\Ticket;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function listado(Request $request)
    {
        // if (!can(Usuario::PERMISO_ACTAS_LISTADO) && !can(Usuario::PERMISO_ACTAS_CREAR) && !can(Usuario::PERMISO_ACTAS_EDITAR) && !can(Usuario::PERMISO_ACTAS_ELIMINAR)) {
        //     throw new ErrorException("No tienes permisos para acceder a esta sección.");
        // }
        $datos = $request->input('datos');

        $pagina = $request->input('pagina') ?? 1;
        $cantidad = $request->input("cantidad_pagina", 6);

        $comentariosQuery = Comentario::with(
                'usuario',
                'infoEstado',
            )
            ->where('comentariable_type', modeloPorNombreTabla($datos['modelo']))
            ->where('comentariable_id', $datos['id'])
            ->whereNot('estado', Comentario::ELIMINADO)
            ->orderByDesc('created_at');

        $comentarios = $comentariosQuery->paginate($cantidad, ["*"], "comentarios", $pagina);
        $info['ultimaPagina'] = $comentarios->lastPage();
        $info["comentarios"] = $comentarios;
        $info['paginaActual'] = $pagina;

        // Se genera la respuesta con estado de éxito.
        $respuesta["estado"] = "success";

        // Se renderiza la vista y se almacena en la respuesta.
        $respuesta['html'] = view("comentarios.listado", $info)->render();

        // Se devuelve la respuesta en formato JSON.
        return response()->json($respuesta);
    }

    public function store(Request $request)
    {
        $datos = $request->all();
        $datos['cod_usuario'] = auth()->user()->id;
        dd($datos);

        $ticket = Ticket::find($datos['']);

        $nuevo = $ticket->crearComentario($datos);
        if (!$nuevo) {
            throw new ErrorException("Error al intentar guardar el comentario.");
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se creo correctamente el comentario.',
        ];
    }
}
