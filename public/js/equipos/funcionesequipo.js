function eliminarInventario(event) {
    event.preventDefault();

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
            event.target.form.submit();
        } else {
            return;
        }
    });
}

function verEquipo(id) {
    $.ajax({
        url: '/equipos/ver-modal-equipo',
        type: 'GET',
        success: function(modalHtml) {
            $('body').append(modalHtml);
            $.ajax({
                url: `/equipos/${id}`,
                type: 'GET',
                success: function(data) {
                    let detalles = `
                        <strong>Escaneo:</strong> ${data.escaneo}<br>
                        <strong>Sucursal:</strong> ${data.sucursal ? data.sucursal.nombre_sucursal : 'Sin sucursal asignada'}<br>
                        <strong>Equipo:</strong> ${data.equipo}<br>
                        <strong>Modelo:</strong> ${data.modelo}<br>
                        <strong>Serie:</strong> ${data.serie}<br>
                        <strong>Observaciones:</strong> ${data.observaciones}<br>
                        <strong>Ubicacion del equipo:</strong> ${data.ubicacion_equipo}<br>
                        <strong>Descripcion equipo:</strong> ${data.descripcion_equipo}<br>
                        <strong>Creado:</strong> ${new Date(data.created_at).toLocaleString()}<br>
                        <strong>Actualizado:</strong> ${new Date(data.updated_at).toLocaleString()}
                    `;
                    $('#detallesEquipo').html(detalles);
                    $('#verModal').modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error al obtener los detalles del equipo: ', textStatus, errorThrown);
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error al cargar el modal: ', textStatus, errorThrown);
        }
    });
}