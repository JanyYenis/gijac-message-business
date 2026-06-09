"use strict";

$(function () {
    iniciarComponentes();
});

const iniciarComponentes = (form = "") => {
}

$(document).on('click', '.btnDetalleEnvio', function(){
    let id = $(this).attr('data-campana');
    window.listadoDetalleCampana(id);
});

$(document).on('click', '.btnEliminar', function(){
    let id = $(this).attr('data-campana');
    Swal.fire({
        icon: "info",
        text: '¿Está seguro de que deseas eliminar el campana?',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Si",
        cancelButtonText: "No",
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
    let ruta = route('campanas.delete', { 'campana': id } );
    let config = {
        "headers": {
            "Accept": generalidades.CONTENT_TYPE_JSON,
            "Content-Type": generalidades.CONTENT_TYPE_JSON
        },
        "method": "DELETE",
        "body": {
            'campana': id
        }
    }

    const success = (response) => {
        if (response.estado == 'success') {
            window.listadoCampana();
            window.cargarListado();
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

$(document).on('click', '.btnReenviar', function(){
    let id = $(this).attr('data-campana');
    const config = {
        'method': 'GET',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
    }

    const success = (response) => {
        generalidades.ocultarCargando('body');
        if (response.estado == 'success') {
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
            window.listadoCampana();
            window.cargarListado();
            setTimeout(() => {
                $(`.btnEditar[data-campana="${response?.cod_campana}"]`).trigger('click');
            }, 3000);
        }
    }

    const error = (response) => {
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    generalidades.get(route('campanas.reenviar', {campana: id}), config, success, error);
    generalidades.mostrarCargando('body');
});

require('./listado');
require('./listado-tarjeta');
require('./crear');
require('./detalle');
require('./editar');
