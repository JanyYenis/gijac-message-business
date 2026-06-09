<?php

namespace App\Models\Sistema;

use App\Classes\Models\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolPermiso extends Model
{
    const ACTIVO = 1;
    const INACTIVO = 0;

    protected $table = "role_has_permissions";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_id',
        'role_id',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function rol()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Get the user that owns the phone.
     */
    public function permiso()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }
}