"use strict";

const tablaEjecicionN8n = '#tablaEjecicionN8n';
const rutaCargarListadoEjecucionesN8n = route('chatbots.n8n.ejecuciones.listado', { automatizacion: window.automatizacion});

$(function () {
    listadoEjecucionesN8n();
});

window.listadoEjecucionesN8n = () => {
    var table = $(tablaEjecicionN8n).DataTable({
        paging: true,
        responsive: true,
        serverSide: true,
        scrollX: true,
        searchDelay: 500,

        ajax: {
            "url": rutaCargarListadoEjecucionesN8n,
            "type": "GET",

            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(tablaEjecicionN8n);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaEjecicionN8n);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-download"></i> Excel',
                className: "btn btn-light-success",
                title: "Listado Ejecuciones.",
                exportOptions: {
                    columns: [0,1,2,3,4,5,6],
                    format: {
                        body: function (data, row, column, node) {
                            // eliminar HTML
                            let text = $('<div>').html(data).text();
                            return text.trim();
                        }
                    }
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
                data: 'fecha',
                name: 'fecha',
                render: function(data, type, full, meta) {
                    return full?.fecha ?? 'N/A';
                },
            },
            {
                data: 'cliente',
                name: 'cliente',
                render: function(data, type, full, meta) {
                    return full?.cliente ?? 'N/A';
                },
            },
            {
                data: 'evento',
                name: 'evento',
                render: function(data, type, full, meta) {
                    return full?.evento ?? 'N/A';
                },
            },
            {
                data: 'estado',
                name: 'estado',
            },
            {
                data: 'duracion',
                name: 'duracion',
                render: function(data, type, full, meta) {
                    return full?.duracion ?? 'N/A';
                },
            },
            {
                data: 'respuesta',
                name: 'respuesta',
                render: function(data, type, full, meta) {
                    return full?.respuesta ?? 'N/A';
                },
            },
        ],
        order: [
            [1, "asc"]
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
