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
