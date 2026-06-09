<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoClienteSeeder extends Seeder
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
            'name' => Usuario::PERMISO_CLIENTES_LISTADO,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Listar Clientes'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CLIENTES_CREAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Crear Clientes'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CLIENTES_EDITAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Editar Clientes'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CLIENTES_ELIMINAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Eliminar Clientes'
        ]);
    }
}
