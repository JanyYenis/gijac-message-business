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
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'etiqueta',
        'key',
        'estado'
    ];

    protected $casts = [
        'id' => 'string',
        "created_at" => "date:d/m/Y",
    ];

    protected $dates = [
        "created_at" => "date:d/m/Y",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public static function generate()
    {
        return 'GMB-' . Str::random(40);
    }

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'uuid', 'uuid');
    }
}
