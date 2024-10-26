@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Crear Movimiento de Salida</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <!--  @if ($errors->any())
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: `
                                <strong>¡No se pueden guardar los equipos!</strong><br>
                                @foreach ($errors->all() as $error)
                                    <span class="badge badge-primary mb-2 mr-2">{{ $error }}</span><br>
                                @endforeach
                            `,
                        });
                    </script>
                    @endif -->

                    @if ($errors->any())
                    <div class="alert alert-dark alert-dismissible fade show" role="alert">
                        <strong>¡Revise los campos!</strong>
                        @foreach ($errors->all() as $error)
                        <span class="badge badge-primary mb-2 mr-2">{{ $error }}</span>
                        @endforeach
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <div class="card-body">
                        <form id="movimientoForm" action="{{ route('movimientos.store') }}" method="POST">
                            @csrf
                            <div class="row">

                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <div class="form-group">
                                        <label for="numerocontrol_salida">Número de control Salida</label>
                                        <input type="text" name="numerocontrol_salida" class="form-control" value="{{ $numerocontrol_salida }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <div class="form-group">
                                        <label for="id_sucursal">Origen</label>
                                        <select name="id_sucursal" class="form-control">
                                            <option value="">Seleccione Origen</option>
                                            @foreach ($sucursales as $sucursal)
                                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre_sucursal }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <div class="form-group">
                                        <label for="nombre_emisor">Nombre Emisor</label>
                                        <input type="text" name="nombre_emisor" class="form-control" placeholder="Ejemplo: MONITOR, IMPRESOR, CPU..." onkeyup="mayus(this)">
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <div class="form-group">
                                        <label for="nombre_receptor">Nombre Receptor</label>
                                        <input type="text" name="nombre_receptor" class="form-control" placeholder="Ejemplo: LX350..." onkeyup="mayus(this)">
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <div class="form-group">
                                        <label for="nombre_motorista">Nombre Motorista</label>
                                        <input type="text" name="nombre_motorista" class="form-control" placeholder="Ejemplo: 3992002" onkeyup="mayus(this)">
                                    </div>
                                </div>

                                <!-- Verifica si el usuario está autenticado y tiene el rol de 'Administrador' -->
                                <!-- @if(Auth::check() && (Auth::user()->hasRole('GESTORTI') || Auth::user()->hasRole('ADMINISTRADOR')))
                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <div class="form-group">
                                        <label for="nombre_destino">Destino</label>
                                        <select name="nombre_destino" class="form-control" required>
                                            <option value="">Seleccione Destino</option>
                                            @foreach ($sucursales as $sucursal)
                                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre_sucursal }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
 -->
                                <!-- Verifica si el usuario está autenticado y tiene el rol de 'GERENTE' -->
                                @if(Auth::check() && Auth::user()->hasAnyRole(['GESTORTI','ADMINISTRADOR']))
                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <div class="form-group">
                                        <label for="DestinoSucursal">Destino</label>
                                        <select name="DestinoSucursal" class="form-control" required>
                                            <option value="">Seleccione Destino</option>
                                            @foreach ($sucursales as $sucursal)
                                             <option value="{{ $sucursal->nombre_sucursal }}">{{ $sucursal->nombre_sucursal }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif


                                @if(Auth::check() && Auth::user()->hasAnyRole(['GERENTE','SECRETARIA']))
                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <div class="form-group">
                                        <label for="DestinoSucursal">Nombre destino</label>
                                        <select name="DestinoSucursal" class="form-control">
                                            <option value="">Seleccione un destino</option>
                                            <option value="INFORMÁTICA">INFORMÁTICA</option>
                                        </select>
                                    </div>
                                </div>
                                @endif

                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <label for="motivo">Motivo</label>
                                    <div class="form-floating">
                                        <textarea class="form-control" id="motivo" name="motivo" style="height: 100px" placeholder="Ejemplo: Reparación..." onkeyup="mayus(this); updateProgressBar()"></textarea>
                                        <p>Por favor agregue una descripción detallada del problema</p>
                                    </div>
                                    <div class="progress">
                                        <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="191"></div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <div class="form-group">
                                        <label for="id_equipo">Equipo</label>
                                        <select id="miSelect" onchange="validarEquipoEnMovimiento()">
                                            <option value="">Buscar equipo</option>
                                            @foreach($equipos as $equipo)
                                            <option value="{{ $equipo->id }}">{{ $equipo->escaneo }}</option>
                                            @endforeach
                                        </select>
                                        <button id="agregarButton" type="button" class="btn btn-primary mt-2" onclick="agregarEquipo()">Agregar</button>
                                        <!--Botón para limpiar lo que el usuario escribe en el select -->
                                        <button id="limpiarButton" type="button" class="btn btn-warning mt-2" onclick="limpiarSelectize()">Limpiar</button>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <input type="hidden" name="estado_actual" value="EN CAMINO...">
                                </div>
                            </div>
                            <!-- <button type="submit" class="btn btn-primary mt-4">Enviar</button> -->
                            <button id="submitButton" type="submit" class="btn btn-primary mt-4">Enviar</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contenedor para la tabla generada -->
        <div class="card" id="tabla">
        </div>
    </div>
</section>


<script>
    function validarEquipoEnMovimiento() {
        let equipoId = $('#miSelect').val();

        if (equipoId) {
            $.ajax({
                url: '{{ route("validar.equipo.movimiento") }}',
                type: 'GET',
                data: {
                    id_equipo: equipoId
                },
                success: function(response) {
                    /*  console.log(response);   */ // Verifica la respuesta en la consola
                    if (response.enMovimiento) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Equipo en movimiento',
                            html: `
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Información</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Detalle</th>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Número de control de salida</td>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">${response.numerocontrol_salida}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Número de control entrada</td>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">${response.numerocontrol_entrada}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Nombre del emisor</td>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">${response.nombre_emisor}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Nombre del receptor</td>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">${response.nombre_receptor}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Nombre del motorista</td>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">${response.nombre_motorista}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Nombre destino</td>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">${response.DestinoSucursal}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Estado actual</td>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">${response.estado_actual}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Creado</td>
                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">${response.created_at}</td>
                                </tr>
                            </table>
                        `,
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            $('#agregarButton').hide(); // Ocultar el botón "Agregar"
                        });
                    } else {
                        $('#agregarButton').show(); // Mostrar el botón "Agregar" si el equipo no está en movimiento
                    }
                },
                error: function(xhr) {
                    /* console.log(xhr.responseText); // Verifica el error en la consola */
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al verificar el estado del equipo.'
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Selecciona un equipo',
                text: 'Por favor selecciona un equipo antes de continuar.'
            });
        }
    }
</script>


<script id="equipos-data" type="application/json">
    @json($equipos)
</script>

<script src="{{ asset('js/movimientos_equipos/funciones_movimientosequipos.js') }}"></script>
@endsection