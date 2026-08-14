<?php

namespace Database\Seeders\Conceptos;

use App\Models\Chatbots\ChatbotFlow;
use App\Models\Chatbots\ChatbotFlowVersion;
use App\Models\Chatbots\ChatbotNode;
use App\Models\Chatbots\ChatbotSession;
use App\Models\Chatbots\ChatbotSessionLog;
use App\Models\Sistema\Concepto;
use App\Models\Sistema\TipoConcepto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NodosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->tipoConceptosEstados();
        $this->tipoConceptosDirecciones();
        $this->tipoConceptosTiposMensajes();
        $this->tipoConceptosTipoNodo();
    }

    public function tipoConceptosEstados()
    {
        $tc_estado = TipoConcepto::updateOrCreate([
            'nombre' => ChatbotFlowVersion::TC_ESTADO,
        ], [
            'descripcion' => 'Estados de las versiones de flujos de chatbot.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotFlowVersion::PUBLICADO,
        ], [
            'nombre' => 'Publicado',
            'estado' => Concepto::ACTIVO,
            'color' => 'primary',
            'icono' => 'fa-solid fa-globe'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotFlowVersion::BORRADOR,
        ], [
            'nombre' => 'Borrador',
            'estado' => Concepto::ACTIVO,
            'color' => 'info',
            'icono' => 'fas fa-file-pen'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotFlowVersion::ARCHIVADO,
        ], [
            'nombre' => 'Archivado',
            'estado' => Concepto::ACTIVO,
            'color' => 'warning',
            'icono' => 'fa-solid fa-file-lines'
        ]);

        // ------------ Estados de flujo de chatbot (ChatbotFlow) ------------
        $tc_estado = TipoConcepto::updateOrCreate([
            'nombre' => ChatbotFlow::TC_ESTADO,
        ], [
            'descripcion' => 'Estados de los flujos de chatbot.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotFlow::BORRADOR,
        ], [
            'nombre' => 'Borrador',
            'estado' => Concepto::ACTIVO,
            'color' => 'info',
            'icono' => 'fas fa-file-pen'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotFlow::ACTIVO,
        ], [
            'nombre' => 'Activo',
            'estado' => Concepto::ACTIVO,
            'color' => 'primary',
            'icono' => 'fas fa-check-circle'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotFlow::INACTIVO,
        ], [
            'nombre' => 'Inactivo',
            'estado' => Concepto::ACTIVO,
            'color' => 'danger',
            'icono' => 'far fa-times-fas fa-clock'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotFlow::ARCHIVADO,
        ], [
            'nombre' => 'Archivado',
            'estado' => Concepto::ACTIVO,
            'color' => 'warning',
            'icono' => 'fa-solid fa-file-lines'
        ]);

        // ------------ Estados de las Secciones de los Chatbots ------------
        $tc_estado = TipoConcepto::updateOrCreate([
            'nombre' => ChatbotSession::TC_ESTADO,
        ], [
            'descripcion' => 'Estados de las secciones de los chatbots.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotSession::ACTIVO,
        ], [
            'nombre' => 'Activo',
            'estado' => Concepto::ACTIVO,
            'color' => 'primary',
            'icono' => 'fas fa-check-circle'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotSession::ESPERANDO,
        ], [
            'nombre' => 'Esperando',
            'estado' => Concepto::ACTIVO,
            'color' => 'primary',
            'icono' => 'fas fa-clock'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotSession::AGENTE,
        ], [
            'nombre' => 'Agente',
            'estado' => Concepto::ACTIVO,
            'color' => 'info',
            'icono' => 'fa-solid fa-users'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotSession::COMPLETADO,
        ], [
            'nombre' => 'Completado',
            'estado' => Concepto::ACTIVO,
            'color' => 'primary',
            'icono' => 'fas fa-check-circle'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotSession::EXPIRADO,
        ], [
            'nombre' => 'Expirado',
            'estado' => Concepto::ACTIVO,
            'color' => 'secondary',
            'icono' => 'fas fa-clock'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotSession::ERROR,
        ], [
            'nombre' => 'Error',
            'estado' => Concepto::ACTIVO,
            'color' => 'danger',
            'icono' => 'fas fa-exclamation-triangle'
        ]);

        // ------------ Estados de los Logs de las Secciones de los Chatbots ------------
        $tc_estado = TipoConcepto::updateOrCreate([
            'nombre' => ChatbotSessionLog::TC_ESTADO,
        ], [
            'descripcion' => 'Estados de los logs de las secciones de los chatbots.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotSessionLog::ENVIADO,
        ], [
            'nombre' => 'Enviado',
            'estado' => Concepto::ACTIVO,
            'color' => 'secondary',
            'icono' => 'fa-solid fa-check'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotSessionLog::ENTREGADO,
        ], [
            'nombre' => 'Entregado',
            'estado' => Concepto::ACTIVO,
            'color' => 'secondary',
            'icono' => 'fa-solid fa-check-double'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotSessionLog::LEIDO,
        ], [
            'nombre' => 'Leído',
            'estado' => Concepto::ACTIVO,
            'color' => 'primary',
            'icono' => 'fa-solid fa-check-double'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => ChatbotSessionLog::FALLIDO,
        ], [
            'nombre' => 'Fallido',
            'estado' => Concepto::ACTIVO,
            'color' => 'danger',
            'icono' => 'fa-solid fa-xmark'
        ]);
    }

    public function tipoConceptosDirecciones()
    {
        $tc_direccion = TipoConcepto::updateOrCreate([
            'nombre' => ChatbotSessionLog::TC_DIRECTION,
        ], [
            'descripcion' => 'Direcciones de los logs de las secciones de los chatbots.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_direccion?->id,
            'codigo' => ChatbotSessionLog::ENTRADA,
        ], [
            'nombre' => 'Entrada',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_direccion?->id,
            'codigo' => ChatbotSessionLog::SALIDA,
        ], [
            'nombre' => 'Salida',
            'estado' => Concepto::ACTIVO,
        ]);
    }

    public function tipoConceptosTiposMensajes()
    {
        $tc_tipo_mensaje = TipoConcepto::updateOrCreate([
            'nombre' => ChatbotSessionLog::TC_TIPO_MENSAJE,
        ], [
            'descripcion' => 'Tipos de mensajes de los logs de las secciones de los chatbots.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_mensaje?->id,
            'codigo' => ChatbotSessionLog::TIPO_TEXT,
        ], [
            'nombre' => 'Texto',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_mensaje?->id,
            'codigo' => ChatbotSessionLog::TIPO_IMAGE,
        ], [
            'nombre' => 'Imagen',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_mensaje?->id,
            'codigo' => ChatbotSessionLog::TIPO_VIDEO,
        ], [
            'nombre' => 'Video',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_mensaje?->id,
            'codigo' => ChatbotSessionLog::TIPO_DOCUMENT,
        ], [
            'nombre' => 'Documento',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_mensaje?->id,
            'codigo' => ChatbotSessionLog::TIPO_AUDIO,
        ], [
            'nombre' => 'Audio',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_mensaje?->id,
            'codigo' => ChatbotSessionLog::TIPO_BUTTONS,
        ], [
            'nombre' => 'Botones',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_mensaje?->id,
            'codigo' => ChatbotSessionLog::TIPO_LIST,
        ], [
            'nombre' => 'Lista',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_mensaje?->id,
            'codigo' => ChatbotSessionLog::TIPO_INTERACTIVE_REPLY,
        ], [
            'nombre' => 'Respuesta Interactiva',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_mensaje?->id,
            'codigo' => ChatbotSessionLog::TIPO_LOCATION,
        ], [
            'nombre' => 'Ubicación',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_mensaje?->id,
            'codigo' => ChatbotSessionLog::TIPO_SYSTEM,
        ], [
            'nombre' => 'Sistema',
            'estado' => Concepto::ACTIVO,
        ]);
    }

    public function tipoConceptosTipoNodo()
    {
        $tc_tipo_variable = TipoConcepto::updateOrCreate([
            'nombre' => ChatbotNode::TC_TIPO,
        ], [
            'descripcion' => 'Tipos de nodos de los chatbots.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::TEXT,
        ], [
            'nombre' => 'Texto',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::IMAGE,
        ], [
            'nombre' => 'Imagen',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::VIDEO,
        ], [
            'nombre' => 'Video',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::DOC,
        ], [
            'nombre' => 'Documento',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::AUDIO,
        ], [
            'nombre' => 'Audio',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::BUTTONS,
        ], [
            'nombre' => 'Botones',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::LIST,
        ], [
            'nombre' => 'Lista',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::QUESTION,
        ], [
            'nombre' => 'Pregunta',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::CAPTURE,
        ], [
            'nombre' => 'Captura',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::CONDITION,
        ], [
            'nombre' => 'Condición',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::VARIABLE,
        ], [
            'nombre' => 'Variable',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::TAG,
        ], [
            'nombre' => 'Etiqueta',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::GOTO,
        ], [
            'nombre' => 'Contacto',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::WEBHOOK,
        ], [
            'nombre' => 'Webhook',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::API,
        ], [
            'nombre' => 'API',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::AGENT,
        ], [
            'nombre' => 'Agente',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::END,
        ], [
            'nombre' => 'Fin',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::AI,
        ], [
            'nombre' => 'Inteligencia Artificial',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::PDF,
        ], [
            'nombre' => 'PDF',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::GENERATE,
        ], [
            'nombre' => 'Generar Documento',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo_variable?->id,
            'codigo' => ChatbotNode::START,
        ], [
            'nombre' => 'Inicio',
            'estado' => Concepto::ACTIVO,
        ]);
    }
}
