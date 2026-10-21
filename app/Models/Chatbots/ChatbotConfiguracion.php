<?php

namespace App\Models\Chatbots;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ChatbotConfiguracion extends Model
{
    protected $table = 'chatbot_configuracion';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    // Acción al detectar una palabra clave (accion_palabra_clave)
    const TC_ACCION_PALABRA_CLAVE = 'TC_CHATBOT_ACCION_PALABRA_CLAVE';
    const PALABRA_TRANSFERIR    = 1; // transfiere sin avisar
    const PALABRA_MENSAJE       = 2; // envía el mensaje de transferencia y transfiere
    const PALABRA_PREGUNTAR     = 3; // pregunta al usuario antes de transferir

    // Si ningún agente atiende en el tiempo de espera (accion_sin_agente)
    const TC_ACCION_SIN_AGENTE = 'TC_CHATBOT_ACCION_SIN_AGENTE';
    const SIN_AGENTE_ESPERAR  = 1;
    const SIN_AGENTE_MENSAJE  = 2;
    const SIN_AGENTE_CERRAR   = 3;

    // Cuando el motor no puede resolver (accion_respuesta_no_resuelta)
    const TC_ACCION_NO_RESUELTA = 'TC_CHATBOT_ACCION_NO_RESUELTA';
    const NO_RESUELTA_REINTENTAR  = 1;
    const NO_RESUELTA_TRANSFERIR  = 2;
    const NO_RESUELTA_SIGUIENTE   = 3;
    const NO_RESUELTA_MENSAJE     = 4;

    // Cuándo empieza una conversación nueva (tipo_reinicio_conversacion)
    const TC_REINICIO = 'TC_CHATBOT_REINICIO_CONVERSACION';
    const REINICIO_INACTIVIDAD   = 1;
    const REINICIO_TRANSFERENCIA = 2;
    const REINICIO_FIN_FLUJO     = 3;

    // Mensajes especiales (chatbot_mensajes.tipo)
    const TC_MENSAJE = 'TC_CHATBOT_TIPO_MENSAJE';
    const MENSAJE_BIENVENIDA    = 1;
    const MENSAJE_FUERA_HORARIO = 2;
    const MENSAJE_TRANSFERENCIA = 3;

    // Motores de atención (chatbot_motores.tipo), en el orden por defecto
    const TC_MOTOR = 'TC_CHATBOT_TIPO_MOTOR';
    const MOTOR_NODOS         = 1;
    const MOTOR_IA            = 2;
    const MOTOR_AUTOMATIZACION = 3;

    // Motivos de transferencia (chatbot_motivos_transferencia.motivo)
    const TC_MOTIVO = 'TC_CHATBOT_MOTIVO_TRANSFERENCIA';
    const MOTIVO_PALABRA_CLAVE   = 1;
    const MOTIVO_SOLICITA_ASESOR = 2;
    const MOTIVO_NO_RESUELVE     = 3;
    const MOTIVO_ERROR           = 4;
    const MOTIVO_SOLICITA_PERSONA = 5;

    protected $fillable = [
        'cod_empresa',
        'activo',
        'respetar_horario',
        'accion_palabra_clave',
        'coincidencia_aproximada',
        'permitir_transferencia',
        'tiempo_espera_agente',
        'accion_sin_agente',
        'mantener_contexto',
        'tiempo_sesion',
        'tipo_reinicio_conversacion',
        'tiempo_respuesta',
        'mostrar_escribiendo',
        'agrupar_mensajes',
        'accion_respuesta_no_resuelta',
        'mensaje_no_resuelto',
        'estado',
    ];

    protected $casts = [
        'id'               => 'string',
        'creado_por'       => 'string',
        'cod_empresa'      => 'string',
        'capacidades'      => 'array',
        'palabras_clave'   => 'array',
        'respetar_horario' => 'boolean',
        'activo'           => 'boolean',
        'coincidencia_aproximada' => 'boolean',
        'permitir_transferencia'  => 'boolean',
        'mantener_contexto'       => 'boolean',
        'mostrar_escribiendo'     => 'boolean',
        'documento_procesado_en' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
