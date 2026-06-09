<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class VariableCampana extends Model
{
    protected $table = 'variables_campanas';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    const TEXT   = 1;
    const URL    = 2;
    const HEADER = 3;

    protected $fillable = [
        "cod_campana",
        "tipo",
        "numero",
        "nombre",
        "valor",
        "estado",
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

    public function campana()
    {
        return $this->belongsTo(Campana::class, 'cod_campana', 'id');
    }
}
