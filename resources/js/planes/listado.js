"use strict";

const tablaPlanes = "#tablaPlanes";
const rutaCargarListadoPlanes = route("planes.listado");

$(function () {
    listadoPlanes();
});

/**
 * Función que permite cargar el listado.
 */
window.listadoPlanes = () => {
    if ($.fn.DataTable.isDataTable('#tablaPlanes')) {
        $('#tablaPlanes').DataTable().destroy();
    }

    var table = $("#tablaPlanes").DataTable({
        paging: true,
        responsive: true,
        serverSide: false,
        scrollX: true,
        searchDelay: 500,

        ajax: {
            "url": rutaCargarListadoPlanes,
            "type": "GET",
            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(tablaPlanes);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaPlanes);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-download"></i> Excel',
                className: "btn btn-light-success",
                title: "Listado Planes.",
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
                data: 'valor',
                name: 'valor',
                render: function (data, type, full, meta) {
                    return full?.valor ? '<span class="fw-bold text-primary">$'+full?.valor.toLocaleString('en-US')+'</span>' : '$0';
                }
            },
            {
                data: 'info_tipo.nombre',
                name: 'info_tipo.nombre',
                render: function (data, type, full, meta) {
                    return full?.info_tipo?.nombre ?? 'N/A';
                }
            },
            {
                data: 'info_categoria.nombre',
                name: 'info_categoria.nombre',
                render: function (data, type, full, meta) {
                    return full?.info_categoria?.nombre ?? 'N/A';
                }
            },
            {
                data: 'max_contactos',
                name: 'max_contactos',
                render: function (data, type, full, meta) {
                    return full?.max_contactos ? full?.max_contactos.toLocaleString('en-US') : 0;
                }
            },
            {
                data: 'servicios',
                name: 'servicios',
            },
            {
                data: 'created_at',
                name: 'created_at',
            },
            {
                data: 'estado',
                name: 'estado',
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
        initComplete: function () {},
        drawCallback: function(settings) {
            KTMenu.createInstances();
        }
    });
}
