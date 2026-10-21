<?php
namespace App\Http\Controllers\Chatbots;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Models\AutomatizacionN8n;
use App\Models\Chatbots\ChatbotAiAssistant;
use App\Models\Chatbots\ChatbotFlow;
use App\Models\ConfiguracionAi;
use App\Services\Chatbot\ChatbotConfigService;
use Illuminate\Http\Request;
use App\Models\Usuario;

class ChatbotController extends Controller
{
    public function index(Request $request)
    {
        if (!can(Usuario::PERMISO_CHATBOT_CREAR) && !can(Usuario::PERMISO_CHATBOT_EDITAR) &&
            !can(Usuario::PERMISO_CHATBOT_ELIMINAR) && !can(Usuario::PERMISO_CHATBOT_LISTADO)) {
            throw new ErrorException(__("No tienes permisos para acceder a esta sección."));
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
        $this->verificarPermiso();

        return view('chatbots.general.index', [
            'configChatbot' => ChatbotConfigService::datos(auth()->user()->empresa->id),
        ]);
    }

    public function guardar(Request $request)
    {
        if (!can(Usuario::PERMISO_CHATBOT_EDITAR) && !can(Usuario::PERMISO_CHATBOT_CREAR)) {
            throw new ErrorException(__("No tienes permisos para acceder a esta sección."));
        }

        $d = $request->validate([
            'activo' => 'required|boolean',
            'respetar_horario' => 'required|boolean',
            'accion_palabra_clave' => 'required|integer|in:1,2,3',
            'coincidencia_aproximada' => 'required|boolean',
            'permitir_transferencia' => 'required|boolean',
            'tiempo_espera_agente' => 'required|integer|min:0|max:1440',
            'accion_sin_agente' => 'required|integer|in:1,2,3',
            'mantener_contexto' => 'required|boolean',
            'tiempo_sesion' => 'required|integer|min:1|max:10080',
            'tipo_reinicio_conversacion' => 'required|integer|in:1,2,3',
            'tiempo_respuesta' => 'required|integer|min:0|max:10',
            'mostrar_escribiendo' => 'required|boolean',
            'agrupar_mensajes' => 'required|integer|min:0|max:30',
            'accion_respuesta_no_resuelta' => 'required|integer|in:1,2,3,4',
            'mensaje_no_resuelto' => 'nullable|string|max:1000',
            'dias' => 'array',
            'dias.*' => 'integer|between:1,7',
            'rangos' => 'array|max:6',
            'rangos.*.a' => ['required', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'],
            'rangos.*.b' => ['required', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/', 'after:rangos.*.a'],
            'palabras' => 'array|max:50',
            'palabras.*' => 'string|max:150',
            'mensajes' => 'array',
            'mensajes.*.tipo' => 'required|integer|in:1,2,3',
            'mensajes.*.activo' => 'required|boolean',
            'mensajes.*.mensaje' => 'nullable|string|max:1000',
            'motores' => 'array',
            'motores.*' => 'integer|in:1,2,3',
            'motivos' => 'array',
        ]);

        ChatbotConfigService::guardar(auth()->user()->empresa->id, $d);

        return response()->json(['message' => __('Configuración guardada correctamente.')]);
    }

    private function verificarPermiso(): void
    {
        if (!can(Usuario::PERMISO_CHATBOT_CREAR) && !can(Usuario::PERMISO_CHATBOT_EDITAR) &&
            !can(Usuario::PERMISO_CHATBOT_ELIMINAR) && !can(Usuario::PERMISO_CHATBOT_LISTADO)) {
            throw new ErrorException(__("No tienes permisos para acceder a esta sección."));
        }
    }
}
