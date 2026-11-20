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
                                Bienvenid@ a tus Chatbots de WhatsApp Business
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                En esta sección podrás configurar y administrar tus Chatbots de WhatsApp Business, tales como chatbots por nodos, chatbots por IA y conecciones con webhooks a n8n.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#driverBtnConfiguracion',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Boton de Configuración
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás acceder a la sección de configuración de tus Chatbots de WhatsApp Business, donde podrás personalizar y ajustar los parámetros según tus necesidades.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#estadisticasRapidas',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Estadísticas Rápidas
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás ver las estadísticas de tus Chatbots de WhatsApp Business.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#seccionChatbots',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Tipos de Automatización
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás seleccionar y configurar el tipo de chatbot que mejor se adapte a tu negocio (Chatbot por Nodos, Chatbot con Inteligencia Artificial, Integraciones n8n).
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
