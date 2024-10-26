<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sucursal;
use App\Models\UserPivot;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todos los usuarios con la relación userPivot y sucursal cargadas
        $usuarios = User::with('userPivot.sucursal')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        $sucursales = Sucursal::pluck('nombre_sucursal', 'id'); // Obtenemos las sucursales con el ID como clave
        return view('usuarios.crear', compact('roles', 'sucursales'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            /* 'sucursal' => 'required|array|exists:sucursales,id' */ // Validar que las sucursales seleccionadas existen
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        // Guardar la relación en la tabla userPivot
        foreach ($request->input('sucursal') as $sucursalId) {
            UserPivot::create([
                'id_usuario' => $user->id,
                'id_sucursal' => $sucursalId
            ]);
        }

        return redirect()->route('usuarios.index');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        $sucursales = Sucursal::pluck('nombre_sucursal', 'id'); // Obtenemos las sucursales con el ID como clave

        // Verificar si el usuario tiene sucursales asociadas
        $selectedSucursales = $user->userPivot ? $user->userPivot->pluck('id_sucursal')->toArray() : [];

        // Obtén el campo 'estado' del usuario
        $estado = $user->estado_usuario;

        return view('usuarios.editar', compact('user', 'roles', 'userRole', 'estado', 'sucursales', 'selectedSucursales'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
           /*  'sucursal' => 'required|array|exists:sucursales,id' */ // Validar que las sucursales seleccionadas existen
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('roles'));

        // Actualizar la relación en la tabla userPivot
        UserPivot::where('id_usuario', $id)->delete();
        foreach ($request->input('sucursal') as $sucursalId) {
            UserPivot::create([
                'id_usuario' => $id,
                'id_sucursal' => $sucursalId
            ]);
        }

        return redirect()->route('usuarios.index');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('usuarios.index')->with('eliminar', 'ok');
    }
}
