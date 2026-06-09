"use strict";

const tablaDetalleCampanas = "#tablaDetalleCampanas";
const tablaDetalleLinksCampanas = "#tablaDetalleLinksCampanas";
const rutaCargarListadoDetalleCampana = "campanas.listado-detalle";
const rutaCargarListadoDetalleLinksCampana = "campanas.ver-links";

$(function () {
    // listadoDetalleCampana(window.idCampana);
});

/**
 * Función que permite cargar el listado.
 */
window.listadoDetalleCampana = (id) => {
    var table = $("#tablaDetalleCampanas").DataTable({
        paging: true,
        responsive: true,
        serverSide: false,
        scrollX: true,
        searchDelay: 500,

        ajax: {
            "url": route(rutaCargarListadoDetalleCampana, {campana: id}),
            "type": "GET",

            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(tablaDetalleCampanas);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaDetalleCampanas);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-download"></i> Excel',
                className: "btn btn-light-success",
                title: "Listado Detalle Campaña.",
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
                data: 'contacto.nombre_completo',
                name: 'contacto.nombre_completo',
            },
            {
                data: 'campana.etiqueta.nombre',
                name: 'campana.etiqueta.nombre',
                render: function (data, type, full, meta) {
                    return full.campana?.etiqueta?.nombre ?? 'N/A';
                }
            },
            {
                data: 'abierto',
                name: 'abierto',
                render: function (data, type, full, meta) {
                    let icono = '<i class="fas fa-check fs-1"></i>';
                    if (full.mensaje?.estado) {
                        if (full.mensaje?.estado == 3) {
                            icono = '<i class="fas fa-check-double text-primary fs-1"></i>';
                        } else if (full.mensaje?.estado == 1) {
                            icono = '<i class="fas fa-check fs-1"></i>';
                        } else if (full.mensaje?.estado == 2) {
                            icono = '<i class="fas fa-check-double fs-1"></i>';
                        } else {
                            icono = '<i class="fas fa-times text-danger fs-1"></i>';
                        }
                    }
                    return icono;
                }
            },
            {
                data: 'click_links',
                name: 'click_links',
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

$(document).on('click', '.btnVerFormulario', function() {
    let id = $(this).attr('data-mensaje');
    generalidades.refrescarSeccion(null, route('campanas.ver-formulario', {wamid: id}), '.seccionRespuesta', function(){
        generalidades.modalActual('#modalVerRespuestaFormulario');
    });
});

$(document).on('click', '.btnVerError', function() {
    let id = $(this).attr('data-mensaje');
    generalidades.refrescarSeccion(null, route('campanas.ver-errores', {wamid: id}), '.seccionVerErrores', function(){
        generalidades.modalActual('#modalVerErrores');
    });
});

$(document).on('click', '.btnVerLinks', function() {
    let id = $(this).attr('data-mensaje');
    generalidades.modalActual('#modalLinksCampana');
    window.listadoDetalleLinksCampana(id);
});

window.listadoDetalleLinksCampana = (id) => {
    var table = $("#tablaDetalleLinksCampanas").DataTable({
        paging: true,
        responsive: true,
        serverSide: false,
        scrollX: true,
        searchDelay: 500,

        ajax: {
            "url": route(rutaCargarListadoDetalleLinksCampana, {id: id}),
            "type": "GET",

            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(tablaDetalleLinksCampanas);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaDetalleLinksCampanas);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-download"></i> Excel',
                className: "btn btn-light-success",
                title: "Listado Detalle Links Campaña.",
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
                data: 'variable_campana.nombre',
                name: 'variable_campana.nombre',
            },
            {
                data: 'variable_campana.valor',
                name: 'variable_campana.valor'
            },
            {
                data: 'click',
                name: 'click',
                render: function (data, type, full, meta) {
                    return full.click ? 'X' : 'N/A' ;
                }
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
