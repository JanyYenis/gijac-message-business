"use strict";

// rutas
const rutaEditar = "usuarios.edit";

// id y clases
const formEditarUsuario = "#formEditarUsuario";
const seccionEditar = ".seccionEditar";
const modalEditar = "#modalEditarUsuario";

$(function () {
    generalidades.validarFormulario(formEditarUsuario, enviarDatos);
});

$(document).on("click", ".btnAccionesUsuarios .btnEditar", function () {
    let id = $(this).attr("data-usuario");
    if (id) {
        // id = JSON.parse(id);
        cargarDatos(id);
    }
});

const cargarDatos = (id) => {
    const ruta = route(rutaEditar, { "usuario": id });
    generalidades.mostrarCargando('body');
    generalidades.ejecutar('GET', ruta, 'body', modalEditar, seccionEditar, function(){
        iniciarComponentes(formEditarUsuario);
        $(`${formEditarUsuario} #selectPaisEdit`).trigger('change');
    });
}

const iniciarComponentes = (form = "") => {
    generalidades.initTelefonoInput(`${form} #tel`);

    $(`${form} #selectGeneroEdit`).select2({
        minimumResultsForSearch: -1
    });
    $(`${form} #selectTipoIdentificacionEdit`).select2({
        minimumResultsForSearch: -1
    });

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
    }).mask(`${form} #inputEmail`);

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

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formEditarUsuario"));
    let inputTelefono = generalidades.darTelefonoInput(`${formEditarUsuario} #tel`);
	let tel = inputTelefono?.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
    tel = tel.replace(/\((\w+)\)/g, "$1");
    tel = tel.replace(/-/g, "");
    tel = tel.replace(/\s/g, "");
	let codigo = inputTelefono?.getSelectedCountryData()?.dialCode ?? '';
	let nombre_tel = inputTelefono?.getSelectedCountryData()?.iso2 ?? '';
    formData.set('telefono', tel);
    formData.set('codigo_tel', codigo);
    formData.set('nombre_tel', nombre_tel);

    const config = {
        'method': 'PUT',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            $('.btnCerrarModal').trigger('click');
            generalidades.ocultarValidaciones(formEditarUsuario);
        }
        generalidades.ocultarCargando(formEditarUsuario);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        $("#tablaUsuarios").DataTable().ajax.reload(null, false);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formEditarUsuario);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formEditarUsuario, response.validaciones);
    }
    const rutaActualizar = route("usuarios.update", { "usuario": formData.get("uuid") });
    generalidades.edit(rutaActualizar, config, success, error);
    generalidades.mostrarCargando(formEditarUsuario);
}
