"use strict";

const tablaConfig = "#tablaConfig";
const rutaCargarListadoConfigs = route("configs.listado");

$(function () {
    listadoConfigs();
});

/**
 * Función que permite cargar el listado.
 */
window.listadoConfigs = () => {
    if ($.fn.DataTable.isDataTable('#tablaConfig')) {
        $('#tablaConfig').DataTable().destroy();
    }

    var table = $("#tablaConfig").DataTable({
        paging: true,
        responsive: true,
        serverSide: false,
        scrollX: true,
        searchDelay: 500,

        ajax: {
            "url": rutaCargarListadoConfigs,
            "type": "GET",
            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(tablaConfig);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaConfig);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-download"></i> Excel',
                className: "btn btn-light-success",
                title: "Listado Configuraciones.",
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
                data: 'version',
                name: 'version'
            },
            {
                data: 'waba_id',
                name: 'waba_id',
            },
            {
                data: 'app_id',
                name: 'app_id',
            },
            {
                data: 'phone_number_id',
                name: 'phone_number_id',
            },
            {
                data: 'token',
                name: 'token',
            },
            {
                data: 'numero',
                name: 'numero',
            },
            {
                data: 'webhook',
                name: 'webhook',
            },
            {
                data: 'estado',
                name: 'estado',
            },
            {
                data: 'token_1',
                name: 'token_1',
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
            iniciarBtnWebhooks();
        }
    });
}

const iniciarBtnWebhooks = () => {
    const target = document.querySelector('.btnWebhook');

    // Validar que el elemento exista
    if (!target) {
        return;
    }

    // Inicializar ClipboardJS solo si el elemento existe
    const clipboard = new ClipboardJS(target);

    clipboard.on('success', function(e) {
        const currentLabel = e.trigger.innerHTML; // Usar e.trigger en lugar de target
        setTimeout(() => {
            e.trigger.innerHTML = currentLabel;
        }, 3000);
    });
};
