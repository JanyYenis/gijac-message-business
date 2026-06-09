"use strict";

const formEditarTicket = '#formEditarTicket';
var divDescripcion;
var divDescripcion1;

$(function () {
    iniciarComponentes(formEditarTicket);
    generalidades.validarFormulario(formEditarTicket, enviarDatos);
});

const iniciarComponentes = (form = "") => {
    divDescripcion1 = new Quill('#divDescripcion1', {
        placeholder: 'Ingresar la descripción...',
        theme: 'snow' // or 'bubble'
    });
    divDescripcion = new Quill('#divDescripcion', {
        placeholder: 'Ingresar el comentario...',
        theme: 'snow' // or 'bubble'
    });
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formEditarTicket"));
    formData.append('descripcion', $('#divDescripcion1 .ql-editor').html());
    formData.append('descripcion_comentario', $('#divDescripcion .ql-editor').html());

    const config = {
        'method': 'PUT',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formEditarTicket);
            window.cargarListadoComentarios();
            divDescripcion.setContents([]); // Borra todo el contenido
        }
        generalidades.ocultarCargando(formEditarTicket);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formEditarTicket);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formEditarTicket, response.validaciones);
    }
    const ruta = route("tickets.update", {'ticket': formData.get('id')});
    generalidades.edit(ruta, config, success, error);
    generalidades.mostrarCargando(formEditarTicket);
}
