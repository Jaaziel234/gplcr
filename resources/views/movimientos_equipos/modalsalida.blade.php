<!-- Modal para salida de equipo individual -->
<div class="modal fade" id="EquipoSalidaIndividualModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Salida Individual del Equipo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="FormSalidaaSucModal" action="{{ route('movimientoequiposalida.store') }}" method="POST">
          @csrf <!-- Asegúrate de incluir el token CSRF -->
          <div class="form-group">
            <label for="nombreEmisor">Nombre Emisor</label>
            <input type="text" class="form-control" id="nombreEmisor" name="nombre_emisor_salida" required>
          </div>
          <div class="form-group">
            <label for="nombreReceptor">Nombre Receptor</label>
            <input type="text" class="form-control" id="nombreReceptor" name="nombre_receptor_salida" required>
          </div>
          <div class="form-group">
            <label for="nombreMotorista">Nombre Motorista</label>
            <input type="text" class="form-control" id="nombreMotorista" name="nombre_motorista_salida" required>
          </div>
          <div class="form-group">
            <label for="motivo">Motivo</label>
            <textarea class="form-control" id="motivo" name="motivo_salida" rows="3" required></textarea>
          </div>
          <!-- Se envia el id del movimiento -->
          <input type="hidden" name="idM">
          <input type="hidden" name="id_equipo" id="id_equipoSalidaaSuc">
          <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <!-- <button type="button" class="btn btn-primary" onclick="submitForm()">Confirmar Salida</button> -->
      </div>
    </div>
  </div>
</div>