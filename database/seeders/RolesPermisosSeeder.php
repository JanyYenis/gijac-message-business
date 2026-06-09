<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesPermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->crearRoles();
    }

    public function crearRoles()
    {
        Role::updateOrCreate([
            'name' => Usuario::ROL_SUPER_ADMINISTRADOR,
        ],[
            'nombre' => 'Super Administrador',
            'guard_name' => 'web',
        ]);

        Role::updateOrCreate([
            'name' => Usuario::ROL_ADMINISTRADOR,
        ],[
            'nombre' => 'Administrador',
            'guard_name' => 'web',
        ]);

        Role::updateOrCreate([
            'name' => Usuario::ROL_CLIENTE,
        ],[
            'nombre' => 'Cliente',
            'guard_name' => 'web',
        ]);

        Role::updateOrCreate([
            'name' => Usuario::ROL_AGENTE,
        ],[
            'nombre' => 'Agente',
            'guard_name' => 'web',
        ]);
    }
}
