<?php
namespace App\Http\Controllers\Chatbots;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Yajra\DataTables\Facades\DataTables;

class ChatbotAsistenteController extends Controller
{
    public function index(Request $request)
    {
        if (!can(Usuario::PERMISO_CHATBOT_CREAR) && !can(Usuario::PERMISO_CHATBOT_EDITAR) &&
            !can(Usuario::PERMISO_CHATBOT_ELIMINAR) && !can(Usuario::PERMISO_CHATBOT_LISTADO)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        return view('chatbots.asistente-ia.index');
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
