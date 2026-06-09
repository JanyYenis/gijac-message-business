"use strict";

const rutaEditar = "campanas.edit";
const seccionEditar = ".seccionEditar";
const formEditarCampana = '#formEditarCampana';
const modalEditarCampana = '#modalEditarCampana';
const tablaContactos = '#tablaContactosEditar';
var contenidoCampanaOriginal = '';
window.contactosSeleccionados_editar = [];
var datos = {1: 'Nombre'};
var urlArchivoOriginal = '';
var tipo_header = 1;
window.campana_selecionada_editar = 0;
var tipo_variables = null;
var nombres_variables = [];

var validar_carga = 0;

$(function () {
    generalidades.validarFormulario(formEditarCampana, enviarDatos);
});

const iniciarComponentes = (form) => {
    $(`${form} #selectCategoriaEditar`).select2({
        minimumResultsForSearch: -1,
        allowClear: true
    });

    $(`${form} #selectEtiquetaEditar`).select2({
        allowClear: true
    });

    $(`${form} #selectPlantillaEditar`).select2({
        allowClear: true
    });
}

$(document).on('shown.bs.modal', '#modalEditarCampana', function () {
    $("#scheduleDateEdit").flatpickr({
        minDate: "today",
        appendTo: document.body
    });
    $("#scheduleTimeEdit").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        appendTo: document.body
    });
    validar_carga = 0;
    currentStep = 1;
});

$(document).on("click", ".btnEditar", function () {
    let id = $(this).attr("data-campana");
    window.campana_selecionada_editar = id;
    if (id) {
        cargarDatos(id);
    }
});

const cargarDatos = (id) => {
    const ruta = route(rutaEditar, { "campana": id });
    generalidades.mostrarCargando('body');
    generalidades.refrescarSeccion(null, ruta, seccionEditar, function(){
        $(modalEditarCampana).modal('show');
        iniciarComponentes(formEditarCampana);
        $('#selectPlantillaEditar').trigger('change');
        $('#selectEtiquetaEditar').trigger('change');
        contenidoCampanaOriginal = '';
        window.contactosSeleccionados_editar = [];
        datos = {1: 'Nombre'};
        urlArchivoOriginal = '';
        tipo_header = 1;
    });
}

$(document).on('change', '#selectPlantillaEditar', function () {
    let valor = 0;
    if (this.value) {
        valor = this.value;
    }
    tipo_header = 1;
    $('#checkUsarRecursoEditar').prop('checked', false).trigger('change');
    $('#inputFileEditar').attr('disabled', false);
    nombres_variables = [];

    $('#templatePreviewEdit').empty();
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
                            $('.seccionEncabezadoEditar').removeClass('d-none');
                            $(`${formEditarCampana} input[name="header_text"]`).attr('required', true);
                        } else {
                            $('.seccionEncabezadoEditar').addClass('d-none');
                            $(`${formEditarCampana} input[name="header_text"]`).attr('required', false);
                        }
                    } else if (template.header.format === 2) {
                        url = JSON.parse(template.header.example).header_handle[0];
                        previewHTML += `<img src="${url}" alt="Header" class="template-header-media">`;
                        $('#inputFileEditar').attr('accept', 'image/png');
                        $('.seccionEncabezadoEditar').addClass('d-none');
                        $(`${formEditarCampana} input[name="header_text"]`).attr('required', false);
                    } else if (template.header.format === 3) {
                        url = JSON.parse(template.header.example).header_handle[0];
                        previewHTML += `<video src="${url}" controls class="template-header-media"></video>`;
                        $('#inputFileEditar').attr('accept', 'video/mp4');
                        $('.seccionEncabezadoEditar').addClass('d-none');
                        $(`${formEditarCampana} input[name="header_text"]`).attr('required', false);
                    } else if (template.header.format === 4) {
                        url = JSON.parse(template.header.example).header_handle[0];
                        previewHTML += `<a href="${url}" target="_blank"><img src="../../img/documento-defecto.png" alt="Header" class="template-header-media"></a>`;
                        $('#inputFileEditar').attr('accept', 'application/pdf');
                        $('.seccionEncabezadoEditar').addClass('d-none');
                        $(`${formEditarCampana} input[name="header_text"]`).attr('required', false);
                    } else {
                        $('.seccionEncabezadoEditar').addClass('d-none');
                        $(`${formEditarCampana} input[name="header_text"]`).attr('required', false);
                    }

                    if (url) {
                        urlArchivoOriginal = url;
                    }

                    if (template.header.format !== 1 && template.header.format !== 5) {
                        $('#divInputFileEditar').removeClass('d-none');
                        $('#inputFileEditar').attr('required', true);
                    } else {
                        $('#divInputFileEditar').addClass('d-none');
                        $('#inputFileEditar').attr('required', false);
                    }
                } else {
                    $(`${formEditarCampana} input[name="header_text"]`).attr('required', false);
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
                        let datoVariable = '';
                        if (template.parameter_format == 1 && template.body.example) {
                            for (let index = 0; index < JSON.parse(template?.body?.example).body_text[0].length; index++) {
                                if (response?.datos_variables?.length) {
                                    datoVariable = response?.datos_variables[index] ?? '';
                                }
                                nombres_variables.push(index + 1);
                                variables = variables + `<div class="row mb-3 inputVariableEditar">
                                    <div class="col-lg-7 col-md-7">
                                        <input type="text" name="variables" required data-numero="${index+1}" placeholder="Ingrese valor de variable {{${index+1}}}" class="form-control variablesCampana" value='${datoVariable}'>
                                    </div>
                                    <div class="col-lg-5 col-md-5">
                                        <select name="" data-numero="${index+1}" class="form-control selectsCampos"
                                            data-placeholder="Campos del contacto">
                                            <option value=""></option>
                                            <option ${datoVariable == 'nombre_completo' ? 'selected' : ''} value="nombre_completo">Nombre Completo</option>
                                            <option ${datoVariable == 'nombre' ? 'selected' : ''} value="nombre">Nombre</option>
                                            <option ${datoVariable == 'apellido' ? 'selected' : ''} value="apellido">Apellido</option>
                                            <option ${datoVariable == 'numero_completo' ? 'selected' : ''} value="numero_completo">Telefono</option>
                                        </select>
                                    </div>
                                </div>`;
                            }
                        } else if (template.parameter_format == 2) {
                            JSON.parse(template?.body?.example).body_text_named_params.forEach(ejemplo => {
                                nombres_variables.push(ejemplo?.param_name);
                            });
                            for (let index = 0; index < JSON.parse(template?.body?.example).body_text_named_params.length; index++) {
                                if (response?.datos_variables?.length) {
                                    datoVariable = response?.datos_variables[index] ?? '';
                                }
                                variables = variables + `<div class="row mb-3 inputVariableEditar">
                                    <div class="col-lg-7 col-md-7">
                                        <input type="text" name="variables" required data-numero="${index+1}" placeholder="Ingrese valor de variable {{${response?.nombres_variables[index]}}}" class="form-control variablesCampana" value='${datoVariable}'>
                                    </div>
                                    <div class="col-lg-5 col-md-5">
                                        <select name="" data-numero="${index+1}" class="form-control selectsCampos"
                                            data-placeholder="Campos del contacto">
                                            <option value=""></option>
                                            <option ${datoVariable == 'nombre_completo' ? 'selected' : ''} value="nombre_completo">Nombre Completo</option>
                                            <option ${datoVariable == 'nombre' ? 'selected' : ''} value="nombre">Nombre</option>
                                            <option ${datoVariable == 'apellido' ? 'selected' : ''} value="apellido">Apellido</option>
                                            <option ${datoVariable == 'numero_completo' ? 'selected' : ''} value="numero_completo">Telefono</option>
                                        </select>
                                    </div>
                                </div>`;
                            }
                        }
                        $('#divTituloVariableEditar').removeClass('d-none');
                        $('#divVariablesEditar').html(variables);
                        $(`${formEditarCampana} .selectsCampos`).select2({
                            minimumResultsForSearch: -1,
                            allowClear: true
                        });
                    } else {
                        $('.inputVariableEditar').remove();
                        $('#divTituloVariableEditar').addClass('d-none');
                    }
                } else {
                    $('.inputVariableEditar').remove();
                    $('#divTituloVariableEditar').addClass('d-none');
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
                        let valorUrl = '';
                        botones.forEach((button, index) => {
                            const buttonClass = button.type === 'PHONE_NUMBER' ? 'call-button' : '';
                            const icon = button.type === 'PHONE_NUMBER' ? '<i class="fas fa-phone me-1"></i>' :
                                        button.type === 'URL' ? '<i class="fas fa-external-link-alt me-1"></i>' : '';
                            previewHTML += `<button type="button" class="template-button ${buttonClass}">${icon}${button.text}</button>`;
                            if (button.type === 'URL' && button?.example) {
                                if (response?.urls_de_campana.length) {
                                    valorUrl = response?.urls_de_campana[index];
                                }
                                variables = variables + `<div class="mb-3 inputUrls">
                                    <input type="url" class="form-control" value="${valorUrl}" placeholder="URL ${index+1}" data-nombreurls="${response.url_nombres[index]}" name="urls">
                                </div>`;
                            }
                        });
                        previewHTML += '</div>';

                        $('#divUrlEditar').html(variables);
                    }
                } else {
                    $('.inputUrlsEditar').remove();
                }

                // Add timestamp
                previewHTML += '<div class="message-time">12:34 <span class="text-success">✓✓</span></div>';

                document.getElementById('templatePreviewEdit').innerHTML = previewHTML;
            } else {
                return;
            }
        }
    }

    const error = (response) => {
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    generalidades.get(route('plantillas.show', {plantilla: valor, campana: window.campana_selecionada_editar}), config, success, error);
    generalidades.mostrarCargando('body');
});

$(document).on('click', '#sendNowEdit', function () {
    $('#scheduledFieldsEdit').addClass('d-none');
    $('#scheduledTimeFieldEdit').addClass('d-none');
    $('#scheduleDateEdit').attr('required', false);
    $('#scheduleTimeEdit').attr('required', false);
});

$(document).on('click', '#sendScheduledEdit', function () {
    if (this.checked) {
        $('#scheduledFieldsEdit').removeClass('d-none');
        $('#scheduledTimeFieldEdit').removeClass('d-none');
        $('#scheduleDateEdit').attr('required', true);
        $('#scheduleTimeEdit').attr('required', true);
    } else {
        $('#scheduledFieldsEdit').addClass('d-none');
        $('#scheduledTimeFieldEdit').addClass('d-none');
        $('#scheduleDateEdit').attr('required', false);
        $('#scheduleTimeEdit').attr('required', false);
    }
});

// Define una función que toma la cadena original y un objeto de datos para reemplazar
const remplazarDatos = (cadena, datos) => {
    // Utiliza una expresión regular para encontrar todas las coincidencias de {{algo}}
    return cadena.replace(/\{\{(\d+)\}\}/g, function(match, grupo1) {
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

$(document).on('input', '.variablesCampanaEditar', function(){
    if ($(this).val()) {
        let numero = $(this).attr('data-numero');
        datos[numero] = $(this).val();
        let cadenaNueva = remplazarDatos(contenidoCampanaOriginal, datos);
        $('.contenidocampanaEditar').text(cadenaNueva);
    } else {
        $('.contenidocampanaEditar').text(contenidoCampanaOriginal);
    }
});

$(document).on('change', '#inputFileEditar', function() {
    var file = $(this).prop('files')[0];
    if (file) {
        var reader = new FileReader();

        reader.onload = function(e) {
            if (tipo_header == 2) {
                $('.imagenCampanaEditar').removeClass('d-none');
                $('.videoCampanaEditar').addClass('d-none');
                $('.imgCampanaEditar').attr('src', e.target.result);
            } else if (tipo_header == 3) {
                $('.imagenCampanaEditar').addClass('d-none');
                $('.videoCampanaEditar').removeClass('d-none');
                $('.videoCampana1Editar').attr('src', e.target.result);
            }
        }

        reader.readAsDataURL(file);
    } else {
        if (tipo_header == 2) {
            $('.imgCampanaEditar').attr('src', '../../img/defecto.png');
            $('.imagenCampanaEditar').removeClass('d-none');
            $('.videoCampanaEditar').addClass('d-none');
        } else if (tipo_header == 3) {
            $('.imagenCampanaEditar').removeClass('d-none');
            $('.videoCampanaEditar').addClass('d-none');
            $('.imgCampanaEditar').attr('src', '../../img/video-defecto.png');
        } else if (tipo_header == 4) {
            $('.imagenCampanaEditar').removeClass('d-none');
            $('.videoCampanaEditar').addClass('d-none');
            $('.imgCampanaEditar').attr('src', '../../img/documento-defecto.png');
        }
    }
});

$(document).on('change', '.selectsCamposEditar', function(){
    let index = $(this).attr('data-numero');
    if ($(this).val()) {
        $(`.variablesCampanaEditar[data-numero='${index}']`).val($(this).val()).attr('readonly', true);
    } else {
        $(`.variablesCampanaEditar[data-numero='${index}']`).val('').attr('readonly', false);
    }
    $(this).trigger('input');
});

$(document).on('click', '#checkUsarRecursoEditar', function(){
    if (this.checked) {
        $('#inputFileEditar').attr('disabled', true);
        if (tipo_header == 2) {
            $('.imagenCampanaEditar').removeClass('d-none');
            $('.videoCampanaEditar').addClass('d-none');
            $('.imgCampanaEditar').attr('src', urlArchivoOriginal);
        } else if (tipo_header == 3) {
            $('.imagenCampanaEditar').addClass('d-none');
            $('.videoCampanaEditar').removeClass('d-none');
            $('.videoCampana1Editar').attr('src', urlArchivoOriginal);
        }
    } else {
        $('#inputFileEditar').attr('disabled', false);
        if (tipo_header == 2) {
            $('.imgCampanaEditar').attr('src', '../../img/defecto.png');
            $('.imagenCampanaEditar').removeClass('d-none');
            $('.videoCampanaEditar').addClass('d-none');
        } else if (tipo_header == 3) {
            $('.imagenCampanaEditar').removeClass('d-none');
            $('.videoCampanaEditar').addClass('d-none');
            $('.imgCampanaEditar').attr('src', '../../img/video-defecto.png');
        } else if (tipo_header == 4) {
            $('.imagenCampanaEditar').removeClass('d-none');
            $('.videoCampanaEditar').addClass('d-none');
            $('.imgCampanaEditar').attr('src', '../../img/documento-defecto.png');
        }
    }
});

$(document).on('change', '#selectEtiquetaEditar', function(){
    window.contactosSeleccionados_editar = [];
    listadoContactosEnviar();
    $('.checkSeleccionarTodosEditar').prop("checked", false);
    if ($('#selectEtiquetaEditar').val().length) {
        $('.seccionContactosEditar').removeClass('d-none');
    } else {
        $('.seccionContactosEditar').addClass('d-none');
    }
});

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formEditarCampana"));
    formData.append('usar_recurso', $(`${formEditarCampana} #checkUsarRecursoEditar`).is(':checked') ? 1 : 0);
    if ($(`${formEditarCampana} #checkUsarRecursoEditar`).is(':checked')) {
        formData.append('url_recurso', urlArchivoOriginal);
    }

    let urlsData = {}; // Objeto para almacenar los datos

    $(`${formEditarCampana} input[name='urls']`).each(function() {
        let nombre = $(this).attr("data-nombreurls"); // Obtener data-nombreurls
        let valor = $(this).val().trim(); // Obtener valor del input y limpiar espacios

        if (valor !== "") { // Si tiene valor, agregar al objeto
            urlsData[nombre] = valor;
        }
    });

    formData.append('urls', JSON.stringify(urlsData));

    let variables = {}; // Objeto para almacenar los datos
    let index = 0;
    $(`${formEditarCampana} input[name="variables"]`).each(function() {
        let valor = $(this).val(); // Obtener valor del input

        if (valor !== "") { // Si tiene valor, agregar al objeto
            variables[index] = valor;
            index++;
        }
    });

    formData.append('variables', JSON.stringify(variables));


    $(`#tablaContactosEditar .checkSeleccionado`).each(function () {
        let idContacto = $(this).attr("data-registro");
        if (this.checked) {
            // Asegurarse de que el valor no existe antes de agregarlo
            if (!window.contactosSeleccionados_editar.includes(idContacto)) {
                window.contactosSeleccionados_editar.push(idContacto);
            }
        } else {
            window.contactosSeleccionados_editar = window.contactosSeleccionados_editar.filter((contacto) => contacto != idContacto);
        }
    });

    let textoMensaje = '';
    let textoBtn = '';
    if (formData.get('estado') == 1) {
        textoMensaje = '¿Está seguro de que desea actualizar y enviar la campaña?';
        textoBtn = 'Actualizar y enviar';
    } else {
        textoMensaje = '¿Está seguro de que desea actualizar la campaña?';
        textoBtn = 'Actualizar';
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
            formData.append('contactos', window.contactosSeleccionados_editar);

            if (formData.get('estado') == 2) {
                formData.append('fecha_envio', formData.get('fecha')+' '+formData.get('hora'));
            }

            const config = {
                'method': 'PUT',
                'headers': {
                    'Accept': generalidades.CONTENT_TYPE_JSON,
                },
                'body': formData
            }

            const success = (response) => {
                if (response.estado == 'success' || response.estado == 'info') {
                    generalidades.ocultarValidaciones(formEditarCampana);
                    $('.btnCerrarModal').trigger('click');
                    window.listadoCampana();
                    window.cargarListado();
                }
                generalidades.ocultarCargando(formEditarCampana);
                generalidades.toastrGenerico(response?.estado, response?.mensaje);
            }

            const error = (response) => {
                generalidades.ocultarCargando(formEditarCampana);
                generalidades.toastrGenerico(response?.estado, response?.mensaje);
                generalidades.mostrarValidaciones(formEditarCampana, response.validaciones);
            }
            const ruta = route("campanas.update", {campana: formData.get('id')});
            generalidades.edit(ruta, config, success, error);
            generalidades.mostrarCargando(formEditarCampana);
        }
    });
}

/**
 * Función que permite cargar el listado.
 */
const listadoContactosEnviar = () => {
    var table = $("#tablaContactosEditar").DataTable({
        paging: true,
        responsive: true,
        serverSide: false,
        scrollX: true,
        searchDelay: 500,
        deferRender: true,

        ajax: {
            "url": route('campanas.cargarContactos', {campana: window.campana_selecionada_editar}),
            "type": "GET",

            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                data.etiquetas = $('#selectEtiquetaEditar').val();
                generalidades.mostrarCargando(tablaContactos);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaContactos);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-download"></i> Excel',
                className: "btn btn-light-success",
                title: "Listado Contactos.",
                exportOptions: {
                    columns: ":not(.excluir)"
                }
            },
            {
                text: '<i class="fa fa-sync-alt"></i> Actualizar',
                className: "btn btn-bg-secondary",
                action: function (e, dt, node, config) {
                    dt.ajax.reload(null, false);
                }
            }
        ],
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
        dom: `<'row d-flex align-items-center justify-content-end'
                <'d-flex align-items-center justify-content-end'B>><'row d-flex align-items-center justify-content-between'<'col-sm-6 col-lg-6 col-md-6'l><'col-sm-6 col-lg-6 col-md-6'f>>
            <'table-responsive'tr>
            <'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>`,
        drawCallback: function(settings) {},
        initComplete: function () {
            $('#checkSeleccionarTodosEditar').prop("checked", true);
        },
    });
}

const marcarSeleccionados = () => {
    // activar el evento del click del check de seleccionar todos.
    $(document).on("change", ".checkSeleccionarTodosEditar", function () {
        let seleccionado = this.checked;
        if (this.checked) {
            $(this).prop("checked", true);
        }
        $(".checkSeleccionadoEditar").each(function () {
            if (this.checked == seleccionado) {
                return;
            }

            this.checked = seleccionado;
            $(this).trigger('change');
        });
    });
}

$(document).on('hidden.bs.modal', modalEditarCampana, function (e) {
    window.campana_selecionada_editar = 0;
});


let currentStep = 1;
const totalSteps = 4;

// Initialize
document.addEventListener('DOMContentLoaded', function () {
    // updateCharCount();
    // updateContactCount();

    // Radio button listeners
    document.querySelectorAll('input[name="sendTypeEdit"]').forEach(radio => {
        radio.addEventListener('change', toggleScheduleFields);
    });

    // File attachment listener
    // document.getElementById('attachFile').addEventListener('change', function () {
    //     document.getElementById('fileUpload').style.display = this.checked ? 'block' : 'none';
    // });

    // Contact selection listeners
    // document.getElementById('selectAll').addEventListener('change', function () {
    //     const checkboxes = document.querySelectorAll('.contact-checkbox');
    //     checkboxes.forEach(cb => cb.checked = this.checked);
    //     updateContactCount();
    // });

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
    document.getElementById(`stepEdit-${currentStep}`).classList.remove('active');
    document.querySelector(`.stepper-item[data-step="${currentStep}"]`).classList.remove('active');

    // Mark completed steps
    if (direction > 0) {
        document.querySelector(`.stepper-item[data-step="${currentStep}"]`).classList.add('completed');
    }

    // Show new step
    currentStep = newStep;
    document.getElementById(`stepEdit-${currentStep}`).classList.add('active');
    document.querySelector(`.stepper-item[data-step="${currentStep}"]`).classList.add('active');

    // Update buttons
    updateNavigationButtons();

    // Update summary if on step 4
    if (currentStep === 4) {
        updateSummary();
    }
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prevBtnEdit');
    const nextBtn = document.getElementById('nextBtnEdit');
    const launchBtn = document.getElementById('launchBtnEdit');

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
    const isScheduled = document.getElementById('sendScheduledEdit').checked;
    const scheduledFields = document.getElementById('scheduledFieldsEdit');
    const scheduledTimeField = document.getElementById('scheduledTimeFieldEdit');

    scheduledFields.style.display = isScheduled ? 'block' : 'none';
    scheduledTimeField.style.display = isScheduled ? 'block' : 'none';
}

function updatePreview() {
    const messageContent = document.getElementById('contenidocampanaEditar').value;
    const previewText = document.getElementById('previewTextEdit');

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

    // updateCharCount();
}

function updateCharCount() {
    const messageContent = document.getElementById('contenidocampanaEditar').value;
    const charCount = document.getElementById('charCount');
    charCount.textContent = `${messageContent.length}/4096`;

    if (messageContent.length > 4000) {
        charCount.style.color = 'var(--secondary-color)';
    } else {
        charCount.style.color = 'var(--gray-500)';
    }
}

function insertVariable(variable) {
    const textarea = document.getElementById('contenidocampanaEditar');
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
    document.getElementById('selectedCountEdit').textContent = count;
}

function updateSummary() {
    // Update summary fields
    document.getElementById('summaryNameEdit').textContent =
        document.getElementById('campaignNameEdit').value || '-';

    const categorySelect = document.getElementById('selectCategoriaEditar');
    document.getElementById('summaryCategoryEdit').textContent =
        categorySelect.options[categorySelect.selectedIndex].text || '-';

    const sendType = document.querySelector('#formEditarCampana input[name="estado"]:checked').value;
    document.getElementById('summarySendTypeEdit').textContent =
        sendType === '1' ? 'Inmediato' : 'Programado';

    const selectedContacts = document.querySelectorAll('.checkSeleccionado:checked').length;
    document.getElementById('summaryContactsEdit').textContent = selectedContacts;

    const messageContent = document.getElementById('contenidocampanaEditar').value;
    document.getElementById('summaryMessageEdit').innerHTML =
        messageContent ? `<div style="white-space: pre-wrap;">${messageContent}</div>` :
            '<em class="text-muted">No hay mensaje configurado</em>';
}

function launchCampaign() {
    // Show confirmation modal or process launch
    if (confirm('¿Estás seguro de que deseas lanzar esta campaña? Esta acción no se puede deshacer.')) {
        // Here you would typically send the data to your backend
        alert('¡Campaña lanzada exitosamente! Serás redirigido al dashboard.');
        // Redirect to campaigns list or dashboard
        // window.location.href = '/campaigns';
    }
}

$(document).on('click', '#nextBtnEdit', function() {
    changeStep(1);
});

$(document).on('click', '#prevBtnEdit', function() {
    changeStep(-1);
});
