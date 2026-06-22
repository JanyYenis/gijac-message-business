<?php

namespace App\Models\Chatbots;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ChatbotNode extends Model
{
    // ── Tipos de nodo ────────────────────────────────────────────────────────
    // MENSAJES
    const TC_TIPO   = 'TC_TIPO_CHATBOT_NODE';
    const TEXT      = 1;
    const IMAGE     = 2;
    const VIDEO     = 3;
    const DOC       = 4;
    const AUDIO     = 5;
    // INTERACCIÓN
    const BUTTONS   = 6;
    const LIST      = 7;
    const QUESTION  = 8;
    const CAPTURE   = 9;
    // LÓGICA
    const CONDITION = 10;
    const VARIABLE  = 11;
    const TAG       = 12;
    const GOTO      = 13;
    // ACCIONES
    const WEBHOOK   = 14;
    const API       = 15;
    const AGENT     = 16;
    const END       = 17;
    // IA
    const AI        = 18;
    const PDF       = 19;
    const GENERATE  = 20;
    // ESPECIAL
    const START     = 21;

    // ── Es nodo de inicio ────────────────────────────────────────────────────
    const NO_PRINCIPAL  = 0;
    const SI_PRINCIPAL  = 1;

    protected $table = 'chatbot_nodes';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'flow_id',
        'tipo',
        'etiqueta',
        'pos_x',
        'pos_y',
        'inputs',
        'outputs',
        'drawflow_id',
        'principal',
        'auto_send',
    ];

    protected $casts = [
        'id'      => 'string',
        'flow_id' => 'string',
        'pos_x'   => 'float',
        'pos_y'   => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    // ─── Helpers de constantes ───────────────────────────────────────────────

    public function infoTipo()
    {
        return darInfoConcepto($this, self::TC_TIPO, 'tipo')->selectRaw('conceptos.*');
    }

    public static function darTipo($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_TIPO, $infoTipoConcepto);
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function flow()
    {
        return $this->belongsTo(ChatbotFlow::class, 'flow_id', 'id');
    }

    public function configs()
    {
        return $this->hasMany(ChatbotNodeConfig::class, 'node_id', 'id');
    }

    public function config(int $key)
    {
        return $this->configs()->where('key', $key)->first();
    }

    public function conexionesSalida()
    {
        return $this->hasMany(ChatbotConnection::class, 'source_node_id', 'id');
    }

    public function conexionesEntrada()
    {
        return $this->hasMany(ChatbotConnection::class, 'target_node_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(ChatbotSessionLog::class, 'node_id', 'id');
    }
}
