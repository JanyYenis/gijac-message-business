<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoConfiguracionMetaSeeder extends Seeder
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
            'name' => Usuario::PERMISO_CONFIGURACION_META_LISTADO,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Listar Configuración de META'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CONFIGURACION_META_CREAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Crear Configuración de META'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CONFIGURACION_META_EDITAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Editar Configuración de META'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CONFIGURACION_META_ELIMINAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Eliminar Configuración de META'
        ]);
    }
}
