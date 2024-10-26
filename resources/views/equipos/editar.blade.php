@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Editar Equipos</h3>
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
                            <span class="badge badge-danger">{{ $error }}</span>
                            @endforeach
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif


                        <form action="{{ route('equipos.update',$equipo->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="titulo">Escaneo</label>
                                        <input type="text" name="escaneo" class="form-control" value="{{ $equipo->escaneo}}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="titulo">Sucursal</label>
                                        <select name="id_sucursal" class="form-control" required>
                                            <option value="">Seleccionar Sucursal</option>
                                            @foreach($sucursales as $sucursal)
                                            <option value="{{ $sucursal->id }}" {{ $sucursal->id == $equipo->id_sucursal ? 'selected' : '' }}>
                                                {{ $sucursal->nombre_sucursal }}
                                            </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="titulo">Equipo</label>
                                        <input type="text" name="equipo" class="form-control" value="{{ $equipo->equipo}}">
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="titulo">Modelo</label>
                                        <input type="text" name="modelo" class="form-control" value="{{ $equipo->modelo}}">
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="titulo">Serie</label>
                                        <input type="text" name="serie" class="form-control" value="{{ $equipo->serie}}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="titulo">Ubicacion de equipo</label>
                                        <input type="text" name="ubicacion_equipo" class="form-control" value="{{ $equipo->ubicacion_equipo}}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="titulo">Descripcion de equipo</label>
                                        <input type="text" name="descripcion_equipo" class="form-control" value="{{ $equipo->descripcion_equipo}}">
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="titulo">Observaciones</label>
                                        <input type="text" name="observaciones" class="form-control" value="{{ $equipo->observaciones}}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
</section>
@endsection