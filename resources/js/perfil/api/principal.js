"use strict";

const formCrearAPIKey = '#formCrearAPIKey';

$(function () {
    iniciarComponentes();
    generalidades.validarFormulario(formCrearAPIKey, enviarDatos);
});

const iniciarComponentes = (form = "") => {
}

$(document).on('click', '#btnTabApiKey', function(){
    window.listadoApiKeys();
    window.listadoApiKeysLogs();
    cargarWebhook();
});

/* ---------- Webhook ---------- */
const webhookAjax = (metodo, ruta, datos) => $.ajax({
    url: ruta,
    method: metodo,
    contentType: 'application/json',
    data: datos ? JSON.stringify(datos) : undefined,
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json' }
});

const webhookError = (xhr) => {
    const errs = xhr.responseJSON?.errors;
    const msg = errs ? Object.values(errs)[0][0] : (xhr.responseJSON?.mensaje ?? __('No se pudo completar la acción.'));
    generalidades.toastrGenerico('error', msg);
};

const cargarWebhook = () => {
    webhookAjax('GET', route('api-webhook.show')).done((r) => {
        const w = r.webhook;
        if (!w) return;
        $('#webhookUrl').val(w.url);
        $('.webhook-evento').each(function () { this.checked = w.eventos.includes(this.value); });
        $('#webhookActivo').prop('checked', w.activo);
        let estado = '';
        if (w.ultimo_envio) {
            estado = `${__('Último envío')}: ${w.ultimo_envio}` + (w.ultimo_http ? ` (HTTP ${w.ultimo_http})` : '');
        }
        if (w.ultimo_error) {
            estado += ` · <span class="text-danger"></span>`;
        }
        $('#webhookEstado').html(estado);
        if (w.ultimo_error) {
            $('#webhookEstado .text-danger').text(w.ultimo_error);
        }
    });
};

const mostrarSecreto = (secreto) => {
    Swal.fire({
        icon: "success",
        title: __('Secreto del webhook'),
        html: `<p class="mb-3">${__('Guárdalo ahora: lo necesitas para verificar la firma y no se volverá a mostrar.')}</p>
               <input type="text" class="form-control text-center" readonly value="${secreto}">`,
        confirmButtonText: __('Copiar y cerrar'),
        buttonsStyling: false,
        allowOutsideClick: false,
        customClass: { confirmButton: "btn btn-success" }
    }).then(function () {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(secreto);
        }
    });
};

$(document).on('submit', '#formApiWebhook', function (e) {
    e.preventDefault();
    const eventos = $('.webhook-evento:checked').map(function () { return this.value; }).get();
    if (!eventos.length) {
        generalidades.toastrGenerico('error', __('Selecciona al menos un evento.'));
        return;
    }
    webhookAjax('POST', route('api-webhook.guardar'), {
        url: $('#webhookUrl').val().trim(),
        eventos: eventos,
        activo: $('#webhookActivo').prop('checked')
    }).done((r) => {
        generalidades.toastrGenerico(r.estado, r.mensaje);
        if (r.secreto) mostrarSecreto(r.secreto);
        cargarWebhook();
    }).fail(webhookError);
});

$(document).on('click', '#btnWebhookProbar', function () {
    webhookAjax('POST', route('api-webhook.probar'))
        .done((r) => generalidades.toastrGenerico(r.estado, r.mensaje))
        .fail(webhookError)
        .always(cargarWebhook);
});

$(document).on('click', '#btnWebhookSecreto', function () {
    Swal.fire({
        icon: "warning",
        text: __('El secreto actual dejará de funcionar. ¿Generar uno nuevo?'),
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: __("Si"),
        cancelButtonText: __("No"),
        customClass: { confirmButton: "btn btn-success", cancelButton: "btn btn-danger" }
    }).then(function (result) {
        if (!result.value) return;
        webhookAjax('POST', route('api-webhook.secreto')).done((r) => {
            generalidades.toastrGenerico(r.estado, r.mensaje);
            if (r.secreto) mostrarSecreto(r.secreto);
        }).fail(webhookError);
    });
});

$(document).on('click', '.btnInactivar', function(){
    let id = $(this).attr('data-key');
    const config = {
        'method': 'PUT',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': {
            estado: 2
        }
    }

    const success = (response) => {
        if (response.estado == 'success') {
            window.listadoApiKeys();
        }
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones('body', response.validaciones);
    }
    const rutaActualizar = route("api-keys.update", { "key": id });
    generalidades.edit(rutaActualizar, config, success, error);
    generalidades.mostrarCargando('body');
});

$(document).on('click', '.btnActivar', function(){
    let id = $(this).attr('data-key');
    const config = {
        'method': 'PUT',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': {
            estado: 1
        }
    }

    const success = (response) => {
        if (response.estado == 'success') {
            window.listadoApiKeys();
        }
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones('body', response.validaciones);
    }
    const rutaActualizar = route("api-keys.update", { "key": id });
    generalidades.edit(rutaActualizar, config, success, error);
    generalidades.mostrarCargando('body');
});

$(document).on('click', '.btnEliminar', function(){
    let id = $(this).attr('data-key');
    Swal.fire({
        icon: "info",
        text: __('¿Está seguro de que deseas eliminar el key?'),
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: __("Si"),
        cancelButtonText: __("No"),
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger"
        }
    }).then(function (result) {
        if (result.value) {
            eliminar(id);
        }
    });
});

const eliminar = (id) => {
    let ruta = route('api-keys.delete', { 'key': id } );
    let config = {
        "headers": {
            "Accept": generalidades.CONTENT_TYPE_JSON,
            "Content-Type": generalidades.CONTENT_TYPE_JSON
        },
        "method": "DELETE",
        "body": {
            'key': id
        }
    }

    const success = (response) => {
        if (response.estado == 'success') {
            window.listadoApiKeys();
        }
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }
    const error = (response) => {
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }
    generalidades.delete(ruta, config, success, error);
    generalidades.mostrarCargando('body');
}

// La llave solo se muestra una vez: se guarda cifrada y no se puede volver a ver.
const mostrarLlave = (llave) => {
    Swal.fire({
        icon: "success",
        title: __('API Key creada'),
        html: `<p class="mb-3">${__('Cópiala ahora y guárdala en un lugar seguro. No se volverá a mostrar.')}</p>
               <input type="text" id="llaveGenerada" class="form-control text-center" readonly value="${llave}">`,
        confirmButtonText: __('Copiar y cerrar'),
        buttonsStyling: false,
        allowOutsideClick: false,
        customClass: { confirmButton: "btn btn-success" }
    }).then(function () {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(llave);
        }
    });
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formCrearAPIKey"));

    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            $('.btnCerrarModal').trigger('click');
            window.listadoApiKeys();
            document.getElementById("formCrearAPIKey").reset();
            if (response.api_key) {
                mostrarLlave(response.api_key);
            }
        }
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }
    let ruta = route("api-keys.store");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando('body');
};

require('./listado');
