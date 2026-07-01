<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class Conversacion extends Model
{
    protected $table = 'conversaciones';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'contacto_id',
        'phone_number_id',
        'ultimo_mensaje',
        'tipo_ultimo_mensaje',
        'ultima_fecha',
        'mensajes_no_leidos',
    ];

    protected $casts = [
        'id' => 'string',
        'contacto_id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function contacto()
    {
        return $this->belongsTo(Contacto::class, 'contacto_id', 'id');
    }
}
