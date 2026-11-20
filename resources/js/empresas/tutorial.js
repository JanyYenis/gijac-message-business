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
                                Bienvenid@ a la información de tu negocio
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí encontrarás toda la información relevante sobre tu negocio.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#logoSection',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Logo de tu negocio
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás subir el logo de tu negocio, para que tus clientes puedan identificarlo fácilmente.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#generalInfoSection',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Información General
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás editar la información general de tu negocio, como el nombre o razón social y el NIT.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#addressSection',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Dirección
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás editar la dirección de tu negocio, para que tus clientes puedan ubicarlo fácilmente.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#contactInfoSection',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Información de Contacto
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás editar la información de contacto de tu negocio, como el teléfono y el correo electrónico.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#socialMediaSection',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Redes Sociales
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás editar los perfiles sociales de tu negocio, para que tus clientes puedan conectarse contigo.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#additionalInfoSection',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Información Adicional
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás editar la descripción general de tu empresa, su propósito y los servicios que ofrece.
                            </span>
                        </div>
                    </div>`,
                }
            },
            {
                element: '#configSection',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Configuración
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás editar las preferencias de visibilidad y notificaciones de tu empresa.
                            </span>
                        </div>
                    </div>`,
                }
            },
        ]
    });

    driverObj.drive();
}
