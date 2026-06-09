<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class DetalleLink extends Model
{
    protected $table = 'detalle_links';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    const ABIERTO = 1;
    const SIN_ABRIR = 0;

    protected $fillable = [
        "cod_campana",
        "cod_detalle",
        "cod_contacto",
        "cod_detalle_wpp",
        "click",
    ];

    protected $casts = [
        'id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function camapan()
    {
        return $this->belongsTo(Campana::class, 'cod_campana', 'id');
    }

    public function envioCampana()
    {
        return $this->belongsTo(EnvioCampana::class, 'cod_detalle', 'cod_detalle');
    }

    public function variableCampana()
    {
        return $this->belongsTo(VariableCampana::class, 'cod_detalle_wpp', 'id');
    }

    public function contacto()
    {
        return $this->belongsTo(Contacto::class, 'cod_contacto', 'id');
    }
}
