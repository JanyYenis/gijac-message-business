'use strict';

$(function () {
    //
});

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
    $('#tplTitle').text($(this).data('name'));
    $('#tplDesc').text($(this).data('desc'));
    $('#tplNodes').text($(this).data('nodes') + ' nodos');
    $('#tplEmoji').text($(this).data('emoji'));
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTpl')).show();
});

$(document).on('click', '#btnDownloadJson', function () {
    var name = $('#tplTitle').text();
    var data = {
        name: name,
        nodes: [],
        connections: {},
        active: true
    };
    var blob = new Blob([JSON.stringify(data, null, 2)], {
        type: 'application/json'
    });
    var a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = name.toLowerCase().replace(/ /g, '-') + '-n8n.json';
    a.click();
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
