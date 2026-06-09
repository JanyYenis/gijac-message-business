<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoChatbotSeeder extends Seeder
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
            'name' => Usuario::PERMISO_CHATBOT_LISTADO,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Listar Chatbots'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CHATBOT_CREAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Crear Chatbots'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CHATBOT_EDITAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Editar Chatbots'
        ]);

        Permission::updateOrCreate([
            'name' => Usuario::PERMISO_CHATBOT_ELIMINAR,
        ], [
            'guard_name' => 'web',
            'nombre' => 'Eliminar Chatbots'
        ]);
    }
}
