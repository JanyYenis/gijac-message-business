<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoCampanaSeeder extends Seeder
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
            'name' => Usuario::PERMISO_CAMPANA_LISTADO,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Listar Campaña'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CAMPANA_CREAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Crear Campaña'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CAMPANA_EDITAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Editar Campaña'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CAMPANA_ELIMINAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Eliminar Campaña'
        ]);
    }
}
