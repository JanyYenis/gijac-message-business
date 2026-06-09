"use strict";

$(function () {
    iniciarComponentes();
});

const iniciarComponentes = (form = "") => {
}

// $(document).on('click', '.btnInactivar', function(){
//     let id = $(this).attr('data-acta');
//     const config = {
//         'method': 'PUT',
//         'headers': {
//             'Accept': generalidades.CONTENT_TYPE_JSON,
//         },
//         'body': {
//             estado: 2
//         }
//     }

//     const success = (response) => {
//         if (response.estado == 'success') {
//             window.listadoActas();
//         }
//         generalidades.ocultarCargando('body');
//         generalidades.toastrGenerico(response?.estado, response?.mensaje);
//     }

//     const error = (response) => {
//         generalidades.ocultarCargando('body');
//         generalidades.toastrGenerico(response?.estado, response?.mensaje);
//         generalidades.mostrarValidaciones('body', response.validaciones);
//     }
//     const rutaActualizar = route("actas.update", { "acta": id });
//     generalidades.edit(rutaActualizar, config, success, error);
//     generalidades.mostrarCargando('body');
// });

// $(document).on('click', '.btnActivar', function(){
//     let id = $(this).attr('data-acta');
//     const config = {
//         'method': 'PUT',
//         'headers': {
//             'Accept': generalidades.CONTENT_TYPE_JSON,
//         },
//         'body': {
//             estado: 1
//         }
//     }

//     const success = (response) => {
//         if (response.estado == 'success') {
//             window.listadoActas();
//         }
//         generalidades.ocultarCargando('body');
//         generalidades.toastrGenerico(response?.estado, response?.mensaje);
//     }

//     const error = (response) => {
//         generalidades.ocultarCargando('body');
//         generalidades.toastrGenerico(response?.estado, response?.mensaje);
//         generalidades.mostrarValidaciones('body', response.validaciones);
//     }
//     const rutaActualizar = route("actas.update", { "acta": id });
//     generalidades.edit(rutaActualizar, config, success, error);
//     generalidades.mostrarCargando('body');
// });

// $(document).on('click', '.btnEliminar', function(){
//     let id = $(this).attr('data-acta');
//     Swal.fire({
//         icon: "info",
//         text: '¿Está seguro de que deseas eliminar el acta de reunión?',
//         showCancelButton: true,
//         buttonsStyling: false,
//         confirmButtonText: "Si",
//         cancelButtonText: "No",
//         customClass: {
//             confirmButton: "btn btn-iniciar",
//             cancelButton: "btn btn-cancelar"
//         }
//     }).then(function (result) {
//         if (result.value) {
//             eliminar(id);
//         }
//     });
// });

// const eliminar = (id) => {
//     let ruta = route('actas.delete', { 'acta': id } );
//     let config = {
//         "headers": {
//             "Accept": generalidades.CONTENT_TYPE_JSON,
//             "Content-Type": generalidades.CONTENT_TYPE_JSON
//         },
//         "method": "DELETE",
//         "body": {
//             'acta': id
//         }
//     }

//     const success = (response) => {
//         if (response.estado == 'success') {
//             window.listadoActas();
//         }
//         generalidades.ocultarCargando('body');
//         generalidades.toastrGenerico(response?.estado, response?.mensaje);
//     }
//     const error = (response) => {
//         generalidades.ocultarCargando('body');
//         generalidades.toastrGenerico(response?.estado, response?.mensaje);
//     }
//     generalidades.delete(ruta, config, success, error);
//     generalidades.mostrarCargando('body');
// }

require('./listado');
require('./crear');
