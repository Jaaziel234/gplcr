<!-- Permite hacer scroll solamente en: Modal para detalles de equipos  -->
<style>
  .modal-body {
    max-height: 70vh;
    /* Ajusta según sea necesario */
    overflow-y: auto;
  }
</style>

<!-- Modal para detalles de equipos -->
<div class="modal fade" id="equiposModal" tabindex="-1" role="dialog" aria-labelledby="equiposModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="equiposModalLabel">Detalles de Equipo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="container mt-2">
        <div class="row">
          <div class="col-md-4 offset-md-4">
            <!--  <div class="input-group">
              <input type="text" id="quantity" class="form-control text-center" value="3" readonly>
              <div class="input-group-append">
                <button class="btn btn-primary" id="incrementBtn" type="button" data-toggle="modal" data-target="#EquipoSalidaPorGrupoModal">Confirmar salida </button>
              </div>
            </div> -->
          </div>
        </div>
      </div>
      <div class="modal-body">
        <ul id="equipos-list" class="list-group">
          <!-- Aquí se añadirán los equipos -->

        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal estados 1 -->
<div class="modal fade" id="asignadoModal" tabindex="-1" role="dialog" aria-labelledby="asignadoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="asignadoModalLabel">Asignar Equipo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="asignadoForm1" action="{{ route('detalle.store') }}" method="POST">
          @csrf
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="EstadoNombreAsignado" onkeyup="mayus(this)" required>
          </div>
          <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" class="form-control" id="fecha" name="EstadoFechaAsignado" required>
          </div>
          <!-- Se envia el id del movimiento -->
          <input type="hidden" name="idM">
          <!-- <input type="text" name="idM" > -->
          <!-- Se envia el id del equipo -->
          <input type="hidden" name="id_equipo" id="id_equipo">

          <button type="submit" class="btn btn-primary mt-4">Enviar</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal estados 2-->
<div class="modal fade" id="reparacionModal" tabindex="-1" role="dialog" aria-labelledby="reparacionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reparacionModalLabel">Reparar Equipo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="asignadoForm2" action="{{ route('detalle.store') }}" method="POST">
          @csrf
          <div class="form-group">
            <label for="nombre2">Nombre</label>
            <input type="text" class="form-control" id="nombre2" name="EstadoNombreReparado" onkeyup="mayus(this)" required>
          </div>
          <div class="form-group">
            <label for="fecha2">Fecha</label>
            <input type="date" class="form-control" id="fecha2" name="EstadoFechaReparado" required>
          </div>
          <!-- Se envia el id del movimiento -->
          <input type="hidden" name="idM">
          <!-- Se envia el id del equipo -->
          <input type="hidden" name="id_equipo" id="id_equipo2">
          <button type="submit" class="btn btn-primary mt-4">Enviar</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal estados 3-->
<div class="modal fade" id="pruebasModal" tabindex="-1" role="dialog" aria-labelledby="pruebasModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pruebasModalLabel">Pruebas Equipo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="asignadoForm3" action="{{ route('detalle.store') }}" method="POST">
          @csrf
          <div class="form-group">
            <label for="nombre3">Nombre</label>
            <input type="text" class="form-control" id="nombre3" name="EstadoNombrePrueba" onkeyup="mayus(this)" required>
          </div>
          <div class="form-group">
            <label for="fecha3">Fecha</label>
            <input type="date" class="form-control" id="fecha3" name="EstadoFechaPrueba" required>
          </div>
          <!-- Se envia el id del movimiento -->
          <input type="hidden" name="idM">
          <!-- Se envia el id del equipo -->
          <input type="hidden" name="id_equipo" id="id_equipo3">
          <button type="submit" class="btn btn-primary mt-4">Enviar</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal  de  detalles asginados-->
<div class="modal fade" id="DetallesAsignadoModal" tabindex="-1" role="dialog" aria-labelledby="DetallesAsignadoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 1rem;">
      <div class="modal-header">
        <h5 class="modal-title" id="DetallesAsignadoModalLabel">Detalles de Asginados</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- Aquí se llenará con la información de Detalles Movimientos -->
        <p id="detallesAsignadoMovimientos"></p>
        <!-- Input hidden para almacenar el movimiento ID -->
        <input type="hidden" name="idM" id="movimientoIdHidden">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal  de  detalles reparacion-->
<div class="modal fade" id="DetallesReparacionModal" tabindex="-1" role="dialog" aria-labelledby="DetallesReparacionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 1rem;">
      <div class="modal-header">
        <h5 class="modal-title" id="DetallesReparacionModalLabel">Detalles de Reparación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- Aquí se llenará con la información de Detalles Movimientos -->
        <p id="detallesReparacionMovimientos"></p>
         <!-- Input hidden para almacenar el movimiento ID -->
         <input type="hidden" name="idM" id="movimientoIdHidden">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal  de  detalles Prueba-->
<div class="modal fade" id="DetallesPruebaModal" tabindex="-1" role="dialog" aria-labelledby="DetallesPruebaModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 1rem;">
      <div class="modal-header">
        <h5 class="modal-title" id="DetallesPruebaModalLabel">Detalles de Pruebas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- Aquí se llenará con la información de Detalles Movimientos -->
        <p id="detallesPruebaMovimientos"></p>
        <!-- Input hidden para almacenar el movimiento ID -->
        <input type="hidden" name="idM" id="movimientoIdHidden">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

