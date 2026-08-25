<?php
namespace App\Http\Controllers\Chatbots;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Models\AutomatizacionN8n;
use App\Models\Chatbots\ChatbotAiAssistant;
use App\Models\Chatbots\ChatbotFlow;
use App\Models\ConfiguracionAi;
use Illuminate\Http\Request;
use App\Models\Usuario;

class ChatbotController extends Controller
{
    public function index(Request $request)
    {
        if (!can(Usuario::PERMISO_CHATBOT_CREAR) && !can(Usuario::PERMISO_CHATBOT_EDITAR) &&
            !can(Usuario::PERMISO_CHATBOT_ELIMINAR) && !can(Usuario::PERMISO_CHATBOT_LISTADO)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $info['chatbot_nodo'] = ChatbotFlow::where('cod_empresa', auth()->user()->empresa->id)
            ->where('estado', ChatbotFlow::ACTIVO)
            ->count();

        $info['chatbot_ia'] = ChatbotAiAssistant::where('cod_empresa', auth()->user()->empresa->id)
            ->where('activo', ChatbotAiAssistant::ACTIVO)
            ->count();

        $info['chatbot_n8n'] = AutomatizacionN8n::where('cod_empresa', auth()->user()->empresa->id)
            ->where('estado', AutomatizacionN8n::ACTIVO)
            ->count();

        // $info['conversaciones'] = ChatbotFlow::where('cod_empresa', auth()->user()->empresa->id)
        //     ->where('estado', ChatbotFlow::ACTIVO)
        //     ->count();

        return view('chatbots.index', $info);
    }

    public function configuracion(Request $request)
    {
        return view('chatbots.general.index');
    }
}
