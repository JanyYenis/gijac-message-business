<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class EtiquetaContacto extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $table = 'etiquetas_contactos';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        "cod_contacto",
        "cod_etiqueta",
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

    public function etiqueta()
    {
        return $this->belongsTo(Etiqueta::class, 'cod_etiqueta', 'id');
    }

    public function contacto()
    {
        return $this->belongsTo(Contacto::class, 'cod_contacto', 'id');
    }
}
