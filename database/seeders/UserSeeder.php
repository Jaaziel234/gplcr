<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         // Obtener el rol ADMINISTRADOR
         $role = Role::where('name', 'ADMINISTRADOR')->first();

         // Crear un usuario administrador
         $admin = User::create([
             'name' => 'Admin',
             'email' => 'admin@gmail.com',
             'password' => Hash::make('123456789'), // Cambia 'password' por una contraseña más segura
         ]);
 
         // Asignar el rol ADMINISTRADOR al usuario
         $admin->assignRole($role);
    }
}
