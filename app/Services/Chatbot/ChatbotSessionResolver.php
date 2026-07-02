<?php

namespace App\Services\Chatbot;

use App\Models\Chatbots\{ChatbotConnection, ChatbotFlow, ChatbotNode, ChatbotNodeConfig, ChatbotSession};
use Illuminate\Support\Facades\Log;

class ChatbotSessionResolver
{
    public function resolveSession(ChatbotFlow $flow, string $waFrom, ?string $nombreContacto): ChatbotSession
    {
        return ChatbotSession::where('flow_id', $flow->id)
            ->where('telefono_contacto', $waFrom)
            ->whereIn('estado', [ChatbotSession::ACTIVO, ChatbotSession::ESPERANDO])
            ->first() ?? ChatbotSession::create([
                'flow_id' => $flow->id,
                'telefono_contacto' => $waFrom,
                'estado' => ChatbotSession::ACTIVO,
                'flow_version' => $flow->versión_actual,
                'nombre_contacto' => $nombreContacto,
                'fecha_inicio' => now(),
                'ultima_interacción_en' => now(),
            ]);
    }

    public function resolveNextNode(ChatbotFlow $flow, ChatbotSession $session, ?string $valorChat, ?string $textInput): ?ChatbotNode
    {
        if ($valorChat) {
            return $this->fromInteraction($flow, $session, $valorChat);
        }

        return $session->current_node_id
            ? $this->fromExistingSession($flow, $session, $textInput)
            : $this->fromStart($flow, $textInput);
    }

    private function fromInteraction(ChatbotFlow $flow, ChatbotSession $session, string $valorChat): ?ChatbotNode
    {
        $connection = ChatbotConnection::where('flow_id', $flow->id)
            ->where('source_node_id', $session->current_node_id)
            ->where('source_output', 'output_' . $valorChat)
            ->first();

        return $connection ? ChatbotNode::find($connection->target_node_id) : null;
    }

    private function fromStart(ChatbotFlow $flow, ?string $textInput): ?ChatbotNode
    {
        $startNode = $this->findStartNode($flow);
        if (!$startNode) return null;

        return $this->triggerMatches($startNode, $textInput) ? $startNode : null;
    }

    private function fromExistingSession(ChatbotFlow $flow, ChatbotSession $session, ?string $textInput): ?ChatbotNode
    {
        $currentNode = ChatbotNode::find($session->current_node_id);

        if ($currentNode && in_array($currentNode->tipo, [ChatbotNode::CAPTURE, ChatbotNode::QUESTION])) {
            if ($varNameCfg = $currentNode->config(ChatbotNodeConfig::KEY_VARIABLE)) {
                $session->asignarVariable($varNameCfg->valor, $textInput);
            }

            $connection = ChatbotConnection::where('flow_id', $flow->id)
                ->where('source_node_id', $currentNode->id)
                ->where('source_output', 'output_1')
                ->first();

            return $connection ? ChatbotNode::find($connection->target_node_id) : null;
        }

        // Texto libre en nodo interactivo -> reiniciar flujo
        $session->update(['estado' => ChatbotSession::COMPLETADO, 'fecha_finalizado' => now()]);
        return $this->fromStart($flow, null); // reinicio siempre dispara si trigger es "any"
    }

    private function findStartNode(ChatbotFlow $flow): ?ChatbotNode
    {
        return $flow->nodoInicio
            ?? ChatbotNode::where('flow_id', $flow->id)->where('principal', ChatbotNode::SI_PRINCIPAL)->first();
    }

    private function triggerMatches(ChatbotNode $startNode, ?string $textInput): bool
    {
        $triggerCfg = $startNode->config(ChatbotNodeConfig::KEY_TRIGGER);
        $triggerValue = $triggerCfg?->valor ?? 'any';

        if ($triggerValue === 'any' || !$triggerCfg) {
            return true;
        }

        if ($triggerValue === 'keyword' && $keywordsCfg = $startNode->config(ChatbotNodeConfig::KEY_KEYWORDS)) {
            return in_array(strtolower($textInput ?? ''), array_map('strtolower', $keywordsCfg->valor));
        }

        return false;
    }
}
