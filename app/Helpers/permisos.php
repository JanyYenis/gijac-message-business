<?php

use App\Models\Plan;
use Spatie\Permission\Models\Role;

if (!function_exists('asignarPermisoRol')) {
    /**
     * Función que permite asignar un permiso a un rol determinado.
     * @param string $nombreRole
     * @param string $permiso
     */
    function asignarPermisoRol($nombreRole, $permiso)
    {
        $role = Role::findByName($nombreRole);
        $role->givePermissionTo($permiso);
    }
}

if (!function_exists('can')) {
    /**
     * Valida un permiso
     * @return boolean - nombre del permids.
     */
    function can($string)
    {
        if (auth()->check()) {
            return auth()->user()->can($string);
        }
        return false;
    }
}

if (!function_exists("canAny")) {
    /**
     * Valida que el usuario tenga al menos UNO de los permisos dados.
     * @return array - Arreglo con los permisos.
     */
    function canAny($permisos)
    {
        $permisos = collect($permisos);
        return $permisos->some(function ($permiso) {
            return can($permiso);
        });
    }
}

if (!function_exists("canTodos")) {
    /**
     * Valida que el usuario tenga TODOS los permisos dados.
     * @return array - Arreglo con los permisos.
     */
    function canTodos($permisos)
    {
        $permisos = collect($permisos);
        return $permisos->every(function ($permiso) {
            return can($permiso);
        });
    }
}

if (!function_exists('nombreRolesUsuario')) {
    /**
     * Valida un rol.
     * @return string - nombre del permids.
     */
    function nombreRolesUsuario()
    {
        if (auth()->check()) {
            return  auth()->user()->roles()->pluck('nombre');
        }
        return false;
    }
}

if (!function_exists('role')) {
    /**
     * Valida un rol.
     * @return string - nombre del permids.
     */
    function role($string)
    {
        if (auth()->check()) {
            return  auth()->user()->hasRole($string);
        }
        return false;
    }
}

if (!function_exists("roleAny")) {
    /**
     * Valida que el usuario tenga al menos UNO de los roles dados.
     * @return array - Arreglo con los roles.
     */
    function roleAny($roles)
    {
        return auth()->user()->hasAnyRole($roles);
    }
}

if (!function_exists("servicioPlan")) {
    /**
     * Valida que el usuario tenga al menos UNO de los roles dados.
     * @return array - Arreglo con los roles.
     */
    function servicioPlan($servicio)
    {
        $tiene = false;
        if (auth()->user()?->cod_plan) {
            $plan = Plan::find(auth()->user()->cod_plan);
            $tiene = $plan->tieneServicio($servicio);
        }
        return $tiene;
    }
}
