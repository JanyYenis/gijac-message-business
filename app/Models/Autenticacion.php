<?php

namespace App\Models;

use App\Classes\Models\Model;

class Autenticacion extends Model
{
    protected $table = 'autenticaciones';
    public $timestamps = false;
    protected $fillable = [
        'cod_usuario',
        'external_id',
        'external_auth',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'cod_usuario', 'id');
    }
}
