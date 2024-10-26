<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\MovimientoEquipoDetalles; //aca necesito guardar el id_equipo en esta tabla 



class MovimientoEquipoDetalleController extends Controller
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

        // Inspeccionar los datos recibidos
        /*  dd('Datos recibidos:', $request->all()); */
        // Validar los datos del formulario
        $request->validate([
            'idM' => 'required|exists:movimiento_equipos,id', // Validar que el ID del movimiento existe
            'id_equipo' => 'required|exists:equipos,id', // Validar que el ID del equipo existe
            'EstadoNombreAsignado' => 'nullable|string|max:255',
            'EstadoFechaAsignado' => 'nullable|date',
            'EstadoNombreReparado' => 'nullable|string|max:255',
            'EstadoFechaReparado' => 'nullable|date',
            'EstadoNombrePrueba' => 'nullable|string|max:255',
            'EstadoFechaPrueba' => 'nullable|date',
        ]);

        try {

            // Buscar un registro existente en la tabla `movimiento_equiposdetalles` con id_movimiento igual al ID enviado
            $detalle = MovimientoEquipoDetalles::where('id_movimiento', $request->idM)
                ->where('id_equipo', $request->id_equipo)
                ->first();

            //dd($detalle);
            if ($detalle) {
                // Añadir la hora y los segundos actuales del servidor a la fecha seleccionada
                if ($request->has('EstadoNombreAsignado') && $request->has('EstadoFechaAsignado')) {
                    $fechaConHora = Carbon::parse($request->EstadoFechaAsignado)
                        ->setTime(now()->hour, now()->minute, now()->second);
                    $detalle->update([
                        'EstadoNombreAsignado' => $request->EstadoNombreAsignado,
                        'EstadoFechaAsignado' => $fechaConHora->format('Y-m-d H:i:s'),
                    ]);
                } elseif ($request->has('EstadoNombreReparado') && $request->has('EstadoFechaReparado')) {
                    $fechaConHora = Carbon::parse($request->EstadoFechaReparado)
                        ->setTime(now()->hour, now()->minute, now()->second);
                    $detalle->update([
                        'EstadoNombreReparado' => $request->EstadoNombreReparado,
                        'EstadoFechaReparado' => $fechaConHora->format('Y-m-d H:i:s'),
                    ]);
                } elseif ($request->has('EstadoNombrePrueba') && $request->has('EstadoFechaPrueba')) {
                    $fechaConHora = Carbon::parse($request->EstadoFechaPrueba)
                        ->setTime(now()->hour, now()->minute, now()->second);
                    $detalle->update([
                        'EstadoNombrePrueba' => $request->EstadoNombrePrueba,
                        'EstadoFechaPrueba' => $fechaConHora->format('Y-m-d H:i:s'),
                    ]);
                }

                return redirect()->route('movimientos.index');
            } else {
                // Si no se encuentra el registro, retornar un mensaje de error específico
                return response()->json(['error' => 'No se pudo encontrar el registro con los IDs proporcionados'], 404);
            }
        } catch (\Exception $e) {
            // Si ocurre una excepción, redirigir de vuelta con el mensaje de error
            return response()->json(['error' => 'Error al guardar los datos: ' . $e->getMessage()], 500);
        }
    }

    public function getDetalleMovimientoEstadoAsignado(Request $request)
    {
        $equipoId = $request->input('equipoId');
        $movimientoId = $request->input('movimientoId');

        // Consulta a la base de datos
        $detalle = MovimientoEquipoDetalles::where('id_equipo', $equipoId)
            ->where('id_movimiento', $movimientoId)
            ->first(['EstadoNombreAsignado', 'EstadoFechaAsignado']);

        // Manejo de la respuesta
        if ($detalle) {
            return response()->json(['detalle' => $detalle], 200);
        } else {
            return response()->json(['error' => 'No se encontraron detalles para el equipo y movimiento especificados'], 404);
        }
    }


    public function getDetalleMovimientoEstadoReparacion(Request $request)
    {
        $equipoId = $request->input('equipoId');
        $movimientoId = $request->input('movimientoId');

        // Consulta a la base de datos
        $detalle = MovimientoEquipoDetalles::where('id_equipo', $equipoId)
        ->where('id_movimiento', $movimientoId)
        ->first(['EstadoNombreReparado', 'EstadoFechaReparado']);

        if ($detalle) {
            return response()->json(['detalle' => $detalle], 200);
        } else {
            return response()->json(['error' => 'No se encontraron detalles para el equipo'], 404);
        }
    }

    public function getDetalleMovimientoEstadoPrueba(Request $request)
    {
        $equipoId = $request->input('equipoId');
        $movimientoId = $request->input('movimientoId');

        // Consulta a la base de datos
        $detalle = MovimientoEquipoDetalles::where('id_equipo', $equipoId)
        ->where('id_movimiento', $movimientoId)
        ->first(['EstadoNombrePrueba', 'EstadoFechaPrueba']);

        if ($detalle) {
            return response()->json(['detalle' => $detalle], 200);
        } else {
            return response()->json(['error' => 'No se encontraron detalles para el equipo'], 404);
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
