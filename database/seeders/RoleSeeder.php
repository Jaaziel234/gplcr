<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         // Crear el rol ADMINISTRADOR
         Role::create(['name' => 'ADMINISTRADOR']);
         Role::create(['name' => 'GESTOR TI']);
         Role::create(['name' => 'GERENTE']);
         Role::create(['name' => 'SECRETARIA']);
    }
}
