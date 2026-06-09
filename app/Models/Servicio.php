<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class Servicio extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $table = 'servicios';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nombre',
        'slug',
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

    public function planes()
    {
        return $this->belongsToMany(Plan::class, 'plan_servicio')
                    ->withPivot('habilitado')
                    ->withTimestamps();
    }
}
