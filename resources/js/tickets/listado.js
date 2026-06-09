"use strict";

const seccionListadoTickets = ".seccionListadoTickets";
const btnPagina = ".btnPagina";
const rutaCargarListadoTickets = "tickets.listado";

$(function () {
    iniciarComponentes();
    window.cargarListado();
});

const iniciarComponentes = (form = '') => {
}

$(document).on("click", btnPagina, function () {
    let pagina = $(this).attr("data-pagina");
    if (pagina) {
        window.cargarListado(pagina);
    }
});

window.cargarListado = (pagina = 1) => {
    generalidades.mostrarCargando(seccionListadoTickets);
    let datos = new FormData();
    datos = generalidades.formToJson(datos);
    datos.pagina = pagina;
    const ruta = route(rutaCargarListadoTickets, datos);
    generalidades.refrescarSeccion(null, ruta, seccionListadoTickets, function (response) {
        generalidades.ocultarCargando(seccionListadoTickets);
        paginaActual = pagina;
    });
}
