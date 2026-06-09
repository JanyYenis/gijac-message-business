<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class EnvioCampana extends Model
{
    protected $table = 'envios_campanas';
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
        "apertura",
        "fecha_apertura",
        "telefono",
        "cod_contacto",
        "wamid",
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

    public function contacto()
    {
        return $this->belongsTo(Contacto::class, 'cod_contacto', 'id');
    }

    public function mensaje()
    {
        return $this->belongsTo(Mensaje::class, 'wamid', 'wa_message_id');
    }

    public function links()
    {
        return $this->hasMany(DetalleLink::class, 'cod_campana', 'cod_campana');
    }

    public function linksActivos()
    {
        return $this->links()->where('estado', DetalleLink::ACTIVO);
    }
}
