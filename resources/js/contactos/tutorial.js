"use strict";

$(function () {
});

$(document).on('click', '#btnTutorial', function(){
    iniciarTutorial();
});

$(document).on('click', '.btnTutorialAcciones', function(){
    // tutorialAcciones();
});

const iniciarTutorial = () => {
    let driverObj = driver({
        nextBtnText: 'Siguiente',
        prevBtnText: 'Atras',
        doneBtnText: 'Salir',
        showProgress: true,
        steps: [
            {
                element: '#tutorialTabla',
                popover: {
                    title: 'Listado de Contactos',
                    description: 'Aqui encontraras el listado de los contactos registrados en GIJAC MESSAGE BUSINESS.',
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#tutorialBtnCrear',
                popover: {
                    title: 'Crear Contacto',
                    description: 'Para crear un contacto, precione el boton Crear Contacto, se abrira un modal que le permitira ingresar la informacion del contacto.',
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#tutorialBtnCargar',
                popover: {
                    title: 'Cargar Contactos',
                    description: 'Para registrar contactos de su base de datos personal, puede precionar el boton Cargar Contactos, se abrira un modal para cargar un archivo Excel o CSV.',
                    side: "left",
                    align: 'start'
                }
            },
            {
                popover: {
                    title: 'GIJAC MESSAGE BUSINESS',
                    description: 'Con GIJAC MESSAGE BUSINESS crecimiento y campañas al máximo nivel.'
                }
            }
        ]
    });

    driverObj.drive();
}
