<?php

namespace App\Services\AI;

use App\Models\Chatbots\ChatbotAiAssistant;
use Illuminate\Support\Str;

class ChatbotAiPromptBuilder
{
    public function construir(ChatbotAiAssistant $asistente, ?string $consulta = null): string
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

        if (in_array(ChatbotAiAssistant::USAR_DOCUMENTOS_CARGADOS, $capacidades) && !empty($asistente->documento_contenido)) {
            $contenido = $this->fragmentos($asistente->documento_contenido, $consulta);
            $prompt   .= "\nBase de conocimiento (documento cargado por el usuario):\n\"\"\"\n{$contenido}\n\"\"\"\n";
            $prompt   .= "Usa esta información para responder cuando sea relevante. Si la pregunta no se relaciona con el documento, responde con tu conocimiento general.\n";
        }

        if (ChatbotAiReply::estructurado($capacidades)) {
            $prompt .= "\nResponde SIEMPRE con un JSON válido: {\"texto\": \"tu respuesta\"";
            if (in_array(ChatbotAiAssistant::ENVIAR_BOTONES, $capacidades)) {
                $prompt .= ", \"botones\": [\"Opción\"]";
            }
            if (in_array(ChatbotAiAssistant::ENVIAR_LISTAS, $capacidades)) {
                $prompt .= ", \"lista\": {\"boton\": \"Ver opciones\", \"opciones\": [{\"titulo\": \"...\", \"descripcion\": \"...\"}]}";
            }
            if (in_array(ChatbotAiAssistant::TRANSFERIR_A_AGENTE_HUMANO, $capacidades)) {
                $prompt .= ", \"transferir\": false";
            }
            $prompt .= "}.\n";
                        if (in_array(ChatbotAiAssistant::ENVIAR_BOTONES, $capacidades)) {
                $prompt .= "Usa \"botones\" (máximo 3, de hasta 20 caracteres) SOLO cuando hagas una pregunta con 2 o 3 respuestas cortas posibles.\n";
            }
            if (in_array(ChatbotAiAssistant::ENVIAR_LISTAS, $capacidades)) {
                $prompt .= "Usa \"lista\" (máximo 10 opciones) SOLO cuando haya 4 o más opciones a elegir.\n";
            }
            $prompt .= "Si solo estás informando o respondiendo una duda, NO incluyas botones ni lista.\n";
            if (in_array(ChatbotAiAssistant::TRANSFERIR_A_AGENTE_HUMANO, $capacidades)) {
                $prompt .= "Pon \"transferir\": true solo si el cliente pide hablar con una persona o no puedes resolver su consulta.\n";
            }
            $prompt .= "Si no hace falta ninguna opción, omite esos campos. El \"texto\" nunca debe quedar vacío.\n";
        } else {
            $prompt .= "\nResponde siempre en texto plano, no describas botones ni listas interactivas.\n";
        }

        return $prompt;
    }

        /** Si el documento es largo, toma los fragmentos que más coinciden con la pregunta (hasta $max caracteres). */
    private function fragmentos(string $doc, ?string $consulta, int $max = 6000): string
    {
        if (mb_strlen($doc) <= $max) {
            return $doc;
        }

        $terminos = $this->terminos($consulta ?? '');
        if (!$terminos) {
            return mb_substr($doc, 0, $max);
        }

        $trozos = [];
        $actual = '';
        foreach (preg_split('/\R+/u', $doc) as $linea) {
            if ($actual !== '' && mb_strlen($actual) + mb_strlen($linea) > 900) {
                $trozos[] = $actual;
                $actual   = '';
            }
            $actual .= $linea . "\n";
        }
        if (trim($actual) !== '') {
            $trozos[] = $actual;
        }

        $puntaje = [];
        foreach ($trozos as $i => $t) {
            $n = $this->normalizar($t);
            $puntaje[$i] = array_sum(array_map(fn ($w) => substr_count($n, $w), $terminos));
        }

        if (max($puntaje) === 0) {
            return mb_substr($doc, 0, $max);
        }

        arsort($puntaje);
        $elegidos = [];
        $total    = 0;
        foreach ($puntaje as $i => $p) {
            if ($p === 0) break;
            $len = mb_strlen($trozos[$i]);
            if ($total + $len > $max) continue;
            $elegidos[] = $i;
            $total     += $len;
        }

        if (!$elegidos) {
            return mb_substr($doc, 0, $max);
        }

        sort($elegidos); // conserva el orden original del documento

        return trim(implode("\n", array_map(fn ($i) => $trozos[$i], $elegidos)));
    }

    private function terminos(string $texto): array
    {
        return array_values(array_unique(array_filter(
            preg_split('/[^a-z0-9]+/', $this->normalizar($texto)),
            fn ($w) => strlen($w) > 3
        )));
    }

    private function normalizar(string $texto): string
    {
        return Str::ascii(mb_strtolower($texto));
    }
}
