<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoClasificacionIaSeeder extends Seeder
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
            'name' => Usuario::PERMISO_CLASIFICACION_IA_LISTADO,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Listar Clasificación IA'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CLASIFICACION_IA_CREAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Crear Clasificación IA'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CLASIFICACION_IA_EDITAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Editar Clasificación IA'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CLASIFICACION_IA_ELIMINAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Eliminar Clasificación IA'
        ]);
    }
}
