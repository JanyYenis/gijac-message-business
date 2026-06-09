"use strict";

const formCrearConfig = '#formCrearConfig';
const modalCrearConfig = '#modalCrearConfig';

$(function () {
    iniciarComponentes(formCrearConfig);
    generalidades.validarFormulario(formCrearConfig, enviarDatos);
});

const iniciarComponentes = (form = "") => {
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formCrearConfig"));
    
    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formCrearConfig);
            $('.btnCerrarModal').trigger('click');
            window.listadoConfigs();
        }
        generalidades.ocultarCargando(formCrearConfig);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formCrearConfig);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formCrearConfig, response.validaciones);
    }
    const ruta = route("configs.store");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(formCrearConfig);
}


$(document).on('hidden.bs.modal', modalCrearConfig, function (e) {
    generalidades.resetValidate(formCrearConfig);
});