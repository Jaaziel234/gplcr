@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Roles</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row d-flex flex-wrap mb-3">
                            @can('crear-rol')
                            <a class="btn btn-warning" href="{{ route('roles.create') }}">Nuevo</a>
                            @endcan
                        </div>
                        <div class="table-responsive">
                            <table id="miTabla" class="table table-striped table-bordered table-reponsive" style="width:100%">
                                <thead style="background-color:#6777ef">
                                    <th style="color:#fff;">Rol</th>
                                    <th style="color:#fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            @can('editar-rol')
                                            <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}">Editar</a>
                                            @endcan

                                            @can('borrar-rol')
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" onclick="eliminarInventario(event)">Borrar</button>
                                            </form>
                                            @endcan

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
    @if(session('eliminar')=='ok')
    <script>
        Swal.fire({
            title: "Eliminado!",
            text: "Su registro ha sido eliminado",
            icon: "success"
        });
    </script>
    @endif
</section>

<script>
    $(document).ready(function() {
        $('#miTabla').DataTable({
            responsive: true,
            autoWidth: false,
            "pageLength": 5, // Número de registros por página
            "lengthMenu": [
                [5, 25, 50, 100, -1],
                [5, 25, 50, 100, "All"]
            ],
        });
    });

    function eliminarInventario(event) {
        // Prevenir la acción predeterminada del formulario (envío)
        event.preventDefault();

        // Mostrar alerta de confirmación usando SweetAlert2
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, enviar el formulario
                event.target.form.submit();
            } else {
                // Si el usuario cancela, no hacer nada
                return;
            }
        });
    }
</script>
@endsection