"use strict";

// rutas
const rutaEditar = "proyectos.usuarios.edit";

// id y clases
const formEditarUsuario = "#formEditarUsuario";

$(function () {
    iniciarComponentes();
    generalidades.validarFormulario(formEditarUsuario, enviarDatos);
});


const iniciarComponentes = (form = "") => {
    generalidades.initTelefonoInput(`${form} #tel`);

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
    $('#selectPaisEdit').trigger('change');
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formEditarUsuario"));
    let inputTelefono = generalidades.darTelefonoInput(`${formEditarUsuario} #tel`);
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
            generalidades.ocultarValidaciones(formEditarUsuario);
            location.reload();
        }
        generalidades.ocultarCargando(formEditarUsuario);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formEditarUsuario);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formEditarUsuario, response.validaciones);
    }
    const rutaActualizar = route("perfil.update", { "usuario": formData.get("id") });
    generalidades.create(rutaActualizar, config, success, error);
    generalidades.mostrarCargando(formEditarUsuario);
}

$(document).on('click', '#btnEditar', function(){
    $('#tabSeccionEditar').trigger('click');
});

$(document).on('click', '.btnCancelarDosFactores', function(){
    Swal.fire({
        text: '¿Está seguro que desea inhabilitar la autenticación de dos factores?',
        icon: "info",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Si",
        cancelButtonText: "No",
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-active-light"
        }
    }).then((function(result) {
        if (result.value) {
            cancelar();
        }
    }))
});

const cancelar = () => {
    let formData = new FormData(document.getElementById("formEditarUsuario"));
    formData.append('google2fa_secret', null);
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
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formEditarUsuario);
            location.reload();
        }
        generalidades.ocultarCargando(formEditarUsuario);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formEditarUsuario);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formEditarUsuario, response.validaciones);
    }
    const rutaActualizar = route("perfil.update", { "usuario": formData.get("id") });
    generalidades.create(rutaActualizar, config, success, error);
    generalidades.mostrarCargando(formEditarUsuario);
}
