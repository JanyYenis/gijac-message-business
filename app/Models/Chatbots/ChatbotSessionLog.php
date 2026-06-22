<?php

namespace App\Models\Chatbots;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ChatbotSessionLog extends Model
{
    // ── Dirección del mensaje ─────────────────────────────────────────────────
    const TC_DIRECTION  = 'TC_DIRECTION_CHATBOT_LOG';
    const SALIDA        = 1;   // bot → usuario
    const ENTRADA       = 2;   // usuario → bot

    // ── Tipo de mensaje ───────────────────────────────────────────────────────
    const TC_TIPO_MENSAJE       = 'TC_TIPO_MENSAJE_CHATBOT';
    const TIPO_TEXT             = 1;
    const TIPO_IMAGE            = 2;
    const TIPO_VIDEO            = 3;
    const TIPO_DOCUMENT         = 4;
    const TIPO_AUDIO            = 5;
    const TIPO_BUTTONS          = 6;
    const TIPO_LIST             = 7;
    const TIPO_INTERACTIVE_REPLY = 8;
    const TIPO_LOCATION         = 9;
    const TIPO_SYSTEM           = 10;

    // ── Estado del mensaje (aplica solo a SALIDA) ─────────────────────────────
    const TC_ESTADO     = 'TC_ESTADO_CHATBOT_LOG';
    const ENVIADO       = 1;
    const ENTREGADO     = 2;
    const LEIDO         = 3;
    const FALLIDO       = 4;

    protected $table = 'chatbot_session_logs';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'session_id',
        'node_id',
        'direction',
        'tipo_mensaje',
        'contenido',
        'raw_payload',
        'estado',
        'provider_message_id',
        'fecha_envio',
    ];

    protected $casts = [
        'id'          => 'string',
        'session_id'  => 'string',
        'node_id'     => 'string',
        'raw_payload' => 'array',
        'fecha_envio' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    // ─── Helpers de constantes ───────────────────────────────────────────────

    public function infoDirection()
    {
        return darInfoConcepto($this, self::TC_DIRECTION, 'direction')->selectRaw('conceptos.*');
    }

    public static function darDirection($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_DIRECTION, $infoTipoConcepto);
    }

    public function infoTipoMensaje()
    {
        return darInfoConcepto($this, self::TC_TIPO_MENSAJE, 'tipo_mensaje')->selectRaw('conceptos.*');
    }

    public static function darTipoMensaje($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_TIPO_MENSAJE, $infoTipoConcepto);
    }

    public function infoEstado()
    {
        return darInfoConcepto($this, self::TC_ESTADO, 'estado')->selectRaw('conceptos.*');
    }

    public static function darEstado($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_ESTADO, $infoTipoConcepto);
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function sesion()
    {
        return $this->belongsTo(ChatbotSession::class, 'session_id', 'id');
    }

    public function nodo()
    {
        return $this->belongsTo(ChatbotNode::class, 'node_id', 'id');
    }
}
