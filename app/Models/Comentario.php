<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class Comentario extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    public $table = 'comentarios';

    public $fillable = [
        'comentariable_type',
        'comentariable_id',
        'cod_usuario',
        'descripcion',
        'cod_comentario',
        'estado',
    ];

    protected $casts = [
        'id' => 'string', // Esto fuerza a que el UUID se mantenga como string
        "created_at" => "date:d/m/Y",
        "updated_at" => "date:d/m/Y",
    ];

    protected $dates = [
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

    public function comentariable()
    {
        return $this->morphTo();
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'cod_usuario', 'id');
    }
}
