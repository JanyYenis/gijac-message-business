"use strict";

const kt_api_keys_table = "#kt_api_keys_table";
const tablaApiKeyLog = "#tablaApiKeyLog";

$(function () {
    iniciarComponentes();
});

const iniciarComponentes = (form = '') => {
}

/**
 * Función que permite cargar el listado.
 */
window.listadoApiKeys = (id = 0) => {
    if ($.fn.DataTable.isDataTable('#kt_api_keys_table')) {
        $('#kt_api_keys_table').DataTable().destroy();
    }

    var table = $("#kt_api_keys_table").DataTable({
        paging: true,
        responsive: true,
        serverSide: true,
        scrollX: true,
        searchDelay: 500,
        
        ajax: {
            "url": route("api-keys.listado"),
            "type": "GET",                  
            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(kt_api_keys_table);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(kt_api_keys_table);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-download"></i> Excel',
                className: "btn btn-primary",
                title: "Listado API Key.",
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
                data: 'etiqueta',
                name: 'etiqueta',
                render: function (data, type, full, meta) {
                    return full?.etiqueta ?? 'N/A';
                }
            },
            {
                data: 'key',
                name: 'key',
                render: function (data, type, full, meta) {
                    return full?.key ?? 'N/A';
                }
            },
            {
                data: 'created_at',
                name: 'created_at',
                render: function (data, type, full, meta) {
                    return full?.created_at ?? 'N/A';
                }
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

/**
 * Función que permite cargar el listado.
 */
window.listadoApiKeysLogs = (id = 0) => {
    if ($.fn.DataTable.isDataTable('#tablaApiKeyLog')) {
        $('#tablaApiKeyLog').DataTable().destroy();
    }

    var table = $("#tablaApiKeyLog").DataTable({
        paging: true,
        responsive: true,
        serverSide: true,
        scrollX: true,
        searchDelay: 500,
        
        ajax: {
            "url": route("api-keys.listado-log"),
            "type": "GET",                  
            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(tablaApiKeyLog);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaApiKeyLog);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-download"></i> Excel',
                className: "btn btn-primary",
                title: "Listado Logs de las API Keys.",
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
                data: 'location',
                name: 'location',
                render: function (data, type, full, meta) {
                    return full?.location ?? 'N/A';
                }
            },
            {
                data: 'ip_address',
                name: 'ip_address',
                render: function (data, type, full, meta) {
                    return full?.ip_address ?? 'N/A';
                }
            },
            {
                data: 'user_agent',
                name: 'user_agent',
                render: function (data, type, full, meta) {
                    return full?.user_agent ?? 'N/A';
                }
            },
            {
                data: 'fecha',
                name: 'fecha',
                render: function (data, type, full, meta) {
                    return full?.fecha ?? 'N/A';
                }
            },
            {
                data: 'estado',
                name: 'estado',
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
