"use strict";

const seccionListadoComentarios = ".seccionListadoComentarios";
const btnPagina = ".btnPagina";
const rutaCargarListadoComentarios = "comentarios.listado";

$(function () {
    iniciarComponentes();
    window.cargarListadoComentarios();
});

const iniciarComponentes = (form = '') => {
}

$(document).on("click", btnPagina, function () {
    let pagina = $(this).attr("data-pagina");
    if (pagina) {
        window.cargarListadoComentarios(pagina);
    }
});

window.cargarListadoComentarios = (pagina = 1) => {
    generalidades.mostrarCargando(seccionListadoComentarios);
    let datos = new FormData();
    datos = generalidades.formToJson(datos);
    datos.pagina = pagina;
    datos.datos = window.datos;
    const ruta = route(rutaCargarListadoComentarios, datos);
    generalidades.refrescarSeccion(null, ruta, seccionListadoComentarios, function (response) {
        generalidades.ocultarCargando(seccionListadoComentarios);
        paginaActual = pagina;
    });
}
