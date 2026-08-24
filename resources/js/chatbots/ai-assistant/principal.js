"use strict";

var modelosCache = [];
const formAsistente = '#formAsistente';
var historialSimulador = [];
/* ===== SIMULADOR (ahora habla con el modelo real) ===== */
var $body = $('#simBody'), $input = $('#simInput');
/* ===== PROMPT CHIPS + COUNTER ===== */
var $prompt = $('#systemPrompt');
/* ===== BASE DE CONOCIMIENTO (1 solo archivo) ===== */
var $dl = $('#docList');

$(function () {

    cargarModelos();

    $('#modelSelect').on('change', renderMeta);

    /* ===== GUARDAR CONFIGURACIÓN ===== */
    $('#btnSave').on('click', function () {
        let formData = new FormData(document.getElementById("formAsistente"));

        var palabrasClave = $('#palabrasClave').val()
            .split(',')
            .map(k => k.trim())
            .filter(k => k !== '');

        palabrasClave.forEach((palabra) => {
            formData.append('palabras_clave[]', palabra);
        });

        const config = {
            'method': 'POST',
            'headers': {
                'Accept': generalidades.CONTENT_TYPE_JSON,
            },
            'body': formData
        }

        const success = (response) => {
            if (response.estado == 'success') {
                generalidades.ocultarValidaciones(formAsistente);
            }
            generalidades.ocultarCargando(formAsistente);
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
        }

        const error = (response) => {
            generalidades.ocultarCargando(formAsistente);
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
            generalidades.mostrarValidaciones(formAsistente, response.validaciones);
        }
        const ruta = route("chatbots.asistente.store");
        generalidades.create(ruta, config, success, error);
        generalidades.mostrarCargando(formAsistente);
    });

    $('#simSend').on('click', send);
    $input.on('keypress', e => { if (e.which === 13) send(); });

    $prompt.on('input', updateCount);
    $('.chip').on('click', function () {
        $prompt.val($(this).data('prompt'));
        updateCount();
    });
    updateCount();

    /* ===== IDENTITY -> SIMULATOR SYNC ===== */
    $('#asstName').on('input', function () { $('#simName').text($(this).val() || 'Asistente'); });
    $('#asstRole').on('input', function () { $('#simRole').text($(this).val() || 'Asistente IA'); });
    $('#mensajeBienvenida').on('input', function () { $('#simBody .bubble.in').html($(this).val() ? $(this).val() + '<span class="time">' + time() + '</span>' : '¡Hola! Soy Gibot, tu asistente de GIJAC WEB. ¿En qué puedo ayudarte hoy?'); });

    iniciarComponentes();
});

const iniciarComponentes = (form = "") => {
    iniciarCarga();
}

const iniciarCarga = () => {
    var myDropzone = new Dropzone("#dropzone", {
        acceptedFiles: ".pdf, .word, .docx, .txt",
        url: route('chatbots.asistente.documento.subir'),
        paramName: "documento", // The name that will be used to transfer the file
        uploadMultiple: false,
        maxFiles: 1,
        maxFilesize: 10, // MB
        autoProcessQueue: true,
        parallelUploads: 1, // Cantidad máxima de archivos cargados simultáneamente
        addRemoveLinks: true,
        accept: function (file, done) {
            if (file.name == "wow.jpg") {
                done("Naha, you don't.");
            } else {
                done();
            }
        },
        init: function () {
            // Evento sending - se ejecuta antes de que se envíe un archivo
            this.on("sending", function (file, xhr, formData) {
                // Agrega el token CSRF al formulario de datos
                formData.append("_token", document.head.querySelector('meta[name="csrf-token"]').content);
            });
        }
    });

    myDropzone.on("success", function (file, response) {
        if (response.estado == 'success') {
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
            renderDocumento(response.documento);
        } else {
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
        }
        myDropzone.removeAllFiles();
    });

    myDropzone.on("error", function (file, errorMessage) {
        toastr.error("A ocurrido un error al intentar cargar el archivos", "¡Error!");
        myDropzone.removeAllFiles();
    });
}

const renderDocumento = (doc) => {
    $dl.html(`
        <div class="doc-item" id="docItemActual">
            <div class="doc-ico"><i class="${iconoPorNombre(doc.nombre)}"></i></div>
            <div class="flex-grow-1">
                <div class="fw-semibold small">${doc.nombre}</div>
                <div class="doc-meta">${(doc.size / 1024).toFixed(0)} KB · ${doc.fecha}</div>
            </div>
            <span class="res-badge res-ok">Procesado</span>
            <button type="button" class="btn btn-sm btn-link text-danger" id="btnEliminarDoc">
                <i class="bi bi-trash text-danger"></i>
            </button>
        </div>
    `);
}

/* ===== MODELOS DESDE OLLAMA ===== */
const cargarModelos = () => {
    fetch(route('chatbots.asistente.modelos'))
        .then(res => res.json())
        .then(data => {
            modelosCache = data.modelos || [];
            var $sel = $('#modelSelect').empty();

            if (modelosCache.length === 0) {
                $sel.append('<option value="">No hay modelos instalados en Ollama</option>');
                return;
            }

            modelosCache.forEach((m) => {
                let selected = data?.modelo_actual && data?.modelo_actual == m.id ? 'selected' : '';
                $sel.append(`<option value="${m.id}" ${selected}>${m.name}</option>`)
            });
            renderMeta();
        })
        .catch((e) => {
            console.error('Error al cargar modelos desde Ollama:', e);
            $('#modelSelect').empty().append('<option value="">No se pudo conectar con Ollama</option>');
            generalidades.toastrGenerico('error', 'No se pudo conectar con Ollama en el servidor');
        });
}

const renderMeta = () => {
    var modelo = modelosCache.find(m => m.id === $('#modelSelect').val());
    var $meta = $('#modelMeta').empty();
    if (!modelo) return;

    var sizeGB = modelo.size ? (modelo.size / 1024 / 1024 / 1024).toFixed(1) + ' GB' : 'N/A';
    $meta.append(`<div class="col-4"><div class="meta-pill"><div class="lbl">Tamaño</div><div class="val">${sizeGB}</div></div></div>`);
    $meta.append(`<div class="col-4"><div class="meta-pill"><div class="lbl">Costo</div><div class="val">Gratis</div></div></div>`);
    $meta.append(`<div class="col-4"><div class="meta-pill"><div class="lbl">Origen</div><div class="val">Ollama local</div></div></div>`);
}

const setSliderValue = (id, val, spanId) => {
    if (val === undefined || val === null) return;
    $('#slider-' + id).val(val);
    $('#' + spanId).text(val + '%');
}

const time = () => {
    return new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
}

const addBubble = (text, dir) => {
    $body.append(`<div class="bubble ${dir}">${text}<span class="time">${time()}</span></div>`);
    $body.scrollTop($body[0].scrollHeight);
}

const send = () => {
    var text = $input.val().trim();
    if (!text) return;

    addBubble(text, 'out');
    $input.val('');

    var $typing = $('<div class="bubble in typing">escribiendo...</div>');
    $body.append($typing);
    $body.scrollTop($body[0].scrollHeight);

    fetch(route('chatbots.asistente.simular'), {
        method: 'POST',
        headers: {
            'Accept': generalidades.CONTENT_TYPE_JSON,
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify({ mensaje: text, historial: historialSimulador })
    })
        .then(res => res.json())
        .then(data => {
            $typing.remove();
            if (data.estado !== 'success') {
                addBubble(data.mensaje || 'Ocurrió un error.', 'in');
                return;
            }
            historialSimulador.push({ role: 'user', content: text });
            historialSimulador.push({ role: 'assistant', content: data.respuesta });
            addBubble(data.respuesta, 'in');
        })
        .catch(() => {
            $typing.remove();
            addBubble('No se pudo contactar al asistente.', 'in');
        });
}

const updateCount = () => {
    $('#charCount').text($prompt.val().length);
}

const iconoPorNombre = (nombre) => {
    if (nombre.endsWith('.pdf')) return 'fa-regular fa-file-pdf';
    if (nombre.endsWith('.docx')) return 'fa-regular fa-file-word';
    return 'fa-regular fa-file-lines';
}

$(document).on('click', '#btnEliminarDoc', function () {
    fetch(route('chatbots.asistente.documento.eliminar'), {
        method: 'DELETE',
        headers: {
            'Accept': generalidades.CONTENT_TYPE_JSON,
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
        .then(res => res.json())
        .then(data => {
            generalidades.toastrGenerico(data.estado, data.mensaje);
            if (data.estado === 'success') $dl.empty();
        });
});
