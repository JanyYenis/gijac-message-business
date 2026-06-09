"use strict";

// rutas 
const rutaEditar = "etiquetas.edit";

// id y clases
const formEditarEtiqueta = "#formEditarEtiqueta";
const seccionEditar = ".seccionEditar";
const modalEditar = "#modalEditarEtiqueta";

$(function () {
    generalidades.validarFormulario(formEditarEtiqueta, enviarDatos);
});

$(document).on("click", ".btnEditar", function () {
    let id = $(this).attr("data-etiqueta");
    if (id) {
        // id = JSON.parse(id);
        cargarDatos(id);
    }
});

const cargarDatos = (id) => {
    const ruta = route(rutaEditar, { "etiqueta": id });
    generalidades.mostrarCargando('body');
    generalidades.ejecutar('GET', ruta, 'body', modalEditar, seccionEditar, function(){
        iniciarComponentes(formEditarEtiqueta);
    });
}

const iniciarComponentes = (form = "") => {
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formEditarEtiqueta"));
    
    const config = {
        'method': 'PUT',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formEditarEtiqueta);
            $('.btnCerrarModal').trigger('click');
            window.listadoEtiquetas();
        }
        generalidades.ocultarCargando(formEditarEtiqueta);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formEditarEtiqueta);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formEditarEtiqueta, response.validaciones);
    }
    const rutaActualizar = route("etiquetas.update", { "etiqueta": formData.get("id") });
    generalidades.edit(rutaActualizar, config, success, error);
    generalidades.mostrarCargando(formEditarEtiqueta);
}