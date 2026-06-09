"use strict";

const tablaAccesos = "#tablaAccesos";
const rutaCargarListadoAccesos = route("accesos.listado");

$(function () {
});

/**
 * Función que permite cargar el listado.
 */
const listadoAccesos = () => {
    var table = $("#tablaAccesos").DataTable({
        paging: true,
        responsive: true,
        processing: false,
        serverSide: false,
        autowidth: false,
        stateSave: true,
        scrollX: true,
        searchDelay: 500,
        fixedHeader: {
            header:true
        },
        ajax: {
            "url": rutaCargarListadoAccesos,
            "type": "GET",                  
            
            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(tablaAccesos);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaAccesos);
                return json.data
            },
        },
        buttons: [
            {
                text: '<i class="fa fa-sync-alt"></i> Actualizar',
                className: "btn btn-secondary",
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
                data: 'usuario.nombre_completo',
                name: 'usuario.nombre_completo',
                render: function (data, type, full, meta) {
                    return full?.usuario?.nombre_completo ?? 'N/A';
                }
            },
            {
                data: 'info_tipo.nombre',
                name: 'info_tipo.nombre',
                render: function (data, type, full, meta) {
                    return `<i class='${full.info_tipo.icono} fs-3 text-${full?.info_tipo.color}'></i>`;
                }
            },
            {
                data: 'localizacion',
                name: 'localizacion',
                render: function (data, type, full, meta) {
                    return full.localizacion ?? 'N/A';
                }
            },
            {
                data: 'dispositivo',
                name: 'dispositivo',
                render: function (data, type, full, meta) {
                    return (full.navegador ?? 'N/A')+' - '+(full.sistema ?? 'N/A');
                }
            },
            {
                data: 'ip',
                name: 'ip'
            },
            {
                data: 'info_estado.nombre',
                name: 'info_estado.nombre',
                render: function (data, type, full, meta) {
                    return `<span class="badge badge-light-${full?.info_estado.color} fs-7 fw-bold">${full?.info_estado?.nombre ?? 'N/A'}</span>`;
                }
            },
            {
                data: 'tiempo',
                name: 'tiempo'
            },
            {
                data: 'fecha_ingreso',
                name: 'fecha_ingreso'
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
        dom: `<'row'<'col-sm-6 col-lg-6 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-6 col-lg-6 col-md-6'<'row'<'col-sm-6 col-lg-6 col-md-6 d-flex justify-content-end'l><'col-sm-6 col-lg-6 col-md-6 d-flex justify-content-end'B>>>>
            <'table-responsive'tr>
            <'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>`,
        drawCallback: function(settings) {
            KTMenu.createInstances();
        }
    });
}

const initResumen = () => {
    const config = {
        'method': 'GET',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
    }

    const success = (response) => {
        if (response.estado == 'success') {
            $('#accesosDoceHoras').attr('data-kt-countup-value', response?.accesosDoceHoras ?? 0).removeClass('counted').removeAttr("data-kt-initialized");
            $('#accesosDoceHorasAdmins').attr('data-kt-countup-value', response?.accesosDoceHorasAdmins ?? 0).removeClass('counted').removeAttr("data-kt-initialized");
            $('#accesosDoceHorasFallidos').attr('data-kt-countup-value', response?.accesosDoceHorasFallidos ?? 0).removeClass('counted').removeAttr("data-kt-initialized");
            $('#accesosHoy').attr('data-kt-countup-value', response?.accesosHoy ?? 0).removeClass('counted').removeAttr("data-kt-initialized");
            $('#accesosHoyAdmins').attr('data-kt-countup-value', response?.accesosHoyAdmins ?? 0).removeClass('counted').removeAttr("data-kt-initialized");
            $('#accesosHoyFallidos').attr('data-kt-countup-value', response?.accesosHoyFallidos ?? 0).removeClass('counted').removeAttr("data-kt-initialized");
        }
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    generalidades.get(route('accesos.resumen'), config, success, error);
}

Echo.join(`online`)
    .here((users) => {
        // Haz algo con los usuarios que ya están en línea.
        initResumen();
        listadoAccesos();
    });