<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoEmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->crearPermisos();
    }

    public function crearPermisos()
    {
        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_EMPRESA_LISTADO,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Listar Empresa/Negocio'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_EMPRESA_CREAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Crear Empresa/Negocio'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_EMPRESA_EDITAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Editar Empresa/Negocio'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_EMPRESA_ELIMINAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Eliminar Empresa/Negocio'
        ]);
    }
}
