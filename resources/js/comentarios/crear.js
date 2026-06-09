"use strict";

const formCrearComentario = '#formCrearComentario';
const modalCrearComentario = '#modalCrearComentario';

$(function () {
    iniciarComponentes(formCrearComentario);
    generalidades.validarFormulario(formCrearComentario, enviarDatos);
});

const iniciarComponentes = (form = "") => {
    new Quill(`${form} #textareaQuill`, {
        placeholder: 'Ingrese su comentario...',
        theme: 'snow' // or 'bubble'
    });
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formCrearComentario"));
    // formData.append('descripcion', $(`${formCrearComentario} #textareaQuill .ql-editor`).html());

    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formCrearComentario);
            // window.listadoActas();
            $('.btnCerrarModal').trigger('click');
            generalidades.resetValidate(formCrearComentario);
        }
        generalidades.ocultarCargando(formCrearComentario);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formCrearComentario);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formCrearComentario, response.validaciones);
    }
    const ruta = route("comentarios.store");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(formCrearComentario);
}
