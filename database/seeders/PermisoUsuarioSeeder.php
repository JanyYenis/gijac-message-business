<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoUsuarioSeeder extends Seeder
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
            'name' => Usuario::PERMISO_LISTADO,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Listar Usuario'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CREAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Crear Usuario'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_EDITAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Editar Usuario'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_ELIMINAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Eliminar Usuario'
        ]);
    }
}
