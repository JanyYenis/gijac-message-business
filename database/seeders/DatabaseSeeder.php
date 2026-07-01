<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesPermisosSeeder::class,
            PermisoUsuarioSeeder::class,
            PermisoTicketSeeder::class,
            PermisoPlantillaSeeder::class,
            PermisoPlanSeeder::class,
            PermisoPerfilWhatsappSeeder::class,
            PermisoEmpresaSeeder::class,
            PermisoEtiquetaSeeder::class,
            PermisoFacturaSeeder::class,
            PermisoClienteSeeder::class,
            PermisoCampanaSeeder::class,
            PermisoChatbotSeeder::class,
            PermisoClasificacionIaSeeder::class,
            PermisoConfiguracionMetaSeeder::class,
            PeriodoSeeder::class,
            AsignarPermisoSeeder::class,
        ]);
    }
}
