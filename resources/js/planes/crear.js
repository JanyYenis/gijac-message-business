"use strict";

const formCrearPlan = '#formCrearPlan';
const modalCrearPlanes = '#modalCrearPlanes';

$(function () {
    iniciarComponentes(formCrearPlan);
    generalidades.validarFormulario(formCrearPlan, enviarDatos);
});

const iniciarComponentes = (form = "") => {
    var dialerElement = document.querySelector(`${form} .seccionDialer`);

    // Create dialer object and initialize a new instance
    var dialerObject = new KTDialer(dialerElement, {
        min: 0,
        max: 5000000,
        step: 0,
        prefix: "$",
        decimals: 0
    });
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formCrearPlan"));
    // Get services data
    $('#formCrearPlan .service-toggle').each(function() {
        const name = $(this).attr('name');
        const match = name ? name.match(/\[([^\]]+)\]/) : null;

        if (match && match[1]) {
            formData.append('servicios[]', match[1]);
        }
    });

    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formCrearPlan);
            $('.btnCerrarModal').trigger('click');
            window.listadoPlanes();
        }
        generalidades.ocultarCargando(formCrearPlan);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formCrearPlan);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formCrearPlan, response.validaciones);
    }
    const ruta = route("planes.store");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(formCrearPlan);
}

$(document).on('hidden.bs.modal', modalCrearPlanes, function (e) {
    generalidades.resetValidate(formCrearPlan);
    resetModal();
});

// Toggle max contacts field
$(document).on('change', '#limitarContactos', function() {
    if ($(this).is(':checked')) {
        $('#maxContactosContainer').slideDown();
        $('#max_contactos').prop('required', true);
    } else {
        $('#maxContactosContainer').slideUp();
        $('#max_contactos').prop('required', false).val('');
        updatePreview();
    }
});

// Update preview on form changes
$(document).on('input change', '#valor, #tipo, #max_contactos', updatePreview);

// Service toggle change
$(document).on('change', '.service-toggle', function() {
    const serviceItem = $(this).closest('.service-item');
    if ($(this).is(':checked')) {
        serviceItem.addClass('active');
    } else {
        serviceItem.removeClass('active');
    }
});

function updatePreview() {
    const valor = parseFloat($('#valor').val()) || 0;
    const tipo = $('#tipo').val();
    const maxContactos = $('#max_contactos').val();

    // Update price
    $('#previewPrice').text('$' + valor.toFixed(2));

    // Update period
    let period = 'por mes';
    if (tipo === 2) period = 'por año';
    else if (tipo === 3) period = 'personalizado';
    $('#previewPeriod').text(period);

    // Update contacts
    let contactsText = 'Contactos ilimitados';
    if (maxContactos && maxContactos > 0) {
        contactsText = parseInt(maxContactos).toLocaleString() + ' contactos máximo';
    }
    $('#previewContacts').html('<i class="fas fa-users me-2"></i>' + contactsText);
}

function resetModal() {
    $('#maxContactosContainer').hide();
    $('#limitarContactos').prop('checked', false);
    $('.service-item').removeClass('active');
    $('.service-toggle').prop('checked', false);
    $('.form-control, .form-select').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    updatePreview();
}
