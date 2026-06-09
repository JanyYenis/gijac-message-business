"use strict";

const seccionListadoCampanas = ".seccionListadoCampanas";
const btnPagina = ".btnPagina";
const rutaCargarListadoCamapanas = "campanas.listado-tarjeta";
var paginaActual = 1;

$(function () {
    iniciarComponentes();
});

$(document).on('click', '#btnTabListadoTarjetas', function(){
    cargarListado();
});

const iniciarComponentes = (form = '') => {
}

$(document).on("click", btnPagina, function () {
    let pagina = $(this).attr("data-pagina");
    if (pagina) {
        cargarListado(pagina);
    }
});

window.cargarListado = (pagina = 1) => {
    generalidades.mostrarCargando(seccionListadoCampanas);
    let datos = new FormData();
    datos = generalidades.formToJson(datos);
    datos.pagina = pagina;
    const ruta = route(rutaCargarListadoCamapanas, datos);
    generalidades.refrescarSeccion(null, ruta, seccionListadoCampanas, function (response) {
        generalidades.ocultarCargando(seccionListadoCampanas);
        paginaActual = pagina;
        KTMenu.createInstances();
    });
}