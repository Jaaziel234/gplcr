<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Sucursal; // Agrega el modelo de Sucursal
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-equipo|crear-equipo|editar-equipo|borrar-equipo')->only('index');
        $this->middleware('permission:crear-equipo', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-equipo', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-equipo', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Con paginación
        $equipos = Equipo::all();
        return view('equipos.index', compact('equipos'));
    }

    public function verModalEquipo(Request $request)
    {
        // Verifica si la solicitud es AJAX
        if (!$request->expectsJson()) {
            return redirect()->route('equipos.index');
        }

        return view('equipos.ver_modal_equipo');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sucursales = Sucursal::all(); // Obtener todas las sucursales
        return view('equipos.crear', compact('sucursales'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'escaneo' => 'required',
            'id_sucursal' => 'required', // Validar que una sucursal sea seleccionada
            'modelo' => 'required',
            'serie' => 'required',
            'observaciones' => 'required',
            'ubicacion_equipo' => 'required',
            'descripcion_equipo' => 'required',

        ]);
        $request->validate([
            
        ]);

        Equipo::create($request->all());
        return redirect()->route('equipos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $equipo = Equipo::with('sucursal')->find($id);
        return response()->json($equipo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Equipo $equipo)
    {
        $sucursales = Sucursal::all(); // Obtener todas las sucursales
        return view('equipos.editar', compact('equipo', 'sucursales'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Equipo $equipo)
    {
        //validar para que los campos sean requeridos
        request()->validate([
            'escaneo' => 'required'
        ]);
        $equipo->update($request->all());
        return redirect()->route('equipos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipo $equipo)
    {
        $equipo->delete();
        return redirect()->route('equipos.index')->with('eliminar', 'ok');
    }
}
