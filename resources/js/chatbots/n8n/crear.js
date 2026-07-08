"use strict";

const formN8n = '#formN8n';

$(function () {
    iniciarComponentes();
    generalidades.validarFormulario(formN8n, enviarDatos);
});

const iniciarComponentes = (form = "") => {
    //
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formN8n"));
    formData.append('webhook_activo', $(`${formN8n} #webhookActivo`).is(':checked') ? 1 : 0)

    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formN8n);
        }
        generalidades.ocultarCargando(formN8n);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formN8n);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formN8n, response.validaciones);
    }
    const ruta = route("chatbots.n8n.store");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(formN8n);
}
