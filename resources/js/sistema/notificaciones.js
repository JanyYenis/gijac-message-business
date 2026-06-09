"use strict";

$(function () {
    iniciarComponentes();
});

const iniciarComponentes = (form = '') => {
    KTMenu.createInstances();
}

$(document).on('click', '#btnMarcarNotificaciones', function(){
    $('body').trigger('click');
    generalidades.refrescarSeccion($(this), route('notificaciones.marcarNotificaciones'), '.seccionNotificacionesGeneral', iniciarComponentes);
});

// Echo
Echo.join(`notificacion.${window.user}`).listen('NotificacionEvent', (e) => {
    generalidades.refrescarSeccion(null, route('notificaciones.index'), '.seccionNotificacionesGeneral', function(){
        iniciarComponentes();
    }, false);
});
// Echo.join(`notificacion.${window.user}`).listen('UsuarioRolEvent', (e) => {
//     generalidades.refrescarSeccion(null, route('notificaciones.index'), '.seccionNotificacionesGeneral', function(){
//         iniciarComponentes();
//     }, false);
// });

Echo.join(`chat.${window.numeroTelefono}`).listen('MensajeSent', (e) => {
    if (Notification.permission === 'granted') {
        let contenido_mensaje = e?.mensaje?.body ?? 'Tienes un mensaje';
        let tipo = e?.mensaje?.type ?? 1;
        if (tipo == 6) {
            contenido_mensaje = 'Audio';
        } else if (tipo == 4) {
            contenido_mensaje = 'Documento';
        } else if (tipo == 3) {
            contenido_mensaje = 'Video';
        } else if (tipo == 2) {
            contenido_mensaje = 'Imagen';
        }
        new Notification('GIJAC MESSAGE BUSINESS', {
            body: (e?.mensaje?.nombre_completo ?? e?.mensaje?.wa_from) + ': ' + (contenido_mensaje),
            icon: '../../img/logo_gmb.png' // opcional
        });
    }
}).joining(user => {
}).leaving(user => {
}).here(users => {
});

Echo.join(`online`)
    .here((users) => {
        // Haz algo con los usuarios que ya están en línea.
        // $('#totalUsuariosActivos').text(users.length);
    })
    .joining((user) => {
        // Haz algo cuando un usuario se conecta.
        // let totalUser = parseInt($('#totalUsuariosActivos').text());
        // $('#totalUsuariosActivos').text(totalUser + 1);
    })
    .leaving((user) => {
        // Haz algo cuando un usuario se desconecta.
        // let totalUser = parseInt($('#totalUsuariosActivos').text());
        // $('#totalUsuariosActivos').text(totalUser - 1);

        // const config = {
        //     'method': 'GET',
        //     'headers': {
        //         'Accept': generalidades.CONTENT_TYPE_JSON,
        //     },
        // }

        // const success = (response) => {
        //     if (response.estado == 'success') {
        //     }
        //     generalidades.toastrGenerico(response?.estado, response?.mensaje);
        // }

        // const error = (response) => {
        //     generalidades.toastrGenerico(response?.estado, response?.mensaje);
        // }

        // generalidades.get(route('accesos.marca-salida', { usuario: user.id }), config, success, error);
    });

Echo.join(`calls.${window.numeroTelefono}`).listen('CallsEvent', (e) => {
    console.log('Llamando', e);

});
