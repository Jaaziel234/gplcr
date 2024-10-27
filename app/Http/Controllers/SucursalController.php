<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
    {
        $this->middleware('permission:ver-sucursal|crear-sucursal|editar-sucursal|borrar-sucursal', ['only' => ['index']]);
        $this->middleware('permission:crear-sucursal', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-sucursal', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-sucursal', ['only' => ['destroy']]);
    }

    public function index()
    {

        $sucursales = Sucursal::all();
        return view('sucursales.index', compact('sucursales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('sucursales.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'nombre_sucursal' => 'required',
            'direccion' => 'required',
        ]);

        // Inserta ambos campos en la base de datos
        $sucursal = Sucursal::create([
            'nombre_sucursal' => $request->input('nombre_sucursal'),
            'direccion' => $request->input('direccion'),
        ]);

        return redirect()->route('sucursales.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $sucursal = Sucursal::find($id);
        return view('sucursales.editar', compact('sucursal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        // Validación de los campos
        $this->validate($request, [
            'nombre_sucursal' => 'required',
            'direccion' => 'required',
        ]);

        // Buscar la sucursal por ID y actualizar sus campos
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->update([
            'nombre_sucursal' => $request->input('nombre_sucursal'),
            'direccion' => $request->input('direccion'),
        ]);

        // Redirigir a la vista de índice o listado
        return redirect()->route('sucursales.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        DB::table("sucursales")->where('id', $id)->delete();
        return redirect()->route('sucursales.index')->with('eliminar','ok');
        
    }
}
