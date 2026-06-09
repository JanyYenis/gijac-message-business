<?php

namespace App\Models;

use App\Classes\Models\Model;

class PreferenciaContacto extends Model
{
    protected $table = 'preferencias_contactos';
    protected $fillable = [
        'cod_contacto',
        'telefono',
        'detalle',
        'categoria',
        'valor',
    ];

    public function contacto()
    {
        return $this->belongsTo(Contacto::class, 'cod_contacto', 'id');
    }
}
