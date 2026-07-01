<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class Etiqueta extends Model
{
    protected $table = 'etiquetas';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $fillable = [
        "slug",
        "nombre",
        "descripcion",
        "color",
        "nombre",
        "uuid",
        "cod_empresa",
        "estado",
    ];

    protected $casts = [
        'id' => 'string',
        'uuid' => 'string',
        "updated_at" => "date:d/m/Y",
        "created_at" => "date:d/m/Y",
    ];

    protected $dates = [
        "created_at" => "date:d/m/Y ",
        "updated_at" => "date:d/m/Y ",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
            $model->slug = Str::slug($model->nombre, '.');
        });

        static::updating(function ($model) {
            if ($model->isDirty('nombre')) {
                $model->slug = Str::slug($model->nombre, '.');
            }
        });
    }

    public function contactosEtiquetas()
    {
        return $this->hasMany(EtiquetaContacto::class, 'cod_etiqueta', 'id');
    }

    public function contactoEtiqueta()
    {
        return $this->hasOne(EtiquetaContacto::class, 'cod_etiqueta', 'id');
    }

    public function contactosEtiquetasActivos()
    {
        return $this->contactosEtiquetas()->where('estado', EtiquetaContacto::ACTIVO);
    }

    public function contactoEtiquetaActivo()
    {
        return $this->contactoEtiqueta()->where('estado', EtiquetaContacto::ACTIVO);
    }
}
