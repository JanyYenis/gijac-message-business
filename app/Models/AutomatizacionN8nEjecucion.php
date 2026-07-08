<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class AutomatizacionN8nEjecucion extends Model
{
    protected $table = 'automatizaciones_n8n_ejecuciones';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const TC_ESTADO  = 'TC_ESTADO_EJECUCION_N8N';
    const PENDIENTE  = 1;
    const PROCESANDO = 2;
    const EXITOSO    = 3;
    const ERROR      = 4;
    const REINTENTANDO = 0;

    protected $fillable = [
        'cod_automatizacion',
        'cod_contacto',
        'evento',
        'estado',
        'codigo_respuesta',
        'duracion_ms',
        'respuesta',
        'payload_enviado',
        'fecha_ejecucion',
    ];

    protected $casts = [
        'id' => 'string',
        "created_at" => "date:d/m/Y",
        "fecha_ejecucion" => "date:d/m/Y",
    ];

    protected $dates = [
        "created_at" => "date:d/m/Y",
        "fecha_ejecucion" => "date:d/m/Y",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
