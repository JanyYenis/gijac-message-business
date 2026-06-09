"use strict";

const modalPerfil = '#modalPerfilWhatsapp';
const seccionEditar = '.seccionEditar';
const formPerfilWhatsapp = '#formPerfilWhatsapp';

$(function () {
    generalidades.validarFormulario(formPerfilWhatsapp, enviarDatos);
});

const iniciarComponentes = (form = '') => {
    $(`.selectCategoria`).select2();
    $('.kt_docs_repeater_basic').repeater({
        initEmpty: false,

        defaultValues: {
            'text-input': 'foo'
        },

        show: function () {
            $(this).slideDown();
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });

    $('.descripcionPerfil').maxlength({
        threshold: 512,
        warningClass: "badge badge-primary",
        limitReachedClass: "badge badge-success"
    });

    // KTImageInput.createInstances();
}

$(document).on("click", ".btnPerfilWhatsapp", function () {
    const ruta = route('perfilWhatsapp');
    generalidades.mostrarCargando('body');
    generalidades.ejecutar('GET', ruta, 'body', modalPerfil, seccionEditar, function(){
        iniciarComponentes(formPerfilWhatsapp);
    });
});

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formPerfilWhatsapp"));
    var websites = []; // Array para almacenar los websites

    // Recorrer todos los inputs con la clase "miClase"
    $('.sitiosWebs').each(function() {
        var valor = $(this).val(); // Obtener el valor del input actual
        websites.push(valor); // Agregar el valor al array
    });
    formData.delete('kt_docs_repeater_basic[0][websites[]]');
    formData.append('websites', websites);

    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formPerfilWhatsapp);
            $('.btnCerrarModal').trigger('click');
        }
        generalidades.ocultarCargando(formPerfilWhatsapp);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formPerfilWhatsapp);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formPerfilWhatsapp, response.validaciones);
    }
    const rutaActualizar = route("perfilWhatsapp.update");
    generalidades.create(rutaActualizar, config, success, error);
    generalidades.mostrarCargando(formPerfilWhatsapp);
}

$(document).on('change', '#inputFile', function() {
    var file = $(this).prop('files')[0];
    var reader = new FileReader();

    reader.onload = function(e) {
    $('.imgPerfil').attr('src', e.target.result);
    }

    reader.readAsDataURL(file);
});
