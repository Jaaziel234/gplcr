<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleHasPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_id = 1; // ID del rol al que quieres asignar permisos

        // Si los permisos ya existen en la tabla permissions, este seeder asignará los primeros 8 permisos al rol con ID 1.
        for ($permission_id = 1; $permission_id <= 8; $permission_id++) {
            DB::table('role_has_permissions')->insert([
                'permission_id' => $permission_id,
                'role_id' => $role_id,
            ]);
        }

    
    }
}