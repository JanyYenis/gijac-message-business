"use strict";

const tablaFacturas = "#tablaFacturas";

$(function () {
    iniciarComponentes();
});

const iniciarComponentes = (form = '') => {
}

$(document).on('click', '.btnTabFacturas', function() {
    window.listadoFacturas();
});

/**
 * Función que permite cargar el listado.
 */
window.listadoFacturas = () => {
    if ($.fn.DataTable.isDataTable('#tablaFacturas')) {
        $('#tablaFacturas').DataTable().destroy();
    }

    var table = $("#tablaFacturas").DataTable({
        paging: true,
        responsive: true,
        serverSide: false,
        scrollX: true,
        searchDelay: 500,

        ajax: {
            "url": route("facturas.listado"),
            "type": "GET",
            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(tablaFacturas);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaFacturas);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-download"></i> Excel',
                className: "btn btn-light-primary",
                title: "Listado Facturas.",
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
                data: 'invoice',
                name: 'invoice',
                render: function (data, type, full, meta) {
                    return full?.invoice ?? 'N/A';
                }
            },
            {
                data: 'x_ref_payco',
                name: 'x_ref_payco',
                render: function (data, type, full, meta) {
                    return full?.x_ref_payco ?? 'N/A';
                }
            },
            {
                data: 'plan.nombre',
                name: 'plan.nombre',
                render: function (data, type, full, meta) {
                    return full?.plan?.nombre ?? 'N/A';
                }
            },
            {
                data: 'bank',
                name: 'bank',
                render: function (data, type, full, meta) {
                    return full?.bank ?? 'N/A';
                }
            },
            {
                data: 'x_franchise',
                name: 'x_franchise',
                render: function (data, type, full, meta) {
                    return full?.x_franchise ?? 'N/A';
                }
            },
            {
                data: 'value',
                name: 'value',
                render: function (data, type, full, meta) {
                    return full?.value ?? 0;
                }
            },
            {
                data: 'x_response',
                name: 'x_response',
                render: function (data, type, full, meta) {
                    return full?.x_response ?? 'N/A';
                }
            },
            {
                data: 'tiempo',
                name: 'tiempo',
                render: function (data, type, full, meta) {
                    let mes = 'mes';
                    if (full?.tiempo && full?.tiempo > 1) {
                        mes = 'meses';
                    }
                    return full?.tiempo ? full?.tiempo+' '+mes : '0 meses';
                }
            },
            {
                data: 'fecha_vencimiento',
                name: 'fecha_vencimiento',
                render: function (data, type, full, meta) {
                    return full?.fecha_vencimiento ?? 'N/A';
                }
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
        dom: `<'row d-flex align-items-center justify-content-between'
                <'col-md-6 col-sm-6 col-lg-6 d-flex align-items-center justify-content-start'<''l><'w-100'f>>
                <'col-md-6 col-sm-6 col-lg-6 d-flex align-items-center justify-content-end' <''B>>
                >
            <'table-responsive'tr>
            <'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>`,
        initComplete: function () {},
        drawCallback: function(settings) {
            KTMenu.createInstances();
        }
    });
}

