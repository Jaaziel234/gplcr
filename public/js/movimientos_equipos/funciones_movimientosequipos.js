/* alert("mensaje desde funciones moviemientos equipos"); */

/* Datatables */
$(document).ready(function() {
            $('#miTabla').DataTable({
                "dom": 't', // This option only shows the table (t) and hides everything else
                "paging": false, // Disable pagination
                "info": false, // Disable the information text
                "searching": false // Disable the search box
            });
});

/* Convertir a mayuscula automaticamente */
function mayus(element) {
        element.value = element.value.toUpperCase();
}

/* Inicializar selectize */
$(document).ready(function() {
    $('#miSelect').selectize({
            
    });
});

// Variable global para almacenar los equipos seleccionados
var equiposSeleccionados = [];

// Función para agregar equipo seleccionado
function agregarEquipo() {
    var select = document.getElementById("miSelect");
    var equipoId = select.value;

    // Verificar si no se ha seleccionado ningún equipo
    if (!equipoId) {
        Swal.fire({
            icon: 'warning',
            title: 'Ningún equipo seleccionado',
            text: 'Por favor, selecciona un equipo antes de agregar.',
        });
        return;
    }

    var equipos = JSON.parse(document.getElementById('equipos-data').textContent);

    var equipoSeleccionado = equipos.find(equipo => equipo.id == equipoId);
    if (equipoSeleccionado && !equiposSeleccionados.some(e => e.id == equipoSeleccionado.id)) {
        if (equiposSeleccionados.length < 5) {
            equiposSeleccionados.push(equipoSeleccionado);
            generarTabla();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Límite de equipos alcanzado',
                text: 'Solo puedes seleccionar hasta 5 equipos.',
            });
        }
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Equipo ya seleccionado',
            text: 'Este equipo ya ha sido agregado.',
        });
    }

    select.value = "";
    verificarEquiposSeleccionados(); // Verificar equipos seleccionados después de agregar
}

// Función para generar la tabla
function generarTabla() {
    if (equiposSeleccionados.length === 0) {
        document.getElementById("tabla").innerHTML = "";
        verificarEquiposSeleccionados(); // Verificar equipos seleccionados después de generar la tabla
        return;
    }

    var tabla = `
    <div class="table-responsive">
        <table id="miTabla" class="table table-striped table-bordered" style="width:100%">
            <thead style="background-color:#6777ef;">
                <tr>
                    <th style="color:#fff;">#</th>
                    <th style="color:#fff;">Escaneo</th>
                    <th style="color:#fff;">Equipo</th>
                    <th style="color:#fff;">Modelo</th>
                    <th style="color:#fff;">Serie</th>
                    <th style="color:#fff;">Creado</th>
                    <th style="color:#fff;">Actualizado</th>
                    <th style="color:#fff;">Acciones</th>
                </tr>
            </thead>
            <tbody>
    <div>
    `;

    equiposSeleccionados.forEach((equipo, index) => {
        tabla += `
            <tr>
                <td>${index + 1}</td>
                <td>${equipo.escaneo}</td>
                <td>${equipo.equipo}</td>
                <td>${equipo.modelo}</td>
                <td>${equipo.serie}</td>
                <td>${new Date(equipo.created_at).toLocaleString('es-ES', {
                     year: 'numeric', month: '2-digit', day: '2-digit',
                     hour: 'numeric', minute: 'numeric', second: 'numeric',
                     hour12: true })}
                </td>
                <td>${new Date(equipo.created_at).toLocaleString('es-ES', {
                     year: 'numeric', month: '2-digit', day: '2-digit',
                     hour: 'numeric', minute: 'numeric', second: 'numeric',
                     hour12: true })}
                </td>
                
                <td>
                  <div class="d-flex justify-content-around">
                    <button type="button" class="btn btn-info my-3" onclick="verEquipo(${equipo.id})">Ver</button>
                    <button type="button" class="btn btn-danger my-3" onclick="eliminarEquipo(${equipo.id})">Quitar</button>                
                  <div>
                </td>
            </tr>
        `;
    });

    tabla += `
            </tbody>
        </table>
    `;

    document.getElementById("tabla").innerHTML = tabla;

    // Eliminar inputs ocultos previos
    var form = document.getElementById("movimientoForm");
    var inputsOcultos = document.querySelectorAll("input[name='id_equipo[]']");
    inputsOcultos.forEach(input => input.remove());

    // Agregar los equipos seleccionados al formulario
    equiposSeleccionados.forEach(equipo => {
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "id_equipo[]";
        input.value = equipo.id;
        form.appendChild(input);
    });

    verificarEquiposSeleccionados(); // Verificar equipos seleccionados después de generar la tabla
}

//Funcion para mandar a llamar la informacion de los equipos.
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

// Función para eliminar un equipo de la tabla
function eliminarEquipo(id) {
    equiposSeleccionados = equiposSeleccionados.filter(equipo => equipo.id !== id);
    generarTabla();
    verificarEquiposSeleccionados(); // Verificar equipos seleccionados después de eliminar

    // Limpiar el selectize después de eliminar el equipo
    limpiarSelectize();
}

//Limpiar el select de equipo
function limpiarSelectize() {
    var selectize = $('#miSelect')[0].selectize;
    selectize.clear();
}

// Función para verificar si hay equipos seleccionados y mostrar/ocultar el botón "Enviar"
function verificarEquiposSeleccionados() {
    var submitButton = document.getElementById("submitButton");
    if (equiposSeleccionados.length === 0) {
        submitButton.style.display = "none";
    } else {
        submitButton.style.display = "block";
    }
}

// Inicialización para ocultar el botón "Enviar" al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    verificarEquiposSeleccionados();
});

//Barra de progreso para completar el campo MOTIVO
function updateProgressBar() {
    const motivo = document.getElementById('motivo');
    const progressBar = document.getElementById('progress-bar');
    const maxLength = 191;
    const warningLimit = 10;
    const currentLength = motivo.value.length;
    const percentage = (currentLength / maxLength) * 100;

    progressBar.style.width = percentage + '%';
    progressBar.setAttribute('aria-valuenow', currentLength);
    progressBar.textContent = currentLength + ' / ' + maxLength;

    if (maxLength - currentLength <= warningLimit) {
        progressBar.classList.add('bg-danger');
    } else {
        progressBar.classList.remove('bg-danger');
    }

    if (currentLength >= maxLength) {
        motivo.value = motivo.value.substring(0, maxLength);
        Swal.fire({
            icon: 'warning',
            title: 'Límite de caracteres alcanzado',
            text: 'No se puede agregar más información.',
            showConfirmButton: true,
           
        });
    }  
document.getElementById('motivo').addEventListener('input', updateProgressBar);
}











