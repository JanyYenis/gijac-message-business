"use strict";

const formCrearCampana = '#formCrearCampana';
const modalCrearCampana = '#modalCrearCampana';
const tablaContactos = '#tablaContactos';
var contenidoCampanaOriginal = '';
window.contactosSeleccionados = [];
var datos = { 1: 'Nombre' };
var urlArchivoOriginal = '';
var tipo_header = 1;
var tipo_variables = null;
var nombres_variables = [];

var validar_carga = 0;

$(function () {
    generalidades.validarFormulario(formCrearCampana, enviarDatos);
});

$(document).on('shown.bs.modal', '#modalCrearCampana', function () {
    $("#scheduleDate").flatpickr({
        minDate: "today",
        appendTo: document.body
    });
    $("#scheduleTime").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        appendTo: document.body
    });

    currentStep = 1;
});

$(document).on('change', '#selectPlantilla', function () {
    let valor = 0;
    if (this.value) {
        valor = this.value;
    }
    tipo_header = 1;
    $('#checkUsarRecurso').prop('checked', false).trigger('change');
    $('#inputFile').attr('disabled', false);
    nombres_variables = [];

    $('#templatePreview').empty();
    const config = {
        'method': 'GET',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
    }

    const success = (response) => {
        generalidades.ocultarCargando('body');
        if (response.estado == 'success') {
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
            if (response?.plantilla) {
                let template = response?.plantilla;
                let previewHTML = '';

                // Header (image/video)
                if (template.header) {
                    tipo_header = template.header.format ?? 1;
                    let url = '';
                    if (template.header.format === 1) {
                        let texto = template.header?.text ?? 'N/A';
                        // texto += JSON.parse(template.header.example)?.header_text[0] ?? '';
                        previewHTML += `<h3>${texto}</h3>`;
                        if (template?.header?.example && JSON.parse(template?.header?.example)?.header_text) {
                            $('.seccionEncabezado').removeClass('d-none');
                            $(`${formCrearCampana} input[name="header_text"]`).attr('required', true);
                        } else {
                            $('.seccionEncabezado').addClass('d-none');
                            $(`${formCrearCampana} input[name="header_text"]`).attr('required', false);
                        }
                    } else if (template.header.format === 2) {
                        url = JSON.parse(template.header.example).header_handle[0];
                        previewHTML += `<img src="${url}" alt="Header" class="template-header-media">`;
                        $('#inputFile').attr('accept', 'image/png');
                        $('.seccionEncabezado').addClass('d-none');
                        $(`${formCrearCampana} input[name="header_text"]`).attr('required', false);
                    } else if (template.header.format === 3) {
                        url = JSON.parse(template.header.example).header_handle[0];
                        previewHTML += `<video src="${url}" controls class="template-header-media"></video>`;
                        $('#inputFile').attr('accept', 'video/mp4');
                        $('.seccionEncabezado').addClass('d-none');
                        $(`${formCrearCampana} input[name="header_text"]`).attr('required', false);
                    } else if (template.header.format === 4) {
                        url = JSON.parse(template.header.example).header_handle[0];
                        previewHTML += `<a href="${url}" target="_blank"><img src="../../img/documento-defecto.png" alt="Header" class="template-header-media"></a>`;
                        $('#inputFile').attr('accept', 'application/pdf');
                        $('.seccionEncabezado').addClass('d-none');
                        $(`${formCrearCampana} input[name="header_text"]`).attr('required', false);
                    } else {
                        $('.seccionEncabezado').addClass('d-none');
                        $(`${formCrearCampana} input[name="header_text"]`).attr('required', false);
                    }

                    if (url) {
                        urlArchivoOriginal = url;
                    }

                    if (template.header.format !== 1 && template.header.format !== 5) {
                        $('#divInputFile').removeClass('d-none');
                        $('#inputFile').attr('required', true);
                    } else {
                        $('#divInputFile').addClass('d-none');
                        $('#inputFile').attr('required', false);
                    }
                } else {
                    $(`${formCrearCampana} input[name="header_text"]`).attr('required', false);
                }

                // Body
                if (template.body) {
                    let bodyText = template.body.text
                        .replace(/{{1}}/g, 'Juan Pérez')
                        .replace(/{{2}}/g, 'ORD-2024-001')
                        .replace(/{{3}}/g, '299.99')
                        .replace(/{{4}}/g, '15 de Enero, 2024')
                        .replace(/{{5}}/g, 'Av. Siempre Viva 123')
                        .replace(/\*([^*]+)\*/g, '<strong>$1</strong>')
                        .replace(/\n/g, '<br>');

                    previewHTML += `<div class="template-body">${bodyText}</div>`;
                    if (template?.body?.example) {
                        let variables = '';
                        if (template.parameter_format == 1 && template.body.example) {
                            for (let index = 0; index < JSON.parse(template?.body?.example).body_text[0].length; index++) {
                                nombres_variables.push(index + 1);
                                variables = variables + `<div class="row mb-3 inputVariable">
                                            <div class="col-lg-7 col-md-7">
                                                <input type="text" name="variables[]" required data-numero="${index + 1}" placeholder="Ingrese valor de variable {{${index + 1}}}" class="form-control variablesCampana" value=''>
                                            </div>
                                            <div class="col-lg-5 col-md-5">
                                                <select name="" data-numero="${index + 1}" class="form-control selectsCampos"
                                                    data-placeholder="Campos del contacto">
                                                    <option value=""></option>
                                                    <option value="nombre_completo">Nombre Completo</option>
                                                    <option value="nombre">Nombre</option>
                                                    <option value="apellido">Apellido</option>
                                                    <option value="numero_completo">Telefono</option>
                                                </select>
                                            </div>
                                        </div>`;
                            }
                        } else if (template.parameter_format == 2) {
                            JSON.parse(template?.body?.example).body_text_named_params.forEach(ejemplo => {
                                nombres_variables.push(ejemplo?.param_name);
                            });
                            for (let index = 0; index < JSON.parse(template?.body?.example).body_text_named_params.length; index++) {
                                variables = variables + `<div class="row mb-3 inputVariable">
                                            <div class="col-lg-7 col-md-7">
                                                <input type="text" name="variables[]" required data-numero="${index + 1}" placeholder="Ingrese valor de variable {{${nombres_variables[index]}}}" class="form-control variablesCampana" value=''>
                                            </div>
                                            <div class="col-lg-5 col-md-5">
                                                <select name="" data-numero="${index + 1}" class="form-control selectsCampos"
                                                    data-placeholder="Campos del contacto">
                                                    <option value=""></option>
                                                    <option value="nombre_completo">Nombre Completo</option>
                                                    <option value="nombre">Nombre</option>
                                                    <option value="apellido">Apellido</option>
                                                    <option value="numero_completo">Telefono</option>
                                                </select>
                                            </div>
                                        </div>`;
                            }
                        }
                        $('#divTituloVariable').removeClass('d-none');
                        $('#divVariables').html(variables);
                        $(`${formCrearCampana} .selectsCampos`).select2({
                            minimumResultsForSearch: -1,
                            allowClear: true
                        });
                    } else {
                        $('.inputVariable').remove();
                        $('#divTituloVariable').addClass('d-none');
                    }
                } else {
                    $('.inputVariable').remove();
                    $('#divTituloVariable').addClass('d-none');
                }

                // Footer
                if (template.footer) {
                    previewHTML += `<div class="template-footer">${template.footer.text}</div>`;
                }

                // Buttons
                if (template.buttons) {
                    let botones = JSON.parse(template.buttons.buttons) ?? [];
                    if (botones.length > 0) {
                        previewHTML += '<div class="template-buttons">';
                        let variables = '';
                        botones.forEach((button, index) => {
                            const buttonClass = button.type === 'PHONE_NUMBER' ? 'call-button' : '';
                            const icon = button.type === 'PHONE_NUMBER' ? '<i class="fas fa-phone me-1"></i>' :
                                        button.type === 'URL' ? '<i class="fas fa-external-link-alt me-1"></i>' : '';
                            previewHTML += `<button type="button" class="template-button ${buttonClass}">${icon}${button.text}</button>`;
                            if (button.type === 'URL' && button?.example) {
                                variables = variables + `<div class="mb-3 inputUrls">
                                    <input type="url" class="form-control" placeholder="URL ${index + 1}" name="urls[${button?.text}]">
                                </div>`;
                            }
                        });
                        previewHTML += '</div>';

                        $('#divUrl').html(variables);
                    }
                } else {
                    $('.inputUrls').remove();
                }

                // Add timestamp
                previewHTML += '<div class="message-time">12:34 <span class="text-success">✓✓</span></div>';

                document.getElementById('templatePreview').innerHTML = previewHTML;
            } else {
                return;
            }
        }
    }

    const error = (response) => {
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    generalidades.get(route('plantillas.show', {plantilla: valor}), config, success, error);
    generalidades.mostrarCargando('body');
});

$(document).on('click', '#sendNow', function () {
    $('#scheduledFields').addClass('d-none');
    $('#scheduledTimeField').addClass('d-none');
    $('#scheduleDate').attr('required', false);
    $('#scheduleTime').attr('required', false);
});

$(document).on('click', '#sendScheduled', function () {
    if (this.checked) {
        $('#scheduledFields').removeClass('d-none');
        $('#scheduledTimeField').removeClass('d-none');
        $('#scheduleDate').attr('required', true);
        $('#scheduleTime').attr('required', true);
    } else {
        $('#scheduledFields').addClass('d-none');
        $('#scheduledTimeField').addClass('d-none');
        $('#scheduleDate').attr('required', false);
        $('#scheduleTime').attr('required', false);
    }
});

// Define una función que toma la cadena original y un objeto de datos para reemplazar
const remplazarDatos = (cadena, datos) => {
    // Utiliza una expresión regular para encontrar todas las coincidencias de {{algo}}
    return cadena.replace(/\{\{(\d+)\}\}/g, function (match, grupo1) {
        // El grupo1 contendrá el número entre las {{}}, que se usa como índice en el objeto de datos
        const indice = parseInt(grupo1, 10);

        // Verifica si el índice existe en los datos y reemplaza con el valor correspondiente
        if (datos.hasOwnProperty(indice)) {
            return datos[indice];
        }

        // Si el índice no existe en los datos, simplemente deja la cadena original sin cambios
        return match;
    });
}

// $(document).on('input', '.variablesCampana', function () {
//     if ($(this).val()) {
//         let numero = $(this).attr('data-numero');
//         datos[numero] = $(this).val();
//         let cadenaNueva = remplazarDatos(contenidoCampanaOriginal, datos);
//         $('.template-body').text(cadenaNueva);
//     } else {
//         $('.template-body').text(contenidoCampanaOriginal);
//     }
// });

$(document).on('change', '#inputFile', function () {
    var file = $(this).prop('files')[0];
    if (file) {
        var reader = new FileReader();

        reader.onload = function (e) {
            if (tipo_header == 2 || tipo_header == 3) {
                $('.template-header-media').attr('src', e.target.result);
            }
        }

        reader.readAsDataURL(file);
    } else {
        if (tipo_header == 2 || tipo_header == 3) {
            $('.template-header-media').attr('src', urlArchivoOriginal);
        } else if (tipo_header == 4) {
            $('.template-header-media').attr('src', '../../img/documento-defecto.png');
        }
    }
});

$(document).on('change', '.selectsCampos', function () {
    let index = $(this).attr('data-numero');
    if ($(this).val()) {
        $(`.variablesCampana[data-numero='${index}']`).val($(this).val()).attr('readonly', true);
    } else {
        $(`.variablesCampana[data-numero='${index}']`).val('').attr('readonly', false);
    }
    $(this).trigger('input');
});

$(document).on('click', '#checkUsarRecurso', function () {
    if (this.checked) {
        $('#inputFile').attr('disabled', true);
        if (tipo_header == 2 || tipo_header == 3) {
            $('.template-header-media').attr('src', urlArchivoOriginal);
        }
    } else {
        $('#inputFile').attr('disabled', false);
        if (tipo_header == 2 || tipo_header == 3) {
            $('.template-header-media').attr('src', urlArchivoOriginal);
        } else if (tipo_header == 4) {
            $('.template-header-media').attr('src', '../../img/documento-defecto.png');
        }
    }
});

$(document).on('change', '#selectEtiqueta', function () {
    window.contactosSeleccionados = [];
    listadoContactosEnviar();
    $('.checkSeleccionarTodos').prop("checked", false);
    if ($('#selectEtiqueta').val().length) {
        $('.seccionContactos').removeClass('d-none');
    } else {
        $('.seccionContactos').addClass('d-none');
    }
});

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formCrearCampana"));
    formData.append('usar_recurso', $(`${formCrearCampana} #checkUsarRecurso`).is(':checked') ? 1 : 0);
    if ($(`${formCrearCampana} #checkUsarRecurso`).is(':checked')) {
        formData.append('url_recurso', urlArchivoOriginal);
    }
    $(`#tablaContactos .checkSeleccionado`).each(function () {
        let idContacto = $(this).attr("data-registro");
        if (this.checked) {
            // Asegurarse de que el valor no existe antes de agregarlo
            if (!window.contactosSeleccionados.includes(idContacto)) {
                window.contactosSeleccionados.push(idContacto);
            }
        } else {
            window.contactosSeleccionados = window.contactosSeleccionados.filter((contacto) => contacto != idContacto);
        }
    });

    let textoMensaje = '';
    let textoBtn = '';
    if (formData.get('estado') == 1) {
        textoMensaje = '¿Está seguro de que desea crear y enviar la campaña?';
        textoBtn = 'Crear y enviar';
    } else {
        textoMensaje = '¿Está seguro de que desea crear la campaña?';
        textoBtn = 'Crear';
    }
    Swal.fire({
        icon: "info",
        text: textoMensaje,
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: textoBtn,
        cancelButtonText: "Cancelar",
        customClass: {
            confirmButton: "btn btn-primary",
            cancelButton: "btn btn-active-light"
        }
    }).then(function (result) {
        if (result.value) {
            formData.append('nombres_variables', nombres_variables);
            formData.append('contactos', window.contactosSeleccionados);

            if (formData.get('estado') == 2) {
                formData.append('fecha_envio', formData.get('fecha') + ' ' + formData.get('hora'));
            }

            const config = {
                'method': 'POST',
                'headers': {
                    'Accept': generalidades.CONTENT_TYPE_JSON,
                },
                'body': formData
            }

            const success = (response) => {
                if (response.estado == 'success' || response.estado == 'info') {
                    generalidades.ocultarValidaciones(formCrearCampana);
                    $('.btnCerrarModal').trigger('click');
                    window.listadoCampana();
                    window.cargarListado();
                }
                generalidades.ocultarCargando(formCrearCampana);
                generalidades.toastrGenerico(response?.estado, response?.mensaje);
            }

            const error = (response) => {
                generalidades.ocultarCargando(formCrearCampana);
                generalidades.toastrGenerico(response?.estado, response?.mensaje);
                generalidades.mostrarValidaciones(formCrearCampana, response.validaciones);
            }
            const ruta = route("campanas.store");
            generalidades.create(ruta, config, success, error);
            generalidades.mostrarCargando(formCrearCampana);
        }
    });
}

/**
 * Función que permite cargar el listado.
 */
const listadoContactosEnviar = () => {
    var table = $("#tablaContactos").DataTable({
        paging: true,
        responsive: true,
        serverSide: false,
        scrollX: true,
        searchDelay: 500,
        deferRender: true,

        ajax: {
            "url": route('campanas.cargarContactos'),
            "type": "GET",

            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                data.etiquetas = $('#selectEtiqueta').val() ?? null;
                generalidades.mostrarCargando(tablaContactos);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaContactos);
                return json.data
            },
        },
        columnDefs: [
            {
                targets: "all",
                className: "text-center"
            },
            {
                targets: "none",
                className: "text-justify"
            }
        ],
        columns: [
            {
                data: 'id',
                name: 'id',
            },
            {
                data: 'nombre_completo',
                name: 'nombre_completo',
            },
            {
                data: 'numero_completo',
                name: 'numero_completo',
            },
            {
                data: 'etiquetas',
                name: 'etiquetas',
            },
            {
                data: 'ultima_interaccion',
                name: 'ultima_interaccion',
                render: function (data, type, full, meta) {
                    return 'N/A';
                }
            },
        ],
        order: [
            [0, "asc"]
        ],
        lengthMenu: [
            [-1],
            ["Todos"]
        ],
        dom: `<'table-responsive'tr>
            <'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>`,
        drawCallback: function (settings) { },
        initComplete: function () {
            $('#checkSeleccionarTodos').prop("checked", true);
        },
    });
}

const marcarSeleccionados = () => {
    // activar el evento del click del check de seleccionar todos.
    $(document).on("change", ".checkSeleccionarTodos", function () {
        let seleccionado = this.checked;
        if (this.checked) {
            $(this).prop("checked", true);
        }
        $(".checkSeleccionado").each(function () {
            if (this.checked == seleccionado) {
                return;
            }

            this.checked = seleccionado;
            $(this).trigger('change');
        });
    });
}

$(document).on('hidden.bs.modal', modalCrearCampana, function (e) {
    generalidades.resetValidate(formCrearCampana);
    $('#divUrl').addClass('d-none');
    $('#inputFile').attr('disabled', true);
    $('#checkUsarRecurso').prop('checked', false).trigger('change');
    validar_carga = 0;
});

let currentStep = 1;
const totalSteps = 4;

// Initialize
document.addEventListener('DOMContentLoaded', function () {
    // Radio button listeners
    document.querySelectorAll('input[name="sendType"]').forEach(radio => {
        radio.addEventListener('change', toggleScheduleFields);
    });

    document.querySelectorAll('.contact-checkbox').forEach(cb => {
        cb.addEventListener('change', updateContactCount);
    });
});

function changeStep(direction) {
    const newStep = currentStep + direction;

    if (newStep < 1 || newStep > totalSteps) return;

    if (!validar_carga && newStep == (totalSteps - 1)) {
        listadoContactosEnviar();
        validar_carga = 1;
    }
    // Hide current step
    document.getElementById(`step-${currentStep}`).classList.remove('active');
    document.querySelector(`.stepper-item[data-step="${currentStep}"]`).classList.remove('active');

    // Mark completed steps
    if (direction > 0) {
        document.querySelector(`.stepper-item[data-step="${currentStep}"]`).classList.add('completed');
    }

    // Show new step
    currentStep = newStep;
    document.getElementById(`step-${currentStep}`).classList.add('active');
    document.querySelector(`.stepper-item[data-step="${currentStep}"]`).classList.add('active');

    // Update buttons
    updateNavigationButtons();

    // Update summary if on step 4
    if (currentStep === 4) {
        updateSummary();
    }
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const launchBtn = document.getElementById('launchBtn');

    prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';

    if (currentStep === totalSteps) {
        nextBtn.style.display = 'none';
        launchBtn.style.display = 'inline-block';
    } else {
        nextBtn.style.display = 'inline-block';
        launchBtn.style.display = 'none';
    }
}

function toggleScheduleFields() {
    const isScheduled = document.getElementById('sendScheduled').checked;
    const scheduledFields = document.getElementById('scheduledFields');
    const scheduledTimeField = document.getElementById('scheduledTimeField');

    scheduledFields.style.display = isScheduled ? 'block' : 'none';
    scheduledTimeField.style.display = isScheduled ? 'block' : 'none';
}

function updatePreview() {
    const messageContent = document.getElementById('contenidocampana').value;
    const previewText = document.getElementById('previewText');

    if (messageContent.trim()) {
        // Replace variables with sample data
        let preview = messageContent
            .replace(/\{\{nombre\}\}/g, 'Juan')
            .replace(/\{\{apellido\}\}/g, 'Pérez')
            .replace(/\{\{empresa\}\}/g, 'Mi Empresa')
            .replace(/\{\{telefono\}\}/g, '+54 11 1234-5678')
            .replace(/\{\{email\}\}/g, 'juan@empresa.com');

        previewText.textContent = preview;
    } else {
        previewText.textContent = 'Tu mensaje aparecerá aquí...';
    }
}

function updateCharCount() {
    const messageContent = document.getElementById('contenidocampana').value;
    const charCount = document.getElementById('charCount');
    charCount.textContent = `${messageContent.length}/4096`;

    if (messageContent.length > 4000) {
        charCount.style.color = 'var(--secondary-color)';
    } else {
        charCount.style.color = 'var(--gray-500)';
    }
}

function insertVariable(variable) {
    const textarea = document.getElementById('contenidocampana');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;

    textarea.value = text.substring(0, start) + variable + text.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + variable.length, start + variable.length);

    updatePreview();
}

function updateContactCount() {
    const selectedCheckboxes = document.querySelectorAll('.contact-checkbox:checked');
    const count = selectedCheckboxes.length;
    document.getElementById('selectedCount').textContent = count;
}

function updateSummary() {
    // Update summary fields
    document.getElementById('summaryName').textContent =
        document.getElementById('campaignName').value || '-';

    const categorySelect = document.getElementById('campaignCategory');
    document.getElementById('summaryCategory').textContent =
        categorySelect.options[categorySelect.selectedIndex].text || '-';

    const sendType = document.querySelector('input[name="estado"]:checked').value;
    document.getElementById('summarySendType').textContent =
        sendType === '1' ? 'Inmediato' : 'Programado';

    const selectedContacts = document.querySelectorAll('.checkSeleccionado:checked').length;
    document.getElementById('summaryContacts').textContent = selectedContacts;

    const messageContent = document.getElementById('contenidocampana').value;
    document.getElementById('summaryMessage').innerHTML =
        messageContent ? `<div style="white-space: pre-wrap;">${messageContent}</div>` :
            '<em class="text-muted">No hay mensaje configurado</em>';
}

$(document).on('click', '#nextBtn', function() {
    changeStep(1);
});

$(document).on('click', '#prevBtn', function() {
    changeStep(-1);
});
