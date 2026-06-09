<?php

namespace App\Http\Controllers;

use App\Events\NotificacionEvent;
use App\Exceptions\ErrorException;
use App\Models\Ticket;
use App\Models\Usuario;
use App\Notifications\NuevoTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        if (!can(Usuario::PERMISO_TICKETS_CREAR) && !can(Usuario::PERMISO_TICKETS_EDITAR) &&
            !can(Usuario::PERMISO_TICKETS_LISTADO) && !can(Usuario::PERMISO_TICKETS_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $responsables = Usuario::selectRaw('id, CONCAT(nombre, " ", apellido) as text')
            ->where('estado', Usuario::ACTIVO)
            ->whereHas('roles', function($query) {
                $query->whereIn('name', [
                    Usuario::ROL_ADMINISTRADOR,
                    Usuario::ROL_SUPER_ADMINISTRADOR,
                ]);
            })
            ->get();

        $info['responsables'] = $responsables;
        $info['estados'] = Ticket::darEstados();
        $info['tipos'] = Ticket::darTipo();
        $info['prioridades'] = Ticket::darPrioridad();

        return view('tickets.index', $info);
    }

    public function listado(Request $request)
    {
        if (!can(Usuario::PERMISO_TICKETS_LISTADO)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $pagina = $request->input('pagina') ?? 1;
        $cantidad = $request->input("cantidad_pagina", 6);

        $ticketsQuery = Ticket::with(
            'cliente',
            'responsable',
            'infoEstado',
            'infoTipo',
            'infoPrioridad',
        )
        ->where(function($query) {
            if (auth()->user()->hasRole(Usuario::ROL_CLIENTE)) {
                $query->where('cod_usuario', auth()->user()->id);
            } else if (!auth()->user()->hasRole(Usuario::ROL_SUPER_ADMINISTRADOR) && !auth()->user()->hasRole(Usuario::ROL_ADMINISTRADOR)) {
                $query->where('cod_responsable', auth()->user()->id);
            }
        })
        ->whereNot('estado', Ticket::ELIMINADO)
        ->orderByDesc('created_at');

        $tickets = $ticketsQuery->paginate($cantidad, ["*"], "tickets", $pagina);
        $info['ultimaPagina'] = $tickets->lastPage();
        $info["tickets"] = $tickets;
        $info['paginaActual'] = $pagina;

        return [
            "estado" => "success",
            "html" => view("tickets.listado", $info)->render()
        ];
    }

    public function store(Request $request)
    {
        $datos = $request->all();
        $datos['cod_usuario'] = auth()->user()->id;

        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $datos['url_archivo'] = $archivo->store('tickets'); // Guarda en storage/app/archivos_subidos
        }

        $ticket = Ticket::create($datos);

        if (!$ticket) {
            throw new ErrorException("Error al intentar crear el ticket.");
        }

        // 🔥 Asegúrate de que el ticket tenga un ID antes de notificar
        $ticket->refresh(); // Recarga el modelo desde la BD (por si acaso)

        // Verifica el ID
        if (empty($ticket->id)) {
            throw new ErrorException("El ticket no tiene un ID asignado.");
        }

        $usuariosAdmin = Usuario::with('roles')->whereHas('roles', function($query){
            $query->whereIn('name', [Usuario::ROL_SUPER_ADMINISTRADOR, Usuario::ROL_ADMINISTRADOR]);
        })
        ->get();
        foreach ($usuariosAdmin as $usu) {
            Notification::send($usu, new NuevoTicket($ticket));
            broadcast(new NotificacionEvent($usu));
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se agrego el ticket correctamente.',
        ];
    }

    public function edit(Request $request, Ticket $ticket)
    {
        $ticket->load(
            'infoTipo',
            'infoPrioridad',
            'infoEstado',
            'cliente',
        );

        $responsables = Usuario::selectRaw('id, CONCAT(nombre, " ", apellido) as text')
            ->where('estado', Usuario::ACTIVO)
            ->whereHas('roles', function($query) {
                $query->whereIn('name', [
                    Usuario::ROL_SUPER_ADMINISTRADOR,
                    Usuario::ROL_ADMINISTRADOR,
                ]);
            })
            ->get();

        $info['clase'] = Ticket::darNombreTabla();
        $info['responsables'] = $responsables;
        $info['estados'] = Ticket::darEstados();
        $info['tipos'] = Ticket::darTipo();
        $info['prioridades'] = Ticket::darPrioridad();
        $info['ticket'] = $ticket;

        return view('tickets.editar', $info);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $datos = $request->all();
        $datos['descripcion_comentario'] = $datos['descripcion_comentario'] != '<p><br></p>' ? $datos['descripcion_comentario'] : null;
        if (!can('tickets.asignar.responsable')) {
            unset($datos['cod_responsable']);
            unset($datos['estado']);
            unset($datos['tipo']);
            unset($datos['prioridad']);
        }

        $actualizar = $ticket->update($datos);

        if (!$actualizar) {
            throw new ErrorException("Error al intentar actualizar el ticket.");
        }

        if ($datos['descripcion_comentario']) {
            $info['descripcion'] = $datos['descripcion_comentario'];
            $info['cod_usuario'] = auth()->user()->id;
            $info['comentariable_id'] = $ticket->id;
            $info['comentariable_type'] = Ticket::darNombreTabla();

            $comentario = $ticket->crearComentario($info);

            if (!$comentario) {
                throw new ErrorException("Error al intentar guardar el comentario.");
            }
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se actualizo el ticket correctamente.',
        ];
    }
}
