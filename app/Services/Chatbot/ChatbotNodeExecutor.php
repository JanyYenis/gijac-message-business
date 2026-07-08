<?php

namespace App\Services\Chatbot;

use App\Models\Chatbots\{ChatbotConnection, ChatbotFlow, ChatbotNode, ChatbotNodeConfig, ChatbotSession, ChatbotSessionLog};
use App\Models\{Contacto, Mensaje};
use Illuminate\Support\Facades\Log;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\{Button, ButtonAction};
use Netflie\WhatsAppCloudApi\Message\OptionsList\{Action, Row, Section};
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

class ChatbotNodeExecutor
{
    public function execute(ChatbotFlow $flow, ChatbotSession $session, ChatbotNode $node, string $waFrom, $config, WhatsAppCloudApi $api): void
    {
        if ($node->tipo === ChatbotNode::END) {
            $this->closeSession($session, $node, $waFrom, $api);
            return;
        }

        $this->sendNodeMessage($config, $api, $node, $waFrom, $session);

        if (in_array($node->tipo, [ChatbotNode::BUTTONS, ChatbotNode::LIST])) {
            $session->update(['current_node_id' => $node->id, 'estado' => ChatbotSession::ESPERANDO, 'ultima_interacción_en' => now()]);
            return;
        }

        $connection = ChatbotConnection::where('flow_id', $flow->id)
            ->where('source_node_id', $node->id)
            ->where('source_output', 'output_1')
            ->first();

        if (!$connection) return;

        $nextNode = ChatbotNode::find($connection->target_node_id);
        $autoSend = filter_var($nextNode->config(ChatbotNodeConfig::KEY_AUTO_SEND)?->valor, FILTER_VALIDATE_BOOLEAN);

        if ($autoSend) {
            $this->execute($flow, $session, $nextNode, $waFrom, $config, $api);
        } else {
            $session->update(['current_node_id' => $nextNode->id, 'estado' => ChatbotSession::ESPERANDO, 'ultima_interacción_en' => now()]);
        }
    }

    private function closeSession(ChatbotSession $session, ChatbotNode $node, string $waFrom, WhatsAppCloudApi $api): void
    {
        $session->update(['estado' => ChatbotSession::COMPLETADO, 'fecha_finalizado' => now()]);
        if ($closeMsg = $node->config(ChatbotNodeConfig::KEY_CLOSE_MESSAGE)) {
            $api->sendTextMessage($waFrom, $closeMsg->valor);
        }
    }

    public function sendNodeMessage($config, WhatsAppCloudApi $api, ChatbotNode $node, string $waFrom, ?ChatbotSession $session = null): void
    {
        $configs = $node->configs()->pluck('valor', 'key')->toArray();
        $get = fn($key) => $configs[$key] ?? null;

        $message = $get(ChatbotNodeConfig::KEY_MESSAGE);
        $caption = $get(ChatbotNodeConfig::KEY_CAPTION);
        $rawMediaUrl = $get(ChatbotNodeConfig::KEY_URL);
        $mediaUrl = is_array($rawMediaUrl) ? ($rawMediaUrl['url'] ?? null) : $rawMediaUrl;

        $contacto = Contacto::where('numero_completo', $waFrom)->where('cod_empresa', $config->cod_empresa)->first();

        $datos = [
            'campaign_id' => null, 'contact_id' => $contacto->id, 'wa_message_id' => null,
            'wa_from' => $config->phone_number_id, 'wa_to' => $waFrom, 'type' => Mensaje::TEXTO,
            'body' => $message, 'metadata' => null, 'estado' => Mensaje::ENVIADO, 'sent_at' => now(),
        ];

        $response = null;

        try {
            $response = match (true) {
                in_array($node->tipo, [ChatbotNode::TEXT, ChatbotNode::START, ChatbotNode::QUESTION]) && $message
                    => $api->sendTextMessage($waFrom, $message),

                $node->tipo === ChatbotNode::BUTTONS
                    => $this->sendButtons($api, $waFrom, $message, $get, $datos),

                $node->tipo === ChatbotNode::LIST
                    => $this->sendList($api, $waFrom, $message, $get, $datos),

                in_array($node->tipo, [ChatbotNode::IMAGE, ChatbotNode::VIDEO, ChatbotNode::DOC, ChatbotNode::AUDIO])
                    => $this->sendMedia($api, $waFrom, $node->tipo, $mediaUrl, $caption, $message, $datos),

                default => null,
            };
        } catch (\Exception $e) {
            Log::error('ERROR CRÍTICO ENVIANDO WHATSAPP: ' . $e->getMessage());
        }

        $this->persistSentMessage($response, $datos, $node, $message, $mediaUrl, $session);
    }

    private function sendButtons(WhatsAppCloudApi $api, string $waFrom, ?string $message, callable $get, array &$datos)
    {
        $buttons = $get(ChatbotNodeConfig::KEY_BUTTONS) ?? [];
        $waButtons = []; $metaButtons = [];

        foreach ($buttons as $index => $btn) {
            $waButtons[] = new Button((string)($index + 1), $btn['label']);
            $metaButtons[] = ['type' => 'text', 'text' => $btn['label']];
        }

        if (!count($waButtons)) return null;

        $datos['type'] = Mensaje::INTERACCION_BOTON;
        $datos['metadata'] = (object)['buttons' => json_encode($metaButtons)];

        return $api->sendButton($waFrom, $message, new ButtonAction($waButtons));
    }

    private function sendList(WhatsAppCloudApi $api, string $waFrom, ?string $message, callable $get, array &$datos)
    {
        $listTitle = $get(ChatbotNodeConfig::KEY_LIST_TITLE) ?: 'Menú';
        $sectionName = $get(ChatbotNodeConfig::KEY_SECTION_NAME) ?: 'Opciones';
        $rows = $get(ChatbotNodeConfig::KEY_SECTIONS) ?? [];

        $waRows = []; $metaRows = [];
        foreach ($rows as $index => $row) {
            $waRows[] = new Row((string)($index + 1), $row['label']);
            $metaRows[] = ['type' => 'text', 'text' => $row['label']];
        }

        if (!count($waRows)) return null;

        $datos['type'] = Mensaje::INTERACCION_LISTADO;
        $datos['metadata'] = (object)['buttons' => json_encode($metaRows)];

        $action = new Action('Seleccionar', [new Section($sectionName, $waRows)]);
        return $api->sendList($waFrom, '✅ Menú', $message, $listTitle, $action);
    }

    private function sendMedia(WhatsAppCloudApi $api, string $waFrom, string $tipo, ?string $mediaUrl, ?string $caption, ?string $message, array &$datos)
    {
        if (!$mediaUrl) return null;

        $linkId = new LinkID($mediaUrl);
        $filename = basename(parse_url($mediaUrl, PHP_URL_PATH));
        $datos['body'] = $caption ?? $filename;

        return match ($tipo) {
            ChatbotNode::IMAGE => tap($api->sendImage($waFrom, $linkId, $caption), fn() => $datos['type'] = Mensaje::IMAGEN),
            ChatbotNode::VIDEO => tap($api->sendVideo($waFrom, $linkId, $caption), fn() => $datos['type'] = Mensaje::VIDEO),
            ChatbotNode::DOC   => tap($api->sendDocument($waFrom, $linkId, $filename, $caption ?: $message), fn() => $datos['type'] = Mensaje::DOCUMENTO),
            ChatbotNode::AUDIO => tap($api->sendAudio($waFrom, $linkId), fn() => $datos['type'] = Mensaje::AUDIO),
        };
    }

    private function persistSentMessage($response, array $datos, ChatbotNode $node, ?string $message, ?string $mediaUrl, ?ChatbotSession $session): void
    {
        if (!$response?->body()) {
            Log::error('ENVIAR MENSAJE: La API de WhatsApp no devolvió respuesta.');
            return;
        }

        $data = json_decode($response->body());
        $waMsgId = $data->messages[0]->id ?? null;
        $datos['wa_message_id'] = $waMsgId;
        Mensaje::create($datos);

        if ($session) {
            ChatbotSessionLog::create([
                'session_id' => $session->id,
                'node_id' => $node->id,
                'direction' => ChatbotSessionLog::SALIDA,
                'tipo_mensaje' => $this->mapNodeToLogType($node->tipo),
                'contenido' => json_encode(['message' => $message, 'media_url' => $mediaUrl]),
                'estado' => ChatbotSessionLog::ENVIADO,
                'provider_message_id' => $waMsgId,
                'fecha_envio' => now(),
            ]);
        }
    }

    private function mapNodeToLogType($nodeType)
    {
        return [
            ChatbotNode::TEXT => ChatbotSessionLog::TIPO_TEXT,
            ChatbotNode::START => ChatbotSessionLog::TIPO_TEXT,
            ChatbotNode::IMAGE => ChatbotSessionLog::TIPO_IMAGE,
            ChatbotNode::VIDEO => ChatbotSessionLog::TIPO_VIDEO,
            ChatbotNode::DOC => ChatbotSessionLog::TIPO_DOCUMENT,
            ChatbotNode::AUDIO => ChatbotSessionLog::TIPO_AUDIO,
            ChatbotNode::BUTTONS => ChatbotSessionLog::TIPO_BUTTONS,
            ChatbotNode::LIST => ChatbotSessionLog::TIPO_LIST,
        ][$nodeType] ?? ChatbotSessionLog::TIPO_TEXT;
    }
}
