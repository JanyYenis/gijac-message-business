<?php

namespace Database\Seeders\Conceptos;

use App\Models\Chatbots\ChatbotConfiguracion as Cfg;
use App\Models\Sistema\Concepto;
use App\Models\Sistema\TipoConcepto;
use Illuminate\Database\Seeder;

/**
 * Conceptos de la configuración general del chatbot (panel Chatbots > Configuración).
 * Ejecutar: php artisan db:seed --class="Database\Seeders\Conceptos\ChatbotGeneralSeeder"
 */
class ChatbotGeneralSeeder extends Seeder
{
    public function run(): void
    {
        $this->tipo(Cfg::TC_ACCION_PALABRA_CLAVE, 'Acción al detectar una palabra clave.', [
            Cfg::PALABRA_TRANSFERIR => 'Transferir inmediatamente',
            Cfg::PALABRA_MENSAJE => 'Enviar mensaje y transferir',
            Cfg::PALABRA_PREGUNTAR => 'Preguntar si desea hablar con un agente',
        ]);

        $this->tipo(Cfg::TC_ACCION_SIN_AGENTE, 'Acción cuando ningún agente está disponible.', [
            Cfg::SIN_AGENTE_ESPERAR => 'Mantener conversación en espera',
            Cfg::SIN_AGENTE_MENSAJE => 'Enviar mensaje alternativo',
            Cfg::SIN_AGENTE_CERRAR => 'Cerrar conversación',
        ]);

        $this->tipo(Cfg::TC_ACCION_NO_RESUELTA, 'Acción cuando el chatbot no resuelve la consulta.', [
            Cfg::NO_RESUELTA_REINTENTAR => 'Intentar nuevamente',
            Cfg::NO_RESUELTA_TRANSFERIR => 'Transferir a un agente',
            Cfg::NO_RESUELTA_SIGUIENTE => 'Ejecutar el siguiente motor disponible',
            Cfg::NO_RESUELTA_MENSAJE => 'Enviar un mensaje personalizado',
        ]);

        $this->tipo(Cfg::TC_REINICIO, 'Cuándo se reinicia la conversación del chatbot.', [
            Cfg::REINICIO_INACTIVIDAD => 'Cierto tiempo de inactividad',
            Cfg::REINICIO_TRANSFERENCIA => 'Transferencia a agente',
            Cfg::REINICIO_FIN_FLUJO => 'Finalización del flujo',
        ]);

        $this->tipo(Cfg::TC_MENSAJE, 'Mensajes especiales del chatbot.', [
            Cfg::MENSAJE_BIENVENIDA => 'Mensaje de bienvenida',
            Cfg::MENSAJE_FUERA_HORARIO => 'Mensaje fuera de horario',
            Cfg::MENSAJE_TRANSFERENCIA => 'Mensaje de transferencia',
        ]);

        $this->tipo(Cfg::TC_MOTOR, 'Motores de atención del chatbot.', [
            Cfg::MOTOR_NODOS => 'Chatbot por nodos',
            Cfg::MOTOR_IA => 'Asistente IA',
            Cfg::MOTOR_AUTOMATIZACION => 'Automatización',
        ]);

        $this->tipo(Cfg::TC_MOTIVO, 'Motivos de transferencia a un agente.', [
            Cfg::MOTIVO_PALABRA_CLAVE => 'Palabra clave detectada',
            Cfg::MOTIVO_SOLICITA_ASESOR => 'Usuario solicita un asesor',
            Cfg::MOTIVO_NO_RESUELVE => 'El chatbot no puede resolver la consulta',
            Cfg::MOTIVO_ERROR => 'Error en el chatbot',
            Cfg::MOTIVO_SOLICITA_PERSONA => 'Usuario solicita hablar con una persona',
        ]);
    }

    private function tipo(string $nombre, string $descripcion, array $conceptos): void
    {
        $tc = TipoConcepto::updateOrCreate(['nombre' => $nombre], [
            'descripcion' => $descripcion,
            'estado' => TipoConcepto::ACTIVO,
        ]);

        foreach ($conceptos as $codigo => $texto) {
            Concepto::updateOrCreate(['id_tipo' => $tc->id, 'codigo' => $codigo], [
                'nombre' => $texto,
                'estado' => Concepto::ACTIVO,
            ]);
        }
    }
}
