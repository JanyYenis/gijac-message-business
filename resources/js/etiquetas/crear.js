"use strict";

const formCrearEtiqueta = '#formCrearEtiqueta';
const modalCrearEtiqueta = '#modalCrearEtiqueta';

$(function () {
    iniciarComponentes(formCrearEtiqueta);
    generalidades.validarFormulario(formCrearEtiqueta, enviarDatos);
});

const iniciarComponentes = (form = "") => {
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formCrearEtiqueta"));
    
    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formCrearEtiqueta);
            $('.btnCerrarModal').trigger('click');
            window.listadoEtiquetas();
        }
        generalidades.ocultarCargando(formCrearEtiqueta);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formCrearEtiqueta);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formCrearEtiqueta, response.validaciones);
    }
    const ruta = route("etiquetas.store");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(formCrearEtiqueta);
}


$(document).on('hidden.bs.modal', modalCrearEtiqueta, function (e) {
    generalidades.resetValidate(formCrearEtiqueta);
});