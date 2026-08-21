"use strict";

const tablaVersiones = '#tablaVersiones';
const rutaCargarListadoVersiones = route('chatbots.nodos.listado-versiones');

$(function () {
    listadoVersiones();
});

window.listadoVersiones = () => {
    var table = $(tablaVersiones).DataTable({
        paging: true,
        responsive: true,
        serverSide: true,
        scrollX: true,
        searchDelay: 500,

        ajax: {
            "url": rutaCargarListadoVersiones,
            "type": "GET",

            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(tablaVersiones);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaVersiones);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-download"></i> Excel',
                className: "btn btn-light-success",
                title: "Listado Versiones.",
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
                data: 'version',
                name: 'version',
                render: function(data, type, full, meta) {
                    return full?.version ?? 'N/A';
                },
            },
            {
                data: 'fecha',
                name: 'fecha',
                render: function(data, type, full, meta) {
                    return full?.fecha ?? 'N/A';
                },
            },
            {
                data: 'usuario',
                name: 'usuario',
                render: function(data, type, full, meta) {
                    return full?.usuario ?? 'N/A';
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
let versionSeleccionada = null;
let viewEditor = null;

function buildImportData(nodes, connections) {
    var importData = { drawflow: { Home: { data: {} } } };
    var def = null;

    nodes.forEach(function (n) {
        def = NODE_DEFS[n.type] || NODE_DEFS['text'];
        var flags = { principal: !!n.principal, auto_send: !!n.auto_send };

        var inputs = {};
        for (var i = 1; i <= def.inputs; i++) inputs['input_' + i] = { connections: [] };
        var outputs = {};
        for (var j = 1; j <= def.outputs; j++) outputs['output_' + j] = { connections: [] };

        importData.drawflow.Home.data[n.drawflow_id] = {
            id: parseInt(n.drawflow_id),
            name: n.type,
            data: {
                type: n.type,
                title: n.label,
                principal: n.principal || 0,
                auto_send: n.auto_send || 0,
                config: n.config || {}
            },
            class: def.cls,
            html: nodeHTML(n.type, n.label, flags),
            typenode: false,
            inputs: inputs,
            outputs: outputs,
            pos_x: n.pos_x,
            pos_y: n.pos_y
        };
    });

    connections.forEach(function (c) {
        var sourceNode = importData.drawflow.Home.data[c.source_id];
        var targetNode = importData.drawflow.Home.data[c.target_id];
        if (sourceNode && sourceNode.outputs[c.output] && targetNode && targetNode.inputs[c.input]) {
            sourceNode.outputs[c.output].connections.push({ node: c.target_id, output: c.input });
            targetNode.inputs[c.input].connections.push({ node: c.source_id, input: c.output });
        }
    });

    return importData;
}

function renderVersionPreview(nodes, connections) {
    var container = document.getElementById('drawflow-ver');
    if (!container) return;

    if (!viewEditor) {
        viewEditor = new Drawflow(container);
        viewEditor.reroute = true;
        viewEditor.editor_mode = 'fixed'; // solo lectura: sin arrastrar nodos ni crear conexiones
        viewEditor.start();
    } else {
        viewEditor.clear();
    }

    viewEditor.import(buildImportData(nodes, connections));
    viewEditor.zoom_reset();
}

$(document).on('click', '.btnVer', function () {
    var id = $(this).data('registro');
    versionSeleccionada = id;

    fetch(route('chatbots.nodos.versiones.ver', { version: id }))
        .then(res => res.json())
        .then(data => {
            $('#mvvNumero').text('v.' + data.numero_version);
            $('#mvvFecha').text(data.fecha);
            $('#mvvUsuario').text(data.usuario);

            var modalEl = document.getElementById('modalVerVersion');
            var modal   = new bootstrap.Modal(modalEl);

            // Drawflow necesita el contenedor visible (con tamaño real) para medir bien,
            // por eso se monta recién cuando el modal termina de mostrarse.
            modalEl.addEventListener('shown.bs.modal', function onShown() {
                renderVersionPreview(data.nodes, data.connections);
                modalEl.removeEventListener('shown.bs.modal', onShown);
            });

            modal.show();
        })
        .catch(() => generalidades.toastrGenerico('error', 'No se pudo cargar la versión'));
});

$(document).on('click', '.btnDesacer', function () {
    confirmarDeshacer($(this).data('registro'));
});

$('#btnRestaurarDesdeModal').on('click', function () {
    if (versionSeleccionada) confirmarDeshacer(versionSeleccionada);
});

function confirmarDeshacer(id) {
    Swal.fire({
        title: '¿Restaurar esta versión?',
        text: 'El flujo actual se reemplazará por el contenido de esta versión.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, restaurar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (!result.isConfirmed) return;

        const config = {
            method: 'POST',
            headers: { 'Accept': generalidades.CONTENT_TYPE_JSON }
        };

        const success = (response) => {
            Swal.fire('¡Restaurado!', response?.mensaje, 'success').then(() => {
                bootstrap.Modal.getInstance(document.getElementById('modalVerVersion'))?.hide();
                if (typeof loadFlowFromDB === 'function') loadFlowFromDB();
                $(tablaVersiones).DataTable().ajax.reload(null, false);
            });
        };
        const error = (response) => generalidades.toastrGenerico('error', response?.mensaje);

        generalidades.create(route('chatbots.nodos.versiones.deshacer', { version: id }), config, success, error);
    });
}
