<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoPerfilWhatsappSeeder extends Seeder
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
            'name' => Usuario::PERMISO_PERFIL_WHATSAPP_LISTADO,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Listar Perfil de WhatsApp'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_PERFIL_WHATSAPP_CREAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Crear Perfil de WhatsApp'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_PERFIL_WHATSAPP_EDITAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Editar Perfil de WhatsApp'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_PERFIL_WHATSAPP_ELIMINAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Eliminar Perfil de WhatsApp'
        ]);
    }
}
