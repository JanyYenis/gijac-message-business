<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class AutomatizacionN8nEvento extends Model
{
    protected $table = 'automatizaciones_n8n_eventos';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    const TC_EVENTOS = 'TC_EVENTOS_N8N';
    const NUEVO_MENSAJE        = 1;
    const MENSAJE_ENVIADO      = 2;
    const CONVERSACION_INICIAL = 3;
    const CONVERSACION_CERRADA = 4;
    const ETIQUETA_AGREGADA    = 5;
    const CLIENTE_CREADO       = 6;
    const CAMPANA_ENTREGADA    = 7;
    const CAMPANA_LEIDA        = 8;

    protected $fillable = [
        'cod_automatizacion',
        'evento',
        'estado',
    ];

    protected $casts = [
        'id' => 'string',
        "created_at" => "date:d/m/Y",
    ];

    protected $dates = [
        "created_at" => "date:d/m/Y",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function infoEvento()
    {
        return darInfoConcepto($this, self::TC_EVENTOS, 'evento')->selectRaw('conceptos.*');
    }

    public static function darEvento($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_EVENTOS, $infoTipoConcepto);
    }
}
