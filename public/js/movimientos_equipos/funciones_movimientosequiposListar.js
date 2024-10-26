document.addEventListener('DOMContentLoaded', function() {
    // Inicializar DataTable
    $('#miTabla').DataTable({
        responsive: true,
        autoWidth: false,
        "pageLength": 5,
        "lengthMenu": [
            [5, 25, 50, 100, -1],
            [5, 25, 50, 100, "All"]
        ],
        "order": [[0, 'desc']] // Ordenar por la primera columna en orden asc
    });

    // Manejador de clic para abrir el modal y evitar cierre automático
    $('.detalle-equipos').click(function(e) {
        e.preventDefault();
        var movimientoId = $(this).data('movimiento-id');
        $('#equiposModal').modal('show');

        // Aquí puedes cargar contenido dinámico basado en el ID del movimiento si es necesario
    });

    // Manejador de clic para el icono de aceptar movimiento
    $('.aceptar-movimiento').click(function(e) {
        e.preventDefault();
        var movimientoId = $(this).data('movimiento-id');

        // Mostrar la alerta de confirmación
        Swal.fire({
            title: '¿Quieres aceptar el movimiento a sucursal?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, aceptar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Realizar una solicitud AJAX al método del controlador
                $.ajax({
                    url: '/movimientos/' + movimientoId + '/aceptar',
                    type: 'GET',
                    success: function(response) {
                        // Recargar la página para reflejar los cambios
                        location.reload();
                    },
                    error: function(xhr) {
                        // Manejar errores si es necesario
                        Swal.fire(
                            'Error!',
                            'Hubo un problema al aceptar el movimiento.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});



 // Asumiendo que la variable equipo ya está definida y tiene el ID
 document.addEventListener('DOMContentLoaded', function() {
    var currentMovimientoId = null; // Variable para almacenar el ID del movimiento actual

    // Manejar el clic en el enlace de detalles de equipos
    $('.detalle-equipos').click(function(event) {
        event.preventDefault();

        currentMovimientoId = $(this).data('movimiento-id'); // Guardar el ID del movimiento actual

        // Obtener los detalles de los equipos a través de AJAX
        $.ajax({
            url: '/movimientos/' + currentMovimientoId + '/equipos',
            method: 'GET',
            success: function(response) {
                var equiposList = $('#equipos-list');
                equiposList.empty(); // Limpiar la lista

                response.forEach((detalle, index) => {
                    var equipo = detalle.equipo;

                    var equipoHtml = '<li class="list-group-item">';
                    /* equipoHtml += '<strong>ID:</strong> ' + equipo.id + '<br>'; */
                    $('#asignadoModal input[name="idM"]').val(currentMovimientoId);
                    $('#reparacionModal input[name="idM"]').val(currentMovimientoId);
                    $('#pruebasModal input[name="idM"]').val(currentMovimientoId);

                    //Le asignamos el id del movimiento al modal 
                    $('#DetallesAsignadoModal input[name="idM"]').val(currentMovimientoId);
                    $('#DetallesReparacionModal input[name="idM"]').val(currentMovimientoId); 
                    $('#DetallesPruebaModal input[name="idM"]').val(currentMovimientoId);
                    //asignamos el id del movimiento para poder enviarlo como hidden en el modal FormSalidaaSucModal
                    $('#FormSalidaaSucModal input[name="idM"]').val(currentMovimientoId);
                    // fin de asignamos el id del movimiento para poder enviarlo como hidden en el modal FormSalidaaSucModal
         
                   /*  HTML básico para los detalles del equipo  */
                    equipoHtml += '<strong>Escaneo:</strong> ' + equipo.escaneo + '<br>';
                    let sucursal = equipo.escaneo.split('-')[0]; // Permite extraer lo que trae escaneo (antes del guion) y lo agregamos a Sucursal
                    equipoHtml += '<strong>Sucursal:</strong> ' + sucursal + '<br>';
                    equipoHtml += '<strong>Equipo:</strong> ' + equipo.equipo + '<br>';
                    equipoHtml += '<strong>Modelo:</strong> ' + equipo.modelo + '<br>';
                    equipoHtml += '<strong>Serie:</strong> ' + equipo.serie + '<br>';
                    equipoHtml += '<strong>Observaciones:</strong> ' + equipo.observaciones + '<br>';
                    equipoHtml += '<strong>Ubicación:</strong> ' + equipo.ubicacion_equipo + '<br>';
                    equipoHtml += '<strong>Descripción:</strong> ' + equipo.descripcion_equipo + '<br>';
                    
                    // Agrupación de botones con diseño responsivo usando el sistema de rejilla de Bootstrap
                    equipoHtml += '<div class="container mt-3">';
                    equipoHtml += '<div class="row">';
                    
                   // Botón Realizar salida
                   if (detalle.EstadoNombreAsignado && detalle.EstadoFechaAsignado && detalle.EstadoNombrePrueba && detalle.EstadoFechaPrueba) {
                        equipoHtml += '<div class="col-12 col-md-4 mb-2">';

                        // Validar si es administrador
                        if (esAdministrador || esGESTORTI) {
                            equipoHtml += `<button type="button" class="btn btn-primary w-100 btn-salida-individual" value="${detalle.id_equipo}">Realizar salida</button>`;
                        }

                        equipoHtml += '</div>';
                    }
                    // Botón Detalles de salida
                    if (detalle.EstadoNombreAsignado && detalle.EstadoFechaAsignado && detalle.EstadoNombrePrueba && detalle.EstadoFechaPrueba) {
                        equipoHtml += '<div class="col-12 col-md-4 mb-2">';
                        equipoHtml += ` <button type="button" class="btn btn-success w-100 btn-salida-individualDetalle" 
                            data-toggle="modal" 
                            data-target="#EquipoSalidaIndividualDetalleModal" 
                            data-equipo-id="${detalle.id_equipo}" 
                            data-movimiento-id="${currentMovimientoId}">
                            Detalles de salida
                        </button>`;
                        equipoHtml += '</div>';
                    }
                    // Botón Aceptar Salida
                    if (detalle.EstadoNombreAsignado && detalle.EstadoFechaAsignado && detalle.EstadoNombrePrueba && detalle.EstadoFechaPrueba) {
                        equipoHtml += '<div class="col-12 col-md-4 mb-2">';
                        equipoHtml += `<button type="button" class="btn btn-warning w-100 btn-salida-individualSucursal" 
                            data-toggle="modal" 
                            data-target="#EquipoSalidaIndividualASucursalModal" 
                            data-equipo-id="${detalle.id_equipo}" 
                            data-movimiento-id="${currentMovimientoId}" 
                            onclick="confirmarSalida('${detalle.id_equipo}', '${currentMovimientoId}')">
                            Aceptar Salida
                        </button>`;
                        equipoHtml += '</div>';
                    }
                    /* if (detalle.EstadoNombreAsignado && detalle.EstadoFechaAsignado && detalle.EstadoNombreReparado && detalle.EstadoFechaReparado && detalle.EstadoNombrePrueba && detalle.EstadoFechaPrueba) {
                        equipoHtml += '<div class="col-12 col-md-4 mb-2">';
                        equipoHtml += `<button type="button" class="btn btn-warning w-100 btn-salida-individualSucursal" 
                            data-toggle="modal" 
                            data-target="#EquipoSalidaIndividualASucursalModal" 
                            data-equipo-id="${detalle.id_equipo}" 
                            data-movimiento-id="${currentMovimientoId}" 
                            onclick="confirmarSalida('${detalle.id_equipo}', '${currentMovimientoId}')">
                            Aceptar Salida
                        </button>`;
                        equipoHtml += '</div>';
                    } */
                    
                    // Cerrar el contenedor de botones
                    equipoHtml += '</div>'; // row
                    equipoHtml += '</div>'; // container       

                    //Fin de codigo de Validacion para mostrar los botones de salida indivual y por lista.

                   // Generar el HTML del equipo
                    // ASIGNACIÓN
                    equipoHtml += `<div class="container my-5">
                    <div class="row text-center">
                        <!-- ASIGNADO -->
                        <div class="col-12 col-md-3 step mb-3">`;

                // Validar si es administrador o GESTORTI antes de mostrar el ícono con función de modal
                if (esAdministrador || esGESTORTI) {
                    equipoHtml += `<button class="icono-asignado btn" value="${equipo.id}">
                        <i class="fas fa-user"></i>
                    </button>`;
                } else {
                    equipoHtml += `<button class="icono-asignado btn" value="${equipo.id}" disabled>
                        <i class="fas fa-user"></i>
                    </button>`;
                }

                // Para depurar, puedes agregar esto
                /* console.log('esAdministrador:', esAdministrador);
                console.log('esGESTORTI:', esGESTORTI); */


                    equipoHtml += `<p>ASIGNADO</p>`;

                    // Mostrar la barra de progreso solo si ambos campos están presentes
                    if (detalle.EstadoNombreAsignado && detalle.EstadoFechaAsignado) {
                        equipoHtml += `
                    <div class="step-bar">
                        <div class="step-bar-fill"></div>
                    </div>`;
                    }

                    // Agregar boton después de la barra de progreso
                    equipoHtml += `
                            <button type="button" class="btn btn-primary btn-sm w-100 mt-2 salida-equipo-detalle" 
                            data-equipo-id="${equipo.id}" data-toggle="modal" data-target="#DetallesAsignadoModal">
                            Detalles
                            </button>
                        </div>`;

                    // Generar el HTML del equipo
                    // REPARACIÓN
                        equipoHtml += `<div class="col-12 col-md-3 step mb-3">`;

                        // Validar si es administrador antes de mostrar el ícono con función de modal
                        if (esAdministrador || esGESTORTI) {
                            equipoHtml += `<button class="icono-reparacion btn" value="${equipo.id}">
                                                <i class="fas fa-user"></i>
                                            </button>`;
                        } else {
                            equipoHtml += `<button class="icono-reparacion btn" value="${equipo.id}" disabled>
                                                <i class="fas fa-user"></i>
                                            </button>`;
                        }

                        equipoHtml += `<p>REPARACIÓN</p>`;


                    // Mostrar la barra de progreso en la sección de reparación si los datos están presentes
                    if (detalle.EstadoNombreReparado && detalle.EstadoFechaReparado) {
                        equipoHtml += `
                    <div class="step-bar">
                        <div class="step-bar-fill"></div>
                    </div>`;
                    }

                    // Agregar boton después de la barra de progreso
                    equipoHtml += `
                            <button type="button" class="btn btn-primary btn-sm w-100 mt-2 salida-equipo-detalle" 
                            data-equipo-id="` + equipo.id + `" data-toggle="modal" data-target="#DetallesReparacionModal">Detalles</button>
                        </div>`;

                     // Generar el HTML del equipo
                    // PRUEBAS
                    equipoHtml += `<div class="col-12 col-md-3 step mb-3">`;

                    // Validar si el usuario es administrador antes de mostrar el ícono con función de modal
                    if (esAdministrador || esGESTORTI) {
                        equipoHtml += `<button class="icono-pruebas btn" value="${equipo.id}">
                                            <i class="fas fa-user"></i>
                                    </button>`;
                    } else {
                        equipoHtml += `<button class="icono-pruebas btn" value="${equipo.id}" disabled>
                                            <i class="fas fa-user"></i>
                                    </button>`;
                    }

                    equipoHtml += `<p>PRUEBAS</p>`;


                    // Mostrar la barra de progreso en la sección de pruebas si los datos están presentes
                    if (detalle.EstadoNombrePrueba && detalle.EstadoFechaPrueba) {
                        equipoHtml += `
                    <div class="step-bar">
                        <div class="step-bar-fill"></div>
                    </div>`;
                    }

                    equipoHtml += `
                            <button type="button" class="btn btn-primary btn-sm w-100 mt-2 salida-equipo-detalle" 
                            data-equipo-id="` + equipo.id + `" data-toggle="modal" data-target="#DetallesPruebaModal">Detalles</button>
                      </div>
                       <div class="col-12 col-md-3 step mb-3">
                            <button class=" btn" value="` + equipo.id + `"disabled><i class="fas fa-user "></i></button> 
                            <p>FINALIZADO</p>`;
                    // Mostrar la barra de progreso en la sección de pruebas si los datos están presentes
                    if (detalle.EstadoNombrePrueba && detalle.EstadoFechaPrueba) {
                        equipoHtml += `
                    <div class="step-bar">
                        <div class="step-bar-fill"></div>
                    </div>`;
                    }
                    equipoHtml += '</li>';

                    equiposList.append(equipoHtml);
                });

                // Mostrar el modal de equipos
                $('#equiposModal').modal('show');
            },
            /* error: function(xhr) {
                console.error(xhr.responseText);
            } */
        });
    });


    // Manejar el clic en el ícono de asignado
    $(document).on('click', '.icono-asignado, .icono-reparacion, .icono-pruebas', function(event) {
        event.preventDefault();

        /* alert($(this).val()); */
        if (currentMovimientoId === null) {
            /* console.error('Movimiento ID no encontrado.'); */
            return; // Salir si no se encuentra el ID
        }

        // Mapeo de clases a IDs de modales
        const modalMap = {
            'icono-asignado': '#asignadoModal',
            'icono-reparacion': '#reparacionModal',
            'icono-pruebas': '#pruebasModal'
        };

        $("#id_equipo").val($(this).val());
        $("#id_equipo2").val($(this).val());
        $("#id_equipo3").val($(this).val());

        // Realizar la validación del estado del movimiento
        fetch(`/movimientos/${currentMovimientoId}/estado`)
            .then(response => response.json())
            .then(data => {
                if (!data.estadoValido) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Acción no permitida',
                        text: 'Necesita aceptar el movimiento para poder realizar la acción',
                    });
                } else {
                    // Determinar cuál ícono fue clickeado y mostrar el modal correspondiente
                    for (const [className, modalId] of Object.entries(modalMap)) {
                        if ($(this).hasClass(className)) {
                            $('#asignadoModal').find('input[name="id"]').val(currentMovimientoId); // Establecer el movimiento ID en el formulario del modal
                            $('#reparacionModal').find('input[name="id"]').val(currentMovimientoId); // Establecer el movimiento ID en el formulario del modal
                            $('#pruebasModal').find('input[name="id"]').val(currentMovimientoId); // Establecer el movimiento ID en el formulario del modal
                            $(modalId).modal('show');
                            break; // Salir del bucle una vez encontrado el ícono clicado
                        }
                    }
                }
            })
            /* .catch(error => {
                console.error('Error al obtener el estado del movimiento:', error);
            }); */
    });


});

/* $(document).ready(function() {
    $('#DetallesAsignadoModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var equipoId = button.data('equipo-id'); // Extrae el id del equipo
        console.log(equipoId);
        // Realiza la petición AJAX
        $.ajax({
            url: '/detalleasignadomovimientoEstado/' + equipoId, // Solo el ID del equipo
            type: 'GET',
            success: function(response) {
                // Verifica si hay detalles en la respuesta
                if (response.detalle && response.detalle.EstadoNombreAsignado && response.detalle.EstadoFechaAsignado) {
                    var detalle = response.detalle;

                    function formatDateTime(dateTimeStr) {
                        var date = new Date(dateTimeStr);

                        var day = ('0' + date.getDate()).slice(-2);
                        var month = ('0' + (date.getMonth() + 1)).slice(-2);
                        var year = date.getFullYear();

                        var hours = date.getHours();
                        var minutes = ('0' + date.getMinutes()).slice(-2);
                        var seconds = ('0' + date.getSeconds()).slice(-2);

                        var ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12; // El '0' se reemplaza por '12'

                        var formattedDate = day + '/' + month + '/' + year;
                        var formattedTime = ('0' + hours).slice(-2) + ':' + minutes + ':' + seconds + ' ' + ampm;

                        return formattedDate + ' ' + formattedTime;
                    }

                    var content = '<p>Nombre Asignado: ' + detalle.EstadoNombreAsignado + '</p>';
                    content += '<p>Fecha Asignado: ' + formatDateTime(detalle.EstadoFechaAsignado) + '</p>';

                    $('#detallesAsignadoMovimientos').html(content);
                } else {
                    $('#detallesAsignadoMovimientos').text('En proceso de Asignar...');
                }
            },
            error: function() {
                $('#detallesAsignadoMovimientos').text('Error al obtener los detalles del equipo.');
            }
        });

    });
});
 */

$(document).ready(function() {
    $('#DetallesAsignadoModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var equipoId = button.data('equipo-id'); // Extrae el id del equipo
        var movimientoId = $('#DetallesAsignadoModal input[name="idM"]').val(); // Extrae el id del movimiento

        /* console.log('Equipo ID:', equipoId, 'Movimiento ID:', movimientoId);  */// Verifica que ambos IDs no sean undefined

        // Verifica si ambos valores están definidos antes de hacer la petición AJAX
        if (equipoId && movimientoId) {
            // Realiza la petición AJAX
            $.ajax({
                url: '/detalleasignadomovimientoEstado', // La URL sin parámetros
                type: 'GET',
                data: { equipoId: equipoId, movimientoId: movimientoId }, // Envía ambos valores como parámetros
                success: function(response) {
                    // Verifica si hay detalles en la respuesta
                    if (response.detalle && response.detalle.EstadoNombreAsignado && response.detalle.EstadoFechaAsignado) {
                        var detalle = response.detalle;

                        function formatDateTime(dateTimeStr) {
                            var date = new Date(dateTimeStr);

                            var day = ('0' + date.getDate()).slice(-2);
                            var month = ('0' + (date.getMonth() + 1)).slice(-2);
                            var year = date.getFullYear();

                            var hours = date.getHours();
                            var minutes = ('0' + date.getMinutes()).slice(-2);
                            var seconds = ('0' + date.getSeconds()).slice(-2);

                            var ampm = hours >= 12 ? 'PM' : 'AM';
                            hours = hours % 12;
                            hours = hours ? hours : 12; // El '0' se reemplaza por '12'

                            var formattedDate = day + '/' + month + '/' + year;
                            var formattedTime = ('0' + hours).slice(-2) + ':' + minutes + ':' + seconds + ' ' + ampm;

                            return formattedDate + ' ' + formattedTime;
                        }

                        var content = '<p>Nombre Asignado: ' + detalle.EstadoNombreAsignado + '</p>';
                        content += '<p>Fecha Asignado: ' + formatDateTime(detalle.EstadoFechaAsignado) + '</p>';

                        $('#detallesAsignadoMovimientos').html(content);
                    } else {
                        $('#detallesAsignadoMovimientos').text('En proceso de Asignar...');
                    }
                },
                error: function() {
                    $('#detallesAsignadoMovimientos').text('Error al obtener los detalles del equipo.');
                }
            });
        } else {
            $('#detallesAsignadoMovimientos').text('No se pudo obtener el ID del equipo o del movimiento.');
        }
    });
});


/* $(document).ready(function() {
    $('#DetallesReparacionModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var equipoId = button.data('equipo-id'); // Extrae el id del equipo
        // Realiza la petición AJAX
        $.ajax({
            url: '/detallereparacionestado/' + equipoId, // Solo el ID del equipo
            type: 'GET',
            success: function(response) {
                // Verifica si hay detalles en la respuesta
                if (response.detalle && response.detalle.EstadoNombreReparado && response.detalle.EstadoFechaReparado) {
                    var detalle = response.detalle;

                    // Función para formatear la fecha y hora
                    function formatDateTime(dateTimeStr) {
                        var date = new Date(dateTimeStr);

                        var day = ('0' + date.getDate()).slice(-2);
                        var month = ('0' + (date.getMonth() + 1)).slice(-2);
                        var year = date.getFullYear();

                        var hours = date.getHours();
                        var minutes = ('0' + date.getMinutes()).slice(-2);
                        var seconds = ('0' + date.getSeconds()).slice(-2);

                        var ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12;

                        var formattedDate = day + '/' + month + '/' + year;
                        var formattedTime = ('0' + hours).slice(-2) + ':' + minutes + ':' + seconds + ' ' + ampm;

                        return formattedDate + ' ' + formattedTime;
                    }

                    // Llenar el contenido del modal con los detalles
                    var content = '<p>Nombre Reparado: ' + detalle.EstadoNombreReparado + '</p>';
                    content += '<p>Fecha Reparado: ' + formatDateTime(detalle.EstadoFechaReparado) + '</p>';

                    $('#detallesReparacionMovimientos').html(content);
                } else {
                    $('#detallesReparacionMovimientos').text('En proceso de asignar reparación... ');
                }
            },
            error: function() {
                $('#detallesReparacionMovimientos').text('Error al obtener los detalles del equipo.');
            }
        });
    });
});
 */

$(document).ready(function() {
    $('#DetallesReparacionModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var equipoId = button.data('equipo-id'); // Extrae el id del equipo
        var movimientoId = $('#DetallesReparacionModal input[name="idM"]').val(); // Extrae el id del movimiento
        /* console.log('Equipo ID:', equipoId, 'Movimiento ID:', movimientoId); */

        // Realiza la petición AJAX
        $.ajax({
            url: '/detallereparacionmovimientoEstado', // La URL sin parámetros
            type: 'GET',
            data: { equipoId: equipoId, movimientoId: movimientoId }, // Envía ambos valores como parámetros
            success: function(response) {
                // Verifica si hay detalles en la respuesta
                if (response.detalle && response.detalle.EstadoNombreReparado && response.detalle.EstadoFechaReparado) {
                    var detalle = response.detalle;

                    // Función para formatear la fecha y hora
                    function formatDateTime(dateTimeStr) {
                        var date = new Date(dateTimeStr);

                        var day = ('0' + date.getDate()).slice(-2);
                        var month = ('0' + (date.getMonth() + 1)).slice(-2);
                        var year = date.getFullYear();

                        var hours = date.getHours();
                        var minutes = ('0' + date.getMinutes()).slice(-2);
                        var seconds = ('0' + date.getSeconds()).slice(-2);

                        var ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12; // El '0' se reemplaza por '12'

                        var formattedDate = day + '/' + month + '/' + year;
                        var formattedTime = ('0' + hours).slice(-2) + ':' + minutes + ':' + seconds + ' ' + ampm;

                        return formattedDate + ' ' + formattedTime;
                    }

                    // Llenar el contenido del modal con los detalles
                    var content = '<p>Nombre Reparado: ' + detalle.EstadoNombreReparado + '</p>';
                    content += '<p>Fecha Reparado: ' + formatDateTime(detalle.EstadoFechaReparado) + '</p>';

                    $('#detallesReparacionMovimientos').html(content);
                } else {
                    $('#detallesReparacionMovimientos').text('En proceso de asignar reparación... ');
                }
            },
            error: function() {
                $('#detallesReparacionMovimientos').text('Error al obtener los detalles del equipo.');
            }
        });
    });
});

/* $(document).ready(function() {
    $('#DetallesPruebaModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var equipoId = button.data('equipo-id'); // Extrae el id del equipo

        // Realiza la petición AJAX
        $.ajax({
            url: '/detallepruebaestado/' + equipoId, // Solo el ID del equipo
            type: 'GET',
            success: function(response) {
                // Verifica si hay detalles en la respuesta
                if (response.detalle && response.detalle.EstadoNombrePrueba && response.detalle.EstadoFechaPrueba) {
                    var detalle = response.detalle;

                    // Función para formatear la fecha y hora
                    function formatDateTime(dateTimeStr) {
                        var date = new Date(dateTimeStr);

                        var day = ('0' + date.getDate()).slice(-2);
                        var month = ('0' + (date.getMonth() + 1)).slice(-2);
                        var year = date.getFullYear();

                        var hours = date.getHours();
                        var minutes = ('0' + date.getMinutes()).slice(-2);
                        var seconds = ('0' + date.getSeconds()).slice(-2);

                        var ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12;

                        var formattedDate = day + '/' + month + '/' + year;
                        var formattedTime = ('0' + hours).slice(-2) + ':' + minutes + ':' + seconds + ' ' + ampm;

                        return formattedDate + ' ' + formattedTime;
                    }

                    // Llenar el contenido del modal con los detalles
                    var content = '<p>Nombre Prueba: ' + detalle.EstadoNombrePrueba + '</p>';
                    content += '<p>Fecha Prueba: ' + formatDateTime(detalle.EstadoFechaPrueba) + '</p>';

                    $('#detallesPruebaMovimientos').html(content);
                } else {
                    $('#detallesPruebaMovimientos').text('En proceso de asignar prueba...');
                }
            },
            error: function() {
                $('#detallesPruebaMovimientos').text('Error al obtener los detalles del equipo.');
            }
        });
    });
}); */

$(document).ready(function() {
    $('#DetallesPruebaModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var equipoId = button.data('equipo-id'); // Extrae el id del equipo
        var movimientoId = $('#DetallesPruebaModal input[name="idM"]').val(); // Extrae el id del movimiento
       /*  console.log('Equipo ID:', equipoId, 'Movimiento ID:', movimientoId); */

        // Realiza la petición AJAX
        $.ajax({
            url: '/detallepruebamovimientoEstado', // URL para enviar los datos
            type: 'GET',
            data: { equipoId: equipoId, movimientoId: movimientoId }, // Envía ambos valores como parámetros
            success: function(response) {
                // Verifica si hay detalles en la respuesta
                if (response.detalle && response.detalle.EstadoNombrePrueba && response.detalle.EstadoFechaPrueba) {
                    var detalle = response.detalle;

                    // Función para formatear la fecha y hora
                    function formatDateTime(dateTimeStr) {
                        var date = new Date(dateTimeStr);

                        var day = ('0' + date.getDate()).slice(-2);
                        var month = ('0' + (date.getMonth() + 1)).slice(-2);
                        var year = date.getFullYear();

                        var hours = date.getHours();
                        var minutes = ('0' + date.getMinutes()).slice(-2);
                        var seconds = ('0' + date.getSeconds()).slice(-2);

                        var ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12;

                        var formattedDate = day + '/' + month + '/' + year;
                        var formattedTime = ('0' + hours).slice(-2) + ':' + minutes + ':' + seconds + ' ' + ampm;

                        return formattedDate + ' ' + formattedTime;
                    }

                    // Llenar el contenido del modal con los detalles
                    var content = '<p>Nombre Prueba: ' + detalle.EstadoNombrePrueba + '</p>';
                    content += '<p>Fecha Prueba: ' + formatDateTime(detalle.EstadoFechaPrueba) + '</p>';

                    $('#detallesPruebaMovimientos').html(content);
                } else {
                    $('#detallesPruebaMovimientos').text('En proceso de asignar prueba...');
                }
            },
            error: function() {
                $('#detallesPruebaMovimientos').text('Error al obtener los detalles del equipo.');
            }
        });
    });
});


//Permite asginarle el id del equipo 
$(document).on('click', '.btn-salida-individual', function(event) {
    event.preventDefault();

    const equipoId = $(this).val();

    if (equipoId === null) {
        /* console.error('ID del equipo no encontrado.'); */
        return; // Salir si no se encuentra el ID del equipo
    }

    // Asignar el ID del equipo al campo oculto en el modal
    $("#id_equipoSalidaaSuc").val(equipoId);

    // Mostrar el modal correspondiente
    $('#EquipoSalidaIndividualModal').modal('show');

});

//Permite asginarle el id del equipo 
$(document).on('click', '.btn-salida-individualSucursal', function(event) {
    event.preventDefault();

    const equipoId = $(this).val();

    if (equipoId === null) {
        console.error('ID del equipo no encontrado.');
        return; // Salir si no se encuentra el ID del equipo
    }

    // Asignar el ID del equipo al campo oculto en el modal
    $("#id_equipoSalida").val(equipoId);

    // Mostrar el modal correspondiente
    $('#EquipoSalidaIndividualASucursalModal').modal('show');

});

//permite asignarle el id del equipo al dar click en el boton
$(document).on('click', '.btn-salida-individualSucursal', function(event) {
    event.preventDefault();

    // Obtener el ID del equipo desde el atributo data-equipo-id
    const equipoId = $(this).data('equipo-id');

    // Mostrar el ID del equipo en la consola
    /* console.log('ID del Equipo asignado al botón:', equipoId); */
    // El resto de tu código para manejar el clic...
});

$(document).ready(function() {
    $('#EquipoSalidaIndividualDetalleModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var equipoId = button.data('equipo-id'); // Extrae el id del equipo
        var movimientoId = button.data('movimiento-id'); // Extrae el id del movimiento

        // Realiza la petición AJAX
        $.ajax({
            url: '/detalleSalidaEstado', // URL de la petición
            type: 'GET',
            data: {
                equipo_id: equipoId,
                movimiento_id: movimientoId
            },
            success: function(response) {
                // Verifica si la respuesta tiene datos o está vacía
                if (response.detalle && Object.keys(response.detalle).length > 0) {
                    var detalle = response.detalle;

                    // Llenar el contenido del modal con los detalles si existen
                    var content = '<p>Nombre Emisor: ' + (detalle.nombre_emisor_salida || 'Sin información') + '</p>';
                    content += '<p>Nombre Receptor: ' + (detalle.nombre_receptor_salida || 'Sin información') + '</p>';
                    content += '<p>Nombre Motorista: ' + (detalle.nombre_motorista_salida || 'Sin información') + '</p>';
                    content += '<p>Motivo Salida: ' + (detalle.motivo_salida || 'Sin información') + '</p>';
                    content += '<p>Estado Actual: ' + (detalle.estado_actual_salida || 'Sin información') + '</p>';

                    $('#detallesSalidaMovimientos').html(content);
                } else {
                    // Si no hay detalles, mostrar un mensaje apropiado
                    $('#detallesSalidaMovimientos').text('No se encontraron detalles de salida para este equipo.');
                }
            },
            error: function() {
                // Manejar errores de la petición AJAX
                $('#detallesSalidaMovimientos').text('Error al obtener los detalles del equipo.');
            }
        });
    });
});


/* Al dar click al boton de aceptar salida, se envia el id del equipo y movimiento, para que el control verifique 
que existen ambos id y poder actualizar unicamente el estado*/
function confirmarSalida(equipoId, movimientoId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas aceptar la salida para este equipo?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, realiza la solicitud AJAX
            $.ajax({
                url: '/update-estadosalidaasuc',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    idM: movimientoId,
                    id_equipo: equipoId
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Éxito!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Aquí puedes manejar lo que sucede después de que el usuario confirma el mensaje
                            // Por ejemplo, puedes cerrar el modal o actualizar la vista
                            /* location.reload(); // Recarga la página para ver los cambios */
                        }
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseText,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }
    });
}

