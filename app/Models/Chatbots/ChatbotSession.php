<?php

namespace App\Models\Chatbots;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ChatbotSession extends Model
{
    // ── Estados de la sesión ─────────────────────────────────────────────────
    const TC_ESTADO     = 'TC_ESTADO_CHATBOT_SESSION';
    const ACTIVO        = 1;   // Conversación en progreso
    const ESPERANDO     = 2;   // Esperando respuesta del usuario (capture/question)
    const AGENTE        = 3;   // Derivada a agente humano
    const COMPLETADO    = 4;   // Terminada normalmente (nodo end)
    const EXPIRADO      = 5;   // Expirada por timeout
    const ERROR         = 6;   // Terminada por error del motor

    protected $table = 'chatbot_sessions';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'flow_id',
        'flow_version',
        'telefono_contacto',
        'nombre_contacto',
        'current_node_id',
        'estado',
        'variables',
        'canal_meta',
        'ultima_interacción_en',
        'fecha_inicio',
        'fecha_finalizado',
    ];

    protected $casts = [
        'id'              => 'string',
        'flow_id'         => 'string',
        'current_node_id' => 'string',
        'variables'       => 'array',
        'canal_meta'      => 'array',
        'fecha_inicio'    => 'datetime',
        'fecha_finalizado'=> 'datetime',
        'ultima_interacción_en' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    // ─── Helpers de constantes ───────────────────────────────────────────────

    public function infoEstado()
    {
        return darInfoConcepto($this, self::TC_ESTADO, 'estado')->selectRaw('conceptos.*');
    }

    public static function darEstado($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_ESTADO, $infoTipoConcepto);
    }

    // ─── Helpers de variables ────────────────────────────────────────────────

    public function darVariable(string $nombre, $defecto = null)
    {
        $vars = $this->variables ?? [];
        return $vars[$nombre] ?? $defecto;
    }

    public function asignarVariable(string $nombre, $valor): void
    {
        $vars = $this->variables ?? [];
        $vars[$nombre] = $valor;
        $this->variables = $vars;
        $this->save();
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function flow()
    {
        return $this->belongsTo(ChatbotFlow::class, 'flow_id', 'id');
    }

    public function nodoActual()
    {
        return $this->belongsTo(ChatbotNode::class, 'current_node_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(ChatbotSessionLog::class, 'session_id', 'id');
    }

    public function logsEnviados()
    {
        return $this->logs()->where('direction', ChatbotSessionLog::SALIDA);
    }

    public function logsRecibidos()
    {
        return $this->logs()->where('direction', ChatbotSessionLog::ENTRADA);
    }
}
