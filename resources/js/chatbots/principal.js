"use strict";

$(function () {
    // Feedback simple con jQuery al pulsar botones de acción
    $(".bot-card .btn").on("click", function() {
        var accion = $(this).text().trim();
        var bot = $(this).closest(".bot-card").find("h3").text().trim();
        console.log("[GIJAC] Acción: " + accion + " | Módulo: " + bot);

        var $btn = $(this);
        var original = $btn.html();
        $btn.prop("disabled", true).html(
            '<span class="spinner-border spinner-border-sm me-1"></span>Procesando...');
        setTimeout(function() {
            $btn.prop("disabled", false).html(original);
        }, 900);
    });
});
