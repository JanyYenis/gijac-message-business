"use strict";

/* =========================================================
   Definición de tipos de nodo
   ========================================================= */
var NODE_DEFS = {
    start:     { cls: "df-start",     icon: "bi-play-fill",          label: "Inicio",               body: "Punto de partida del flujo", inputs: 0, outputs: 1 },
    text:      { cls: "df-text",      icon: "bi-chat-left-text",     label: "Mensaje de Texto",      body: "Envía un mensaje de texto",  inputs: 1, outputs: 1 },
    image:     { cls: "df-image",     icon: "bi-image",              label: "Imagen",                body: "Envía una imagen",           inputs: 1, outputs: 1 },
    video:     { cls: "df-video",     icon: "bi-camera-video",       label: "Video",                 body: "Envía un video",             inputs: 1, outputs: 1 },
    doc:       { cls: "df-doc",       icon: "bi-file-earmark-text",  label: "Documento",             body: "Envía un documento",         inputs: 1, outputs: 1 },
    audio:     { cls: "df-audio",     icon: "bi-mic",                label: "Audio",                 body: "Envía un audio",             inputs: 1, outputs: 1 },
    buttons:   { cls: "df-buttons",   icon: "bi-ui-checks-grid",     label: "Botones",               body: "Mensaje con botones",        inputs: 1, outputs: 3 },
    list:      { cls: "df-list",      icon: "bi-list-ul",            label: "Lista",                 body: "Mensaje con lista",          inputs: 1, outputs: 10 },
    question:  { cls: "df-question",  icon: "bi-question-circle",    label: "Pregunta",              body: "Realiza una pregunta",       inputs: 1, outputs: 1 },
    capture:   { cls: "df-capture",   icon: "bi-input-cursor-text",  label: "Capturar Respuesta",    body: "Guarda la respuesta",        inputs: 1, outputs: 1 },
    condition: { cls: "df-condition", icon: "bi-signpost-split",     label: "Condición",             body: "Evalúa una condición",       inputs: 1, outputs: 2 },
    variable:  { cls: "df-variable",  icon: "bi-braces",             label: "Variable",              body: "Asigna una variable",        inputs: 1, outputs: 1 },
    tag:       { cls: "df-tag",       icon: "bi-tag",                label: "Etiqueta",              body: "Asigna una etiqueta",        inputs: 1, outputs: 1 },
    goto:      { cls: "df-goto",      icon: "bi-arrow-return-right", label: "Ir a Nodo",             body: "Salta a otro nodo",          inputs: 1, outputs: 1 },
    webhook:   { cls: "df-webhook",   icon: "bi-hdd-network",        label: "Webhook",               body: "Llama un webhook externo",   inputs: 1, outputs: 1 },
    api:       { cls: "df-api",       icon: "bi-cloud-arrow-up",     label: "API",                   body: "Petición a una API",         inputs: 1, outputs: 1 },
    agent:     { cls: "df-agent",     icon: "bi-person-badge",       label: "Asignar Agente",        body: "Deriva a un agente",         inputs: 1, outputs: 1 },
    end:       { cls: "df-end",       icon: "bi-flag",               label: "Finalizar",             body: "Termina la conversación",    inputs: 1, outputs: 0 },
    ai:        { cls: "df-ai",        icon: "bi-robot",              label: "Nodo IA",               body: "Respuesta con IA",           inputs: 1, outputs: 1 },
    pdf:       { cls: "df-pdf",       icon: "bi-file-earmark-pdf",   label: "Consulta PDF",          body: "Consulta documentos PDF",    inputs: 1, outputs: 1 },
    generate:  { cls: "df-generate",  icon: "bi-stars",              label: "Generar Respuesta",     body: "Genera respuesta con IA",    inputs: 1, outputs: 1 },
};

/* =========================================================
   Variables globales
   ========================================================= */
var editor;
var nodeCounter  = 0;
var selectedNode = null;   // { id, type, data } del nodo actualmente seleccionado
var uploadCache  = {};     // { drawflow_node_id: { file: File, previewUrl: string } }
var currentFlowId = null;

/* =========================================================
   HTML de un nodo en el canvas
   ========================================================= */
function nodeHTML(type, title, flags) {
    var def   = NODE_DEFS[type];
    var name  = title || def.label;
    flags     = flags || {};
    var badges = "";
    if (flags.principal) badges += `<span class="df-badge df-badge-principal" title="Nodo principal"><i class="bi bi-star-fill"></i></span>`;
    if (flags.auto_send) badges += `<span class="df-badge df-badge-auto" title="Envío inmediato"><i class="bi bi-lightning-fill"></i></span>`;
    return `<div class="df-node ${def.cls}${flags.principal ? " df-node-principal" : ""}">
                <div class="df-header">
                    <i class="bi ${def.icon}"></i>
                    <span class="df-title">${name}</span>
                    <span class="df-badges ms-auto">${badges}</span>
                </div>
                <div class="df-body">${def.body}</div>
            </div>`;
}

/* =========================================================
   Inicialización
   ========================================================= */
$(function () {

    /* ── Drawflow ─────────────────────────────────────── */
    var container = document.getElementById("drawflow");
    editor = new Drawflow(container);
    editor.reroute = true;
    editor.start();

    loadFlowFromDB();

    /* ── Drag & Drop desde la biblioteca ──────────────── */
    var dragType = null;

    $(".drag-node").on("dragstart", function (e) {
        dragType = $(this).data("node");
        e.originalEvent.dataTransfer.setData("text/plain", dragType);
    });

    $("#drawflow").on("dragover", function (e) { e.preventDefault(); });

    $("#drawflow").on("drop", function (e) {
        e.preventDefault();
        var type = dragType || e.originalEvent.dataTransfer.getData("text/plain");
        if (!type || !NODE_DEFS[type]) return;
        var rect = container.getBoundingClientRect();
        var zoom = editor.zoom;
        var x    = (e.originalEvent.clientX - rect.left) / zoom - editor.canvas_x / zoom;
        var y    = (e.originalEvent.clientY - rect.top)  / zoom - editor.canvas_y / zoom;
        addNodeAt(type, x, y);
        updateNodeCount();
        dragType = null;
    });

    /* ── Eventos del editor ───────────────────────────── */
    editor.on("nodeSelected", function (id) {
        // Drawflow puede pasar el id como número o string según la versión —
        // normalizarlo a string para que el acceso a Home.data[id] sea consistente.
        id = String(id);
        selectedNode = { id: id };

        // Leer SIEMPRE del objeto interno (referencia real, no copia)
        var nodeData = editor.drawflow.drawflow.Home.data[id];
        if (!nodeData) return;
        if (!nodeData.data.config) nodeData.data.config = {};

        renderProps(nodeData.data.type, id, nodeData.data);
    });
    editor.on("nodeUnselected", function () {
        selectedNode = null;
        renderEmptyProps();
    });
    editor.on("nodeRemoved", function (id) {
        delete uploadCache[id];
        updateNodeCount();
        renderEmptyProps();
    });

    /* ── Zoom ─────────────────────────────────────────── */
    $("#btnZoomIn").on("click",    function () { editor.zoom_in();    updateZoomLabel(); });
    $("#btnZoomOut").on("click",   function () { editor.zoom_out();   updateZoomLabel(); });
    $("#btnZoomReset").on("click", function () { editor.zoom_reset(); updateZoomLabel(); });

    /* ── Guardar ──────────────────────────────────────── */
    $("#btnGuardar").on("click", function () {
        var payload = buildPayload("draft");
        submitFlow(payload, this, "Guardado");
    });

    /* ── Publicar ─────────────────────────────────────── */
    $("#btnPublicar").on("click", function () {
        var payload = buildPayload("published");
        submitFlow(payload, this, "Publicado");
    });

    /* ── Duplicar ─────────────────────────────────────── */
    $("#btnDuplicar").on("click", function () { flash(this, "Duplicado"); });

    /* ── Historial ────────────────────────────────────── */
    $("#btnHistorial").on("click", function () {
        $("html, body").animate({ scrollTop: $(".table-card").offset().top - 20 }, 400);
    });

    /* ── Exportar JSON ────────────────────────────────── */
    $("#btnExportar").on("click", function () {
        var data = JSON.stringify(buildPayload("export"), null, 2);
        var blob = new Blob([data], { type: "application/json" });
        var url  = URL.createObjectURL(blob);
        var a    = document.createElement("a");
        a.href   = url;
        a.download = "flujo-chatbot.json";
        a.click();
        URL.revokeObjectURL(url);
    });

    /* ── Importar ─────────────────────────────────────── */
    $("#btnImportTop").on("click", function () { flash(this, "Importar"); });

    /* ── Categorías colapsables ───────────────────────── */
    $(".cat-toggle").on("click", function () {
        $(this).toggleClass("collapsed");
        $("#" + $(this).data("target")).slideToggle(180);
    });

    /* ── Buscador de nodos ────────────────────────────── */
    $("#nodeSearch").on("input", function () {
        var q = $(this).val().toLowerCase().trim();
        $(".drag-node").each(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(q) > -1);
        });
    });

    /* ── Simulador de chat ────────────────────────────── */
    $("#simSend").on("click", sendSim);
    $("#simInput").on("keypress", function (e) { if (e.which === 13) sendSim(); });
});

/* =========================================================
   Cargar flujo desde Base de Datos (Método Nativo Import)
   ========================================================= */
async function loadFlowFromDB() {
    try {
        $("#propBadge").text("Cargando...");

        const response = await fetch(route('chatbots.nodos.consultar-nodes'));
        const data = await response.json();

        // Guardar ID del flujo
        currentFlowId = data.flow_id || null;

        if (!data.nodes || data.nodes.length === 0) {
            currentFlowId = null;
            $("#propBadge").text("Nuevo Flujo");
            return;
        }

        // Limpiamos el editor
        editor.clear();

        // 1. Construir la estructura exacta que Drawflow procesa
        var importData = {
            drawflow: {
                Home: {
                    data: {}
                }
            }
        };

        var def = null;

        // 2. Registrar los nodos en la estructura
        data.nodes.forEach(function(n) {
            def = NODE_DEFS[n.type] || NODE_DEFS['text'];
            var flags = { principal: !!n.principal, auto_send: !!n.auto_send };

            // Generar inputs vacíos
            var inputs = {};
            for (var i = 1; i <= def.inputs; i++) {
                inputs['input_' + i] = { connections: [] };
            }

            // Generar outputs vacíos
            var outputs = {};
            for (var j = 1; j <= def.outputs; j++) {
                outputs['output_' + j] = { connections: [] };
            }

            // Insertar en el objeto usando el drawflow_id original como clave
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

        // 3. Inyectar las conexiones DENTRO de la estructura
        data.connections.forEach(function(c) {
            var sourceNode = importData.drawflow.Home.data[c.source_id];
            var targetNode = importData.drawflow.Home.data[c.target_id];

            if (sourceNode && sourceNode.outputs[c.output] &&
                targetNode && targetNode.inputs[c.input]) {

                // Agregar conexión en la salida
                sourceNode.outputs[c.output].connections.push({
                    node: c.target_id,
                    output: c.input
                });

                // Agregar conexión en la entrada
                targetNode.inputs[c.input].connections.push({
                    node: c.source_id,
                    input: c.output
                });
            }
        });

        // 4. MAGIC: Drawflow carga todo de golpe (nodos + líneas)
        editor.import(importData);

        // Actualizar contadores
        updateNodeCount();
        $("#propBadge").text("Flujo Cargado");

    } catch (error) {
        console.error("Error cargando flujo:", error);
        Swal.fire('Error', 'No se pudo cargar el flujo', 'error');
    }
}

/* =========================================================
   Helpers de canvas
   ========================================================= */
function addNodeAt(type, x, y, title, savedData) {
    nodeCounter++;
    var def   = NODE_DEFS[type];
    var forcePrincipal = (type === 'start') ? 1 : 0;

    var data  = Object.assign(
        {
            type: type,
            title: title || def.label,
            principal: forcePrincipal, // Usamos la variable calculada
            auto_send: 0
        },
        savedData || {}
    );
    var flags = { principal: !!data.principal, auto_send: !!data.auto_send };
    var id    = editor.addNode(type, def.inputs, def.outputs, x, y, type, data, nodeHTML(type, data.title, flags));
    return id;
}

function updateNodeCount() {
    var count = Object.keys(editor.drawflow.drawflow.Home.data).length;
    $("#statNodos").text(count);
}

function updateZoomLabel() {
    $("#btnZoomReset").text(Math.round(editor.zoom * 100) + "%");
}

/* ── Devuelve lista [{id, label}] de nodos actuales para selects ── */
function getNodeOptions(excludeId) {
    // Leer del objeto interno para reflejar títulos actualizados
    var nodes = editor.drawflow.drawflow.Home.data;
    var opts  = [{ id: "", label: "— Selecciona nodo —" }];
    Object.keys(nodes).forEach(function (nid) {
        if (String(nid) === String(excludeId)) return;
        var n = nodes[nid];
        opts.push({ id: nid, label: n.data.title || n.name });
    });
    return opts;
}

function nodeSelectHTML(name, selectedId, excludeId) {
    var opts = getNodeOptions(excludeId);
    var html = `<select class="form-select form-select-sm" name="${name}">`;
    opts.forEach(function (o) {
        var sel = (o.id == selectedId) ? "selected" : "";
        html += `<option value="${o.id}" ${sel}>${o.label}</option>`;
    });
    html += `</select>`;
    return html;
}

/* =========================================================
   Construcción del payload para guardar / publicar
   ========================================================= */
function buildPayload(action) {
    // IMPORTANTE: leer del objeto INTERNO (no de editor.export() que clona y
    // pierde los cambios de config guardados vía saveNodeProps)
    var internalNodes = editor.drawflow.drawflow.Home.data;

    // El snapshot para el backend sí puede venir del export (para Drawflow)
    var snapshot = editor.export();

    // Serializar datos de cada nodo (incluyendo configs del panel de props)
    var nodeList = [];
    Object.keys(internalNodes).forEach(function (nid) {
        var n    = internalNodes[nid];
        var item = {
            drawflow_id: nid,
            type:        n.data.type,
            label:       n.data.title,
            pos_x:       n.pos_x,
            pos_y:       n.pos_y,
            inputs:      Object.keys(n.inputs).length,
            outputs:     Object.keys(n.outputs).length,
            principal:   n.data.principal || 0,
            auto_send:   n.data.auto_send || 0, // <--- AGREGA ESTA LÍNEA
            config:      n.data.config || {},
        };
        // Si tiene archivo pendiente de subir se marca para que el backend lo espere
        if (uploadCache[nid]) {
            item.has_pending_file = true;
            item.file_field_name  = "file_" + nid;
        }
        nodeList.push(item);
    });

    // Conexiones
    var connList = [];
    Object.keys(internalNodes).forEach(function (nid) {
        var n = internalNodes[nid];
        Object.keys(n.outputs).forEach(function (outKey) {
            n.outputs[outKey].connections.forEach(function (conn) {
                connList.push({
                    source_node_drawflow_id: nid,
                    target_node_drawflow_id: conn.node,
                    source_output:           outKey,
                    target_input:            conn.output,
                });
            });
        });
    });

    return {
        action:      action,
        drawflow:    snapshot,     // JSON completo de Drawflow (snapshot para restaurar)
        nodes:       nodeList,     // Nodos con config actualizada (fuente de verdad)
        connections: connList,
    };
}

/* =========================================================
   Envío al backend (multipart para soportar archivos)
   ========================================================= */
function submitFlow(payload, btn, label) {
    var formData = new FormData();

    // Payload JSON principal
    formData.append("payload", JSON.stringify(payload));
    formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

    // Archivos pendientes de subir
    var hasFiles = false;
    Object.keys(uploadCache).forEach(function (nid) {
        var entry = uploadCache[nid];
        if (entry && entry.file) {
            formData.append("file_" + nid, entry.file);
            hasFiles = true;
        }
    });

    flash(btn, label);

    const config = {
        'method': 'POST',
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
                // window.location.href = route('chatbots.index');
            }
        });
    }

    const error = (response) => {
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }
    const ruta = route("chatbots.nodos.store");
    generalidades.create(ruta, config, success, error);
}

/* =========================================================
   Sincronizar una conexión de salida con el canvas
   Elimina la conexión anterior en ese output y crea la nueva.
   ========================================================= */
function syncConnection(sourceId, outputKey, targetId) {
    var internalNodes = editor.drawflow.drawflow.Home.data;
    var sourceNode    = internalNodes[String(sourceId)];
    if (!sourceNode) return;

    var outputData = sourceNode.outputs[outputKey];
    if (!outputData) return;

    // Eliminar todas las conexiones actuales en ese output específico
    // (copiamos el array porque se modifica durante la iteración)
    var existing = (outputData.connections || []).slice();
    existing.forEach(function (conn) {
        try {
            editor.removeSingleConnection(
                String(sourceId),
                String(conn.node),
                outputKey,
                conn.output
            );
        } catch (e) { /* ya no existía */ }
    });

    // Crear la nueva conexión si se seleccionó un nodo destino
    if (!targetId) return;

    var targetNode = internalNodes[String(targetId)];
    if (!targetNode) return;

    // El nodo destino entra siempre por input_1
    var inputKey = Object.keys(targetNode.inputs)[0] || "input_1";

    try {
        editor.addConnection(
            String(sourceId),
            String(targetId),
            outputKey,
            inputKey
        );
    } catch (e) {
        console.warn("syncConnection:", e.message);
    }
}

/* =========================================================
   Actualizar badges (estrella / rayo) del nodo en el canvas
   ========================================================= */
function refreshNodeBadges(id) {
    var nodeData = editor.drawflow.drawflow.Home.data[String(id)];
    if (!nodeData) return;
    var d = nodeData.data;

    // Actualizar título
    $("#node-" + id + " .df-title").text(d.title || "");

    // Badges
    var $badges = $("#node-" + id + " .df-badges");
    var html    = "";
    if (d.principal) html += `<span class="df-badge df-badge-principal" title="Nodo principal"><i class="bi bi-star-fill"></i></span>`;
    if (d.auto_send) html += `<span class="df-badge df-badge-auto"      title="Envío inmediato"><i class="bi bi-lightning-fill"></i></span>`;
    $badges.html(html);

    // Borde de nodo principal
    var $nodeEl = $("#node-" + id);
    if (d.principal) $nodeEl.find(".df-node").addClass("df-node-principal");
    else             $nodeEl.find(".df-node").removeClass("df-node-principal");
}

/* =========================================================
   Listeners de cambio en los selects de destino de lista
   Se llama al renderizar y al agregar nuevas filas.
   ========================================================= */
function bindListRowTargetChange(nodeId) {
    // Re-bindear todos los selects de fila actuales
    $("#pf-list-rows .pf-list-row").each(function (i) {
        var $sel = $(this).find("select[name='pf-list-row-target-" + i + "']");
        $sel.off("change").on("change", function () {
            syncConnection(nodeId, "output_" + (i + 1), $(this).val());
        });
        // Si ya tiene un valor guardado, sincronizar al abrir el panel
        if ($sel.val()) {
            syncConnection(nodeId, "output_" + (i + 1), $sel.val());
        }
    });
}

/* =========================================================
   Panel de propiedades — vacío
   ========================================================= */
function renderEmptyProps() {
    $("#propBadge").text("—");
    $("#propsPanel").html(
        `<div class="props-empty">
            <i class="bi bi-hand-index-thumb"></i>
            Selecciona un nodo para editar su configuración
        </div>`
    );
}

/* =========================================================
   Panel de propiedades — por tipo de nodo
   ========================================================= */
function renderProps(type, id, data) {
    var def = NODE_DEFS[type];
    var cfg = data.config || {};

    $("#propBadge").text(def.label);

    var html = `<div class="prop-form">`;

    // Campos comunes
    html += propGroup("Tipo",           `<input class="form-control" value="${def.label}" disabled>`);
    html += propGroup("Nombre del nodo",`<input class="form-control" id="pf-name" value="${escHtml(data.title || def.label)}">`);

    // ── Flags de comportamiento (todos los nodos) ────────
    html += `<div class="prop-group">
                <div class="d-flex gap-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="pf-principal"
                               ${data.principal ? "checked" : ""}>
                        <label class="form-check-label" for="pf-principal">
                            <i class="bi bi-star-fill text-warning me-1"></i>Nodo principal
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="pf-auto-send"
                               ${data.auto_send ? "checked" : ""}>
                        <label class="form-check-label" for="pf-auto-send">
                            <i class="bi bi-lightning-fill text-primary me-1"></i>Envío inmediato
                        </label>
                    </div>
                </div>
                <small class="text-muted d-block mt-1">
                    <b>Principal:</b> primer nodo que dispara el bot.
                    <b>Envío inmediato:</b> el bot envía este nodo sin esperar respuesta del usuario.
                </small>
            </div>`;

    // ── Campos específicos por tipo ──────────────────────
    switch (type) {

        case "text":
        case "question":
            html += propGroup("Mensaje",
                `<textarea class="form-control" id="pf-cfg-message" rows="3">${escHtml(cfg.message || "")}</textarea>`);
            html += waPreviewBlock(cfg.message || "");
            html += varsBlock();
            break;

        case "image":
        case "video":
        case "doc":
            html += mediaUploadBlock(type, id, cfg);
            html += propGroup("Texto (caption)",
                `<textarea class="form-control" id="pf-cfg-caption" rows="2">${escHtml(cfg.caption || "")}</textarea>`);
            break;

        case "audio":
            html += mediaUploadBlock(type, id, cfg);
            break;

        case "buttons":
            html += propGroup("Mensaje",
                `<textarea class="form-control" id="pf-cfg-message" rows="2">${escHtml(cfg.message || "")}</textarea>`);
            // 3 botones dinámicos
            [1, 2, 3].forEach(function (n) {
                var btn    = (cfg.buttons && cfg.buttons[n - 1]) || {};
                var isOpt  = n === 3;
                html += propGroup(
                    "Botón " + n + (isOpt ? " (opcional)" : ""),
                    `<div class="input-group input-group-sm">
                        <input class="form-control" id="pf-cfg-btn${n}-label"
                               placeholder="${isOpt ? "Vacío" : "Etiqueta"}"
                               value="${escHtml(btn.label || "")}">
                        <span class="input-group-text">→</span>
                        ${nodeSelectHTML("pf-cfg-btn" + n + "-target", btn.target_node_id, id)}
                    </div>`
                );
            });
            break;

        case "list":
            html += propGroup("Mensaje",
                `<textarea class="form-control" id="pf-cfg-message" rows="2">${escHtml(cfg.message || "")}</textarea>`);
            html += propGroup("Título de la lista",
                `<input class="form-control" id="pf-cfg-list_title" value="${escHtml(cfg.list_title || "")}">`);
            html += propGroup("Sección",
                `<input class="form-control" id="pf-cfg-section" value="${escHtml(cfg.section || "")}">`);
            // Opciones dinámicas — cada una con su nodo destino (igual que botones)
            var listRows = cfg.rows || [{ label: "", target_node_id: "" }, { label: "", target_node_id: "" }];
            html += `<div class="prop-group">
                        <label>Opciones <small class="text-muted">(máx 10, cada una va a un nodo)</small></label>
                        <div id="pf-list-rows">`;
            listRows.forEach(function (r, i) {
                html += listRowHTML(i, r, id);
            });
            html += `</div>
                     <button type="button" class="btn btn-sm btn-toolbar mt-1 w-100" id="pf-add-row">
                        <i class="bi bi-plus"></i> Agregar opción
                     </button>
                    </div>`;
            break;

        case "ai":
        case "generate":
            html += propGroup("Prompt del sistema",
                `<textarea class="form-control" id="pf-cfg-system_prompt" rows="3">${escHtml(cfg.system_prompt || "")}</textarea>`);
            html += propGroup("Modelo",
                `<select class="form-select" id="pf-cfg-model">
                    <option value="gpt-4o-mini"  ${cfg.model === "gpt-4o-mini"  ? "selected" : ""}>GPT-4o mini</option>
                    <option value="llama-3.1"    ${cfg.model === "llama-3.1"    ? "selected" : ""}>Llama 3.1</option>
                    <option value="claude-haiku" ${cfg.model === "claude-haiku" ? "selected" : ""}>Claude Haiku</option>
                </select>`);
            html += propGroup(
                `Temperatura: <span id="tempVal">${cfg.temperature || 0.7}</span>`,
                `<input type="range" class="form-range" id="pf-cfg-temperature"
                        min="0" max="1" step="0.1" value="${cfg.temperature || 0.7}"
                        oninput="document.getElementById('tempVal').textContent=this.value">`);
            html += propGroup("Tokens máximos",
                `<input type="number" class="form-control" id="pf-cfg-max_tokens" value="${cfg.max_tokens || 512}">`);
            break;

        case "condition":
            html += propGroup("Variable a evaluar",
                `<input class="form-control" id="pf-cfg-variable" value="${escHtml(cfg.variable || "")}">`);
            html += propGroup("Operador",
                `<select class="form-select" id="pf-cfg-operator">
                    <option value="equals"       ${cfg.operator === "equals"       ? "selected" : ""}>igual a</option>
                    <option value="contains"     ${cfg.operator === "contains"     ? "selected" : ""}>contiene</option>
                    <option value="greater_than" ${cfg.operator === "greater_than" ? "selected" : ""}>mayor que</option>
                    <option value="less_than"    ${cfg.operator === "less_than"    ? "selected" : ""}>menor que</option>
                    <option value="regex"        ${cfg.operator === "regex"        ? "selected" : ""}>expresión regular</option>
                </select>`);
            html += propGroup("Valor de comparación",
                `<input class="form-control" id="pf-cfg-compare" value="${escHtml(cfg.compare || "")}">`);
            html += propGroup("Rama Verdadero (output 1)", nodeSelectHTML("pf-cfg-true_target",  cfg.true_target,  id));
            html += propGroup("Rama Falso (output 2)",     nodeSelectHTML("pf-cfg-false_target", cfg.false_target, id));
            break;

        case "webhook":
        case "api":
            html += propGroup("Método",
                `<select class="form-select" id="pf-cfg-method">
                    <option value="POST"   ${cfg.method === "POST"   ? "selected" : ""}>POST</option>
                    <option value="GET"    ${cfg.method === "GET"    ? "selected" : ""}>GET</option>
                    <option value="PUT"    ${cfg.method === "PUT"    ? "selected" : ""}>PUT</option>
                    <option value="DELETE" ${cfg.method === "DELETE" ? "selected" : ""}>DELETE</option>
                </select>`);
            html += propGroup("URL",
                `<input class="form-control" id="pf-cfg-url" value="${escHtml(cfg.url || "")}">`);
            html += propGroup("Cabeceras (JSON)",
                `<textarea class="form-control font-monospace" id="pf-cfg-headers" rows="2">${escHtml(cfg.headers || '{"Authorization":"Bearer ..."}')}</textarea>`);
            html += propGroup("Body (JSON)",
                `<textarea class="form-control font-monospace" id="pf-cfg-body" rows="2">${escHtml(cfg.body || "")}</textarea>`);
            html += propGroup("Guardar respuesta en variable",
                `<input class="form-control" id="pf-cfg-response_variable" value="${escHtml(cfg.response_variable || "api_result")}">`);
            break;

        case "variable":
            html += propGroup("Nombre de variable",
                `<input class="form-control" id="pf-cfg-var_name" value="${escHtml(cfg.var_name || "")}">`);
            html += propGroup("Valor",
                `<input class="form-control" id="pf-cfg-var_value" value="${escHtml(cfg.var_value || "")}">`);
            break;

        case "tag":
            html += propGroup("Etiqueta",
                `<input class="form-control" id="pf-cfg-tag_name" value="${escHtml(cfg.tag_name || "")}">`);
            break;

        case "agent":
            html += propGroup("Departamento",
                `<select class="form-select" id="pf-cfg-department">
                    <option value="Ventas"   ${cfg.department === "Ventas"   ? "selected" : ""}>Ventas</option>
                    <option value="Soporte"  ${cfg.department === "Soporte"  ? "selected" : ""}>Soporte</option>
                    <option value="Facturación" ${cfg.department === "Facturación" ? "selected" : ""}>Facturación</option>
                </select>`);
            html += propGroup("Mensaje de transición",
                `<textarea class="form-control" id="pf-cfg-transition_message" rows="2">${escHtml(cfg.transition_message || "Te conectamos con un agente...")}</textarea>`);
            break;

        case "pdf":
            html += mediaUploadBlock("pdf", id, cfg);
            html += propGroup("Pregunta",
                `<textarea class="form-control" id="pf-cfg-question" rows="2">${escHtml(cfg.question || "{{input}}")}</textarea>`);
            html += varsBlock();
            break;

        case "goto":
            html += propGroup("Nodo destino", nodeSelectHTML("pf-cfg-target_node_id", cfg.target_node_id, id));
            break;

        case "capture":
            html += propGroup("Guardar en variable",
                `<input class="form-control" id="pf-cfg-variable" value="${escHtml(cfg.variable || "respuesta")}">`);
            html += propGroup("Tipo de dato",
                `<select class="form-select" id="pf-cfg-data_type">
                    <option value="text"   ${cfg.data_type === "text"   ? "selected" : ""}>Texto</option>
                    <option value="number" ${cfg.data_type === "number" ? "selected" : ""}>Número</option>
                    <option value="email"  ${cfg.data_type === "email"  ? "selected" : ""}>Email</option>
                    <option value="phone"  ${cfg.data_type === "phone"  ? "selected" : ""}>Teléfono</option>
                    <option value="date"   ${cfg.data_type === "date"   ? "selected" : ""}>Fecha</option>
                </select>`);
            break;

        case "start":
            html += propGroup("Disparador",
                `<select class="form-select" id="pf-cfg-trigger">
                    <option value="any"     ${cfg.trigger === "any"     ? "selected" : ""}>Cualquier mensaje</option>
                    <option value="keyword" ${cfg.trigger === "keyword" ? "selected" : ""}>Palabra clave</option>
                </select>`);
            html += propGroup("Palabras clave (separadas por coma)",
                `<input class="form-control" id="pf-cfg-keywords"
                        placeholder="hola, inicio, menú"
                        value="${escHtml((cfg.keywords || []).join(", "))}">`);
            break;

        case "end":
            html += propGroup("Mensaje de cierre",
                `<textarea class="form-control" id="pf-cfg-close_message" rows="2">${escHtml(cfg.close_message || "¡Gracias por contactarnos!")}</textarea>`);
            break;
    }

    html += `<div class="d-grid gap-2 mt-2">
                <button class="btn btn-sm" style="background:var(--wa-green);color:#fff;" id="pf-save">
                    <i class="bi bi-check-lg text-white me-1"></i> Aplicar cambios
                </button>
                <button class="btn btn-sm btn-toolbar" id="pf-del">
                    <i class="bi bi-trash me-1"></i> Eliminar nodo
                </button>
             </div>`;
    html += `</div>`;

    $("#propsPanel").html(html);

    // ── Eventos post-render ──────────────────────────────

    // Vista previa de mensaje en tiempo real
    if (type === "text" || type === "question") {
        $("#pf-cfg-message").on("input", function () {
            $(".wa-bubble-text").html(interpolateVars($(this).val()));
        });
    }

    // Agregar fila en lista
    $("#pf-add-row").on("click", function () {
        var $rows = $("#pf-list-rows .pf-list-row");
        if ($rows.length >= 10) return;
        var idx = $rows.length;
        $("#pf-list-rows").append(listRowHTML(idx, { label: "", target_node_id: "" }, id));
        bindRemoveRow();
        // El select recién agregado también necesita su listener de syncConnection
        bindListRowTargetChange(id);
    });
    bindRemoveRow();

    // Chips de variables
    $(".var-chip").on("click", function () {
        var token = $(this).text();
        var $focused = $("#propsPanel textarea:focus, #propsPanel input[type=text]:focus");
        if ($focused.length) {
            var el  = $focused[0];
            var val = el.value;
            var pos = el.selectionStart;
            el.value = val.slice(0, pos) + token + val.slice(pos);
            el.selectionStart = el.selectionEnd = pos + token.length;
        }
    });

    // ── Conexiones en tiempo real al cambiar selects de destino ────────────
    if (type === "buttons") {
        [1, 2, 3].forEach(function (n) {
            $("[name='pf-cfg-btn" + n + "-target']").on("change", function () {
                syncConnection(id, "output_" + n, $(this).val());
            });
        });
    }
    if (type === "list") {
        bindListRowTargetChange(id);
    }
    if (type === "condition") {
        $("[name='pf-cfg-true_target']").on("change", function () {
            syncConnection(id, "output_1", $(this).val());
        });
        $("[name='pf-cfg-false_target']").on("change", function () {
            syncConnection(id, "output_2", $(this).val());
        });
    }

    // Guardar cambios en node.data
    $("#pf-save").on("click", function () {
        saveNodeProps(type, id);
        flash(this, "Aplicado");
    });

    // Eliminar nodo
    $("#pf-del").on("click", function () {
        editor.removeNodeId("node-" + id);
    });
}

/* =========================================================
   Guardar props del panel → node.data.config
   ========================================================= */
function saveNodeProps(type, id) {
    // Drawflow guarda los nodos con el ID como STRING.
    // editor.getNodeFromId() devuelve una COPIA (JSON parse/stringify),
    // hay que mutar directamente el objeto interno para que persista.
    var nodeData = editor.drawflow.drawflow.Home.data[String(id)];
    if (!nodeData) return;

    // Actualizar título y flags
    var newTitle   = $("#pf-name").val().trim() || NODE_DEFS[type].label;
    var isPrincipal = $("#pf-principal").is(":checked") ? 1 : 0;
    var isAutoSend  = $("#pf-auto-send").is(":checked") ? 1 : 0;

    // Si se marca como principal, quitar el flag en los demás nodos
    if (isPrincipal) {
        var allNodes = editor.drawflow.drawflow.Home.data;
        Object.keys(allNodes).forEach(function (nid) {
            if (String(nid) !== String(id)) {
                allNodes[nid].data.principal = 0;
                refreshNodeBadges(nid);
            }
        });
    }

    nodeData.data.title     = newTitle;
    nodeData.data.principal = isPrincipal;
    nodeData.data.auto_send = isAutoSend;

    // Actualizar el HTML del nodo en el canvas
    refreshNodeBadges(id);

    // Recolectar config según tipo
    var cfg = {};

    switch (type) {
        case "text":
        case "question":
            cfg.message = $("#pf-cfg-message").val();
            break;

        case "image":
        case "video":
        case "doc":
            cfg.caption  = $("#pf-cfg-caption").val();
            cfg.media_url      = nodeData.data.config.media_url      || null;
            cfg.media_filename = nodeData.data.config.media_filename  || null;
            break;

        case "audio":
            cfg.media_url      = nodeData.data.config.media_url      || null;
            cfg.media_filename = nodeData.data.config.media_filename  || null;
            break;

        case "buttons":
            cfg.message = $("#pf-cfg-message").val();
            cfg.buttons = [1, 2, 3].map(function (n) {
                return {
                    label:          $("#pf-cfg-btn" + n + "-label").val(),
                    target_node_id: $("[name='pf-cfg-btn" + n + "-target']").val(),
                };
            }).filter(function (b) { return b.label.trim() !== ""; });
            break;

        case "list":
            cfg.message    = $("#pf-cfg-message").val();
            cfg.list_title = $("#pf-cfg-list_title").val();
            cfg.section    = $("#pf-cfg-section").val();
            cfg.rows       = [];
            $("#pf-list-rows .pf-list-row").each(function (i) {
                var label    = $(this).find(".pf-list-row-input").val().trim();
                var targetId = $(this).find("select[name='pf-list-row-target-" + i + "']").val();
                if (label !== "") cfg.rows.push({ label: label, target_node_id: targetId || "" });
            });
            break;

        case "ai":
        case "generate":
            cfg.system_prompt = $("#pf-cfg-system_prompt").val();
            cfg.model         = $("#pf-cfg-model").val();
            cfg.temperature   = parseFloat($("#pf-cfg-temperature").val());
            cfg.max_tokens    = parseInt($("#pf-cfg-max_tokens").val());
            break;

        case "condition":
            cfg.variable    = $("#pf-cfg-variable").val();
            cfg.operator    = $("#pf-cfg-operator").val();
            cfg.compare     = $("#pf-cfg-compare").val();
            cfg.true_target = $("[name='pf-cfg-true_target']").val();
            cfg.false_target= $("[name='pf-cfg-false_target']").val();
            break;

        case "webhook":
        case "api":
            cfg.method             = $("#pf-cfg-method").val();
            cfg.url                = $("#pf-cfg-url").val();
            cfg.headers            = $("#pf-cfg-headers").val();
            cfg.body               = $("#pf-cfg-body").val();
            cfg.response_variable  = $("#pf-cfg-response_variable").val();
            break;

        case "variable":
            cfg.var_name  = $("#pf-cfg-var_name").val();
            cfg.var_value = $("#pf-cfg-var_value").val();
            break;

        case "tag":
            cfg.tag_name = $("#pf-cfg-tag_name").val();
            break;

        case "agent":
            cfg.department         = $("#pf-cfg-department").val();
            cfg.transition_message = $("#pf-cfg-transition_message").val();
            break;

        case "pdf":
            cfg.question       = $("#pf-cfg-question").val();
            cfg.media_url      = nodeData.data.config.media_url      || null;
            cfg.media_filename = nodeData.data.config.media_filename  || null;
            break;

        case "goto":
            cfg.target_node_id = $("[name='pf-cfg-target_node_id']").val();
            break;

        case "capture":
            cfg.variable  = $("#pf-cfg-variable").val();
            cfg.data_type = $("#pf-cfg-data_type").val();
            break;

        case "start":
            cfg.trigger  = $("#pf-cfg-trigger").val();
            cfg.keywords = $("#pf-cfg-keywords").val()
                .split(",")
                .map(function (k) { return k.trim(); })
                .filter(function (k) { return k !== ""; });
            break;

        case "end":
            cfg.close_message = $("#pf-cfg-close_message").val();
            break;
    }

    // Persistir en el objeto INTERNO de Drawflow (por referencia, no copia)
    nodeData.data.config = cfg;
}

/* =========================================================
   Bloque de subida de archivos (imagen, video, doc, audio, pdf)
   ========================================================= */
function mediaUploadBlock(type, nodeId, cfg) {
    var accept  = {
        image: "image/jpeg,image/png,image/webp,image/gif",
        video: "video/mp4,video/3gpp",
        doc:   "application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        audio: "audio/ogg,audio/mpeg,audio/mp4",
        pdf:   "application/pdf",
    }[type] || "*/*";

    var label   = { image: "Imagen", video: "Video", doc: "Documento", audio: "Audio", pdf: "PDF" }[type] || "Archivo";
    var icon    = { image: "bi-image", video: "bi-camera-video", doc: "bi-file-earmark-text", audio: "bi-mic", pdf: "bi-file-earmark-pdf" }[type] || "bi-paperclip";

    // Si ya hay un archivo en cache o una URL guardada
    var cached  = uploadCache[nodeId];
    var preview = cached ? cached.previewUrl : (cfg.media_url || null);

    var previewHTML = "";
    if (preview) {
        if (type === "image") {
            previewHTML = `<img src="${preview}" class="media-preview-img mt-2" width="100%" alt="preview">`;
        } else {
            var filename = cfg.media_filename || (cached && cached.file ? cached.file.name : preview);
            previewHTML  = `<div class="media-preview-file mt-2">
                                <i class="bi ${icon} me-1"></i>
                                <span class="text-truncate">${escHtml(filename)}</span>
                            </div>`;
        }
    }

    return `<div class="prop-group">
                <label>${label}</label>
                <div class="media-upload-area" id="mua-${nodeId}">
                    <input type="file" accept="${accept}"
                           class="media-file-input d-none" id="pf-file-${nodeId}">
                    <button type="button" class="btn btn-sm btn-toolbar w-100"
                            onclick="document.getElementById('pf-file-${nodeId}').click()">
                        <i class="bi ${icon} me-1"></i> Seleccionar ${label}
                    </button>
                    <div id="pf-preview-${nodeId}">${previewHTML}</div>
                </div>
            </div>`;
}

/* ── Enlazar el input file después de renderizar ─────── */
$(document).on("change", ".media-file-input", function () {
    var file   = this.files[0];
    if (!file) return;

    // Extraer el nodeId del id del input (pf-file-{nodeId})
    var nodeId = this.id.replace("pf-file-", "");
    var url    = URL.createObjectURL(file);
    var type   = editor.getNodeFromId(nodeId)?.data?.type || "";

    uploadCache[nodeId] = { file: file, previewUrl: url };

    // Actualizar media_url temporal en node.data para que buildPayload lo vea
    var node = editor.getNodeFromId(nodeId);
    if (node) {
        node.data.config            = node.data.config || {};
        node.data.config.media_url  = url;
        node.data.config.media_filename = file.name;
    }

    // Mostrar preview inline
    var previewEl = document.getElementById("pf-preview-" + nodeId);
    if (!previewEl) return;

    if (type === "image") {
        previewEl.innerHTML = `<img src="${url}" class="media-preview-img mt-2" width="100%" alt="preview">`;
    } else {
        var icon = { video: "bi-camera-video", doc: "bi-file-earmark-text", audio: "bi-mic", pdf: "bi-file-earmark-pdf" }[type] || "bi-paperclip";
        previewEl.innerHTML = `<div class="media-preview-file mt-2">
                                   <i class="bi ${icon} me-1"></i>
                                   <span class="text-truncate">${escHtml(file.name)}</span>
                               </div>`;
    }
});

/* =========================================================
   Helpers de UI
   ========================================================= */
function propGroup(label, controlHTML) {
    return `<div class="prop-group"><label>${label}</label>${controlHTML}</div>`;
}

// row puede ser string (legacy) o { label, target_node_id }
function listRowHTML(idx, row, excludeId) {
    var label    = typeof row === "string" ? row : (row.label || "");
    var targetId = typeof row === "string" ? ""  : (row.target_node_id || "");
    return `<div class="pf-list-row mb-2">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">${idx + 1}</span>
                    <input type="text" class="form-control pf-list-row-input"
                           placeholder="Etiqueta opción ${idx + 1}" value="${escHtml(label)}">
                    <button class="btn btn-toolbar pf-remove-row" type="button" title="Eliminar">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <div class="ps-2">
                    ${nodeSelectHTML("pf-list-row-target-" + idx, targetId, excludeId)}
                </div>
            </div>`;
}

function bindRemoveRow() {
    $(".pf-remove-row").off("click").on("click", function () {
        var $rows = $("#pf-list-rows .pf-list-row");
        if ($rows.length <= 1) return;
        $(this).closest(".pf-list-row").remove();
        // Reindexar números y placeholders
        $("#pf-list-rows .pf-list-row").each(function (i) {
            $(this).find(".input-group-text").text(i + 1);
            $(this).find(".pf-list-row-input").attr("placeholder", "Etiqueta opción " + (i + 1));
        });
    });
}

function waPreviewBlock(msg) {
    return `<div class="prop-group">
                <label>Vista previa WhatsApp</label>
                <div class="wa-preview">
                    <div class="wa-bubble">
                        <span class="wa-bubble-text">${interpolateVars(msg)}</span>
                        <span class="wa-time">10:30 ✓✓</span>
                    </div>
                </div>
            </div>`;
}

function varsBlock() {
    return `<div class="prop-group">
                <label>Variables disponibles <small class="text-muted">(click para insertar)</small></label>
                <div>
                    <span class="var-chip">{{nombre}}</span>
                    <span class="var-chip">{{telefono}}</span>
                    <span class="var-chip">{{respuesta}}</span>
                    <span class="var-chip">{{fecha}}</span>
                    <span class="var-chip">{{input}}</span>
                </div>
            </div>`;
}

/* Reemplaza {{variable}} por <b>variable</b> para la preview */
function interpolateVars(text) {
    return escHtml(text || "").replace(/\{\{(\w+)\}\}/g, "<b>{{$1}}</b>");
}

function escHtml(str) {
    return String(str || "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;");
}

function flash(btn, label) {
    var $b   = $(btn);
    var orig = $b.html();
    $b.prop("disabled", true).html(`<span class="spinner-border spinner-border-sm me-1"></span>${label}...`);
    setTimeout(function () {
        $b.html(`<i class="bi bi-check-lg me-1"></i>${label}`);
        setTimeout(function () { $b.prop("disabled", false).html(orig); }, 900);
    }, 700);
}

/* =========================================================
   Simulador de conversación
   ========================================================= */
function sendSim() {
    var $in = $("#simInput");
    var txt = $in.val().trim();
    if (!txt) return;

    $("#simChat").append(`<div class="wa-msg user">${$("<div>").text(txt).html()}</div>`);
    $in.val("");

    var chat = document.getElementById("simChat");
    chat.scrollTop = chat.scrollHeight;

    setTimeout(function () {
        var resp = "Entendido. Selecciona una opción del menú para continuar.";
        if (/acepto/i.test(txt))  resp = "¡Perfecto! Aquí tienes nuestro menú principal:";
        else if (/no/i.test(txt)) resp = "Sin problema. Si cambias de opinión, escríbenos cuando quieras.";
        $("#simChat").append(`<div class="wa-msg bot">${resp}</div>`);
        chat.scrollTop = chat.scrollHeight;
    }, 700);
}
