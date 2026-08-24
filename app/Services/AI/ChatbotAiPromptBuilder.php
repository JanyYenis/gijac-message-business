<?php

namespace App\Services\AI;

use App\Models\Chatbots\ChatbotAiAssistant;

class ChatbotAiPromptBuilder
{
    public function construir(ChatbotAiAssistant $asistente): string
    {
        $capacidades = $asistente->capacidades ?? [];
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

        if (in_array(ChatbotAiAssistant::TRANSFERIR_A_AGENTE_HUMANO, $capacidades) && !empty($asistente->palabras_clave)) {
            $prompt .= 'Si el usuario menciona alguna de estas palabras clave (' .
                implode(', ', $asistente->palabras_clave) .
                '), indica que lo vas a transferir con un agente humano.' . "\n";
        }

        if (in_array(ChatbotAiAssistant::USAR_DOCUMENTOS_CARGADOS, $capacidades) && !empty($asistente->documento_contenido)) {
            $contenido = mb_substr($asistente->documento_contenido, 0, 6000);
            $prompt   .= "\nBase de conocimiento (documento cargado por el usuario):\n\"\"\"\n{$contenido}\n\"\"\"\n";
            $prompt   .= "Usa esta información para responder cuando sea relevante. Si la pregunta no se relaciona con el documento, responde con tu conocimiento general.\n";
        }

        if (!in_array(ChatbotAiAssistant::ENVIAR_BOTONES, $capacidades) && !in_array(ChatbotAiAssistant::ENVIAR_LISTAS, $capacidades)) {
            $prompt .= "\nResponde siempre en texto plano, no describas botones ni listas interactivas.\n";
        }

        return $prompt;
    }
}
