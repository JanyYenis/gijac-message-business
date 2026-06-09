"use strict";

const tablaVerContactos = "#tablaVerContactos";

$(function () {
});


$(document).on('click', '.btnVer', function () {
    let id = $(this).attr('data-contacto');
    if (id) {
        listadoContactosVer(id);
        $('#modalVerContacto').modal('show');
    }
});

/**
 * Función que permite cargar el listado.
 */
window.listadoContactosVer = (id) => {
    var table = $("#tablaVerContactos").DataTable({
        paging: true,
        responsive: true,
        serverSide: false,
        scrollX: true,
        searchDelay: 500,
        ajax: {
            "url": route("contactos.show", { contacto: id }),
            "type": "GET",

            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(tablaVerContactos);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaVerContactos);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-download"></i> Excel',
                className: "btn btn-light-success",
                title: "Listado Etiquetas de contacto.",
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
                data: 'nombre_completo',
                name: 'nombre_completo'
            },
            {
                data: 'telefono',
                name: 'telefono',
            },
            {
                data: 'nombre_etiqueta',
                name: 'nombre_etiqueta'
            },
            {
                data: 'estado',
                name: 'estado',
                render: function (data, type, full, meta) {
                    let estado = '';
                    if (full?.estado == 1) {
                        estado = `<div class="text-center">
                        <span class="py-5 px-5 text-success">
                            <i class="fas fa-check-circle text-success"></i> Activo
                        </span>
                    </div>`;
                    } else {
                        estado = `<div class="text-center">
                        <span class="py-5 px-5 text-danger">
                            <i class="far fa-times-fas fa-clock text-danger"></i> Inactivo
                        </span>
                    </div>`;
                    }
                    return estado;
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
        drawCallback: function (settings) {
            KTMenu.createInstances();
        }
    });
}
