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
                                Bienvenid@ a tus métricas
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí encontrarás todas las métricas que GIJAC MESSAGE BUSINESS te ofrece para tu negocio.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#kt_drawer_example_basic_button',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Filtros
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Para obtener métricas más detalladas según tus necesidades, puedes ajustar los filtros por fechas, etiquetas, y/o contcatos.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#driverMetrics',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Metricas
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Conoce el número de contactos, campañas enviadas, la fecha mas efectiva y el porcentaje de efectividad.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#campaignsChart',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Campañas enviadas por mes
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Podras saber cuantas campañas se enviaron por mes.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#tagsChart',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Distribución por Etiquetas
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Conocerás la distribución de tus etiquetas, para saber cuál es la etiqueta con más campañas enviadas.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#driverHorarios',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Horarios de Apertura por Día
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Conocerás los horarios de apertura de tus campañas por día, para saber cuál es el horario con más aperturas.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#chartEmbudo',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Embudo de efectividad
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Conocerás el embudo de efectividad de tus campañas, para saber cuál es el porcentaje de efectividad de tus campañas (Alcance, Aperturas y Fallos).
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                GIJAC MESSAGE BUSINESS
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Mejorando la comunicación de tu negocio con tus clientes, a través de WhatsApp Business.
                            </span>
                        </div>
                    </div>`,
                }
            }
        ]
    });

    driverObj.drive();
}
