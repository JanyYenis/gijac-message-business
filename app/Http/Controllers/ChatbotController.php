<?php
namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\Chatbot;
use Illuminate\Http\Request;
use App\Models\ChatbotMessage;
use App\Models\ChatbotNode;
use App\Models\ChatbotOption;
use App\Models\ConfiguracionMeta;
use App\Models\Usuario;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Yajra\DataTables\Facades\DataTables;

class ChatbotController extends Controller
{
    public function index(Request $request)
    {
        if (!can(Usuario::PERMISO_CHATBOT_CREAR) && !can(Usuario::PERMISO_CHATBOT_EDITAR) &&
            !can(Usuario::PERMISO_CHATBOT_ELIMINAR) && !can(Usuario::PERMISO_CHATBOT_LISTADO)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        return view('chatbots.index');
    }

    public function create(Request $request)
    {
        if (!can(Usuario::PERMISO_CHATBOT_CREAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        return view('chatbots.crear');
    }

    public function listado(Request $request)
    {
        if (!can(Usuario::PERMISO_CHATBOT_EDITAR) &&
            !can(Usuario::PERMISO_CHATBOT_ELIMINAR) && !can(Usuario::PERMISO_CHATBOT_LISTADO)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $chatbots = Chatbot::with(
            'infoEstado',
            'nodes',
        )->where('estado', '!=', Chatbot::ELIMINADO)
        ->where('uuid', $this->uuid)
        ->orderByDesc('created_at');

        return DataTables::eloquent($chatbots)
            ->addColumn("estado", function ($model) {
                $info['concepto'] = $model?->infoEstado;
                return view("sistema.estado", $info);
            })
            ->addColumn("action", function($model) {
                $info['model'] = $model;
                $info['puedeEditar'] = can(Usuario::PERMISO_CHATBOT_EDITAR);
                $info['puedeEliminar'] = can(Usuario::PERMISO_CHATBOT_ELIMINAR);
                return view("chatbots.columnas.acciones", $info);
            })
            ->rawColumns(["action", "estado"])
            ->make(true);
    }

    public function store(Request $request)
    {
        $datos = $request->all();
        $datos['nodes'] = json_decode($datos['nodes'], true);

        $chatbot = Chatbot::create([
            'uuid' => auth()->user()->uuid,
            'name' => $datos['name'],
        ]);

        if (!$chatbot) {
            throw new ErrorException("Error al intentar crear el chatbot.");
        }

        $chatbot->refresh();

        if (count($datos['nodes'])) {
            foreach ($datos['nodes'] as $index => $node) {
                $media_url = null;
                if (isset($datos['archivos'][$index])) {
                    $archivo = $datos['archivos'][$index];
                    $nombreOriginal = time() . '.' . $archivo->getClientOriginalExtension();
                    $mimeType = $archivo->getMimeType();

                    // Verificar si el archivo es un PDF
                    if ($mimeType == 'application/pdf') {
                        $archivo->move(public_path('documentos/chatbots'), $nombreOriginal);
                        $media_url = asset('documentos/chatbots/'.$nombreOriginal);
                    } elseif (str_starts_with($mimeType, 'image/')) {
                        $archivo->move(public_path('img/chatbots'), $nombreOriginal);
                        $media_url = asset('img/chatbots/'.$nombreOriginal);
                    } elseif ($mimeType == 'video/mp4') {
                        $archivo->move(public_path('videos/chatbots'), $nombreOriginal);
                        $media_url = asset('videos/chatbots/'.$nombreOriginal);
                    } else {
                        throw new ErrorException('Error al intentar cargar la imagen.');
                    }

                    // if (!file_exists($datos['contenido_multimedia'])) {
                    //     throw new ErrorException('Error al intentar guardar el archivo.');
                    // }
                }

                $new_node = ChatbotNode::create([
                    'chatbot_id' => $chatbot->id,
                    'number' => $index+1,
                    'title' => null,
                    'type' => ChatbotNode::DATOS_TIPO_CHATBOT[$node['type']],
                    'message' => $node['message'],
                    'media_url' => $media_url,
                ]);

                if (!$new_node) {
                    throw new ErrorException("Error al intentar crear un node.");
                }

                $new_node->refresh();

                if (count($node['connections'])) {
                    foreach ($node['connections'] as $key => $connection) {
                        ChatbotOption::create([
                            'node_id' => $new_node->id,
                            'label' => $connection['response'],
                            'value' => $key+1,
                            'next_node_id' => isset($connection['nextNode']) ? $connection['nextNode'] : null,
                        ]);
                    }
                }
            }
        }

        return [
            "estado" => "success",
            "mensaje" => "Se creo correctamente el chatbot {$chatbot->name}.",
        ];
    }

    public function edit(Request $request, Chatbot $chatbot)
    {
        if (!can(Usuario::PERMISO_CHATBOT_EDITAR) && !can(Usuario::PERMISO_CHATBOT_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $info['chatbot'] = $chatbot;

        return view('chatbots.editar', $info);
    }

    public function consultarNodes(Request $request, Chatbot $chatbot)
    {
        $chatbot->load('nodesOrdenados.opcionesOrdenado');

        return [
            'estado' => 'success',
            'mensaje' => 'Se cargo correctamenta la información',
            'chatbot' => $chatbot,
        ];
    }

    public function update(Request $request, Chatbot $chatbot)
    {
        $datos = $request->all();
        $datos['nodes'] = json_decode($datos['nodes'], true);

        $actulizar = $chatbot->update([
            'name' => $datos['name'],
        ]);

        if (!$actulizar) {
            throw new ErrorException("Error al intentar actualizar el chatbot.");
        }

        if (count($datos['nodes'])) {
            ChatbotNode::where('estado', ChatbotNode::ACTIVO)
                ->update(['estado' => ChatbotNode::INACTIVO]);
            foreach ($datos['nodes'] as $index => $node) {
                $nodeActual = ChatbotNode::where([
                    'chatbot_id' => $chatbot->id,
                    'number' => $index+1,
                    'type' => ChatbotNode::DATOS_TIPO_CHATBOT[$node['type']]
                ])->first();
                $media_url = $nodeActual ? $nodeActual->media_url : null;
                if (isset($datos['archivos'][$index])) {
                    $archivo = $datos['archivos'][$index];
                    $nombreOriginal = time() . '.' . $archivo->getClientOriginalExtension();
                    $mimeType = $archivo->getMimeType();

                    // Verificar si el archivo es un PDF
                    if ($mimeType == 'application/pdf') {
                        $archivo->move(public_path('documentos/chatbots'), $nombreOriginal);
                        $media_url = asset('documentos/chatbots/'.$nombreOriginal);
                    } elseif (str_starts_with($mimeType, 'image/')) {
                        $archivo->move(public_path('img/chatbots'), $nombreOriginal);
                        $media_url = asset('img/chatbots/'.$nombreOriginal);
                    } elseif ($mimeType == 'video/mp4') {
                        $archivo->move(public_path('videos/chatbots'), $nombreOriginal);
                        $media_url = asset('videos/chatbots/'.$nombreOriginal);
                    } else {
                        throw new ErrorException('Error al intentar cargar la imagen.');
                    }

                    // if (!file_exists($datos['contenido_multimedia'])) {
                    //     throw new ErrorException('Error al intentar guardar el archivo.');
                    // }
                }

                $new_node = ChatbotNode::updateOrCreate([
                    'chatbot_id' => $chatbot->id,
                    'number' => $index+1,
                    'type' => ChatbotNode::DATOS_TIPO_CHATBOT[$node['type']],
                ], [
                    'title' => null,
                    'message' => $node['message'],
                    'media_url' => $media_url,
                    'estado' => ChatbotNode::ACTIVO,
                ]);

                if (!$new_node) {
                    throw new ErrorException("Error al intentar crear un node.");
                }

                $new_node->refresh();

                if (count($node['connections'])) {
                    ChatbotOption::where('estado', ChatbotOption::ACTIVO)
                        ->where('node_id', $new_node->id)
                        ->update(['estado' => ChatbotOption::INACTIVO]);
                    foreach ($node['connections'] as $key => $connection) {
                        ChatbotOption::updateOrCreate([
                            'node_id' => $new_node->id,
                            'value' => $key+1,
                        ], [
                            'label' => $connection['response'],
                            'next_node_id' => isset($connection['nextNode']) ? $connection['nextNode'] : null,
                            'estado' => ChatbotOption::ACTIVO
                        ]);
                    }
                }
            }
        }

        return [
            "estado" => "success",
            "mensaje" => "Se actualizo correctamente el chatbot {$chatbot->name}.",
        ];
    }
}
