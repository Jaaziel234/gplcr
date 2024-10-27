@extends('layouts.app')

@section('content')
<section class="section">
  <div class="section-header">
    <h3 class="page__heading">Usuarios</h3>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="row d-flex flex-wrap mb-3">
              <a class="btn btn-warning" href="{{ route('usuarios.create') }}">Nuevo</a>
            </div>
            <div class="table-responsive">
              <table id="miTabla" class="table" style="width:100%">
                <thead style="background-color:#6777ef">
                  <th style="color:#fff;">ID</th>
                  <th style="color:#fff;">Nombre</th>
                  <th style="color:#fff;">Sucursal</th>
                  <th style="color:#fff;">E-mail</th>
                  <th style="color:#fff;">Estado</th>
                  <th style="color:#fff;">Rol</th>
                  <th style="color:#fff;">Acciones</th>
                </thead>
                <tbody>

                  @foreach ($usuarios as $usuario)
                  <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->name }}</td>
                    <td>
                      @if ($usuario->userPivot && $usuario->userPivot->sucursal)
                      Sucursal ID: {{ $usuario->userPivot->id_sucursal }} - Nombre: {{ $usuario->userPivot->sucursal->nombre_sucursal }}
                      @else
                      Ninguna sucursal asignada
                      @endif
                    </td>
                    <td>{{ $usuario->email }}</td>
                    <td>
                      @if($usuario->estado_usuario == 0)
                      <span class="badge badge-success">Activo</span>
                      @else
                      <span class="badge badge-warning">Inactivo</span>
                      @endif
                    </td>
                    <td>
                      @if(!empty($usuario->getRoleNames()))
                      @foreach($usuario->getRoleNames() as $rolNombre)
                      <h5><span class="badge badge-dark">{{ $rolNombre }}</span></h5>
                      @endforeach
                      @endif
                    </td>

                    <td>
                      <a class="btn btn-info" href="{{ route('usuarios.edit',$usuario->id) }}">Editar</a>
                      <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="eliminarInventario(event)">Borrar</button>
                      </form>

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