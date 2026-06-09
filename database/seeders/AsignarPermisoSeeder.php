<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AsignarPermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->asignarPermisos();
    }

    /**
     * Función que permite asignar los permisos a los roles.
     * @return void
     */
    public function asignarPermisos()
    {
        $rolAdministrador = Role::findByName(Usuario::ROL_ADMINISTRADOR);
        $rolAdministrador->syncPermissions([
            Usuario::PERMISO_LISTADO,
            Usuario::PERMISO_CREAR,
            Usuario::PERMISO_EDITAR,
            Usuario::PERMISO_ELIMINAR,
            Usuario::PERMISO_PLANTILLAS_LISTADO,
            Usuario::PERMISO_PLANTILLAS_CREAR,
            Usuario::PERMISO_PLANTILLAS_EDITAR,
            Usuario::PERMISO_PLANTILLAS_ELIMINAR,
            Usuario::PERMISO_CAMPANA_LISTADO,
            Usuario::PERMISO_CAMPANA_CREAR,
            Usuario::PERMISO_CAMPANA_EDITAR,
            Usuario::PERMISO_CAMPANA_ELIMINAR,
            Usuario::PERMISO_CLIENTES_LISTADO,
            Usuario::PERMISO_CLIENTES_CREAR,
            Usuario::PERMISO_CLIENTES_EDITAR,
            Usuario::PERMISO_CLIENTES_ELIMINAR,
            Usuario::PERMISO_ETIQUETAS_LISTADO,
            Usuario::PERMISO_ETIQUETAS_CREAR,
            Usuario::PERMISO_ETIQUETAS_EDITAR,
            Usuario::PERMISO_ETIQUETAS_ELIMINAR,
            Usuario::PERMISO_PERFIL_WHATSAPP_LISTADO,
            Usuario::PERMISO_PERFIL_WHATSAPP_CREAR,
            Usuario::PERMISO_PERFIL_WHATSAPP_EDITAR,
            Usuario::PERMISO_PERFIL_WHATSAPP_ELIMINAR,
            Usuario::PERMISO_TICKETS_CREAR,
            Usuario::PERMISO_TICKETS_LISTADO,
            Usuario::PERMISO_TICKETS_EDITAR,
            Usuario::PERMISO_TICKETS_ELIMINAR,
            Usuario::PERMISO_TICKETS_ASIGNAR_RESPONSABLE,
            Usuario::PERMISO_CONFIGURACION_META_CREAR,
            Usuario::PERMISO_CONFIGURACION_META_LISTADO,
            Usuario::PERMISO_CONFIGURACION_META_EDITAR,
            Usuario::PERMISO_CONFIGURACION_META_ELIMINAR,
            Usuario::PERMISO_CLASIFICACION_IA_CREAR,
            Usuario::PERMISO_CLASIFICACION_IA_LISTADO,
            Usuario::PERMISO_CLASIFICACION_IA_EDITAR,
            Usuario::PERMISO_CLASIFICACION_IA_ELIMINAR,
            Usuario::PERMISO_CHATBOT_CREAR,
            Usuario::PERMISO_CHATBOT_LISTADO,
            Usuario::PERMISO_CHATBOT_EDITAR,
            Usuario::PERMISO_CHATBOT_ELIMINAR,
        ]);
        $rolCliente = Role::findByName(Usuario::ROL_CLIENTE);
        $rolCliente->syncPermissions([
            Usuario::PERMISO_LISTADO,
            Usuario::PERMISO_CREAR,
            Usuario::PERMISO_EDITAR,
            Usuario::PERMISO_ELIMINAR,
            Usuario::PERMISO_PLANTILLAS_LISTADO,
            Usuario::PERMISO_PLANTILLAS_CREAR,
            Usuario::PERMISO_PLANTILLAS_EDITAR,
            Usuario::PERMISO_PLANTILLAS_ELIMINAR,
            Usuario::PERMISO_CAMPANA_LISTADO,
            Usuario::PERMISO_CAMPANA_CREAR,
            Usuario::PERMISO_CAMPANA_EDITAR,
            Usuario::PERMISO_CAMPANA_ELIMINAR,
            Usuario::PERMISO_CLIENTES_LISTADO,
            Usuario::PERMISO_CLIENTES_CREAR,
            Usuario::PERMISO_CLIENTES_EDITAR,
            Usuario::PERMISO_CLIENTES_ELIMINAR,
            Usuario::PERMISO_ETIQUETAS_LISTADO,
            Usuario::PERMISO_ETIQUETAS_CREAR,
            Usuario::PERMISO_ETIQUETAS_EDITAR,
            Usuario::PERMISO_ETIQUETAS_ELIMINAR,
            Usuario::PERMISO_PERFIL_WHATSAPP_LISTADO,
            Usuario::PERMISO_PERFIL_WHATSAPP_CREAR,
            Usuario::PERMISO_PERFIL_WHATSAPP_EDITAR,
            Usuario::PERMISO_PERFIL_WHATSAPP_ELIMINAR,
            Usuario::PERMISO_TICKETS_CREAR,
            Usuario::PERMISO_TICKETS_LISTADO,
            Usuario::PERMISO_TICKETS_EDITAR,
            Usuario::PERMISO_TICKETS_ELIMINAR,
            Usuario::PERMISO_CONFIGURACION_META_CREAR,
            Usuario::PERMISO_CONFIGURACION_META_LISTADO,
            Usuario::PERMISO_CONFIGURACION_META_EDITAR,
            Usuario::PERMISO_CONFIGURACION_META_ELIMINAR,
            Usuario::PERMISO_CLASIFICACION_IA_CREAR,
            Usuario::PERMISO_CLASIFICACION_IA_LISTADO,
            Usuario::PERMISO_CLASIFICACION_IA_EDITAR,
            Usuario::PERMISO_CLASIFICACION_IA_ELIMINAR,
            Usuario::PERMISO_CHATBOT_CREAR,
            Usuario::PERMISO_CHATBOT_LISTADO,
            Usuario::PERMISO_CHATBOT_EDITAR,
            Usuario::PERMISO_CHATBOT_ELIMINAR,
        ]);
        $rolAgente = Role::findByName(Usuario::ROL_AGENTE);
        $rolAgente->syncPermissions([
            Usuario::PERMISO_PLANTILLAS_LISTADO,
            Usuario::PERMISO_CAMPANA_LISTADO,
            Usuario::PERMISO_CLIENTES_LISTADO,
            Usuario::PERMISO_CLIENTES_CREAR,
            Usuario::PERMISO_CLIENTES_EDITAR,
            Usuario::PERMISO_CLIENTES_ELIMINAR,
            Usuario::PERMISO_ETIQUETAS_LISTADO,
            Usuario::PERMISO_ETIQUETAS_CREAR,
            Usuario::PERMISO_ETIQUETAS_EDITAR,
            Usuario::PERMISO_ETIQUETAS_ELIMINAR,
            Usuario::PERMISO_PERFIL_WHATSAPP_LISTADO,
            // Usuario::PERMISO_TICKETS_LISTADO,
            Usuario::PERMISO_CONFIGURACION_META_LISTADO,
            Usuario::PERMISO_CLASIFICACION_IA_LISTADO,
            Usuario::PERMISO_CHATBOT_LISTADO,
        ]);
    }
}
