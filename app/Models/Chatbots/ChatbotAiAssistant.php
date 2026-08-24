<?php

namespace App\Models\Chatbots;

use App\Classes\Models\Model;
use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Support\Str;

class ChatbotAiAssistant extends Model
{
    protected $table = 'chatbot_ai_assistants';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    const TC_CAPACIDAD = 'TC_CAPACIDAD_CHATBOT_IA';
    const RESPONDER_AUTOMATICAMENTE  = 1;
    const ENVIAR_BOTONES             = 2;
    const ENVIAR_LISTAS              = 3;
    const TRANSFERIR_A_AGENTE_HUMANO = 4;
    const USAR_DOCUMENTOS_CARGADOS   = 5;
    const GUARDAR_CONTEXTO           = 6;
    const REMEMBRAR_CONVERSACION     = 7;

    protected $fillable = [
        'nombre', 'rol', 'descripcion', 'system_prompt',
        'provider', 'modelo',
        'creatividad', 'formalidad', 'brevedad', 'empatia',
        'capacidades',
        'respetar_horario', 'hora_inicio', 'hora_fin',
        'palabras_clave',
        'mensaje_bienvenida', 'mensaje_fuera_horario', 'mensaje_transferencia',
        'activo', 'creado_por', 'cod_empresa', 'estado',
        'documento_path', 'documento_nombre', 'documento_size', 'documento_contenido', 'documento_procesado_en',
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

    public function creadoPor()
    {
        return $this->belongsTo(Usuario::class, 'creado_por', 'uuid');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'cod_empresa', 'uuid');
    }

    public static function darCapacidad($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_CAPACIDAD, $infoTipoConcepto);
    }
}
