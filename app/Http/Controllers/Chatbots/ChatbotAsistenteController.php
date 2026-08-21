<?php
namespace App\Http\Controllers\Chatbots;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Models\Chatbots\ChatbotAiAssistant;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Services\AI\OllamaService;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ChatbotAsistenteController extends Controller
{
    public function __construct(private OllamaService $ollama)
    {
    }

    public function index(Request $request)
    {
        if (!can(Usuario::PERMISO_CHATBOT_CREAR) && !can(Usuario::PERMISO_CHATBOT_EDITAR) &&
            !can(Usuario::PERMISO_CHATBOT_ELIMINAR) && !can(Usuario::PERMISO_CHATBOT_LISTADO)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $info['asistente'] = $this->asistenteDelUsuario();

        return view('chatbots.asistente-ia.index', $info);
    }

    public function modelos()
    {
        return response()->json([
            'modelos' => $this->ollama->listarModelos(),
        ]);
    }

    public function guardar(Request $request)
    {
        $data = $request->validate([
            'nombre'                 => 'required|string|max:120',
            'rol'                    => 'nullable|string|max:120',
            'descripcion'            => 'nullable|string',
            'system_prompt'          => 'required|string|max:2000',
            'modelo'                 => 'required|string',
            'creatividad'            => 'required|integer|min:0|max:100',
            'formalidad'             => 'required|integer|min:0|max:100',
            'brevedad'               => 'required|integer|min:0|max:100',
            'empatia'                => 'required|integer|min:0|max:100',
            'capacidades'            => 'nullable|array',
            'respetar_horario'       => 'boolean',
            'hora_inicio'            => 'nullable|date_format:H:i',
            'hora_fin'               => 'nullable|date_format:H:i',
            'palabras_clave'         => 'nullable|array',
            'mensaje_bienvenida'     => 'nullable|string',
            'mensaje_fuera_horario'  => 'nullable|string',
            'mensaje_transferencia'  => 'nullable|string',
        ]);

        $asistente = $this->asistenteDelUsuario() ?? new ChatbotAiAssistant();

        $asistente->fill($data);
        $asistente->provider    = 'ollama';
        $asistente->creado_por  = $asistente->creado_por ?? auth()->user()->uuid;
        $asistente->cod_empresa = $asistente->cod_empresa ?? auth()->user()->empresa?->id;
        $asistente->activo      = true;
        $asistente->save();

        return response()->json([
            'estado'  => 'success',
            'mensaje' => 'Configuración guardada correctamente.',
        ]);
    }

    public function simular(Request $request)
    {
        $request->validate([
            'mensaje'   => 'required|string',
            'historial' => 'nullable|array',
        ]);

        $asistente = $this->asistenteDelUsuario();

        if (!$asistente) {
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Primero guarda la configuración del asistente.',
            ], 422);
        }

        $historial = collect($request->input('historial', []))
            ->map(fn ($m) => ['role' => $m['role'], 'content' => $m['content']])
            ->push(['role' => 'user', 'content' => $request->input('mensaje')])
            ->values()
            ->all();

        try {
            $respuesta = $this->ollama->chat(
                $this->construirSystemPrompt($asistente),
                $historial,
                $asistente->modelo,
                ['temperature' => $asistente->creatividad / 100]
            );
        } catch (Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'No se pudo contactar al modelo. Verifica que Ollama esté corriendo.',
            ], 500);
        }

        return response()->json([
            'estado'    => 'success',
            'respuesta' => $respuesta,
        ]);
    }

    private function asistenteDelUsuario(): ?ChatbotAiAssistant
    {
        return ChatbotAiAssistant::where(function ($q) {
                $q->where('creado_por', auth()->user()->uuid)
                    ->orWhere('cod_empresa', auth()->user()->empresa?->id);
            })
            ->latest()
            ->first();
    }

    private function construirSystemPrompt(ChatbotAiAssistant $asistente): string
    {
        $tono = [];

        if ($asistente->formalidad >= 70) $tono[] = 'formal';
        elseif ($asistente->formalidad <= 30) $tono[] = 'muy casual y cercano';
        else $tono[] = 'semi-formal';

        if ($asistente->brevedad >= 70) $tono[] = 'responde de forma breve y directa';
        elseif ($asistente->brevedad <= 30) $tono[] = 'puedes dar respuestas más detalladas';

        if ($asistente->empatia >= 70) $tono[] = 'muestra empatía y calidez en tus respuestas';

        $prompt  = $asistente->system_prompt . "\n\n";
        $prompt .= "Tu nombre es {$asistente->nombre} y tu rol es {$asistente->rol}. ";
        $prompt .= 'Estilo de comunicación: ' . implode(', ', $tono) . ".\n";

        if (!empty($asistente->palabras_clave)) {
            $prompt .= 'Si el usuario menciona alguna de estas palabras clave (' .
                implode(', ', $asistente->palabras_clave) .
                '), indica que lo vas a transferir con un agente humano.' . "\n";
        }

        return $prompt;
    }
}
