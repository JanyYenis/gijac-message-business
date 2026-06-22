<?php

namespace App\Models\Chatbots;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ChatbotNodeConfig extends Model
{
    // ── Keys de configuración por tipo de nodo ───────────────────────────────
    // Comunes
    const KEY_MESSAGE           = 1;   // Mensaje principal del nodo
    const KEY_CAPTION           = 2;   // Texto caption (image/video/doc/audio)
    const KEY_URL               = 3;   // URL del archivo multimedia o endpoint

    // Nodo buttons
    const KEY_BUTTONS           = 4;   // JSON: [{label, target_node_id}, ...]

    // Nodo list
    const KEY_LIST_TITLE        = 5;   // Título de la lista
    const KEY_SECTIONS          = 6;   // JSON: [{title, rows:[{id,label}]}]

    // Nodo condition
    const KEY_VARIABLE          = 7;   // Variable a evaluar  ||respuesta||
    const KEY_OPERATOR          = 8;   // equals|contains|greater_than|less_than|regex
    const KEY_COMPARE           = 9;   // Valor de comparación

    // Nodo ai / generate
    const KEY_SYSTEM_PROMPT     = 10;  // Prompt de sistema
    const KEY_MODEL             = 11;  // Modelo de IA
    const KEY_TEMPERATURE       = 12;  // Temperatura (0-1)
    const KEY_MAX_TOKENS        = 13;  // Tokens máximos

    // Nodo webhook / api
    const KEY_METHOD            = 14;  // POST|GET|PUT|DELETE
    const KEY_HEADERS           = 15;  // JSON: {"Authorization":"Bearer ..."}
    const KEY_BODY              = 16;  // JSON body de la petición
    const KEY_RESPONSE_VARIABLE = 17;  // Variable donde guardar la respuesta

    // Nodo capture
    const KEY_DATA_TYPE         = 18;  // text|number|email|phone|date

    // Nodo goto
    const KEY_TARGET_NODE_ID    = 19;  // UUID del nodo destino

    // Nodo agent
    const KEY_DEPARTMENT        = 20;  // Departamento destino
    const KEY_TRANSITION_MSG    = 21;  // Mensaje de transición al agente

    // Nodo pdf
    const KEY_DOCUMENT_ID       = 22;  // ID del documento PDF
    const KEY_QUESTION          = 23;  // Pregunta a resolver contra el PDF

    // Nodo tag
    const KEY_TAG_NAME          = 24;  // Nombre de la etiqueta

    // Nodo variable
    const KEY_VAR_NAME          = 25;  // Nombre de la variable
    const KEY_VAR_VALUE         = 26;  // Valor a asignar

    // Nodo start
    const KEY_TRIGGER           = 27;  // any|keyword
    const KEY_KEYWORDS          = 28;  // JSON: ["hola","inicio","menu"]

    // Nodo end
    const KEY_CLOSE_MESSAGE     = 29;  // Mensaje de cierre
    const KEY_CLOSE_SESSION     = 30;  // true|false

    const KEY_AUTO_SEND         = 31;  // true|false (Usado en cualquier nodo)
    const KEY_SECTION_NAME      = 32;  // Nombre de la sección de la lista (ej: "Opciones")

    protected $table = 'chatbot_node_configs';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'node_id',
        'key',
        'valor',
    ];

    protected $casts = [
        'id'      => 'string',
        'node_id' => 'string',
        'valor'   => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function nodo()
    {
        return $this->belongsTo(ChatbotNode::class, 'node_id', 'id');
    }
}
