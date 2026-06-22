<?php

namespace App\Models\Chatbots;

use App\Classes\Models\Model;
use App\Models\Usuario;
use Illuminate\Support\Str;

class ChatbotFlowVersion extends Model
{
    // ── Estados de la versión ────────────────────────────────────────────────
    const TC_ESTADO     = 'TC_ESTADO_CHATBOT_VERSION';
    const PUBLICADO     = 1;
    const BORRADOR      = 2;
    const ARCHIVADO     = 3;

    protected $table = 'chatbot_flow_versions';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'flow_id',
        'numero_version',
        'snapshot',
        'estado',
        'creado_por',
        'nota_cambio',
        'fecha_publicado',
    ];

    protected $casts = [
        'id'              => 'string',
        'flow_id'         => 'string',
        'creado_por'      => 'string',
        'fecha_publicado' => 'datetime',
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

    // ─── Helpers de snapshot ─────────────────────────────────────────────────

    public function darSnapshot(): array
    {
        return json_decode($this->snapshot, true) ?? [];
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function flow()
    {
        return $this->belongsTo(ChatbotFlow::class, 'flow_id', 'id');
    }

    public function creadoPor()
    {
        return $this->belongsTo(Usuario::class, 'creado_por', 'uuid');
    }
}
