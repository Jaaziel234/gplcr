@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Equipos</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex flex-wrap mb-3">
                            <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="escaneo" id="escaneo" placeholder="Ingresar Código de Escaneo..." class="form-control"">
                                    <button class=" btn btn-primary btn-sm">Filtrar por Escaneo</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="miTabla" class="table table-striped table-bordered table-reponsive" style="width:100%">
                                <thead style="background-color:#6777ef">
                                    <!--  <th style="color:#fff;">ID</th> -->
                                    <th style="color:#fff;">Escaneo</th>
                                    <th style="color:#fff;">Sucursal</th>
                                    <th style="color:#fff;">Equipo</th>
                                    <th style="color:#fff;">Modelo</th>
                                    <th style="color:#fff;">Serie</th>
                                    <!-- <th style="color:#fff;">Observaciones</th> -->
                                    <th style="color:#fff;">Creado</th>
                                    <th style="color:#fff;">Actualizado</th>
                                    <th style="color:#fff;">Acciones</th>
                                </thead>
                            </table>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('#escaneo').on('change', function() {
                                    var query = $(this).val().trim();

                                    // Make sure the search query is not empty before making the request
                                    if (query !== "") {
                                        $.ajax({
                                            url: "search",
                                            type: "GET",
                                            data: {
                                                search: query
                                            },
                                            dataType: 'json',
                                            success: function(data) {
                                                // Clear the existing content of the table
                                                $('#miTabla tbody').empty();

                                                // Recorrer los resultados de la búsqueda y actualizar las filas de la tabla
                                                $.each(data, function(index, item) {
                                                    var row = $('<tr></tr>');
                                                    row.append('<td>' + item.escaneo + '</td>');
                                                    row.append('<td>' + item.nombre_sucursal + '</td>'); // Mostrar nombre de la sucursal
                                                    row.append('<td>' + item.equipo + '</td>');
                                                    row.append('<td>' + item.modelo + '</td>');
                                                    row.append('<td>' + item.serie + '</td>');
                                                    row.append('<td>' + new Date(item.created_at).toLocaleString() + '</td>');
                                                    row.append('<td>' + new Date(item.updated_at).toLocaleString() + '</td>');
                                                    row.append(`
                                                            <td>
                                                                <form id="formulario-eliminar" action="/equipos/${item.id}" method="POST">
                                                                 @can('ver-equipo')
                                                                         <button type="button" class="btn btn-info my-2" onclick="verEquipo(${item.id})">Ver</button>
                                                                    @endcan
                                                                    @can('editar-equipo')
                                                                        <a class="btn btn-info my-2" href="/equipos/${item.id}/edit">Editar</a>
                                                                    @endcan
                                                                   
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    @can('borrar-equipo')
                                                                        <button type="submit" class="btn btn-danger" onclick="eliminarInventario(event)">Borrar</button>
                                                                    @endcan
                                                                </form>
                                                            </td>
                                                        `);

                                                    $('#miTabla tbody').append(row);
                                                });
                                            },
                                            error: function(jqXHR, textStatus, errorThrown) {
                                                // Manejar errores (opcional)
                                                /* console.error('Error al realizar la solicitud: ', textStatus, errorThrown); */
                                            }
                                        });
                                    }
                                });

                                // Event to handle keyup for clearing and reinitializing DataTable
                                $('#escaneo').on('keyup', function() {
                                    var query = $(this).val().trim();

                                    // Clear the table if the search query is empty and reinitialize DataTable
                                    if (query === "") {
                                        $('#miTabla tbody').empty();
                                        datos();
                                    }
                                });

                                // Initialize DataTable
                                function datos(query = '') {
                                    $('#miTabla').DataTable({
                                        "serverSide": true,
                                        "processing": true,
                                        "responsive": true,
                                        "paging": true,
                                        "searching": false, //quitamos el buscador de Datatables
                                        "lengthMenu": [
                                            [10, 25, 50, 100, -1],
                                            [10, 25, 50, 100, "All"]
                                        ],
                                        "pageLength": 10,
                                        "destroy": true, // Agregado para destruir la tabla antes de volver a inicializarla
                                        "ajax": {
                                            "type": "GET",
                                            "url": "/listado",
                                            "dataType": "json",
                                            "contentType": "application/json; charset=utf-8",
                                            "data": function(d) {
                                                // Aquí puedes agregar parámetros adicionales si los necesitas
                                                d.page = (d.start / d.length) + 1; // Calcular el número de página
                                                d.search = query; // Término de búsqueda
                                            }
                                        },
                                        "columns": [{
                                                "data": "escaneo"
                                            },
                                            {
                                                "data": "sucursal.nombre_sucursal",
                                                "render": function(data, type, full, meta) {
                                                    return data || 'Sin sucursal asignada';
                                                }
                                            },
                                            {
                                                "data": "modelo"
                                            },
                                            {
                                                "data": "equipo"
                                            },
                                            {
                                                "data": "serie"
                                            },
                                            {
                                                "data": "created_at",
                                                "render": function(data) {
                                                    return new Date(data).toLocaleString();
                                                }
                                            },
                                            {
                                                "data": "updated_at",
                                                "render": function(data) {
                                                    return new Date(data).toLocaleString();
                                                }
                                            },
                                            {
                                                "data": "id",
                                                "render": function(data) {
                                                    return `
                                                            <form id="formulario-eliminar" action="/equipos/${data}" method="POST">
                                                             @can('ver-equipo')
                                                                     <button type="button" class="btn btn-info my-2" onclick="verEquipo(${data})">Ver</button>
                                                                @endcan
                                                                @can('editar-equipo')
                                                                    <a class="btn btn-info my-2" href="/equipos/${data}/edit">Editar</a>
                                                                @endcan
                                                                @csrf
                                                                @method('DELETE')
                                                                @can('borrar-equipo')
                                                                    <button type="submit" class="btn btn-danger" onclick="eliminarInventario(event)">Borrar</button>
                                                                @endcan
                                                            </form>
                                                        `;
                                                }
                                            }
                                        ],
                                        "pageLength": 5, // Número de registros por página
                                        "lengthMenu": [
                                            [5, 25, 50],
                                            [5, 25, 50]
                                        ] // Opciones de paginación
                                    });
                                }

                                // Initialize DataTable on page load
                                datos();
                            });
                        </script>


                        @if(session('eliminar')=='ok')
                        <script>
                            Swal.fire({
                                title: "Eliminado!",
                                text: "Su registro ha sido eliminado",
                                icon: "success"
                            });
                        </script>
                        @endif
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Incluir el archivo de funciones JavaScript al final del archivo -->
<script src="{{ asset('js/equipos/funcionesequipo.js') }}"></script>
@endsection