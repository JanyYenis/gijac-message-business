'use strict';

let drawflowPreview = null;
let plantillaActual = {
    url: null,
    name: null
};

$(function () {
    //
});

const initDrawflowPreview = () => {

    const container = document.getElementById('drawflowPreview');

    if (!drawflowPreview) {
        drawflowPreview = new Drawflow(container);
        drawflowPreview.reroute = true;
        drawflowPreview.reroute_fix_curvature = true;
        drawflowPreview.start();
        drawflowPreview.editor_mode = 'fixed';
    } else {
        drawflowPreview.clear();
    }
};

const getNodeInfo = (node) => {

    const type = node.type || '';

    if (type.includes('webhook')) {
        return {
            icon: '🔗',
            color: '#198754',
            label: 'Webhook'
        };
    }

    if (type.includes('agent')) {
        return {
            icon: '🤖',
            color: '#6f42c1',
            label: 'Agente IA'
        };
    }

    if (type.includes('lmChat')) {
        return {
            icon: '🧠',
            color: '#0d6efd',
            label: 'Modelo IA'
        };
    }

    if (type.includes('memory')) {
        return {
            icon: '💾',
            color: '#fd7e14',
            label: 'Memoria'
        };
    }

    if (type.includes('respondToWebhook')) {
        return {
            icon: '↩️',
            color: '#dc3545',
            label: 'Respuesta'
        };
    }

    return {
        icon: '⚙️',
        color: '#6c757d',
        label: node.name
    };
};

const getInputCount = (node) => {

    if (node.type === 'n8n-nodes-base.webhook') {
        return 0;
    }

    if (node.type.includes('agent')) {
        return 4;
    }

    return 1;
};

const getOutputCount = (node) => {
    return 1;
};

const construirDrawflow = (workflow) => {

    const data = {};
    const nodeMap = {};
    const SCALE = 1.8;

    /*
     * 1. Crear nodos Drawflow
     */
    workflow.nodes.forEach((node, index) => {

        const drawflowId = index + 1;

        nodeMap[node.id] = drawflowId;

        const info = getNodeInfo(node);

        const inputs = getInputCount(node);
        const outputs = getOutputCount(node);

        const html = `
            <div class="n8n-preview-node">

                <div class="n8n-preview-node-header"
                     style="border-top: 4px solid ${info.color};">

                    <span class="n8n-preview-icon">
                        ${info.icon}
                    </span>

                    <span class="n8n-preview-title">
                        ${info.label}
                    </span>

                </div>

                <div class="n8n-preview-type">
                    ${node.name}
                </div>

            </div>
        `;

        data[drawflowId] = {
            id: drawflowId,
            name: node.name,
            data: node,
            class: 'n8n-node',
            html: html,
            typenode: false,
            pos_x: (node.position?.[0] ?? 0) * SCALE,
            pos_y: (node.position?.[1] ?? 0),
            inputs: {},
            outputs: {}
        };

        /*
         * Crear inputs
         */
        for (let i = 1; i <= inputs; i++) {

            data[drawflowId].inputs[`input_${i}`] = {
                connections: []
            };

        }

        /*
         * Crear outputs
         */
        for (let i = 1; i <= outputs; i++) {

            data[drawflowId].outputs[`output_${i}`] = {
                connections: []
            };

        }

    });


    /*
     * 2. Crear conexiones
     */
    Object.entries(workflow.connections || {}).forEach(
        ([sourceName, outputs]) => {

            const sourceNode = workflow.nodes.find(
                node => node.name === sourceName
            );

            if (!sourceNode) {
                return;
            }

            const sourceId = nodeMap[sourceNode.id];

            Object.entries(outputs).forEach(
                ([connectionType, connectionGroups]) => {

                    connectionGroups.forEach(
                        (connectionsGroup, outputIndex) => {

                            connectionsGroup.forEach(connection => {

                                const targetNode = workflow.nodes.find(
                                    node => node.name === connection.node
                                );

                                if (!targetNode) {
                                    return;
                                }

                                const targetId =
                                    nodeMap[targetNode.id];

                                /*
                                 * Determinar input según
                                 * el tipo de conexión de n8n.
                                 */
                                let inputIndex = connection.index + 1;

                                switch (connectionType) {

                                    case 'main':
                                        inputIndex = 1;
                                        break;

                                    case 'ai_languageModel':
                                        inputIndex = 2;
                                        break;

                                    case 'ai_memory':
                                        inputIndex = 3;
                                        break;

                                    case 'ai_tool':
                                        inputIndex = 4;
                                        break;

                                    default:
                                        inputIndex = connection.index + 1;
                                }

                                const outputName =
                                    `output_${outputIndex + 1}`;

                                const inputName =
                                    `input_${inputIndex}`;


                                /*
                                 * Conexión desde el output
                                 */
                                if (
                                    data[sourceId]?.outputs?.[outputName]
                                ) {

                                    data[sourceId]
                                        .outputs[outputName]
                                        .connections
                                        .push({
                                            node: String(targetId),
                                            output: inputName
                                        });

                                }


                                /*
                                 * Conexión desde el input
                                 */
                                if (
                                    data[targetId]?.inputs?.[inputName]
                                ) {

                                    data[targetId]
                                        .inputs[inputName]
                                        .connections
                                        .push({
                                            node: String(sourceId),
                                            input: outputName
                                        });

                                }

                            });

                        }
                    );

                }
            );

        }
    );


    return {
        drawflow: {
            Home: {
                data: data
            }
        }
    };
};

const cargarPreview = async (url) => {

    try {

        initDrawflowPreview();

        const response = await fetch(url);

        if (!response.ok) {
            throw new Error('No se pudo cargar el JSON');
        }

        const workflow = await response.json();

        /*
         * Convertir JSON de n8n → JSON de Drawflow
         */
        const drawflowData = construirDrawflow(workflow);

        /*
         * Importar directamente en Drawflow
         */
        drawflowPreview.import(drawflowData);

        /*
         * Esperar renderizado
         */
        setTimeout(() => {

            centrarDrawflow();

        }, 200);

    } catch (error) {

        console.error(error);

        $('#drawflowPreview').html(`
            <div class="d-flex align-items-center justify-content-center h-100">
                <div class="text-center text-danger">

                    <i class="bi bi-exclamation-triangle fs-1"></i>

                    <div class="mt-2">
                        No se pudo cargar la vista previa
                    </div>

                </div>
            </div>
        `);

    }
};

const centrarDrawflow = () => {

    const container = document.getElementById('drawflowPreview');
    const nodes = container.querySelectorAll('.drawflow-node');

    if (!nodes.length) {
        return;
    }

    let minX = Infinity;
    let minY = Infinity;
    let maxX = -Infinity;
    let maxY = -Infinity;

    nodes.forEach(node => {

        const x = parseFloat(node.style.left) || 0;
        const y = parseFloat(node.style.top) || 0;

        const width = node.offsetWidth;
        const height = node.offsetHeight;

        minX = Math.min(minX, x);
        minY = Math.min(minY, y);

        maxX = Math.max(maxX, x + width);
        maxY = Math.max(maxY, y + height);
    });

    const flowWidth = maxX - minX;
    const flowHeight = maxY - minY;

    const containerWidth = container.clientWidth;
    const containerHeight = container.clientHeight;

    // Zoom inicial
    let zoom = 1;

    // Reducir si el flujo es muy grande
    if (flowWidth > containerWidth * 0.85) {
        zoom = Math.min(
            zoom,
            (containerWidth * 0.85) / flowWidth
        );
    }

    if (flowHeight > containerHeight * 0.85) {
        zoom = Math.min(
            zoom,
            (containerHeight * 0.85) / flowHeight
        );
    }

    // No hacerlo demasiado pequeño
    zoom = Math.max(0.55, Math.min(1, zoom));

    drawflowPreview.zoom = zoom;

    const centerX =
        (containerWidth - flowWidth * zoom) / 2 -
        minX * zoom;

    const centerY =
        (containerHeight - flowHeight * zoom) / 2 -
        minY * zoom;

    drawflowPreview.canvas_x = centerX;
    drawflowPreview.canvas_y = centerY;

    drawflowPreview.precanvas.style.transform =
        `translate(${centerX}px, ${centerY}px) scale(${zoom})`;
};

// Guardar
$(document).on('click', '#btnGuardar', function () {
    $('#btnGuardarTodo').trigger('click');
});

// Toggle token
$(document).on('click', '#toggleToken', function () {
    var $i = $('#tokenInput'),
        $ic = $(this).find('i');
    if ($i.attr('type') === 'password') {
        $i.attr('type', 'text');
        $ic.removeClass('bi-eye').addClass('bi-eye-slash');
    } else {
        $i.attr('type', 'password');
        $ic.removeClass('bi-eye-slash').addClass('bi-eye');
    }
});

// Copiar JSON
$(document).on('click', '#btnCopyJson', function () {
    var txt = $('#jsonExample').text().replace('Copiar Ejemplo', '').trim();
    navigator.clipboard.writeText(txt);
    var $b = $(this);
    $b.html('<i class="bi bi-check2 me-1"></i>Copiado');
    setTimeout(function () {
        $b.html('<i class="bi bi-clipboard me-1"></i>Copiar Ejemplo');
    }, 1500);
});

// Probar webhook (simulado)
$(document).on('click', '#btnProbar', function () {
    let $b = $(this);

    $b.prop('disabled', true).html(
        '<span class="spinner-border spinner-border-sm me-1"></span>Probando...'
    );

    $('#resOk,#resErr').hide();

    let formData = new FormData(document.getElementById('formN8n'));

    fetch(route('chatbots.n8n.probar'), {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: formData
    })
        .then(response => response.json())
        .then(data => {

            $b.prop('disabled', false).html(
                '<i class="bi bi-play-circle me-1"></i>Probar Webhook'
            );

            if (data.estado === 'success') {

                $('#resOk')
                    .html(`
                    <i class="bi bi-check-circle-fill me-1"></i>
                    Conexión Exitosa
                    <span class="d-block fw-normal small mt-1">
                        Respuesta ${data.codigo} · ${data.tiempo} ms
                    </span>
                `)
                    .fadeIn();

            } else {

                $('#resErr')
                    .html(`
                    <i class="bi bi-x-circle-fill me-1"></i>
                    Error
                    <span class="d-block fw-normal small mt-1">
                        ${data.mensaje}
                    </span>
                `)
                    .fadeIn();
            }

        })
        .catch(() => {

            $b.prop('disabled', false).html(
                '<i class="bi bi-play-circle me-1"></i>Probar Webhook'
            );

            $('#resErr').fadeIn();

        });
});

// Template modal
$(document).on('click', '.tpl-item', function () {

    const item = $(this);

    const name = item.data('name');
    const desc = item.data('desc');
    const nodes = item.data('nodes');
    const emoji = item.data('emoji');
    const jsonUrl = item.data('json');

    plantillaActual.url = jsonUrl;
    plantillaActual.name = name;

    $('#tplTitle').text(name);
    $('#tplDesc').text(desc);
    $('#tplNodes').text(nodes + ' nodos');
    $('#tplEmoji').text(emoji);

    const modal = bootstrap.Modal.getOrCreateInstance(
        document.getElementById('modalTpl')
    );

    modal.show();

    cargarPreview(jsonUrl);
});

$(document).on('click', '#btnDownloadJson', async function () {
    if (!plantillaActual.url) {
        return;
    }

    try {
        const response = await fetch(plantillaActual.url);

        if (!response.ok) {
            throw new Error(
                'No se pudo descargar la plantilla'
            );
        }

        const blob = await response.blob();

        /*
         * Crear URL temporal
         */
        const url = window.URL.createObjectURL(blob);

        /*
         * Crear enlace temporal
         */
        const link = document.createElement('a');
        link.href = url;
        link.download = `${plantillaActual.name}.json`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        /*
         * Liberar memoria
         */
        window.URL.revokeObjectURL(url);
    } catch (error) {
        console.error(error);
        generalidades.toastrGenerico(
            'error',
            'No se pudo descargar la plantilla.'
        );
    }
});

$(document).on('click', '#btnEnviarPrueba', function () {

    let $b = $(this);

    let payload = {
        numero: $('#tNum').val(),
        nombre: $('#tName').val(),
        mensaje: $('#tMsg').val(),
    };

    $b.prop('disabled', true).html(
        '<span class="spinner-border spinner-border-sm me-1"></span>Enviando...'
    );

    $('#testOut').hide();

    fetch(route('chatbots.n8n.enviar-prueba'), {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify(payload)
    })
        .then(response => response.json())
        .then(data => {

            $('#reqBox').text(
                JSON.stringify(data.request, null, 2)
            );

            $('#resBox').text(
                JSON.stringify(data.response, null, 2)
            );

            $('#timeBadge').text(
                'Tiempo de respuesta: ' + data.tiempo + ' ms'
            );

            $('#testOut').fadeIn();

        })
        .catch(error => {

            $('#reqBox').text(
                JSON.stringify(payload, null, 2)
            );

            $('#resBox').text(
                JSON.stringify({
                    error: error.message
                }, null, 2)
            );

            $('#testOut').fadeIn();

        })
        .finally(() => {

            $b.prop('disabled', false).html(
                '<i class="bi bi-send me-1"></i>Enviar Prueba'
            );

        });

});

require('./ejecuciones');
