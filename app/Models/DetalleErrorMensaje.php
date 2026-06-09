<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class DetalleErrorMensaje extends Model
{
    protected $table = 'detalles_errores_mensajes';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'wamid',
        'code',
        'title',
        'message',
        'details',
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

    public function mensaje()
    {
        return $this->belongsTo(Mensaje::class, 'wamid', 'wa_message_id');
    }
}
