@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Crear Equipos</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

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

                        <form action="{{ route('equipos.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="titulo">Escaneo</label>
                                        <input type="text" name="escaneo" class="form-control" onkeyup="mayus(this)">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="sucursal">Sucursal</label>
                                        <select name="id_sucursal" class="form-control">
                                            <option value="">Seleccionar Sucursal</option>
                                            @foreach($sucursales as $sucursal)
                                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre_sucursal }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="titulo">Equipo</label>
                                        <input type="text" name="equipo" class="form-control" placeholder="ejemplo: MONITOR,IMPRESOR,CPU..." onkeyup="mayus(this)">
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="titulo">Modelo</label>
                                        <input type="text" name="modelo" class="form-control" placeholder="ejemplo: LX350..." onkeyup="mayus(this)">
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="titulo">Serie</label>
                                        <input type="text" name="serie" class="form-control" placeholder="ejemplo: 3992002" onkeyup="mayus(this)">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="titulo">Ubicacion de equipo</label>
                                        <input type="text" name="ubicacion_equipo" class="form-control" placeholder="vendedor2, caja, gerente" onkeyup="mayus(this)">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="titulo">Descripcion de equipo</label>
                                        <input type="text" name="descripcion_equipo" class="form-control" placeholder="pantalla de 16 pulgadas" onkeyup="mayus(this)">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <label for="contenido">Observaciones</label>
                                    <div class="form-floating">
                                        <textarea class="form-control" name="observaciones" style="height: 100px" placeholder="ejemplo: Reparacion..." onkeyup="mayus(this)"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nos permite convertir automaticamente a MAYUSCULA todos lo que se escriba en el INPUT -->
<script>
    function mayus(element) {
        element.value = element.value.toUpperCase();
    }
</script>
<!-- Fin Nos permite convertir automaticamente a MAYUSCULA todos lo que se escriba en el INPUT -->
@endsection