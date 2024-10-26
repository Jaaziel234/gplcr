<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimientoEquipo;
use App\Models\MovimientoEquipoSalida;

class MovimientoEquipoSalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'nombre_emisor_salida' => 'required|string|max:255',
            'nombre_receptor_salida' => 'required|string|max:255',
            'nombre_motorista_salida' => 'required|string|max:255',
            'motivo_salida' => 'required|string',
            'idM' => 'required|integer',
            'id_equipo' => 'required|integer',
        ]);

        // Verificar si ya existe un registro con el mismo id_movimiento y id_equipo
        $movimientoExistente = MovimientoEquipoSalida::where('id_movimiento', $validatedData['idM'])
            ->where('id_equipo', $validatedData['id_equipo'])
            ->first();

        if ($movimientoExistente) {
            // Actualizar el registro existente con los nuevos datos
            $movimientoExistente->nombre_emisor_salida = $validatedData['nombre_emisor_salida'];
            $movimientoExistente->nombre_receptor_salida = $validatedData['nombre_receptor_salida'];
            $movimientoExistente->nombre_motorista_salida = $validatedData['nombre_motorista_salida'];

            $movimientoExistente->motivo_salida = $validatedData['motivo_salida'];
            $movimientoExistente->estado_actual_salida = 'SALIDA'; // Establecer el estado a 'SALIDA'
            $movimientoExistente->save();

            // Redirigir a movimientos_equipos.index sin mostrar mensaje
            return redirect()->route('movimientos_equipos.index');
            /* return response()->json(['message' => 'Datos actualizados exitosamente'], 200); */
        }
    }

    public function getDetalleSalidaEstado(Request $request)
    {
        $equipoId = $request->input('equipo_id');
        $movimientoId = $request->input('movimiento_id');

        // Buscar el detalle usando el ID del equipo
        $detalle = MovimientoEquipoSalida::where('id_equipo', $equipoId)->where('id_movimiento', $movimientoId)->first();

        if ($detalle) {
            return response()->json(['detalle' => $detalle], 200);
        } else {
            return response()->json(['message' => 'Detalles no encontrados para el equipo'], 404);
        }
    }


    public function updateEstadoSalidaASucursal(Request $request)
    {
        $validatedData = $request->validate([
            'idM' => 'required|integer',
            'id_equipo' => 'required|integer',
        ]);

        $movimientoExistente = MovimientoEquipoSalida::where('id_movimiento', $validatedData['idM'])
            ->where('id_equipo', $validatedData['id_equipo'])
            ->first();

        if ($movimientoExistente) {
            // Verificar si el estado ya es "EN SUCURSAL" o cualquier otro estado final
            if ($movimientoExistente->estado_actual_salida === 'EN SUCURSAL') {
                return response()->json('No se puede actualizar, la salida ya fue aceptada', 400);
            }

            // Si no ha sido aceptado, se procede con la actualización
            $movimientoExistente->estado_actual_salida = 'EN SUCURSAL';
            $movimientoExistente->save();

            return response()->json(['message' => 'Ha aceptado la salida del equipo hacia la sucursal presente'], 200);
        } else {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
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
        /*  $movimiento = MovimientoEquipo::find($id);

        return view('movimientos_equipos.modals', ['idM_value' => $movimiento->id]); */
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
