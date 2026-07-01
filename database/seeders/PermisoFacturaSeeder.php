<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoFacturaSeeder extends Seeder
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
            'name' => Usuario::PERMISO_FACTURA_LISTADO,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Listar Factura'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_FACTURA_CREAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Crear Factura'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_FACTURA_EDITAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Editar Factura'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_FACTURA_ELIMINAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Eliminar Factura'
        ]);
    }
}
