"use strict";

const modalArchivo = '#modalArchivo';
var myDropzone;

$(function () {
});

$(document).on('click', '.btnDocumentos', function() {
    $(modalArchivo).modal('show');
    iniciarCarga(".xls, .xlsx, .pdf, .doc, .docx, .txt, .ppt, .pptx", 1, 100);
});

$(document).on('click', '.btnFotosVideos', function() {
    $(modalArchivo).modal('show');
    iniciarCarga(".png, .jpg, .jpeg, .mp4", 1, 16);
});

const iniciarCarga  = (formatos = ".xls, .xlsx, .pdf, .doc, .docx", maxFiles = 1, maxFilesize = 100) => {
    if (myDropzone) {
        myDropzone.destroy();
        document.getElementById('archivoCargar').dropzone = null;
    }

    myDropzone = new Dropzone("#archivoCargar", {
        acceptedFiles: formatos,
        url: route('chats.store'),
        paramName: "archivo", // The name that will be used to transfer the file
        uploadMultiple: true,
        maxFiles: maxFiles,
        maxFilesize: maxFilesize, // MB
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
            // Evento al agregar archivo
            this.on("addedfile", function(file) {
                // console.log("Archivo agregado:", file.name);
                $("#mensajeInputArchivo input").val(""); // o el valor que necesites
                $("#mensajeInputArchivo").removeClass('d-none');    // si estaba oculto con CSS
                $("#btnEnviarArchivo").removeClass('d-none');    // si estaba oculto con CSS
            });

            // Evento al eliminar archivo
            this.on("removedfile", function(file) {
                // console.log("Archivo eliminado:", file.name);

                // Si ya no quedan archivos, ocultamos el input
                if (this.files.length === 0) {
                    $("#mensajeInputArchivo input").val("");
                    $("#mensajeInputArchivo").addClass('d-none');
                    $("#btnEnviarArchivo").addClass('d-none');
                }
            });

            // Evento sending - se ejecuta antes de que se envíe un archivo
            this.on("sending", function(file, xhr, formData) {
                // Agrega el token CSRF al formulario de datos
                formData.append("_token", document.head.querySelector('meta[name="csrf-token"]').content);
                formData.append('id', $('#idContacto').val());
                formData.append('mensaje', $("#mensajeInputArchivo input").val());
            });
        }
    });

    $(document).on('click', '#btnEnviarArchivo', function () {
        myDropzone.processQueue(); // Inicia el proceso de carga de los archivos en la cola de Dropzone
    });

    myDropzone.on("success", function(file, response) {
        if (response.estado == 'success') {
            $('.btnCerrarModal').trigger('click');
            if (response.html != undefined && response.html != '') {
                $('.messanger-list').html(response.html);
                window.iniciarAudio();
                window.iniciarEmoji();
                $(".messanger").scrollTop($(".messanger").prop("scrollHeight"));
            }
        }
        myDropzone.removeAllFiles();
    });

    myDropzone.on("error", function(file, errorMessage) {
        console.log(errorMessage);

        toastr.error("A ocurrido un error al intentar enviar el mensaje.", "¡Error!");
        myDropzone.removeAllFiles();
    });
}
