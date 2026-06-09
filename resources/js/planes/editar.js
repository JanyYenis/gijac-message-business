"use strict";

// rutas
const rutaEditar = "planes.edit";

// id y clases
const formEditarPlanes = "#formEditarPlanes";
const seccionEditar = ".seccionEditar";
const modalEditar = "#modalEditarPlanes";

$(function () {
    generalidades.validarFormulario(formEditarPlanes, enviarDatos);
});

$(document).on("click", ".btnEditar", function () {
    let id = $(this).attr("data-plan");
    if (id) {
        // id = JSON.parse(id);
        cargarDatos(id);
    }
});

const cargarDatos = (id) => {
    const ruta = route(rutaEditar, { "plan": id });
    generalidades.mostrarCargando('body');
    generalidades.ejecutar('GET', ruta, 'body', modalEditar, seccionEditar, function(){
        iniciarComponentes(formEditarPlanes);
        updatePreview();
    });
}

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
    let formData = new FormData(document.getElementById("formEditarPlanes"));
    formData.append('_method', 'PUT');

    // Get services data
    $('#formEditarPlanes .service-toggle').each(function() {
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
            generalidades.ocultarValidaciones(formEditarPlanes);
            $('.btnCerrarModal').trigger('click');
            window.listadoPlanes();
        }
        generalidades.ocultarCargando(formEditarPlanes);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formEditarPlanes);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formEditarPlanes, response.validaciones);
    }
    const rutaActualizar = route("planes.update", { "plan": formData.get("id") });
    generalidades.create(rutaActualizar, config, success, error);
    generalidades.mostrarCargando(formEditarPlanes);
}

// Toggle max contacts field
$(document).on('change', '#limitarContactosEdit', function() {
    if ($(this).is(':checked')) {
        $('#maxContactosContainerEdit').slideDown();
        $('#max_contactosEdit').prop('required', true);
    } else {
        $('#maxContactosContainerEdit').slideUp();
        $('#max_contactosEdit').prop('required', false).val('');
        updatePreview();
    }
});

// Update preview on form changes
$(document).on('input change', '#valorEdit, #tipoEdit, #max_contactosEdit', updatePreview);

// Service toggle change
$(document).on('change', '#formEditarPlanes .service-toggle', function() {
    const serviceItem = $(this).closest('.service-item');
    if ($(this).is(':checked')) {
        serviceItem.addClass('active');
    } else {
        serviceItem.removeClass('active');
    }
});

function updatePreview() {
    const valor = parseFloat($('#valorEdit').val()) || 0;
    const tipo = $('#tipoEdit').val();
    const maxContactos = $('#max_contactosEdit').val();

    // Update price
    $('#previewPriceEdit').text('$' + valor.toFixed(2));

    // Update period
    let period = 'por mes';
    if (tipo === 2) period = 'por año';
    else if (tipo === 3) period = 'personalizado';
    $('#previewPeriodEdit').text(period);

    // Update contacts
    let contactsText = 'Contactos ilimitados';
    if (maxContactos && maxContactos > 0) {
        contactsText = parseInt(maxContactos).toLocaleString() + ' contactos máximo';
    }
    $('#previewContactsEdit').html('<i class="fas fa-users me-2"></i>' + contactsText);
}
