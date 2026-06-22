<?php
namespace App\Http\Controllers\Chatbots;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chatbots\Nodos\StoreNodoRequest;
use App\Models\Chatbots\ChatbotConnection;
use App\Models\Chatbots\ChatbotFlow;
use App\Models\Chatbots\ChatbotFlowVersion;
use App\Models\Chatbots\ChatbotNode;
use App\Models\Chatbots\ChatbotNodeConfig;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ChatbotNodoController extends Controller
{
    private const CONFIG_KEY_MAP = [
        'message'              => ChatbotNodeConfig::KEY_MESSAGE,
        'caption'              => ChatbotNodeConfig::KEY_CAPTION,
        'media_url'            => ChatbotNodeConfig::KEY_URL,
        'buttons'              => ChatbotNodeConfig::KEY_BUTTONS,
        'list_title'           => ChatbotNodeConfig::KEY_LIST_TITLE,
        'rows'                 => ChatbotNodeConfig::KEY_SECTIONS, // Mantienes el tuyo
        'section'              => ChatbotNodeConfig::KEY_SECTION_NAME, // <--- AGREGAR ESTO
        'variable'             => ChatbotNodeConfig::KEY_VARIABLE,
        'operator'             => ChatbotNodeConfig::KEY_OPERATOR,
        'compare'              => ChatbotNodeConfig::KEY_COMPARE,
        'system_prompt'        => ChatbotNodeConfig::KEY_SYSTEM_PROMPT,
        'model'                => ChatbotNodeConfig::KEY_MODEL,
        'temperature'          => ChatbotNodeConfig::KEY_TEMPERATURE,
        'max_tokens'           => ChatbotNodeConfig::KEY_MAX_TOKENS,
        'method'               => ChatbotNodeConfig::KEY_METHOD,
        'headers'              => ChatbotNodeConfig::KEY_HEADERS,
        'body'                 => ChatbotNodeConfig::KEY_BODY,
        'response_variable'    => ChatbotNodeConfig::KEY_RESPONSE_VARIABLE,
        'data_type'            => ChatbotNodeConfig::KEY_DATA_TYPE,
        'target_node_id'       => ChatbotNodeConfig::KEY_TARGET_NODE_ID,
        'department'           => ChatbotNodeConfig::KEY_DEPARTMENT,
        'transition_message'   => ChatbotNodeConfig::KEY_TRANSITION_MSG,
        'question'             => ChatbotNodeConfig::KEY_QUESTION,
        'tag_name'             => ChatbotNodeConfig::KEY_TAG_NAME,
        'var_name'             => ChatbotNodeConfig::KEY_VAR_NAME,
        'var_value'            => ChatbotNodeConfig::KEY_VAR_VALUE,
        'trigger'              => ChatbotNodeConfig::KEY_TRIGGER,
        'keywords'             => ChatbotNodeConfig::KEY_KEYWORDS,
        'close_message'        => ChatbotNodeConfig::KEY_CLOSE_MESSAGE,
        'auto_send'            => ChatbotNodeConfig::KEY_AUTO_SEND,
        // section / media_filename se guardan tal cual dentro de otras keys
        // compuestas (rows / buttons) o no requieren persistirse por separado.
    ];

    public function index(Request $request)
    {
        if (!can(Usuario::PERMISO_CHATBOT_CREAR) && !can(Usuario::PERMISO_CHATBOT_EDITAR) &&
            !can(Usuario::PERMISO_CHATBOT_ELIMINAR) && !can(Usuario::PERMISO_CHATBOT_LISTADO)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $info['total_flujos'] = ChatbotFlow::where('creado_por', auth()->user()->uuid)
            ->where('estado', ChatbotFlow::ACTIVO)
            ->count();
        $info['ultimo_flujo'] = ChatbotFlow::where('creado_por', auth()->user()->uuid)
            ->where('estado', ChatbotFlow::ACTIVO)
            ->orderByDesc('fecha_publicado')
            ->first();

        return view('chatbots.chatbot-nodos.index', $info);
    }

    public function store(StoreNodoRequest $request, ?ChatbotFlow $flow = null)
    {
        $action      = $request->input('action');     // draft | published
        $drawflow    = $request->input('drawflow');
        $nodesInput  = $request->input('nodes', []);
        $connsInput  = $request->input('connections', []);

        try {
            $flow = DB::transaction(function () use ($request, $flow, $action, $drawflow, $nodesInput, $connsInput) {

                $userId = Auth::user()->uuid;
                $inputFlowId = $request->input('flow_id');

                // ── 1. INTELIGENCIA: Buscar el flujo correcto ────────────────────────
                // Prioridad 1: El ID específico que envió el Javascript (si existe y es del usuario)
                if ($inputFlowId) {
                    $flow = ChatbotFlow::where('id', $inputFlowId)
                        ->where('creado_por', $userId)
                        ->first();
                }

                // Prioridad 2: Si no hay ID (o es inválido), buscar el flujo ACTIVO del usuario
                if (!$flow) {
                    $flow = ChatbotFlow::where('creado_por', $userId)
                        ->where('estado', ChatbotFlow::ACTIVO)
                        ->first();
                }

                // Prioridad 3: Si no hay activo, buscar el último borrador que haya creado
                if (!$flow) {
                    $flow = ChatbotFlow::where('creado_por', $userId)
                        ->latest()
                        ->first();
                }

                // Prioridad 4: Si absolutamente no tiene nada, crear uno nuevo
                if (!$flow) {
                    $flow = new ChatbotFlow();
                    $flow->canal = ChatbotFlow::WHATSAPP; // Canal por defecto
                }

                // ── 2. Llenar datos del flujo ─────────────────────────────────────────
                $flow->fill([
                    'nombre'         => $request->input('nombre', $flow->nombre ?? 'Mi Chatbot'),
                    'descripcion'    => $request->input('descripcion', $flow->descripcion),
                    'canal'          => $flow->canal, // Mantener canal existente
                    'estado'         => $action === 'published' ? ChatbotFlow::ACTIVO : ChatbotFlow::BORRADOR,
                    'creado_por'     => $flow->creado_por ?? $userId, // Mantener creador original
                    'versión_actual' => $action === 'published'
                        ? ($flow->exists ? $flow->versión_actual + 1 : 1)
                        : ($flow->versión_actual ?? 1),
                    'fecha_publicado'=> $action === 'published' ? now() : $flow->fecha_publicado,
                ]);
                $flow->save();

                // ── 3. Estrategia Delete & Recreate (Limpiar y Reconstruir) ────────
                // Esto asegura que no queden nodos huérfanos ni conexiones rotas.
                // (ChatbotConnection tiene flow_id, es fácil borrarlas todas de una vez)
                ChatbotConnection::where('flow_id', $flow->id)->delete();

                // Como ChatbotNodeConfig depende de node_id, primero sacamos los IDs de los nodos actuales
                $nodeIds = $flow->nodos()->pluck('id');

                if ($nodeIds->isNotEmpty()) {
                    // Borramos todas las configs asociadas a esos nodos
                    ChatbotNodeConfig::whereIn('node_id', $nodeIds)->delete();
                }

                $flow->nodos()->delete();

                $idMap = [];

                foreach ($nodesInput as $nodeData) {
                    $node = ChatbotNode::create([
                        'flow_id'     => $flow->id,
                        'tipo'        => $this->tipoToInt($nodeData['type']),
                        'etiqueta'    => $nodeData['label'],
                        'pos_x'       => $nodeData['pos_x'],
                        'pos_y'       => $nodeData['pos_y'],
                        'inputs'      => $nodeData['inputs'],
                        'outputs'     => $nodeData['outputs'],
                        'drawflow_id' => $nodeData['drawflow_id'],
                        'principal'   => $nodeData['principal'] ?? 0,
                        'auto_send'   => $nodeData['auto_send'] ?? 0,
                    ]);

                    $idMap[$nodeData['drawflow_id']] = $node->id;

                    $this->guardarConfigNodo($node, $nodeData, $request);
                }

                foreach ($connsInput as $conn) {
                    $sourceId = $idMap[$conn['source_node_drawflow_id']] ?? null;
                    $targetId = $idMap[$conn['target_node_drawflow_id']] ?? null;

                    if (!$sourceId || !$targetId) continue;

                    ChatbotConnection::create([
                        'flow_id'         => $flow->id,
                        'source_node_id'  => $sourceId,
                        'target_node_id'  => $targetId,
                        'source_output'   => $conn['source_output'],
                        'target_input'    => $conn['target_input'],
                        'etiqueta'        => $conn['label'] ?? null,
                    ]);
                }

                // ── 4. Guardar versión si se publica ────────────────────────────────
                if ($action === 'published') {
                    ChatbotFlowVersion::create([
                        'flow_id'        => $flow->id,
                        'numero_version' => $flow->versión_actual,
                        'snapshot'       => json_encode([
                            'drawflow'    => $drawflow,
                            'nodes'       => $nodesInput,
                            'connections' => $connsInput,
                        ]),
                        'estado'         => ChatbotFlowVersion::PUBLICADO,
                        'creado_por'     => $userId,
                        'fecha_publicado'=> now(),
                    ]);
                }

                return $flow;
            });
        } catch (Throwable $e) {
            report($e);
            return response()->json([
                'estado'  => 'error',
                'mensaje' => 'Ocurrió un error al guardar el flujo.',
            ], 500);
        }

        return response()->json([
            'estado'  => 'success',
            'mensaje' => $action === 'published' ? 'Flujo publicado.' : 'Flujo guardado.',
            'flow_id' => $flow->id,
        ]);
    }

    /**
     * Persiste la config de un nodo en chatbot_node_configs,
     * subiendo el archivo multimedia si el nodo lo requiere.
     */
    private function guardarConfigNodo(ChatbotNode $node, array $nodeData, StoreNodoRequest $request): void
    {
        $cfg = $nodeData['config'] ?? [];

        if (isset($nodeData['auto_send'])) {
            $cfg['auto_send'] = $nodeData['auto_send'];
        }

        // INYECCIÓN: Mensaje por defecto para el nodo END (Finalizar)
        if ($node->tipo === ChatbotNode::END) {
            $mensajeDefecto = '¡Gracias por contactarnos!'; // Puedes cambiar el texto aquí
            if (empty($cfg['close_message']) && !isset($cfg['close_message'])) {
                $cfg['close_message'] = $mensajeDefecto;
            }
        }

        // ── Subida de archivo multimedia (image, video, doc, audio, pdf) ──
        $fileField = 'file_' . $nodeData['drawflow_id'];

        if ($request->hasFile($fileField)) {
            $path = $request->file($fileField)->store('chatbots/media', 'public');
            $cfg['media_url']      = url(Storage::url($path));
            $cfg['media_filename'] = $request->file($fileField)->getClientOriginalName();
        }

        // ── Guardar cada key del config como fila independiente ─────────
        foreach ($cfg as $key => $value) {
            // Asegúrate de que 'message' esté mapeado para el tipo START
            // si deseas que el nodo de inicio también envíe texto.
            // Normalmente CONFIG_KEY_MAP es un array protected en el controlador.
            $configKey = self::CONFIG_KEY_MAP[$key] ?? null;

            if ($configKey === null) continue;

            ChatbotNodeConfig::create([
                'node_id' => $node->id,
                'key'     => $configKey,
                'valor'   => $value,
            ]);
        }

        // media_filename se guarda dentro de la misma fila de URL para no
        // perder el nombre original del archivo
        if (isset($cfg['media_url']) && isset($cfg['media_filename'])) {
            ChatbotNodeConfig::where('node_id', $node->id)
                ->where('key', ChatbotNodeConfig::KEY_URL)
                ->update([
                    'valor' => [
                        'url'      => $cfg['media_url'],
                        'filename' => $cfg['media_filename'],
                    ],
                ]);
        }
    }

    /**
     * Traduce el string de tipo de nodo (frontend) a la constante
     * entera definida en ChatbotNode.
     */
    private function tipoToInt(string $type): int
    {
        $map = [
            'text'      => ChatbotNode::TEXT,
            'image'     => ChatbotNode::IMAGE,
            'video'     => ChatbotNode::VIDEO,
            'doc'       => ChatbotNode::DOC,
            'audio'     => ChatbotNode::AUDIO,
            'buttons'   => ChatbotNode::BUTTONS,
            'list'      => ChatbotNode::LIST,
            'question'  => ChatbotNode::QUESTION,
            'capture'   => ChatbotNode::CAPTURE,
            'condition' => ChatbotNode::CONDITION,
            'variable'  => ChatbotNode::VARIABLE,
            'tag'       => ChatbotNode::TAG,
            'goto'      => ChatbotNode::GOTO,
            'webhook'   => ChatbotNode::WEBHOOK,
            'api'       => ChatbotNode::API,
            'agent'     => ChatbotNode::AGENT,
            'end'       => ChatbotNode::END,
            'ai'        => ChatbotNode::AI,
            'pdf'       => ChatbotNode::PDF,
            'generate'  => ChatbotNode::GENERATE,
            'start'     => ChatbotNode::START,
        ];

        return $map[$type] ?? ChatbotNode::TEXT;
    }

    public function getFlowData()
    {
        $user = Auth::user();

        // 1. Buscar el flujo del usuario (Asumiendo que solo tiene uno activo/borrador)
        $flow = ChatbotFlow::where('creado_por', $user->uuid)
            ->orderBy('updated_at', 'desc') // Traer el último editado
            ->first();

        if (!$flow) {
            // IMPORTANTE: Devolver flow_id explícitamente como null para que el JS sepa que no hay
            return response()->json([
                'flow_id'     => null,
                'nodes'       => [],
                'connections' => []
            ]);
        }

        // 2. Obtener nodos con sus configuraciones
        $nodes = ChatbotNode::where('flow_id', $flow->id)->get();

        // Mapa inverso: ID Entero -> String
        $typeMap = array_flip(ChatbotNode::darTipo(false)); // Esto depende de tu helper darConcepto
        // Si darTipo devuelve objetos, constrúyelo manualmente:
        $typeMap = [
            ChatbotNode::TEXT      => 'text',
            ChatbotNode::IMAGE     => 'image',
            ChatbotNode::VIDEO     => 'video',
            ChatbotNode::DOC       => 'doc',
            ChatbotNode::AUDIO     => 'audio',
            ChatbotNode::BUTTONS   => 'buttons',
            ChatbotNode::LIST      => 'list',
            ChatbotNode::QUESTION  => 'question',
            ChatbotNode::CAPTURE   => 'capture',
            ChatbotNode::CONDITION => 'condition',
            ChatbotNode::VARIABLE  => 'variable',
            ChatbotNode::TAG       => 'tag',
            ChatbotNode::GOTO      => 'goto',
            ChatbotNode::WEBHOOK   => 'webhook',
            ChatbotNode::API       => 'api',
            ChatbotNode::AGENT     => 'agent',
            ChatbotNode::END       => 'end',
            ChatbotNode::AI        => 'ai',
            ChatbotNode::PDF       => 'pdf',
            ChatbotNode::GENERATE  => 'generate',
            ChatbotNode::START     => 'start',
        ];

        // Mapa inverso de Config Keys
        $configKeyMap = array_flip(self::CONFIG_KEY_MAP); // Necesitas que CONFIG_KEY_MAP sea público o accesible

        $nodeList = [];
        $configMap = []; // Para agrupar configs por nodo

        // Recuperar todas las configs de este flujo de una vez
        $allConfigs = ChatbotNodeConfig::whereIn('node_id', $nodes->pluck('id'))->get();

        foreach ($allConfigs as $cfg) {
            $configKey = $configKeyMap[$cfg->key] ?? null;
            if ($configKey) {
                $configMap[$cfg->node_id][$configKey] = $cfg->valor;
            }
        }

        foreach ($nodes as $node) {
            $typeString = $typeMap[$node->tipo] ?? 'text';

            // Reconstruir el objeto de configuración
            $finalConfig = $configMap[$node->id] ?? [];

            // Manejo especial para URL/Archivo si están separados
            if (isset($finalConfig['url']) && is_array($finalConfig['url'])) {
                // Tu lógica anterior los unía en 'url', aquí ya viene unido si guardaste como array
                // Si lo guardaste separado, ajústalo aquí.
            }

            $nodeList[] = [
                'drawflow_id' => $node->drawflow_id,
                'type'        => $typeString,
                'label'       => $node->etiqueta,
                'pos_x'       => $node->pos_x,
                'pos_y'       => $node->pos_y,
                'principal'   => $node->principal,
                'auto_send'   => $node->auto_send ?? 0, // Asegúrate de tener esta columna
                'config'      => $finalConfig,
            ];
        }

        // 3. Obtener conexiones
        // Necesitamos hacer un join para obtener los drawflow_id de origen y destino
        $connections = ChatbotConnection::where('chatbot_connections.flow_id', $flow->id)
            ->join('chatbot_nodes as source', 'chatbot_connections.source_node_id', '=', 'source.id')
            ->join('chatbot_nodes as target', 'chatbot_connections.target_node_id', '=', 'target.id')
            ->select(
                'source.drawflow_id as source_id',
                'target.drawflow_id as target_id',
                'chatbot_connections.source_output',
                'chatbot_connections.target_input'
            )
            ->get();

        $connList = [];
        foreach ($connections as $c) {
            $connList[] = [
                'source_id' => $c->source_id,
                'target_id' => $c->target_id,
                'output'    => $c->source_output, // ej: 'output_1'
                'input'     => $c->target_input   // ej: 'input_1'
            ];
        }

        return response()->json([
            'flow_id'     => $flow->id,
            'nodes'       => $nodeList,
            'connections' => $connList,
        ]);
    }

    public function listadoVersiones(Request $request)
    {
        $versiones = ChatbotFlowVersion::where('creado_por', auth()->user()->uuid)
            ->oderbyDesc('numero_version');

        return DataTables::eloquent($versiones)
            ->addColumn("estado", function ($model) {
                $info['concepto'] = $model?->infoEstado;
                return view("sistema.estado", $info);
            })
            ->addColumn("action", function ($model) {
                $info['model'] = $model;
                // $info['estado_eliminado'] = $model?->estado == Campana::ELIMINADO;
                // $info['estado_enviado'] = $model?->estado == Campana::ENVIADO;
                // $info['puede_listado'] = can(Usuario::PERMISO_CAMPANA_LISTADO);
                // $info['puede_crear'] = can(Usuario::PERMISO_CAMPANA_CREAR);
                // $info['puede_editar'] = can(Usuario::PERMISO_CAMPANA_EDITAR);
                // $info['puede_eliminar'] = can(Usuario::PERMISO_CAMPANA_ELIMINAR);
                return view("campanas.columnas.acciones", $info);
            })
            ->rawColumns(["action", "estado"])
            ->make(true);
    }
}
