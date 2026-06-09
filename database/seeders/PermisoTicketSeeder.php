<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoTicketSeeder extends Seeder
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
            'name' => Usuario::PERMISO_TICKETS_LISTADO,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Listar tickets'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_TICKETS_CREAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Crear tickets'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_TICKETS_EDITAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Editar tickets'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_TICKETS_ELIMINAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Eliminar tickets'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_TICKETS_ASIGNAR_RESPONSABLE,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Asignar responsable al ticket'
        ]);
    }
}
