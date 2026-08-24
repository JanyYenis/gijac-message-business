<?php
namespace App\Http\Controllers\Chatbots;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Models\Chatbots\ChatbotAiAssistant;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Services\AI\ChatbotAiPromptBuilder;
use App\Services\AI\DocumentTextExtractor;
use App\Services\AI\OllamaService;
use Illuminate\Support\Facades\Storage;
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
        $info['capacidadOpciones'] = ChatbotAiAssistant::darCapacidad();

        return view('chatbots.asistente-ia.index', $info);
    }

    public function modelos()
    {
        return response()->json([
            'modelos' => $this->ollama->listarModelos(),
            'modelo_actual' => $this->asistenteDelUsuario()?->modelo ?? null,
        ]);
    }

    public function store(Request $request)
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
            'hora_fin'               => 'nullable|date_format:H:i|after:hora_inicio',
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
            throw new ErrorException("No se encontró un asistente configurado para este usuario.");
        }

        $historial = collect($request->input('historial', []))
            ->map(fn ($m) => ['role' => $m['role'], 'content' => $m['content']])
            ->push(['role' => 'user', 'content' => $request->input('mensaje')])
            ->values()
            ->all();

        try {
            $respuesta = $this->ollama->chat(
                app(ChatbotAiPromptBuilder::class)->construir($asistente),
                $historial,
                $asistente->modelo,
                ['temperature' => $asistente->creatividad / 100]
            );
        } catch (Throwable $e) {
            report($e);
            throw new ErrorException("No se pudo contactar al modelo. Verifica que Ollama esté corriendo: ".$e->getMessage());
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

    public function subirDocumento(Request $request, DocumentTextExtractor $extractor)
    {
        $request->validate([
            'documento' => 'required|file|mimes:pdf,docx,txt|max:10240', // 10 MB
        ]);

        $asistente = $this->asistenteDelUsuario();
        if (!$asistente) {
            throw new ErrorException("Primero guarda la configuración básica del asistente.");
        }

        $file = $request->file('documento');

        try {
            $contenido = $extractor->extraer($file);
        } catch (Throwable $e) {
            report($e);
            throw new ErrorException("No se pudo leer el archivo: " . $e->getMessage());
        }

        if (trim($contenido) === '') {
            throw new ErrorException("No se pudo extraer texto de este documento.");
        }

        // Solo se permite 1 documento por asistente: si ya había uno, se reemplaza
        if ($asistente->documento_path) {
            Storage::disk('local')->delete($asistente->documento_path);
        }

        $path = $file->store('chatbots/ai-assistant/docs', 'local');

        $asistente->update([
            'documento_path'         => $path,
            'documento_nombre'       => $file->getClientOriginalName(),
            'documento_size'         => $file->getSize(),
            'documento_contenido'    => $contenido,
            'documento_procesado_en' => now(),
        ]);

        return response()->json([
            'estado'    => 'success',
            'mensaje'   => 'Documento procesado correctamente.',
            'documento' => [
                'nombre' => $asistente->documento_nombre,
                'size'   => $asistente->documento_size,
                'fecha'  => $asistente->documento_procesado_en->format('d/m/Y'),
            ],
        ]);
    }

    public function eliminarDocumento()
    {
        $asistente = $this->asistenteDelUsuario();
        if (!$asistente || !$asistente->documento_path) {
            throw new ErrorException("No hay ningún documento para eliminar.");
        }

        Storage::disk('local')->delete($asistente->documento_path);

        $asistente->update([
            'documento_path'         => null,
            'documento_nombre'       => null,
            'documento_size'         => null,
            'documento_contenido'    => null,
            'documento_procesado_en' => null,
        ]);

        return response()->json([
            'estado'  => 'success',
            'mensaje' => 'Documento eliminado.',
        ]);
    }
}
