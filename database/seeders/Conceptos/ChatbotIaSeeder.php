<?php

namespace Database\Seeders\Conceptos;

use App\Models\Chatbots\ChatbotAiAssistant;
use App\Models\Sistema\Concepto;
use App\Models\Sistema\TipoConcepto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatbotIaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->tipoConceptosCapacidades();
    }

    public function tipoConceptosCapacidades()
    {
        $tc_estado = TipoConcepto::updateOrCreate([
            'nombre' => ChatbotAiAssistant::TC_CAPACIDAD,
        ], [
            'descripcion' => 'Capacidades de los chatbots de IA.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotAiAssistant::RESPONDER_AUTOMATICAMENTE,
        ], [
            'nombre' => 'Responder automáticamente',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotAiAssistant::ENVIAR_BOTONES,
        ], [
            'nombre' => 'Enviar botones',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotAiAssistant::ENVIAR_LISTAS,
        ], [
            'nombre' => 'Enviar listas',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotAiAssistant::TRANSFERIR_A_AGENTE_HUMANO,
        ], [
            'nombre' => 'Transferir a agente humano',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotAiAssistant::USAR_DOCUMENTOS_CARGADOS,
        ], [
            'nombre' => 'Usar documentos cargados',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotAiAssistant::GUARDAR_CONTEXTO,
        ], [
            'nombre' => 'Guardar contexto',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotAiAssistant::REMEMBRAR_CONVERSACION,
        ], [
            'nombre' => 'Recordar conversación',
            'estado' => Concepto::ACTIVO,
        ]);
    }
}
