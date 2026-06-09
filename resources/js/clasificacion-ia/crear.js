"use strict";

const promptForm = '#promptForm';

$(function () {
    iniciarComponentes();
    generalidades.validarFormulario(promptForm, enviarDatos);
});

const iniciarComponentes = (form = "") => {
    //
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("promptForm"));

    const prompt = $('#promptTextarea').val().trim();

    // Validation
    if (!prompt) {
        window.showAlert('warning', 'Campo vacío', 'Por favor, escribe un prompt antes de guardar.');
        $('#promptTextarea').addClass('shake');
        setTimeout(() => {
            $('#promptTextarea').removeClass('shake');
        }, 500);
        $('#promptTextarea').focus();
        return;
    }

    if (prompt.length < 20) {
        window.showAlert('warning', 'Prompt muy corto', 'El prompt debe tener al menos 20 caracteres para ser efectivo.');
        $('#promptTextarea').focus();
        return;
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
            generalidades.ocultarValidaciones(promptForm);
            // Save to localStorage
            localStorage.setItem('saved_prompt', $('#promptTextarea').val().trim());
            localStorage.removeItem('draft_prompt');

            // Show success message
            showAlert('success', '¡Prompt guardado exitosamente!', 'La configuración de clasificación ha sido actualizada correctamente.');

            // Success animation
            $('#saveBtn').html('<i class="fas fa-check"></i> ¡Guardado!');
            setTimeout(() => {
                $('#saveBtn').html('<i class="fas fa-save"></i> Guardar Prompt');
            }, 3000);
        }
        generalidades.ocultarCargando(promptForm);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(promptForm);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(promptForm, response.validaciones);
    }
    const ruta = route("clasificacion-ia.store");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(promptForm);
}
