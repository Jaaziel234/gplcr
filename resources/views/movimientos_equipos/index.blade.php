@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Lista de movimientos por Sucursal</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="miTabla" class="table" style="width:100%">
                                <thead style="background-color:#6777ef">
                                    <tr>
                                        <th style="color:#fff;">Numero Salida</th>
                                        <th style="color:#fff;">#Equipos</th>
                                        <th style="color:#fff;">Numero Entrada</th>
                                        <th style="color:#fff;">Estado Actual</th>
                                        <th style="color:#fff;">Nombre Emisor</th>
                                        <th style="color:#fff;">Nombre Receptor</th>
                                        <th style="color:#fff;">Nombre Motorista</th>
                                        <th style="color:#fff;">Motivo</th>
                                        <th style="color:#fff;">Nombre Origen</th>
                                        <th style="color:#fff;">Nombre destino</th>
                                       <!--  @if(Auth::check() && (Auth::user()->hasRole('GESTORTI') || Auth::user()->hasRole('ADMINISTRADOR')))
                                        <th style="color:#fff;">Destino</th>
                                         @endif -->
                                        <th style="color:#fff;">Creado</th>

                                        @if(Auth::check() && (Auth::user()->hasRole('GESTORTI') || Auth::user()->hasRole('ADMINISTRADOR')))
                                        <th style="color:#fff;">Acciones</th>
                                        @else
                                        <th style="display:none;"></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movimientos as $movimiento)
                                    <tr id="movimiento-{{ $movimiento->id }}">
                                        <td>{{ $movimiento->numerocontrol_salida }}</td>
                                        <td>
                                            <a href="#" class="detalle-equipos" data-movimiento-id="{{ $movimiento->id }}">{{ $movimiento->equipos_count }} equipos</a>
                                        </td>
                                        <td>
                                            @if ($movimiento->numerocontrol_entrada)
                                            {{ $movimiento->numerocontrol_entrada }}
                                            @else
                                            <span class="badge badge-warning">PENDIENTE</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($movimiento->estado_actual == 'ACEPTADO')
                                            <span class="badge badge-success">{{ $movimiento->estado_actual }}</span>
                                            @else
                                            <span class="badge badge-warning">{{ $movimiento->estado_actual }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $movimiento->nombre_emisor }}</td>
                                        <td>{{ $movimiento->nombre_receptor }}</td>
                                        <td>{{ $movimiento->nombre_motorista }}</td>
                                        <td>{{ $movimiento->motivo }}</td>
                                       <td>{{ $movimiento->sucursalOrigen->nombre_sucursal ?? 'Sin sucursal' }}</td>
                                       <td>{{ $movimiento->DestinoSucursal }}</td>
                                       <!-- <td>{{ $movimiento->sucursalDestino->nombre_sucursal ?? 'Sin sucursal' }}</td> -->
                                       <!--  @if(Auth::check() && (Auth::user()->hasRole('GESTORTI') || Auth::user()->hasRole('ADMINISTRADOR')))
                                        <td>{{ $movimiento->sucursalDestino->nombre_sucursal ?? 'Sin sucursal' }}</td>
                                        @endif  -->
                                        <td>{{ $movimiento->created_at->format('d/m/Y h:i:s A') }}</td>
                                        <td>
                                            @if ($movimiento->estado_actual != 'ACEPTADO')
                                            @if(Auth::check() && (Auth::user()->hasRole('GESTORTI') || Auth::user()->hasRole('ADMINISTRADOR')))
                                            <a href="#" class="aceptar-movimiento" data-movimiento-id="{{ $movimiento->id }}">
                                                <i class="fas fa-check-circle" style="font-size: 2em;"></i>
                                            </a>
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Incluir el contenido de modals.blade.php -->
@include('movimientos_equipos.modals')
@include('movimientos_equipos.modalsalida')
@include('movimientos_equipos.modaldetallessalida')

<!-- Estilos CSS para estados de equipos -->
<style>
    .step {
        text-align: center;
        width: 25%;
        position: relative;
    }

    .step i {
        font-size: 2em;
        color: #6c757d;
    }

    .step p {
        margin-top: 20px;
        /* Ajusta según tu necesidad */
        color: #6c757d;
    }

    .step-bar {
        position: relative;
        /* Cambiado a relative para el contexto del .step */
        width: 100%;
        height: 10px;
        background-color: #d4d4d4;
        margin-top: 10px;
        /* Espacio entre el texto y la barra */
        overflow: hidden;
        /* Para ocultar el overflow */
    }

    .step-bar-fill {
        height: 100%;
        width: 0;
        background-color: #007bff;
        /* Color de la barra azul */
        transition: width 2s ease-in-out;
        /* Transición suave de la anchura */
        animation: progress 2s infinite;
        /* Animación de carga */
    }

    @keyframes progress {
        0% {
            width: 0;
        }

        50% {
            width: 50%;
        }

        100% {
            width: 100%;
        }
    }
</style>

<!-- Archivo de funciones JavaScript -->
<script src="{{ asset('js/movimientos_equipos/funciones_movimientosequiposListar.js') }}"></script>
<script src="{{ asset('js/movimientos_equipos/funciones_modalestados.js') }}"></script>

{{-- VALIDACIONES PARA ADMINISTRADOR --}}
<script>
    var esAdministrador = @json(Auth::check() && Auth::user()->hasRole('ADMINISTRADOR'));
    var esGESTORTI = @json(Auth::check() && Auth::user()->hasRole('GESTORTI'));
</script>


@endsection