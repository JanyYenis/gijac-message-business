"use strict";

const tablaCampanas = "#tablaCampanas";
const rutaCargarListadoCampana = route("campanas.listado");

$(function () {
    listadoCampana();
});

/**
 * Función que permite cargar el listado.
 */
window.listadoCampana = () => {
    var table = $("#tablaCampanas").DataTable({
        paging: true,
        responsive: true,
        serverSide: true,
        scrollX: true,
        searchDelay: 500,

        ajax: {
            "url": rutaCargarListadoCampana,
            "type": "GET",

            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(tablaCampanas);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaCampanas);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-download"></i> Excel',
                className: "btn btn-light-success",
                title: "Listado Campañas.",
                exportOptions: {
                    columns: ":not(.excluir)"
                }
            },
            {
                text: '<i class="fa fa-sync-alt"></i> Actualizar',
                className: "btn btn-bg-secondary",
                action: function (e, dt, node, config) {
                    dt.ajax.reload(null, false);
                }
            }
        ],
        columnDefs: [
            {
                targets: "all",
                className: "text-center"
            },
            {
                targets: "none",
                className: "text-justify"
            }
        ],
        columns: [
            {
                render: function (data, type, full, meta) {
                    return meta.row + 1;
                }
            },
            {
                data: 'nombre',
                name: 'nombre'
            },
            {
                data: 'descripcion',
                name: 'descripcion',
                render: function(data, type, full, meta) {
                    return full?.descripcion ?? 'N/A';
                }
            },
            {
                data: 'nombre_gerente',
                name: 'nombre_gerente',
                render: function(data, type, full, meta) {
                    return full?.nombre_gerente ?? 'N/A';
                }
            },
            {
                data: 'estado',
                name: 'estado',
            },
            {
                data: 'fecha_envio',
                name: 'fecha_envio'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'id_plantilla',
                name: 'id_plantilla'
            },
            {
                data: 'tipo',
                name: 'tipo'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ],
        order: [
            [0, "asc"]
        ],
        lengthMenu: [
            [15, 20, 50, 100, -1],
            [15, 20, 50, 100, "Todos"]
        ],
        pageLength: 15,
        dom: `<'row d-flex align-items-center justify-content-end'
                <'d-flex align-items-center justify-content-end'B>><'row d-flex align-items-center justify-content-between'<'col-sm-6 col-lg-6 col-md-6'l><'col-sm-6 col-lg-6 col-md-6'f>>
            <'table-responsive'tr>
            <'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>`,
        drawCallback: function(settings) {
            KTMenu.createInstances();
        }
    });
}
