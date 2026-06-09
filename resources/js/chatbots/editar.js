"use strict";

// Global variables
let chatbots = [];
var currentChatbot = null;
let currentNodeId = 0;
let selectedNodeType = null;
let isEditing = false;
var archivo_arctual = null;

$(function () {
    consultarNodes();
});

const consultarNodes = () => {
    let tipo_datos ={
        1: 'text',
        2: 'image',
        3: 'video',
        4: 'document',
        5: 'buttons',
        6: 'list',
        7: 'input',
    };
    const config = {
        'method': 'GET',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
    }

    const success = (response) => {
        generalidades.ocultarCargando('body');
        if (response.estado == 'success' && response.chatbot) {

            if (!currentChatbot?.nodes) {
                currentChatbot = { nodes: [] };
            }

            // Verificar si hay nodes
            if (!response.chatbot.nodes_ordenados || response.chatbot.nodes_ordenados.length === 0) {
                return;
            }

            response.chatbot.nodes_ordenados.forEach((node, index) => {
                currentNodeId++;
                // Validar que node.type exista en tipo_datos
                if (!tipo_datos[node.type]) {
                    console.warn(`Tipo ${node.type} no encontrado para node ${index}`);
                    return; // Saltar este node
                }

                let objeto_nodes = {
                    id: node.number || index + 1,
                    type: tipo_datos[node.type],
                    message: node.message || '',
                    connections: []
                };

                // Procesar botones si es tipo 5 - CON VALIDACIÓN
                if (node.type == 5) {
                    if (node.opciones_ordenado && Array.isArray(node.opciones_ordenado)) {
                        objeto_nodes.buttons = node.opciones_ordenado.map(opcion => {
                            return opcion.label || ''; // Validar que texto exista
                        }).filter(text => text !== ''); // Filtrar vacíos

                        // Agregar connections
                        node.opciones_ordenado.forEach(opcion => {
                            if (opcion.label) {
                                objeto_nodes.connections.push({
                                    response: opcion.label,
                                    nextNode: opcion.next_node_id || null
                                });
                            }
                        });
                    }
                } else if (node.type == 6) {
                    if (node.opciones_ordenado && Array.isArray(node.opciones_ordenado)) {
                        objeto_nodes.options = node.opciones_ordenado.map(opcion => {
                            return opcion.label || ''; // Validar que texto exista
                        }).filter(text => text !== ''); // Filtrar vacíos

                        // Agregar connections
                        node.opciones_ordenado.forEach(opcion => {
                            if (opcion.label) {
                                objeto_nodes.connections.push({
                                    response: opcion.label,
                                    nextNode: opcion.next_node_id || null
                                });
                            }
                        });
                    }
                }

                currentChatbot.nodes.push(objeto_nodes);
            });

            // Esperar a que termine el loop antes de renderizar
            setTimeout(() => {
                renderFlowBuilder();
                updatePreview();
            }, 100);

        } else {
            console.log('Estado no success o chatbot no definido');
        }
    }

    const error = (response) => {
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    generalidades.get(route('chatbots.consultar-nodes', {chatbot: window.id_chatbot}), config, success, error);
    generalidades.mostrarCargando('body');
}

// Node type selection
$(document).on('click', '.node-type-card', function() {
    $('.node-type-card').removeClass('selected');
    $(this).addClass('selected');
    selectedNodeType = $(this).data('type');
    loadNodeConfigForm(selectedNodeType);
});

function loadNodeConfigForm(type, node_config = '#nodeConfigForm', btn_save = '#saveNodeBtn') {
    const forms = {
        'text': `
            <div class="mb-3">
                <label class="form-label required"># Nodo</label>
                <input type="number" class="form-control" id="idNodo" placeholder="# Nodo" required/>
            </div>
            <div class="mb-3">
                <label class="form-label required">Mensaje de texto</label>
                <textarea class="form-control" id="nodeMessage" rows="3" placeholder="Escribe el mensaje que enviará el chatbot..." required></textarea>
            </div>
        `,
        'image': `
            <div class="mb-3">
                <label class="form-label required"># Nodo</label>
                <input type="number" class="form-control" id="idNodo" placeholder="# Nodo" required/>
            </div>
            <div class="mb-3">
                <label class="form-label required">Mensaje de texto</label>
                <textarea class="form-control" id="nodeMessage" rows="3" placeholder="Escribe el mensaje que acompañará la imagen..." required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label required">Imagen</label>
                <input type="file" class="form-control" id="nodeImage" accept="image/*" required>
                <small class="text-muted">Formatos soportados: JPG, PNG, GIF (máx. 5MB)</small>
            </div>
        `,
        'video': `
            <div class="mb-3">
                <label class="form-label required"># Nodo</label>
                <input type="number" class="form-control" id="idNodo" placeholder="# Nodo" required/>
            </div>
            <div class="mb-3">
                <label class="form-label required">Mensaje de texto</label>
                <textarea class="form-control" id="nodeMessage" rows="3" placeholder="Escribe el mensaje que acompañará el video..." required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label required">Video</label>
                <input type="file" class="form-control" id="nodeVideo" accept="video/*" required>
                <small class="text-muted">Formatos soportados: MP4 (máx. 16MB)</small>
            </div>
        `,
        'document': `
            <div class="mb-3">
                <label class="form-label required"># Nodo</label>
                <input type="number" class="form-control" id="idNodo" placeholder="# Nodo" required/>
            </div>
            <div class="mb-3">
                <label class="form-label required">Mensaje de texto</label>
                <textarea class="form-control" id="nodeMessage" rows="3" placeholder="Escribe el mensaje que acompañará el documento..." required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label required">Documento</label>
                <input type="file" class="form-control" id="nodeDocument" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                <small class="text-muted">Formatos soportados: PDF, DOC, XLS, PPT (máx. 100MB)</small>
            </div>
        `,
        'buttons': `
            <div class="mb-3">
                <label class="form-label required"># Nodo</label>
                <input type="number" class="form-control" id="idNodo" placeholder="# Nodo" required/>
            </div>
            <div class="mb-3">
                <label class="form-label required">Mensaje de texto</label>
                <textarea class="form-control" id="nodeMessage" rows="3" placeholder="Escribe el mensaje que acompañará los botones..." required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Botones (máximo 3)</label>
                <div id="buttonsContainer">
                    <div class="row seccionOpcion">
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Texto botón</label>
                                <input type="text" class="form-control button-text" placeholder="Texto del botón 1" maxlength="20">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Node (opcional)</label>
                                <input type="number" class="form-control button-node" placeholder="Ingrese el node al que dependera"/>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <div class="mt-7">
                                <button type="button" class="btn btn-danger btn-text-danger removeButton">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm addButton">
                    <i class="fas fa-plus me-1"></i>
                    Agregar Botón
                </button>
            </div>
        `,
        'list': `
            <div class="mb-3">
                <label class="form-label required"># Nodo</label>
                <input type="number" class="form-control" id="idNodo" placeholder="# Nodo" required/>
            </div>
            <div class="mb-3">
                <label class="form-label required">Mensaje de texto</label>
                <textarea class="form-control" id="nodeMessage" rows="3" placeholder="Escribe el mensaje que acompañará la lista..." required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Opciones de la lista</label>
                <div id="listContainer">
                    <div class="row seccionOpcion">
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Texto opción</label>
                                <input type="text" class="form-control list-text" placeholder="Opción 1">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Node (opcional)</label>
                                <input type="number" class="form-control list-node" placeholder="Ingrese el node al que dependera"/>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <div class="mt-7">
                                <button type="button" class="btn btn-danger removeListOption">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm addListOption">
                    <i class="fas fa-plus me-1"></i>
                    Agregar Opción
                </button>
            </div>
        `,
        'input': `
            <div class="mb-3">
                <label class="form-label required"># Nodo</label>
                <input type="number" class="form-control" id="idNodo" placeholder="# Nodo" required/>
            </div>
            <div class="mb-3">
                <label class="form-label required">Pregunta</label>
                <textarea class="form-control" id="nodeMessage" rows="3" placeholder="Escribe la pregunta que hará el chatbot..." required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Variable para guardar respuesta</label>
                <input type="text" class="form-control" id="nodeVariable" placeholder="nombre_usuario">
                <small class="text-muted">La respuesta del usuario se guardará en esta variable</small>
            </div>
        `
    };

    $(node_config).html(forms[type] || '');
    $(btn_save).prop('disabled', false);
}

$(document).on('click', '#saveNodeBtn', function() {
    saveNode();
});

$(document).on('click', '#saveNodeBtnEdit', function() {
    saveNodeEdit();
});

// Save node edit
function saveNodeEdit() {
    if (!selectedNodeType) {
        Swal.fire('Error', 'Selecciona un tipo de nodo', 'error');
        return;
    }

    const message = $('#editNodeModal #nodeMessage').val().trim();
    if (!message) {
        Swal.fire('Error', 'El mensaje es obligatorio', 'error');
        return;
    }

    const newNode = {
        id: $('#editNodeModal #idNodo').val(),
        type: selectedNodeType,
        message: message,
        connections: []
    };

    // Add type-specific data
    if (selectedNodeType === 'buttons') {
        const buttons = [];
        const buttonsNode = [];
        $('#editNodeModal .button-text').each(function() {
            const text = $(this).val().trim();
            if (text) buttons.push(text);
        });
        $('#editNodeModal .button-node').each(function() {
            const text = $(this).val().trim();
            if (text) buttonsNode.push(text);
        });
        newNode.buttons = buttons;

        // Create connections for each button
        buttons.forEach((button, index) => {
            newNode.connections.push({
                response: button,
                nextNode: buttonsNode[index] ?? null
            });
        });
    } else if (selectedNodeType === 'list') {
        const options = [];
        const listNode = [];
        $('#editNodeModal .list-text').each(function() {
            const text = $(this).val().trim();
            if (text) options.push(text);
        });
        $('#editNodeModal .list-node').each(function() {
            const text = $(this).val().trim();
            if (text) listNode.push(text);
        });
        newNode.options = options;

        // Create connections for each option
        options.forEach((option, index) => {
            newNode.connections.push({
                response: option,
                nextNode: listNode[index]
            });
        });
    } else if (selectedNodeType === 'input') {
        newNode.variable = $('#editNodeModal #nodeVariable').val().trim();
        newNode.connections.push({
            response: '*',
            nextNode: null
        });
    } else if (selectedNodeType == 'document' || selectedNodeType == 'image' || selectedNodeType == 'video') {
        newNode.archivo = archivo_arctual;
    }

    if (!currentChatbot) {
        currentChatbot = { nodes: [] };
    }

    if (!currentChatbot.nodes) {
        currentChatbot.nodes = [];
    }
     // 🔹 Buscar si ya existe el nodo
    const existingIndex = currentChatbot.nodes.findIndex(n => n.id == newNode.id);

    if (existingIndex !== -1) {
        // Si existe, lo actualizamos
        currentChatbot.nodes[existingIndex] = newNode;
    }

    $('.btnCerrarModal').trigger('click');
    renderFlowBuilder();
    updatePreview();

    Swal.fire({
        title: '¡Nodo agregado!',
        text: 'El nodo se ha agregado correctamente al flujo',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
}

// Save node
function saveNode() {
    if (!selectedNodeType) {
        Swal.fire('Error', 'Selecciona un tipo de nodo', 'error');
        return;
    }

    const message = $('#nodeMessage').val().trim();
    if (!message) {
        Swal.fire('Error', 'El mensaje es obligatorio', 'error');
        return;
    }

    currentNodeId++;
    const newNode = {
        id: currentNodeId,
        type: selectedNodeType,
        message: message,
        connections: []
    };

    // Add type-specific data
    if (selectedNodeType === 'buttons') {
        const buttons = [];
        const buttonsNode = [];
        $('.button-text').each(function() {
            const text = $(this).val().trim();
            if (text) buttons.push(text);
        });
        $('.button-node').each(function() {
            const text = $(this).val().trim();
            if (text) buttonsNode.push(text);
        });
        newNode.buttons = buttons;

        // Create connections for each button
        buttons.forEach((button, index) => {
            newNode.connections.push({
                response: button,
                nextNode: buttonsNode[index] ?? null
            });
        });
    } else if (selectedNodeType === 'list') {
        const options = [];
        const listNode = [];
        $('.list-text').each(function() {
            const text = $(this).val().trim();
            if (text) options.push(text);
        });
        $('.list-node').each(function() {
            const text = $(this).val().trim();
            if (text) listNode.push(text);
        });
        newNode.options = options;

        // Create connections for each option
        options.forEach((option, index) => {
            newNode.connections.push({
                response: option,
                nextNode: listNode[index]
            });
        });
    } else if (selectedNodeType === 'input') {
        newNode.variable = $('#nodeVariable').val().trim();
        newNode.connections.push({
            response: '*',
            nextNode: $('.input-node').val(),
        });
    } else if (selectedNodeType === 'document') {
        newNode.archivo = archivo_arctual;
    }

    if (!currentChatbot) {
        currentChatbot = { nodes: [] };
    }

    if (!currentChatbot.nodes) {
        currentChatbot.nodes = [];
    }
    currentChatbot.nodes.push(newNode);

    $('.btnCerrarModal').trigger('click');
    renderFlowBuilder();
    updatePreview();

    Swal.fire({
        title: '¡Nodo agregado!',
        text: 'El nodo se ha agregado correctamente al flujo',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
}

// Render flow builder
function renderFlowBuilder() {
    const flowBuilder = $('#flowBuilder');
    const emptyState = $('#builderEmptyState');

    if (!currentChatbot.nodes || currentChatbot.nodes.length === 0) {
        emptyState.removeClass('d-none');
        flowBuilder.find('.flow-node').remove();
        return;
    }

    emptyState.addClass('d-none');
    flowBuilder.find('.flow-node').remove();

    currentChatbot.nodes.forEach(node => {
        const nodeHtml = createNodeHtml(node);
        flowBuilder.append(nodeHtml);
    });
}

// Create node HTML
function createNodeHtml(node) {
    const typeLabels = {
        'text': 'Texto',
        'image': 'Imagen',
        'video': 'Video',
        'document': 'Documento',
        'buttons': 'Botones',
        'list': 'Lista',
        'input': 'Capturar'
    };

    let contentHtml = '';
    if (node.type === 'buttons' && node.buttons) {
        contentHtml = `
            <div class="mb-2">
                <strong>Botones:</strong>
                ${node.buttons.map(btn => `<span class="badge bg-secondary me-1">${btn}</span>`).join('')}
            </div>
        `;
    }

    let connectionsHtml = '';
    if (node.connections && node.connections.length > 0) {
        connectionsHtml = `
            <div class="node-connections">
                <small class="text-muted mb-2 d-block">Conexiones:</small>
                ${node.connections.map(conn => `
                    <div class="connection-item">
                        <span>${conn.response === '*' ? 'Cualquier respuesta' : conn.response}</span>&nbsp;
                        <i class="fas fa-arrow-right text-muted"></i>&nbsp;
                        <span>Nodo ${conn.nextNode}</span>
                    </div>
                `).join('')}
            </div>
        `;
    }

    return `
        <div class="flow-node" data-node-id="${node.id}">
            <div class="node-header">
                <div class="d-flex align-items-center">
                    <span class="node-type">${typeLabels[node.type] || node.type}</span>
                    <span class="ms-2 text-muted">Nodo ${node.id}</span>
                </div>
                <div class="node-actions ms-3">
                    <button type="button" class="btn btn-sm btn-light-warning editNode" data-node="${node.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-light-danger deleteNode" data-node="${node.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="node-content">
                <div class="mb-2">
                    <strong>Mensaje:</strong>
                    <p class="mb-0 text-muted">${node.message}</p>
                </div>
                ${contentHtml}
            </div>
            ${connectionsHtml}
        </div>
    `;
}

$(document).on('shown.bs.modal', '#addNodeModal', function () {
    selectedNodeType = null;
    $('.node-type-card').removeClass('selected');
    $('#nodeConfigForm').empty();
    $('#saveNodeBtn').prop('disabled', true);
    archivo_arctual = null;
});

function updatePreview() {
    const previewContainer = $('#previewMessages');
    previewContainer.empty();

    if (!currentChatbot.nodes || currentChatbot.nodes.length === 0) {
        previewContainer.html(`
            <div class="text-center text-muted py-4">
                <i class="fas fa-comment-dots fa-2x mb-2"></i>
                <p>La vista previa aparecerá aquí</p>
            </div>
        `);
        return;
    }

    // Show first node as preview
    const firstNode = currentChatbot.nodes[0];
    if (firstNode) {
        const messageHtml = createPreviewMessage(firstNode);
        previewContainer.append(messageHtml);
    }
}

// Create preview message
function createPreviewMessage(node) {
    let buttonsHtml = '';
    if (node.type === 'buttons' && node.buttons) {
        buttonsHtml = `
            <div class="message-buttons">
                ${node.buttons.map(btn => `<button class="message-button">${btn}</button>`).join('')}
            </div>
        `;
    } else if (node.type === 'list' && node.options) {
        buttonsHtml = `
            <div class="message-buttons">
                ${node.options.map(opt => `<button class="message-button">${opt}</button>`).join('')}
            </div>
        `;
    }

    return `
        <div class="whatsapp-message received">
            <div class="message-content">
                ${node.message}
            </div>
            ${buttonsHtml}
            <div class="message-time">
                ${new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })}
            </div>
        </div>
    `;
}

$(document).on('click', '.addButton', function() {
    addButton();
});

// Add/Remove buttons and list options
function addButton(form = '#addNodeModal') {
    const container = $(`${form} #buttonsContainer`);
    if (container.children().length >= 3) {
        Swal.fire('Límite alcanzado', 'Solo puedes agregar máximo 3 botones', 'warning');
        return;
    }

    const buttonIndex = container.children().length + 1;
    container.append(`
        <div class="row seccionOpcion">
            <div class="col-lg-6 col-md-6">
                <div class="mb-3">
                    <label class="form-label required">Texto botón</label>
                    <input type="text" class="form-control button-text" placeholder="Texto del botón ${buttonIndex}" maxlength="20">
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="mb-3">
                    <label class="form-label">Node (opcional)</label>
                    <input type="number" class="form-control button-node" placeholder="Ingrese el node al que dependera"/>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                <div class="mt-7">
                    <button type="button" class="btn btn-danger removeButton">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `);
}

$(document).on('click', '.removeButton', function() {
    removeButton(this);
});

function removeButton(btn) {
    $(btn).closest('.seccionOpcion').remove();
}

$(document).on('click', '.addListOption', function() {
    addListOption();
});

function addListOption(form = '#addNodeModal') {
    const container = $(`${form} #listContainer`);
    const optionIndex = container.children().length + 1;
    container.append(`
        <div class="row seccionOpcion">
            <div class="col-lg-6 col-md-6">
                <div class="mb-3">
                    <label class="form-label required">Texto opción</label>
                    <input type="text" class="form-control list-text" placeholder="Opción ${optionIndex}">
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="mb-3">
                    <label class="form-label">Node (opcional)</label>
                    <input type="number" class="form-control list-node" placeholder="Ingrese el node al que dependera"/>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                <div class="mt-7">
                    <button type="button" class="btn btn-danger removeListOption">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `);
}

$(document).on('click', '.removeListOption', function() {
    removeListOption(this);
});

function removeListOption(btn) {
    $(btn).closest('.seccionOpcion').remove();
}

$(document).on('change', '#nodeDocument', function() {
    archivo_arctual = $(this)[0].files[0];
});

$(document).on('change', '#nodeImage', function() {
    archivo_arctual = $(this)[0].files[0];
});

$(document).on('change', '#nodeVideo', function() {
    archivo_arctual = $(this)[0].files[0];
});

$(document).on('click', '.editNode', function() {
    let id = $(this).attr('data-node');
    editNode(id);
});

// Edit node
function editNode(nodeId) {
    const node = currentChatbot.nodes.find(n => parseInt(n.id) === parseInt(nodeId));
    if (!node) return;

    $('#editNodeModal').modal('show');


    $('#editNodeModal .node-type-card').removeClass('selected');
    $(`#editNodeModal .node-type-card[data-type="${node?.type ?? 'text'}"]`).addClass('selected');
    loadNodeConfigForm(node?.type, '#nodeConfigFormEdit', '#saveNodeBtnEdit');

    selectedNodeType = node?.type;

    console.log(node);
    if (node?.type == 'text') {
        $('#editNodeModal #idNodo').val(node.id);
        $('#editNodeModal #nodeMessage').val(node.message);
    } else if (node?.type == 'image') {
        $('#editNodeModal #idNodo').val(node.id);
        $('#editNodeModal #nodeMessage').val(node.message);
    } else if (node?.type == 'video') {
        $('#editNodeModal #idNodo').val(node.id);
        $('#editNodeModal #nodeMessage').val(node.message);
    } else if (node?.type == 'document') {
        $('#editNodeModal #idNodo').val(node.id);
        $('#editNodeModal #nodeMessage').val(node.message);
    } else if (node?.type == 'buttons') {
        $('#editNodeModal #idNodo').val(node.id);
        $('#editNodeModal #nodeMessage').val(node.message);

        if (node?.buttons?.length > 1) {
            for (let index = 0; index < node?.buttons?.length; index++) {
                if (index) {
                    addButton('#editNodeModal');
                }
            }
            for (let index = 0; index < node?.buttons?.length; index++) {
                let boton = node?.connections[index];
                $(`#editNodeModal .seccionOpcion:eq(${index}) .button-text`).val(boton?.response);
                $(`#editNodeModal .seccionOpcion:eq(${index}) .button-node`).val(boton?.nextNode ?? '');
            }
        }
    } else if (node?.type == 'list') {
        $('#editNodeModal #idNodo').val(node.id);
        $('#editNodeModal #nodeMessage').val(node.message);
        if (node?.options?.length > 1) {
            for (let index = 0; index < node?.options?.length; index++) {
                if (index) {
                    addListOption('#editNodeModal');
                }
            }
            for (let index = 0; index < node?.options?.length; index++) {
                let opcion_lista = node?.connections[index];
                $(`#editNodeModal .seccionOpcion:eq(${index}) .list-text`).val(opcion_lista?.response);
                $(`#editNodeModal .seccionOpcion:eq(${index}) .list-node`).val(opcion_lista?.nextNode ?? '');
            }
        }
    } else if (node?.type == 'input') {
    }
}

$(document).on('click', '.deleteNode', function() {
    let id = $(this).attr('data-node');
    deleteNode(id);
});

// Delete node
function deleteNode(nodeId) {
    Swal.fire({
        title: '¿Eliminar nodo?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            currentChatbot.nodes = currentChatbot.nodes.filter(n => n.id !== nodeId);

            // Update connections that point to this node
            currentChatbot.nodes.forEach(node => {
                if (node.connections) {
                    node.connections.forEach(conn => {
                        if (conn.nextNode === nodeId) {
                            conn.nextNode = null;
                        }
                    });
                }
            });

            renderFlowBuilder();
            updatePreview();

            Swal.fire({
                title: '¡Eliminado!',
                text: 'El nodo ha sido eliminado correctamente',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

$(document).on('click', '.saveChatbot', function() {
    saveChatbot();
});

// Save chatbot
function saveChatbot() {
    if (!currentChatbot.nodes || currentChatbot.nodes.length === 0) {
        Swal.fire('Error', 'Agrega al menos un nodo al chatbot', 'error');
        return;
    }

    Swal.fire({
        title: 'Guardar Chatbot',
        input: 'text',
        inputLabel: 'Nombre del chatbot',
        inputValue: window.name_chatbot,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'El nombre es obligatorio';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            currentChatbot.name = result.value;

            let formData = new FormData();
            formData.append('name', currentChatbot.name);
            formData.append('nodes', JSON.stringify(currentChatbot.nodes));
            // Buscar y agregar archivos de nodos tipo 'document'
            currentChatbot.nodes.forEach((node, index) => {
                if (node.type === 'document' && node.archivo) {
                    // Agregar el archivo al FormData
                    formData.append(`archivos[${index}]`, node.archivo);
                    // También podrías agregar información adicional si necesitas
                    formData.append(`archivos_info[${index}]`, JSON.stringify({
                        node_id: node.id,
                        file_name: node.archivo.name,
                        file_type: node.archivo.type
                    }));
                }
            });

            const config = {
                'method': 'PUT',
                'headers': {
                    'Accept': generalidades.CONTENT_TYPE_JSON,
                },
                'body': formData
            }

            const success = (response) => {
                Swal.fire({
                    title: '¡Guardado!',
                    text: response?.mensaje,
                    icon: response?.estado,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    if (response.estado == 'success') {
                        window.location.href = route('chatbots.index');
                    }
                });
            }

            const error = (response) => {
                generalidades.toastrGenerico(response?.estado, response?.mensaje);
            }
            const ruta = route("chatbots.update", {chatbot: window.id_chatbot});
            generalidades.edit(ruta, config, success, error);
        }
    });
}

function editChatbot(chatbotId) {
    if (typeof chatbotId === 'number') {
        currentChatbot = chatbots.find(c => c.id === chatbotId);
        if (currentChatbot) {
            currentChatbot = JSON.parse(JSON.stringify(currentChatbot)); // Deep copy
            isEditing = true;
            currentNodeId = Math.max(...currentChatbot.nodes.map(n => n.id), 0);
            showChatbotBuilder();
        }
    } else {
        // Called from detail view
        isEditing = true;
        currentNodeId = Math.max(...currentChatbot.nodes.map(n => n.id), 0);
        showChatbotBuilder();
    }
}

// Delete chatbot
function deleteChatbot(chatbotId) {
    const chatbot = chatbots.find(c => c.id === chatbotId);
    if (!chatbot) return;

    Swal.fire({
        title: '¿Estás seguro?',
        html: `¿Deseas eliminar el chatbot <strong>"${chatbot.name}"</strong>?<br><small class="text-muted">Esta acción no se puede deshacer</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Sí, eliminar',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            chatbots = chatbots.filter(c => c.id !== chatbotId);
            renderChatbotList();

            Swal.fire({
                title: '¡Eliminado!',
                text: 'El chatbot ha sido eliminado correctamente',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

function showChatbotBuilder() {
    renderFlowBuilder();
    updatePreview();
}

