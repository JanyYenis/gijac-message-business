"use strict";

// rutas 
const rutaEditar = "configs.edit";

// id y clases
const formEditarConfig = "#formEditarConfig";
const seccionEditar = ".seccionEditar";
const modalEditar = "#modalEditarConfig";

$(function () {
    generalidades.validarFormulario(formEditarConfig, enviarDatos);
});

$(document).on("click", ".btnEditar", function () {
    let id = $(this).attr("data-config");
    if (id) {
        // id = JSON.parse(id);
        cargarDatos(id);
    }
});

const cargarDatos = (id) => {
    const ruta = route(rutaEditar, { "config": id });
    generalidades.mostrarCargando('body');
    generalidades.ejecutar('GET', ruta, 'body', modalEditar, seccionEditar, function(){
        iniciarComponentes(formEditarConfig);
    });
}

const iniciarComponentes = (form = "") => {
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formEditarConfig"));
    
    const config = {
        'method': 'PUT',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formEditarConfig);
            $('.btnCerrarModal').trigger('click');
            window.listadoConfigs();
        }
        generalidades.ocultarCargando(formEditarConfig);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formEditarConfig);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formEditarConfig, response.validaciones);
    }
    const rutaActualizar = route("configs.update", { "config": formData.get("id") });
    generalidades.edit(rutaActualizar, config, success, error);
    generalidades.mostrarCargando(formEditarConfig);
}