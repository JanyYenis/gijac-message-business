<?php

namespace App\Models\Sistema;

use App\Classes\Models\Model;
use Spatie\Permission\Models\Role;

class RolUsuario extends Model
{
    const ACTIVO = 1;
    const INACTIVO = 0;

    protected $table = "model_has_roles";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
    ];

    public function model()
    {
        return $this->morphTo("model");
    }

    /**
     * Get the user that owns the phone.
     */
    public function rol()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}