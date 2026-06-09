"use strict";

const formCrearContacto = '#formCrearContacto';
const modalCrearContactos = '#modalCrearContactos';

$(function () {
    iniciarComponentes();
    generalidades.validarFormulario(formCrearContacto, enviarDatos);
    iniciarCarga();
});

const iniciarComponentes = (form = "") => {
    generalidades.initTelefonoInput(`${form} #tel`);
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formCrearContacto"));
    let inputTelefono = generalidades.darTelefonoInput(`${formCrearContacto} #tel`);
	let tel = inputTelefono?.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
    tel = tel.replace(/\((\w+)\)/g, "$1");
    tel = tel.replace(/-/g, "");
    tel = tel.replace(/\s/g, "");
	let codigo = inputTelefono?.getSelectedCountryData()?.dialCode ?? '';
    formData.set('telefono', tel);
    formData.set('codigo_telefono', codigo);

    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formCrearContacto);-
            generalidades.resetValidate(formCrearContacto);
            $('.btnCerrarModal').trigger('click');
            window.listadoContactos();
        }
        generalidades.ocultarCargando(formCrearContacto);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formCrearContacto);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formCrearContacto, response.validaciones);
    }
    const ruta = route("contactos.store");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(formCrearContacto);
}

const iniciarCarga  = () => {
    var myDropzone = new Dropzone("#kt_dropzonejs_example_1", {
        acceptedFiles: ".xls, .xlsx",
        url: route('contactos.cargarContactos'),
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

    $(document).on('click', '#enviarButton', function () {
        myDropzone.processQueue(); // Inicia el proceso de carga de los archivos en la cola de Dropzone
    });

    myDropzone.on("success", function(file, response) {
        if (response.estado == 'success') {
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
            $('.btnCerrarModal').trigger('click');
            window.listadoContactos();
        } else {
            $('.btnCerrarModal').trigger('click');
            window.listadoContactos();
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
        }
        myDropzone.removeAllFiles();
    });

    myDropzone.on("error", function(file, errorMessage) {
        toastr.error("A ocurrido un error al intentar cargar el archivos", "¡Error!");
        myDropzone.removeAllFiles();
    });
}
