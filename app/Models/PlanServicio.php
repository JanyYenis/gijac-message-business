<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class PlanServicio extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    const TC_ESTADO_OPCION = 'TC_ESTADO_OPCION';
    const APLICA    = 1;
    const NO_APLICA = 0;

    protected $table = 'plan_servicio';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'plan_id',
        'servicio_id',
        'habilitado',
        'estado',
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

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }
}
