<?php

namespace App\Models;

use App\Classes\Models\Model;
use App\Traits\Comentariable;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use Comentariable;

    const TC_ESTADO = 'TC_ESTADO_TICKETS';
    const PENDIENTE            = 1;
    const EN_REVISION          = 2;
    const CERRADO              = 3;
    const PENDIENTE_APROBACION = 4;
    const ELIMINADO            = 0;

    const TC_PRIORIDAD = 'TC_PRIORIDAD_TICKETS';
    const BAJA       = 1;
    const MEDIDA     = 2;
    const ALTA       = 3;
    const CRITICO    = 4;
    const BLOQUEANTE = 5;

    const TC_TIPO = 'TC_TIPO_TICKETS';
    const SOLICITUD = 1;
    const PREGUNTA  = 2;
    const MEJORA    = 3;
    const ERROR     = 4;

    protected $table = 'tickets';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'titulo',
        'descripcion',
        'cod_usuario',
        'cod_responsable',
        'estado',
        'tipo',
        'prioridad',
        'url',
        'url_archivo',
        'fecha_hallazgo',
    ];

    protected $casts = [
        'id' => 'string', // Esto fuerza a que el UUID se mantenga como string
        "fecha_hallazgo" => "date:d/m/Y g:i a",
        "created_at" => "date:d/m/Y",
        "updated_at" => "date:d/m/Y",
    ];

    protected $dates = [
        "fecha_hallazgo" => "date:d/m/Y g:i a",
        "created_at" => "date:d/m/Y",
        "updated_at" => "date:d/m/Y",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'cod_usuario', 'id');
    }

    public function responsable()
    {
        return $this->belongsTo(Usuario::class, 'cod_responsable', 'id');
    }

    public function infoTipo()
    {
        return darInfoConcepto($this, self::TC_TIPO, 'tipo')->selectRaw('conceptos.*');
    }

    public function infoPrioridad()
    {
        return darInfoConcepto($this, self::TC_PRIORIDAD, 'prioridad')->selectRaw('conceptos.*');
    }

    public static function darTipo()
    {
        return darConceptos(self::TC_TIPO, false);
    }

    public static function darPrioridad()
    {
        return darConceptos(self::TC_PRIORIDAD, false);
    }
}
