"use strict";

const formCrearTickets = '#formCrearTickets';
const modalCrearTickets = '#modalCrearTickets';
var myDropzone;

$(function () {
    iniciarComponentes();
    generalidades.validarFormulario(formCrearTickets, enviarDatos);
    iniciarCarga();
});

const iniciarComponentes = (form = "") => {
    $("#fecha_hallazgo").flatpickr();
    new Quill('#divDescripcion', {
        placeholder: 'Ingrese la descripción...',
        theme: 'snow' // or 'bubble'
    });
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formCrearTickets"));
    formData.append('descripcion', $('#divDescripcion .ql-editor').html());
    // Obtener los archivos de Dropzone
    let file = myDropzone.getAcceptedFiles()[0]; // Obtener el primer archivo
    if (file) {
        formData.append("archivo", file); // Agregar el archivo al FormData
    }

    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formCrearTickets);
            $('.btnCerrarModal').trigger('click');
            window.cargarListado();
        }
        generalidades.ocultarCargando(formCrearTickets);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formCrearTickets);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formCrearTickets, response.validaciones);
    }
    const ruta = route("tickets.store");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(formCrearTickets);
}

const iniciarCarga  = () => {
    myDropzone = new Dropzone("#kt_modal_create_ticket_attachments", {
        acceptedFiles: ".xls, .xlsx, .pdf, .doc, .docx, .png, .jpg",
        url: route('tickets.store'),
        paramName: "archivo", // The name that will be used to transfer the file
        uploadMultiple: true,
        maxFiles: 1,
        maxFilesize: 100, // MB
        autoProcessQueue: false, // Deshabilita el procesamiento automático de archivos
        parallelUploads: 1, // Cantidad máxima de archivos cargados simultáneamente
        addRemoveLinks: true,
        accept: function(file, done) {
            if (file.name == "wow.jpg") {
                done("Naha, you don't.");
            } else {
                done();
            }
        },
        init: function() {
            // Evento sending - se ejecuta antes de que se envíe un archivo
            this.on("sending", function(file, xhr, formData) {
                // Agrega el token CSRF al formulario de datos
                formData.append("_token", document.head.querySelector('meta[name="csrf-token"]').content);
            });
        }
    });
}

$(document).on('hidden.bs.modal', modalCrearTickets, function (e) {
    generalidades.resetValidate(formCrearTickets);
});
