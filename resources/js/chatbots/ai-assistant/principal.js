"use strict";

var modelosCache = [];

$(function () {
    var asistente = window.asistenteInicial || null;
    var historialSimulador = [];

    cargarModelos(function () {
        if (asistente) precargarFormulario(asistente);
    });

    /* ===== MODELOS DESDE OLLAMA ===== */
    function cargarModelos(cb) {
        fetch(route('chatbots.asistente.modelos'))
            .then(res => res.json())
            .then(data => {
                modelosCache = data.modelos || [];
                var $sel = $('#modelSelect').empty();

                if (modelosCache.length === 0) {
                    $sel.append('<option value="">No hay modelos instalados en Ollama</option>');
                    return;
                }

                modelosCache.forEach(m => $sel.append(`<option value="${m.id}">${m.name}</option>`));
                renderMeta();
                if (cb) cb();
            })
            .catch(() => {
                $('#modelSelect').empty().append('<option value="">No se pudo conectar con Ollama</option>');
                generalidades.toastrGenerico('error', 'No se pudo conectar con Ollama en el servidor');
            });
    }

    function renderMeta() {
        var modelo = modelosCache.find(m => m.id === $('#modelSelect').val());
        var $meta = $('#modelMeta').empty();
        if (!modelo) return;

        var sizeGB = modelo.size ? (modelo.size / 1024 / 1024 / 1024).toFixed(1) + ' GB' : 'N/A';
        $meta.append(`<div class="col-4"><div class="meta-pill"><div class="lbl">Tamaño</div><div class="val">${sizeGB}</div></div></div>`);
        $meta.append(`<div class="col-4"><div class="meta-pill"><div class="lbl">Costo</div><div class="val">Gratis</div></div></div>`);
        $meta.append(`<div class="col-4"><div class="meta-pill"><div class="lbl">Origen</div><div class="val">Ollama local</div></div></div>`);
    }
    $('#modelSelect').on('change', renderMeta);

    /* ===== PRECARGAR CONFIG EXISTENTE ===== */
    function precargarFormulario(a) {
        $('#asstName').val(a.nombre);
        $('#asstRole').val(a.rol);
        $('#asstDescripcion').val(a.descripcion);
        $('#systemPrompt').val(a.system_prompt);
        $('#modelSelect').val(a.modelo);
        renderMeta();

        $('input[type=range]').each(function () {
            // se setean abajo explícitamente por claridad
        });
        setSlider('#asstName', null); // no-op, evita linter de vacío

        setSliderValue('creatividad', a.creatividad, 'vCrea');
        setSliderValue('formalidad', a.formalidad, 'vForm');
        setSliderValue('brevedad', a.brevedad, 'vBrev');
        setSliderValue('empatia', a.empatia, 'vEmp');

        $('#horaInicio').val(a.hora_inicio);
        $('#horaFin').val(a.hora_fin);
        $('#scheduleSwitch').prop('checked', !!a.respetar_horario);

        $('#palabrasClave').val((a.palabras_clave || []).join(', '));
        $('#msgBienvenida').val(a.mensaje_bienvenida);
        $('#msgFueraHorario').val(a.mensaje_fuera_horario);
        $('#msgTransferencia').val(a.mensaje_transferencia);

        $('#simName').text(a.nombre);
        $('#simRole').text(a.rol);
        $('#charCount').text((a.system_prompt || '').length);

        (a.capacidades || []).forEach(function (key) {
            $('#cap-' + key).prop('checked', true);
        });
    }

    function setSliderValue(id, val, spanId) {
        if (val === undefined || val === null) return;
        $('#slider-' + id).val(val);
        $('#' + spanId).text(val + '%');
    }
    function setSlider() { /* placeholder, ver nota abajo */ }

    /* ===== GUARDAR CONFIGURACIÓN ===== */
    $('#btnSave').on('click', function () {
        var capacidades = [];
        $('#capList input[type=checkbox]:checked').each(function () {
            capacidades.push($(this).data('key'));
        });

        var palabrasClave = $('#palabrasClave').val()
            .split(',')
            .map(k => k.trim())
            .filter(k => k !== '');

        var payload = {
            nombre: $('#asstName').val(),
            rol: $('#asstRole').val(),
            descripcion: $('#asstDescripcion').val(),
            system_prompt: $('#systemPrompt').val(),
            modelo: $('#modelSelect').val(),
            creatividad: parseInt($('#slider-creatividad').val()),
            formalidad: parseInt($('#slider-formalidad').val()),
            brevedad: parseInt($('#slider-brevedad').val()),
            empatia: parseInt($('#slider-empatia').val()),
            capacidades: capacidades,
            respetar_horario: $('#scheduleSwitch').is(':checked'),
            hora_inicio: $('#horaInicio').val(),
            hora_fin: $('#horaFin').val(),
            palabras_clave: palabrasClave,
            mensaje_bienvenida: $('#msgBienvenida').val(),
            mensaje_fuera_horario: $('#msgFueraHorario').val(),
            mensaje_transferencia: $('#msgTransferencia').val(),
        };

        const config = {
            method: 'POST',
            headers: { 'Accept': generalidades.CONTENT_TYPE_JSON, 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        };

        const success = (response) => {
            new bootstrap.Toast(document.getElementById('saveToast')).show();
        };
        const error = (response) => generalidades.toastrGenerico('error', response?.mensaje);

        generalidades.create(route('chatbots.ai-assistant.guardar'), config, success, error);
    });

    /* ===== SIMULADOR (ahora habla con el modelo real) ===== */
    var $body = $('#simBody'), $input = $('#simInput');

    function time() {
        return new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
    }
    function addBubble(text, dir) {
        $body.append(`<div class="bubble ${dir}">${text}<span class="time">${time()}</span></div>`);
        $body.scrollTop($body[0].scrollHeight);
    }

    function send() {
        var text = $input.val().trim();
        if (!text) return;

        addBubble(text, 'out');
        $input.val('');

        var $typing = $('<div class="bubble in typing">escribiendo...</div>');
        $body.append($typing);
        $body.scrollTop($body[0].scrollHeight);

        fetch(route('chatbots.ai-assistant.simular'), {
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
    $('#simSend').on('click', send);
    $input.on('keypress', e => { if (e.which === 13) send(); });

    /* ===== PROMPT CHIPS + COUNTER ===== */
    var $prompt = $('#systemPrompt');
    function updateCount() { $('#charCount').text($prompt.val().length); }
    $prompt.on('input', updateCount);
    $('.chip').on('click', function () {
        $prompt.val($(this).data('prompt'));
        updateCount();
    });
    updateCount();

    /* ===== IDENTITY -> SIMULATOR SYNC ===== */
    $('#asstName').on('input', function () { $('#simName').text($(this).val() || 'Asistente'); });
    $('#asstRole').on('input', function () { $('#simRole').text($(this).val() || 'Asistente IA'); });
});
