"use strict";

// rutas
const rutaEditar = "contactos.edit";

// id y clases
const formEditarContacto = "#formEditarContacto";
const seccionEditar = ".seccionEditar";
const modalEditar = "#modalEditarContacto";
const tablaSubLinesEspecialidadesEdit = "#tablaSubLinesEspecialidadesEdit";

$(function () {
    generalidades.validarFormulario(formEditarContacto, enviarDatos);
});

const iniciarComponentes = (form = '') => {
    $(`${form} #selectGeneroEdit`).select2({
        minimumResultsForSearch: -1
    });

    $(`${form} #selectTipoIdentificacionEdit`).select2({
        minimumResultsForSearch: -1
    });

    $(`${form} #selectEtiquetasEdit`).select2();

    Inputmask({
        mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
        greedy: false,
        onBeforePaste: function (pastedValue, opts) {
            pastedValue = pastedValue.toLowerCase();
            return pastedValue.replace("mailto:", "");
        },
        definitions: {
            "*": {
                validator: '[0-9A-Za-z!#$%&"*+/=?^_`{|}~\-]',
                cardinality: 1,
                casing: "lower"
            }
        }
    }).mask("#inputEmailEdit");

    generalidades.initTelefonoInput(`${form} #telEdit`);

    // Format options
    var optionFormat = function(item) {
        if ( !item.id ) {
            return item.text;
        }

        var span = document.createElement('span');
        var imgUrl = item.element.getAttribute('data-kt-select2-country');
        var template = '';

        template += '<img src="' + imgUrl + '" class="rounded-circle h-40px w-40px me-4" alt="image"/>';
        template += item.text;

        span.innerHTML = template;

        return $(span);
    }

    // Init Select2 --- more info: https://select2.org/
    $(`${form} #selectPaisEdit`).select2({
        templateSelection: optionFormat,
        templateResult: optionFormat
    });

    $(document).on('change', `${form} #selectPaisEdit`, function(){
        if (this.value) {
            $.ajax({
                type: 'GET',
                url: route('ciudades.buscar', {'pais': this.value}),
                success: function(response) {
                    if (response.estado == 'success') {
                        let ciudades = response?.ciudades ?? [];
                        let selectCiudad = $(`${form} #selectCiudadEdit`);
                        selectCiudad.empty();
                        let opcion = new Option('', '', false, false);
                        selectCiudad.append(opcion);
                        ciudades.forEach((ciudad) => {
                            let selected = false;
                            if (selectCiudad.attr('data-ciudad') && selectCiudad.attr('data-ciudad') == ciudad.id) {
                                selected = true;
                            }
                            selectCiudad.append(new Option(ciudad.text, ciudad.id, selected, selected));
                        });
                        $(`${form} #selectCiudadEdit`).attr('disabled', false);
                        $(`${form} #selectCiudadEdit`).select2();
                    }
                    generalidades.toastrGenerico(response?.estado, response?.mensaje);
                    // $('.divOpciones').removeClass('d-none');
                }
            });
        } else {
            $(`${form} #selectCiudadEdit`).attr('disabled', true);
        }
    });
}

$(document).on("click", ".editarContacto", function () {
    let id = $(this).attr("data-contacto");
    if (id) {
        // id = JSON.parse(id);
        cargarDatos(id);
    }
});

$(document).on('shown.bs.modal', '#modalEditarContacto', function () {
    let element = document.querySelector("#modalEditarContacto #kt_stepper_example_clickable_edit");
    let stepper = new KTStepper(element);
    stepper.on("kt.stepper.click", function (stepper) {
        stepper.goTo(stepper.getClickedStepIndex()); // go to clicked step
    });
    stepper.on("kt.stepper.next", function (stepper) {
        stepper.goNext(); // go next step
    });
    stepper.on("kt.stepper.previous", function (stepper) {
        stepper.goPrevious(); // go previous step
    });
});

const cargarDatos = (id) => {
    const ruta = route(rutaEditar, { "contacto": id });
    generalidades.mostrarCargando('body');
    generalidades.refrescarSeccion(null, ruta, seccionEditar, function(){
        $(modalEditar).modal('show');
        iniciarComponentes(formEditarContacto);
        $(`${formEditarContacto} #selectPaisEdit`).trigger('change');
    });
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formEditarContacto"));
    let inputTelefono = generalidades.darTelefonoInput(`${formEditarContacto} #telEdit`);
	let tel = inputTelefono?.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
    tel = tel.replace(/\((\w+)\)/g, "$1");
    tel = tel.replace(/-/g, "");
    tel = tel.replace(/\s/g, "");
	let codigo = inputTelefono?.getSelectedCountryData()?.dialCode ?? '';
    formData.set('telefono', tel);
    formData.set('codigo_telefono', codigo);
    formData.set('etiquetas', $(`${formEditarContacto} #selectEtiquetasEdit`).val());

    const config = {
        'method': 'PUT',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            $(modalEditar).modal('hide');
            generalidades.ocultarValidaciones(formEditarContacto);
            window.listadoContactos();
        }
        generalidades.ocultarCargando(formEditarContacto);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formEditarContacto);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formEditarContacto, response.validaciones);
    }
    const rutaActualizar = route("contactos.update", { "contacto": formData.get("id") });
    generalidades.edit(rutaActualizar, config, success, error);
    generalidades.mostrarCargando(formEditarContacto);
}
