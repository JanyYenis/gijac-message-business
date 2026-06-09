<?php

namespace App\Models\Sistema;

use App\Classes\Models\Model;
use App\Models\Usuario;

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
