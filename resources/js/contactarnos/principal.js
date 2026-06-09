"use strict";

const contactForm = '#contactForm';

$(function () {
    generalidades.validarFormulario(contactForm, enviarDatos);
});

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("contactForm"));

    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(contactForm);
            generalidades.resetValidate(contactForm);
        }
        generalidades.ocultarCargando(contactForm);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(contactForm);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(contactForm, response.validaciones);
    }
    const ruta = route("contactarnos.store");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(contactForm);
}
