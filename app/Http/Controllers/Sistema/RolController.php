<?php

namespace App\Http\Controllers\Sistema;

use App\Events\UsuarioRolEvent;
use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Models\Sistema\RolPermiso;
use App\Models\Usuario;
use App\Notifications\UsuarioRolNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permisos = Permission::get();
        $info['permisos'] = $permisos;
        return view('roles.index', $info);
    }

    public function listado(Request $request)
    {
        $pagina = $request->input('pagina') ?? 1;
        $cantidad = $request->input("cantidad_pagina", 6);
        $roles = Role::paginate(8);

        // $this->filtroListado($request, $productos);

        // return [
        //     "estado" => "success",
        //     "html" => view("tienda.ver-productos", $info)->render()
        // ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permisos = Permission::get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datos = $request->all();
        $permisos = $request->input('permisos');
        $rol = Role::create($datos);

        if (!$rol) {
            throw new ErrorException("Error al intentar crear un rol.");
        }

        $rol->syncPermissions($permisos);

        return [
            'estado' => 'success',
            'mensaje' => 'Se a creado exitosamente el registro.',
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $rol)
    {
        $permisos = Permission::get();
        $roles_permisos = RolPermiso::where('role_id', $rol->id)->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function asignarRol(Request $request, Usuario $usuario)
    {
        $datos = $request->all();
        $datos['roles'] = Role::whereIn('id', explode(',', $request->input('roles')))->pluck('name');
        $asignar = $usuario->syncRoles($datos['roles']);

        if (!$asignar) {
            throw new ErrorException('Error al intentar registrar los roles al usuario.');
        }
        
        return [
            'estado' => 'success',
            'mensaje' => 'Se asignaron correctamente los roles.'
        ];
    }

    public function asignarPermiso(Request $request, Usuario $usuario)
    {
        $datos = $request->all();
        $datos['permisos'] = Permission::whereIn('id', explode(',', $request->input('permisos')))->pluck('name');
        $asignar = $usuario->syncPermissions($datos['permisos']);

        if (!$asignar) {
            throw new ErrorException('Error al intentar registrar los permisos al usuario.');
        }

        // dd($request->all());
        return [
            'estado' => 'success',
            'mensaje' => 'Se asignaron correctamente los permisos.'
        ];
    }

    public function buscarRol(Request $request)
    {
        $nombre = $request->get("busqueda");
        $filtro = "%$nombre%";
        $usuario = $request->input('usuario') ?? '';
        if (!$usuario) {
            throw new ErrorException("Por favor, seleccione un usuario.");
        }
        $usuario = Usuario::find($usuario);
        $rolesUsuario = $usuario->getRoleNames()->toArray();

        $roles = Role::selectRaw('id, nombre as text, name')
            ->whereRaw("LOWER(nombre) LIKE LOWER(?)", $filtro)
            ->get()
            ->map(function ($item, $key) use($rolesUsuario) {
                $item->seleccionado = false;
                if (in_array($item->name, $rolesUsuario)) {
                    $item->seleccionado = true;
                }
                return $item;
            });

        return [
            'estado' => 'success',
            'roles' => $roles,
        ];
    }

    public function buscarPermisos(Request $request)
    {
        $nombre = $request->get("busqueda");
        $filtro = "%$nombre%";
        $usuario = $request->input('usuario') ?? '';
        if (!$usuario) {
            throw new ErrorException("Por favor, seleccione un usuario.");
        }
        $usuario = Usuario::find($usuario);
        $permisosUsuario = $usuario->getPermissionNames()->toArray();

        $permisos = Permission::selectRaw('id, nombre as text, name')
            ->whereRaw("LOWER(nombre) LIKE LOWER(?)", $filtro)
            ->get()
            ->map(function ($item, $key) use($permisosUsuario) {
                $item->seleccionado = false;
                if (in_array($item->name, $permisosUsuario)) {
                    $item->seleccionado = true;
                }
                return $item;
            });
        
        return [
            'estado' => 'success',
            'permisos' => $permisos,
        ];
    }
}
