"use strict";

const tablaContactos = "#tablaContactos";
const rutaCargarListadoContactos = route("contactos.listado");

$(function () {
    listadoContactos();
});

/**
 * Función que permite cargar el listado.
 */
window.listadoContactos = () => {
    var table = $("#tablaContactos").DataTable({
        paging: true,
        responsive: true,
        serverSide: true,
        scrollX: true,
        searchDelay: 500,

        ajax: {
            "url": rutaCargarListadoContactos,
            "type": "GET",

            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(tablaContactos);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaContactos);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-download"></i> Excel',
                className: "btn btn-light-success",
                title: "Listado Contactos.",
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
                data: 'nombre_completo_select',
                name: 'nombre_completo_select',
                render: function(data, type, full, meta) {
                    return full?.nombre_completo_select ?? 'N/A';
                },
            },
            {
                data: 'numero_completo_select',
                name: 'numero_completo_select',
                render: function(data, type, full, meta) {
                    return full?.numero_completo_select ?? 'N/A';
                },
            },
            {
                data: 'genero',
                name: 'genero',
                render: function(data, type, full, meta) {
                    return full?.genero ?? 'N/A';
                },
            },
            {
                data: 'tratamiento_datos',
                name: 'tratamiento_datos',
                render: function(data, type, full, meta) {
                    return full?.tratamiento_datos ? 'SI' : 'NO';
                },
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
