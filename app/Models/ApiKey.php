<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $table = 'api_keys';
    protected $fillable = [
        'id_usuario',
        'etiqueta',
        'key',
        'estado'
    ];

    protected $casts = [
        "created_at" => "date:d/m/Y",
    ];

    protected $dates = [
        "created_at" => "date:d/m/Y",
    ];

    public static function generate()
    {
        return Str::random(40);
    }

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
    }
}
