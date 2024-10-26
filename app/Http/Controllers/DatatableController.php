<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipo;
use Yajra\DataTables\Datatables; // Este plugin es necesario para que pueda funcionar el Datatables ServerSide 

class DatatableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $equipos = Equipo::select('equipos.*')->with('sucursal')->orderBy('id', 'desc');
            return Datatables::of($equipos)->make(true);
        }
        return view('equipos.index');
    }

    public function search(Request $request)
{
    if ($request->ajax()) {
        // Obtener el parámetro de búsqueda y eliminar espacios en blanco
        $search = trim($request->input('search'));

        // Si el parámetro de búsqueda está vacío, devolver una respuesta JSON vacía
        if (empty($search)) {
            return response()->json([]);
        }

        // Realizar la búsqueda en la base de datos
        $data = Equipo::where('id', 'like', "%$search%")
            ->orWhere('escaneo', 'like', "%$search%")
            ->with('sucursal') // Cargar la relación con sucursal
            ->get();

        // Transformar los resultados para incluir el nombre de la sucursal
        $transformedData = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'escaneo' => $item->escaneo,
                'id_sucursal' => $item->id_sucursal,
                'nombre_sucursal' => $item->sucursal->nombre_sucursal, // Obtener el nombre de la sucursal
                'equipo' => $item->equipo,
                'modelo' => $item->modelo,
                'serie' => $item->serie,
                'created_at' => $item->created_at->toDateTimeString(), // Ajustar formato de fecha si es necesario
                'updated_at' => $item->updated_at->toDateTimeString(), // Ajustar formato de fecha si es necesario
            ];
        });

        // Devolver los resultados transformados como respuesta JSON
        return response()->json($transformedData);
    }

    // Si la solicitud no es AJAX, devolver un error 404
    return abort(404);
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
