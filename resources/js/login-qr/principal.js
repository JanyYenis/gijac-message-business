"use strict";

const estado = window.loginQr || {};
let temporizadorQR = null;

const cabeceras = {
    "Accept": generalidades.CONTENT_TYPE_JSON,
    "Content-Type": generalidades.CONTENT_TYPE_JSON
};

// ───── Vista ─────
const mostrarSesion = (d) => {
    $('#qrCard').addClass('d-none');
    $('#sessionCard').removeClass('d-none');

    $('#deviceName').text(d.nombre_dispositivo || 'N/A');
    $('#deviceModel').text(d.modelo || 'N/A');
    $('#deviceOS').text(d.sistema_operativo || 'N/A');
    $('#deviceIP').text(d.ip || 'N/A');
    $('#connectionTime').text(d.vinculado_en || 'N/A');

    $('#sideSistema').text(d.sistema_operativo || 'N/A');
    $('#sideVersion').text(d.version_so || 'N/A');
    $('#lastAttempt').text(d.vinculado_en || 'N/A');
    estadoLateral(true);
};

const mostrarQR = () => {
    $('#sessionCard').addClass('d-none');
    $('#qrCard').removeClass('d-none');

    $('#sideSistema, #sideVersion, #lastAttempt').text('N/A');
    estadoLateral(false);
    refrescarQR();
};

const estadoLateral = (conectado) => {
    $('#statusBadge').toggleClass('connected', conectado).toggleClass('disconnected', !conectado).css('animation', 'pulse 0.5s ease-in-out');
    $('#statusText').text(conectado ? __('Conectado') : __('Desconectado'));
};

// ───── Código QR (se renueva solo al vencer) ─────
const iniciarTemporizadorQR = (expiraEnMs) => {
    if (temporizadorQR) clearTimeout(temporizadorQR);
    if (!expiraEnMs) return;
    temporizadorQR = setTimeout(refrescarQR, Math.max(expiraEnMs - Date.now(), 0));
};

function refrescarQR() {
    const success = (response) => {
        if (!response?.qr) return;
        $('#qrCode img').attr('src', response.qr);
        iniciarTemporizadorQR(response.expira_en);
    };
    const error = (response) => {
        generalidades.toastrGenerico('error', response?.mensaje || __('No se pudo actualizar el código QR'));
    };
    generalidades.get(route('login-qr.refresh'), { "method": "GET", "headers": cabeceras }, success, error);
}

// ───── Desconectar ─────
$(document).on('click', '#btnDesconectar', function () {
    Swal.fire({
        icon: "info",
        text: __('¿Está seguro de que deseas desconectar este dispositivo?'),
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: __("Si"),
        cancelButtonText: __("No"),
        customClass: {
            confirmButton: "btn btn-primary",
            cancelButton: "btn btn-active-light"
        }
    }).then(function (result) {
        if (!result.value) return;

        const success = (response) => {
            generalidades.ocultarCargando('body');
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
            if (response?.estado == 'success') mostrarQR();
        };
        const error = (response) => {
            generalidades.ocultarCargando('body');
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
        };
        generalidades.mostrarCargando('body');
        generalidades.delete(route('login-qr.desconectar'), { "method": "DELETE", "headers": cabeceras, "body": {} }, success, error);
    });
});

// ───── Tiempo real ─────
$(function () {
    if (!estado.vinculado) iniciarTemporizadorQR(estado.expiraEnMs);

    Echo.private('dispositivos.' + estado.uuid)
        .listen('.device.linked', (d) => {
            if (temporizadorQR) clearTimeout(temporizadorQR);
            mostrarSesion(d);
            generalidades.toastrGenerico('success', __('Sesión iniciada correctamente desde dispositivo móvil'));
        })
        .listen('.device.unlinked', () => {
            // Se cerró la sesión desde la app (o desde otra pestaña)
            if ($('#sessionCard').hasClass('d-none')) return;
            mostrarQR();
            generalidades.toastrGenerico('warning', __('El dispositivo se desconectó'));
        });
});
