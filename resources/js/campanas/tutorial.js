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
        // overlayOpacity: 0.75,

        showProgress: true,
        progressText: '{{current}} de {{total}}',

        nextBtnText: 'Siguiente →',
        prevBtnText: '← Anterior',
        doneBtnText: 'Finalizar',

        // popoverClass: 'driverjs-theme',
        steps: [
            {
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Bienvenid@ a tus campañas
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podras crear y administrar tus campañas de WhatsApp Business, para mejorar la comunicación con tus clientes.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#btnCrearCampana',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Botón de Crear Campaña
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Podrás crear una nueva campaña de WhatsApp Business (con estas podras abrir conversaciones).
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#sectionToolbar',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Filtros y Búsqueda
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Podrás filtrar tus campañas por estado, etiquetas y fecha de creación, así como buscar por nombre de campaña, ademas podras cambiar la forma de ver tus campañas.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#kt_content_container',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Lista de Campañas
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás ver todas tus campañas de WhatsApp Business, así como su estado, fecha de creación, etiquetas y acciones.
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
