<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoPlanSeeder extends Seeder
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
            'name' => Usuario::PERMISO_PLANES_LISTADO,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Listar Planes'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_PLANES_CREAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Crear Planes'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_PLANES_EDITAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Editar Planes'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_PLANES_ELIMINAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Eliminar Planes'
        ]);
    }
}
