<?php

namespace App\Http\Controllers;

use App\Models\MovimientoEquipo;
use App\Models\Equipo; // ya inclui mi modelo para mandar a llamar mis equipos
use App\Models\MovimientoEquipoDetalles; //aca necesito guardar el id_equipo en esta tabla 
use App\Models\MovimientoEquipoSalida;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Importa la clase Str
use Yajra\DataTables\Datatables; // Este plugin es necesario para que pueda funcionar el Datatables ServerSide 
use Illuminate\Support\Facades\Auth;


class MovimientoEquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
   public function index()
    {
        $user = Auth::user();

        // Verificar si el usuario tiene una sucursal asociada
        if ($user->userPivot) {
            $sucursalId = $user->userPivot->id_sucursal;
        } else {
            $sucursalId = null;
        }

        // Obtener movimientos según la sucursal del usuario o todos los movimientos si no tiene sucursal
        if ($sucursalId) {
            // Obtener los IDs de los equipos de la sucursal del usuario
            $equiposIds = Equipo::where('id_sucursal', $sucursalId)->pluck('id')->toArray();

            // Obtener los movimientos que incluyen estos equipos y ordenarlos de manera descendente por ID
            $movimientos = MovimientoEquipo::whereHas('movimientoEquipoDetalles', function ($query) use ($equiposIds) {
                $query->whereIn('id_equipo', $equiposIds);
            })
                ->withCount('equipos')
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $movimientos = MovimientoEquipo::withCount('equipos')
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('movimientos_equipos.index', compact('movimientos'));
    }
    

    

     /* public function index()
    {
        $user = Auth::user();
        $movimientos = collect(); // Inicializamos la colección de movimientos vacía

        // Verificar si el usuario tiene una sucursal asociada
        if ($user->userPivot) {
            $sucursal = Sucursal::find($user->userPivot->id_sucursal); // Obtener la sucursal del usuario a través de UserPivot

            if ($sucursal) {
                // Verificar si la sucursal es "INFORMATICA"
                if (strtoupper($sucursal->nombre_sucursal) == 'INFORMATICA') {
                    // Mostrar todos los movimientos si la sucursal es "INFORMATICA"
                    $movimientos = MovimientoEquipo::with(['sucursalDestino', 'sucursalOrigen']) // Incluir las sucursales de destino y origen
                        ->withCount('equipos')
                        ->orderBy('id', 'desc')
                        ->get();
                } else {
                    // Si el usuario pertenece a otra sucursal, solo mostrar los movimientos de esa sucursal
                    $equiposIds = Equipo::where('id_sucursal', $sucursal->id)->pluck('id')->toArray();

                    // Obtener los movimientos que incluyen estos equipos y ordenarlos de manera descendente por ID
                    $movimientos = MovimientoEquipo::whereHas('movimientoEquipoDetalles', function ($query) use ($equiposIds) {
                        $query->whereIn('id_equipo', $equiposIds);
                    })
                        ->with(['sucursalDestino', 'sucursalOrigen']) // Incluir las sucursales de destino y origen
                        ->withCount('equipos')
                        ->orderBy('id', 'desc')
                        ->get();
                }
            } else {
                // Si no se encuentra la sucursal, opcionalmente mostrar todos los movimientos o manejar el error
                $movimientos = MovimientoEquipo::with(['sucursalDestino', 'sucursalOrigen']) // Incluir las sucursales de destino y origen
                    ->withCount('equipos')
                    ->orderBy('id', 'desc')
                    ->get();
            }
        } else {
            // Si el usuario no tiene una sucursal asociada, mostrar todos los movimientos
            $movimientos = MovimientoEquipo::with(['sucursalDestino', 'sucursalOrigen']) // Incluir las sucursales de destino y origen
                ->withCount('equipos')
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('movimientos_equipos.index', compact('movimientos'));
    }  */


    /* public function getEquiposByMovimiento($id)
    {
        try {
            // Obtener los detalles de los equipos asociados a un movimiento específico
            $equipos = MovimientoEquipoDetalles::where('id_movimiento', $id)
                ->with('equipo')
                ->get();

            return response()->json($equipos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener equipos: ' . $e->getMessage()], 500);
        }
    } */

    public function getEquiposByMovimiento($id)
    {
        try {
            // Obtener los detalles de los equipos asociados a un movimiento específico
            $equipos = MovimientoEquipoDetalles::where('id_movimiento', $id)
                ->with(['equipo' => function ($query) {
                    $query->select('id', 'escaneo', 'equipo', 'modelo', 'serie', 'observaciones', 'ubicacion_equipo', 'descripcion_equipo');
                }])
                ->get();

            // Iterar sobre los equipos para agregar información adicional
            $equipos->each(function ($detalle) {
                $detalle->mostrar_boton_listo = !empty($detalle->EstadoNombreAsignado) && !empty($detalle->EstadoFechaAsignado);
            });

            return response()->json($equipos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener equipos: ' . $e->getMessage()], 500);
        }
    }



    public function aceptarMovimiento($id)
    {
        try {
            // Generar código aleatorio para numerocontrol_entrada
            $numerocontrol_entrada = $this->generateUniqueCode();

            // Actualizar el movimiento con el número de control generado
            $movimiento = MovimientoEquipo::findOrFail($id);
            $movimiento->numerocontrol_entrada = $numerocontrol_entrada;
            $movimiento->estado_actual = 'ACEPTADO';
            $movimiento->save();

            return response()->json(['message' => 'Movimiento aceptado correctamente', 'movimiento' => $movimiento], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hubo un problema al aceptar el movimiento: ' . $e->getMessage()], 500);
        }
    }

    public function getEstadoMovimiento($id)
    {
        try {
            // Obtener el movimiento según el ID proporcionado
            $movimiento = MovimientoEquipo::find($id);

            // Si el movimiento no se encuentra, devolver un error
            if (!$movimiento) {
                return response()->json(['error' => 'Movimiento no encontrado'], 404);
            }

            // Verificar si numerocontrol_salida, numerocontrol_entrada y estado_actual tienen información
            $numerocontrolSalida = $movimiento->numerocontrol_salida;
            $numerocontrolEntrada = $movimiento->numerocontrol_entrada;

            $estadoValido = ($numerocontrolSalida && $numerocontrolEntrada);

            return response()->json(['estadoValido' => $estadoValido], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hubo un problema al obtener el estado del movimiento: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $user = Auth::user();
        $sucursales = Sucursal::all(); // Obtener todas las sucursales por defecto
        $equipos = Equipo::all(); // Obtener todos los equipos por defecto

        // Verificar si el usuario tiene una sucursal asociada
        if ($user->userPivot) {
            $sucursal = Sucursal::find($user->userPivot->id_sucursal); // Obtener la sucursal del usuario a través de UserPivot

            if ($sucursal) {
                $sucursales = collect([$sucursal]); // Si tiene sucursal, solo mostrar esa sucursal
                $equipos = Equipo::where('id_sucursal', $sucursal->id)->get(); // Obtener equipos según la sucursal del usuario
            }
        }

        $numerocontrol_salida = $this->generateUniqueCode();
        return view('movimientos_equipos.crear', compact('equipos', 'numerocontrol_salida', 'sucursales'));
    }

    /*  public function create()
    {
        $user = Auth::user();
        $sucursales = Sucursal::all(); // Obtener todas las sucursales por defecto
        $equipos = Equipo::all(); // Obtener todos los equipos por defecto

        // Verificar si el usuario tiene una sucursal asociada
        if ($user->userPivot) {
            $sucursal = Sucursal::find($user->userPivot->id_sucursal); // Obtener la sucursal del usuario a través de UserPivot

            if ($sucursal) {
                // Si la sucursal del usuario es "INFORMÁTICA", mostrar todas las sucursales
                if (strtoupper($sucursal->nombre_sucursal) == 'INFORMATICA') {
                    $sucursales = Sucursal::all(); // Mostrar todas las sucursales
                } else {
                    // Si no es "INFORMÁTICA", mostrar solo su sucursal
                    $sucursales = collect([$sucursal]); // Solo mostrar la sucursal asociada
                    $equipos = Equipo::where('id_sucursal', $sucursal->id)->get(); // Obtener equipos según la sucursal del usuario
                }
            }
        }

        $numerocontrol_salida = $this->generateUniqueCode();
        return view('movimientos_equipos.crear', compact('equipos', 'numerocontrol_salida', 'sucursales'));
    } */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /* public function validarEquipoEnMovimiento(Request $request)
    {
        $equipoId = $request->get('id_equipo');
    
        // Obtener el movimiento más reciente para el equipo
        $movimientoSalida = MovimientoEquipoSalida::where('id_equipo', $equipoId)
            ->orderBy('created_at', 'desc') // Ordenar por fecha de creación descendente
            ->first(); // Solo obtenemos el más reciente
    
        if ($movimientoSalida) {
            // Verificar si el estado actual del movimiento de salida es "EN SUCURSAL"
            if ($movimientoSalida->estado_actual_salida === 'EN SUCURSAL') {
                return response()->json([
                    'enMovimiento' => false,
                    'mensaje' => 'El equipo está en la sucursal y puede realizar un nuevo movimiento.'
                ]);
            } else {
                // Obtener el detalle del movimiento relacionado con el último movimiento de salida activo
                $detalleMovimiento = MovimientoEquipoDetalles::where('id_equipo', $equipoId)
                    ->where('id_movimiento', $movimientoSalida->id)
                    ->first(); // Solo obtenemos el más relevante
    
                if ($detalleMovimiento) {
                    return response()->json([
                        'enMovimiento' => true,
                        'numerocontrol_salida' => $detalleMovimiento->movimiento->numerocontrol_salida,
                        'nombre_emisor' => $detalleMovimiento->movimiento->nombre_emisor,
                        'nombre_receptor' => $detalleMovimiento->movimiento->nombre_receptor,
                        'nombre_destino' => $detalleMovimiento->movimiento->nombre_destino,
                        'estado_actual' => $detalleMovimiento->movimiento->estado_actual,
                        'created_at' => $detalleMovimiento->movimiento->created_at,
                    ]);
                }
            }
        }
    
        // Si no hay movimientos de salida activos, se puede realizar un nuevo movimiento
        return response()->json(['enMovimiento' => false]);
    }
    
 */

    public function validarEquipoEnMovimiento(Request $request)
    {
        $equipoId = $request->get('id_equipo');

        // Obtener el último movimiento activo (no finalizado) para el equipo desde MovimientoEquipoSalida
        $movimientoSalida = MovimientoEquipoSalida::where('id_equipo', $equipoId)
            ->orderBy('created_at', 'desc') // Obtener el último movimiento
            ->first(); // Solo el más reciente

        if ($movimientoSalida) {
            // Si el equipo no está "EN SUCURSAL", no se puede realizar un nuevo movimiento
            if ($movimientoSalida->estado_actual_salida !== 'EN SUCURSAL') {
                // Obtener detalles del movimiento desde la tabla MovimientoEquipo
                $movimientoEquipo = MovimientoEquipo::where('id', $movimientoSalida->id_movimiento)
                    ->first(); // Asumiendo que 'id_movimiento' en MovimientoEquipoSalida se refiere a 'id' en MovimientoEquipo

                if ($movimientoEquipo) {
                    return response()->json([
                        'enMovimiento' => true,
                        'mensaje' => 'El equipo ya está en movimiento y no puede realizar un nuevo movimiento.',
                        'numerocontrol_salida' => $movimientoEquipo->numerocontrol_salida ?? 'No disponible',
                        'numerocontrol_entrada' => $movimientoEquipo->numerocontrol_entrada ?? 'No disponible',
                        'nombre_emisor' => $movimientoEquipo->nombre_emisor ?? 'No disponible',
                        'nombre_receptor' => $movimientoEquipo->nombre_receptor ?? 'No disponible',
                        'nombre_motorista' => $movimientoEquipo->nombre_motorista ?? 'No disponible',
                        'DestinoSucursal' => $movimientoEquipo->DestinoSucursal ?? 'No disponible',
                        'estado_actual' => $movimientoSalida->estado_actual_salida ?? 'No disponible',
                        'created_at' => $movimientoEquipo->created_at ? $movimientoEquipo->created_at->format('d/m/Y h:i:s A') : 'No disponible'
                    ]);
                }
            }
        }

        // Si el equipo está "EN SUCURSAL" o no tiene movimientos activos, permitir un nuevo movimiento
        return response()->json([
            'enMovimiento' => false,
            'detalles_equipo' => null // No hay detalles si no está en movimiento
        ]);
    }


    public function store(Request $request)
{
  /* dd('Datos recibidos:', $request->all());  */ 
    $this->validate($request, [
        'id_equipo' => 'required|array|max:5',
        'id_equipo.*' => 'exists:equipos,id',
        'nombre_emisor' => 'required',
        'nombre_receptor' => 'required',
        'nombre_motorista' => 'required',
        'motivo' => 'required',
        'DestinoSucursal' => 'required',
        'id_sucursal' => 'required',
    ]);

    // Verificar si alguno de los equipos está en movimiento y no está en la sucursal
    $equiposEnMovimiento = MovimientoEquipoDetalles::whereIn('id_equipo', $request->id_equipo)
        ->with(['movimientoSalida' => function($query) {
            $query->orderBy('created_at', 'desc'); // Obtener el último estado del equipo
        }])
        ->get();

    // Recopila los equipos que están en movimiento y no están en la sucursal
    $equiposBloqueados = [];
    foreach ($equiposEnMovimiento as $detalle) {
        $movimientoSalida = $detalle->movimientoSalida; // Ahora accedemos a la relación definida
        if ($movimientoSalida && $movimientoSalida->estado_actual_salida !== 'EN SUCURSAL') {
            $equiposBloqueados[] = $detalle->id_equipo;
        }
    }

    // Si hay equipos bloqueados, devolver el error
    if (!empty($equiposBloqueados)) {
        return redirect()->back()->withErrors(['equipos' => 'Uno o más equipos seleccionados están en estado SALIDA y no pueden ser movidos.']);
    }

    // Crea el movimiento principal
    $movimiento = MovimientoEquipo::create($request->except('id_equipo'));

    // Guarda los detalles de los equipos asociados
    foreach ($request->id_equipo as $equipoId) {
        // Guardar en la tabla MovimientoEquipoDetalles
        MovimientoEquipoDetalles::create([
            'id_movimiento' => $movimiento->id,
            'id_equipo' => $equipoId,
        ]);

        // Guardar en la tabla MovimientoEquipoSalida
        MovimientoEquipoSalida::create([
            'id_movimiento' => $movimiento->id,
            'id_equipo' => $equipoId,
            'estado_actual_salida' => 'SALIDA', // Asumimos que se crea con este estado
        ]);
    }

    return redirect()->route('movimientos.index');
}

    /* Si por alguna razón el código generado aleatoriamente coincide 
    con otro ya existente, la lógica en el método generateUniqueCode()
     se encargará de verificar y generar un nuevo código hasta que se asegure de que sea único. */

    private function generateUniqueCode()
    {
        do {
            $code = Str::random(8);
        } while (MovimientoEquipo::where('numerocontrol_salida', $code)->exists());

        return $code;
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
