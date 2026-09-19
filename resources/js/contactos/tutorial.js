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
        nextBtnText: __('Siguiente'),
        prevBtnText: __('Atras'),
        doneBtnText: __('Salir'),
        showProgress: true,
        steps: [
            {
                element: '#tutorialTabla',
                popover: {
                    title: __('Listado de Contactos'),
                    description: __('Aqui encontraras el listado de los contactos registrados en GIJAC MESSAGE BUSINESS.'),
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#tutorialBtnCrear',
                popover: {
                    title: __('Crear Contacto'),
                    description: __('Para crear un contacto, precione el boton Crear Contacto, se abrira un modal que le permitira ingresar la informacion del contacto.'),
                    side: "left",
                    align: 'start'
                }
            },
            {
                element: '#tutorialBtnCargar',
                popover: {
                    title: __('Cargar Contactos'),
                    description: __('Para registrar contactos de su base de datos personal, puede precionar el boton Cargar Contactos, se abrira un modal para cargar un archivo Excel o CSV.'),
                    side: "left",
                    align: 'start'
                }
            },
            {
                popover: {
                    title: 'GIJAC MESSAGE BUSINESS',
                    description: __('Con GIJAC MESSAGE BUSINESS crecimiento y campañas al máximo nivel.')
                }
            }
        ]
    });

    driverObj.drive();
}
