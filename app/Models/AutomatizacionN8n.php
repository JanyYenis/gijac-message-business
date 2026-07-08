<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class AutomatizacionN8n extends Model
{
    protected $table = 'automatizaciones_n8n';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const TC_ESTADO  = 'TC_ESTADO_AUTOMATIZACIONES_N8N';
    const ACTIVO     = 1;
    const INACTIVO   = 2;
    const SUSPENDIDO = 3;
    const ERROR      = 4;
    const ELIMINADO  = 0;

    const TC_METODO_HTTP = 'TC_METODO_HTTP';
    const GET    = 1;
    const POST   = 2;
    const PUT    = 3;
    const PATCH  = 4;
    const DELETE = 5;

    protected $fillable = [
        'cod_empresa',
        'nombre',
        'descripcion',
        'url_webhook',
        'token_seguridad',
        'metodo_http',
        'estado',
        'webhook_activo',
        'cantidad_reintentos',
        'tiempo_entre_intentos',
        'timeout_segundos',
        'ultima_ejecucion',
    ];

    protected $casts = [
        'id' => 'string',
        "created_at" => "date:d/m/Y",
        "ultima_ejecucion" => "date:d/m/Y",
    ];

    protected $dates = [
        "created_at" => "date:d/m/Y",
        "ultima_ejecucion" => "date:d/m/Y",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function infoMetodo()
    {
        return darInfoConcepto($this, self::TC_METODO_HTTP, 'metodo_http')->selectRaw('conceptos.*');
    }

    public static function darMetodo($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_METODO_HTTP, $infoTipoConcepto);
    }
}
