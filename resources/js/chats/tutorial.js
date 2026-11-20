"use strict";

$(function () {
    //
});

$(document).on('click', '#btnTutorialGeneral', function(){
    iniciarTutorial();
});

const iniciarTutorial = () => {
    let driverObj = driver({
        animate: true,
        smoothScroll: true,
        allowClose: true,

        showProgress: true,
        progressText: '{{current}} de {{total}}',

        nextBtnText: 'Siguiente →',
        prevBtnText: '← Anterior',
        doneBtnText: 'Finalizar',

        steps: [
            {
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Bienvenid@ al chat de tu negocio
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás comunicarte con tus clientes de manera rápida y sencilla, a través de WhatsApp Business.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#driverPerfilWhatsapp',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Perfil de WhatsApp de tu negocio
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás ver el perfil de WhatsApp de tu negocio, con la foto de perfil y el nombre de tu empresa (solo para numeros de producción no de pruebas).
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#seccionListadoContactos',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Listado de Contactos
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás ver todos los contactos registrados en tu chat.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
        ]
    });

    driverObj.drive();
}
