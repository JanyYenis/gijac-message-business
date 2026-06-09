"use strict";

const formCrearUsuario = '#formCrearUsuario';
const modalCrearUsuario = '#modalCrearUsuario';

$(function () {
    iniciarComponentes(formCrearUsuario);
    generalidades.validarFormulario(formCrearUsuario, enviarDatos);
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
    $(`${form} #selectPais`).select2({
        templateSelection: optionFormat,
        templateResult: optionFormat,
        dropdownParent: $(`${form}`), // 👈 muy importante
    });

    $(document).on('change', `${form} #selectPais`, function(){
        if (this.value) {
            $.ajax({
                type: 'GET',
                url: route('ciudades.buscar', {'pais': this.value}),
                success: function(response) {
                    if (response.estado == 'success') {
                        let ciudades = response?.ciudades ?? [];
                        let selectCiudad = $(`${form} #selectCiudad`);
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
                        $(`${form} #selectCiudad`).attr('disabled', false);
                    }
                    generalidades.toastrGenerico(response?.estado, response?.mensaje);
                    // $('.divOpciones').removeClass('d-none');
                }
            });
        } else {
            $(`${form} #selectCiudad`).attr('disabled', true);
        }
    });

    $(`${form} #selectPais`).trigger('change');
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formCrearUsuario"));
    let inputTelefono = generalidades.darTelefonoInput(`${formCrearUsuario} #tel`);
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
            generalidades.ocultarValidaciones(formCrearUsuario);
            $('.btnCerrarModal').trigger('click');
            window.listadoUsuarios();
        }
        generalidades.ocultarCargando(formCrearUsuario);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formCrearUsuario);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formCrearUsuario, response.validaciones);
    }
    const ruta = route("usuarios.store");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(formCrearUsuario);
}

$(document).on('hidden.bs.modal', modalCrearUsuario, function (e) {
    generalidades.resetValidate(formCrearUsuario);
});
