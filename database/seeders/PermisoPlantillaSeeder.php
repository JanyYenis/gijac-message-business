<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoPlantillaSeeder extends Seeder
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
            'name' => Usuario::PERMISO_PLANTILLAS_LISTADO,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Listar Plantilla'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_PLANTILLAS_CREAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Crear Plantilla'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_PLANTILLAS_EDITAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Editar Plantilla'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_PLANTILLAS_ELIMINAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Eliminar Plantilla'
        ]);
    }
}
