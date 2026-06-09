<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoEtiquetaSeeder extends Seeder
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
            'name' => Usuario::PERMISO_ETIQUETAS_LISTADO,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Listar Etiquetas'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_ETIQUETAS_CREAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Crear Etiquetas'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_ETIQUETAS_EDITAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Editar Etiquetas'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_ETIQUETAS_ELIMINAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Eliminar Etiquetas'
        ]);
    }
}
