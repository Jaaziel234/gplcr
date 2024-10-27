@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Crear Sucursal</h3>
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


                        {!! Form::open(array('route' => 'sucursales.store','method'=>'POST')) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="">Nombre de la Sucursal:</label>                                    
                                    {!! Form::text('nombre_sucursal', null, array('class' => 'form-control', 'onkeyup' => 'mayus(this)')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="">Dirección </label>                                    
                                    {!! Form::text('direccion', null, array('class' => 'form-control', 'onkeyup' => 'mayus(this)')) !!}
                                </div>
                            </div>
                                   
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Nos permite convertir automaticamente a MAYUSCULA todos lo que se escriba en el INPUT -->
    <script>
        function mayus(element){
            element.value = element.value.toUpperCase();
        }
    </script>
    <!-- Fin Nos permite convertir automaticamente a MAYUSCULA todos lo que se escriba en el INPUT -->

@endsection
