"use strict";

const seccionListadoCampanas = ".seccionListadoCampanas";
const btnPagina = ".btnPagina";
const rutaCargarListadoCamapanas = "campanas.listado-tarjeta";
var paginaActual = 1;

$(function () {
    iniciarComponentes();
});

$(document).on('click', '#btnTabListadoTarjetas', function() {

    cargarListado();

    // Botones
    $('#btnTabListadoTarjetas').addClass('active');
    $('#toTable').removeClass('active');

    // Contenido
    $('#tabListadoCampanasTarjeta').addClass('show active');
    $('#tabListadoCampanasTabla').removeClass('show active');
});

const iniciarComponentes = (form = '') => {
    window.cargarListado();
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
    datos.estado = $('#quickChips .chip.active').attr('data-quick') ?? 10;
    datos.tipo = $('#fType').val() ?? null;
    datos.busqueda = $('#q').val().trim() ?? null;
    const ruta = route(rutaCargarListadoCamapanas, datos);
    generalidades.refrescarSeccion(null, ruta, seccionListadoCampanas, function (response) {
        generalidades.ocultarCargando(seccionListadoCampanas);
        paginaActual = pagina;
        KTMenu.createInstances();
    });
}
