<?php

namespace App\Models\Chatbots;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ChatbotVariable extends Model
{
    // ── Tipo de dato ─────────────────────────────────────────────────────────
    const TC_TIPO_DATO  = 'TC_TIPO_DATO_CHATBOT_VAR';
    const TEXTO         = 1;
    const NUMERO        = 2;
    const EMAIL         = 3;
    const TELEFONO      = 4;
    const FECHA         = 5;
    const BOOLEANO      = 6;
    const JSON          = 7;

    // ── Alcance ──────────────────────────────────────────────────────────────
    const TC_ALCANCE    = 'TC_ALCANCE_CHATBOT_VAR';
    const FLUJO         = 1;   // Definida por el diseñador del flujo
    const CONTACTO      = 2;   // Capturada durante la sesión
    const SISTEMA       = 3;   // Inyectada por el motor (fecha, hora, etc.)

    protected $table = 'chatbot_variables';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'flow_id',
        'nombre',
        'tipo_dato',
        'alcance',
        'valor_defecto',
        'descripcion',
    ];

    protected $casts = [
        'id'      => 'string',
        'flow_id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    // ─── Helpers de constantes ───────────────────────────────────────────────

    public function infoTipoDato()
    {
        return darInfoConcepto($this, self::TC_TIPO_DATO, 'tipo_dato')->selectRaw('conceptos.*');
    }

    public static function darTipoDato($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_TIPO_DATO, $infoTipoConcepto);
    }

    public function infoAlcance()
    {
        return darInfoConcepto($this, self::TC_ALCANCE, 'alcance')->selectRaw('conceptos.*');
    }

    public static function darAlcance($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_ALCANCE, $infoTipoConcepto);
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function flow()
    {
        return $this->belongsTo(ChatbotFlow::class, 'flow_id', 'id');
    }
}
