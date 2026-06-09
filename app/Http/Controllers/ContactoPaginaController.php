<?php

namespace App\Http\Controllers;

use App\Events\NotificacionEvent;
use App\Exceptions\ErrorException;
use App\Models\ContactoPagina;
use App\Models\Usuario;
use App\Notifications\ContactoFormulario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactoPaginaController extends Controller
{
    public function index(Request $request)
    {
        return view('contactar');
    }

    public function store(Request $request)
    {
        $datos = $request->all();
        // dd($datos);

        $contacto = ContactoPagina::create($datos);

        if (!$contacto) {
            throw new ErrorException("Error al intentar enviar el contacto.");
        }

        $contacto->refresh();

        $usuariosAdmin = Usuario::with('roles')->whereHas('roles', function($query){
                $query->whereIn('name', [Usuario::ROL_SUPER_ADMINISTRADOR, Usuario::ROL_ADMINISTRADOR]);
            })
            ->get();
        foreach ($usuariosAdmin as $usu) {
            Notification::send($usu, new ContactoFormulario($contacto->id));
            broadcast(new NotificacionEvent($usu));
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se envio tu solicitud correctamente.',
        ];
    }
}
