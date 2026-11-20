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
                                Bienvenid@ a la configuración de tus Chatbots de WhatsApp Business
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                En esta sección podrás configurar y administrar tu Chatbot de WhatsApp Business, incluyendo la configuración de respuestas automáticas, mensajes de bienvenida, mensajes de ausencia y mucho más.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#tabs',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Atajos de navegación
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Utiliza los atajos de navegación para acceder rápidamente a las diferentes secciones de configuración de tus Chatbots de WhatsApp Business, como la configuración de respuestas automáticas, mensajes de bienvenida, mensajes de ausencia y mucho más.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#sec-general',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Configuración General
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás configurar las opciones generales de tu Chatbot de WhatsApp Business.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#sec-horario',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Configuración de Horario
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás configurar el horario de atención de tu Chatbot de WhatsApp Business.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#sec-palabras',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Configuración de Palabras Clave
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás configurar las palabras clave para tu Chatbot de WhatsApp Business.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#sec-mensajes',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Configuración de Mensajes
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás configurar los mensajes para tu Chatbot de WhatsApp Business.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#sec-flujo',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Configuración de Flujo de Atención
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás configurar el flujo de atención para tu Chatbot de WhatsApp Business.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#sec-transfer',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Configuración de Transferencia
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás configurar las transferencias para tu Chatbot de WhatsApp Business.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#sec-avanzado',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Configuración Avanzada
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás configurar las opciones avanzadas para tu Chatbot de WhatsApp Business.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#sec-contexto',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Configuración de Contexto
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás configurar las opciones de contexto para tu Chatbot de WhatsApp Business.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#sec-comportamiento',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Configuración de Comportamiento
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás configurar las opciones de comportamiento para tu Chatbot de WhatsApp Business.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '.status-card',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Estado de las configuraciones
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás ver el estado de las configuraciones de tu Chatbot de WhatsApp Business, incluyendo si están activas o inactivas.
                            </span>
                        </div>
                    </div>`,
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#sec-summary',
                popover: {
                    description: `<div class="d-flex justify-content-center align-items-center">
                        <div class="me-4">
                            <img src='../img/logo_gmb.png' width="100%"/>
                        </div>
                        <div class="text-start">
                            <header id="driver-popover-title" class="driver-popover-title" style="display: block;">
                                Resumen de configuración
                            </header>
                            <span style='font-size: 15px; display: block; margin-top: 10px;'>
                                Aquí podrás ver un resumen de cómo quedará tu chatbot.
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
