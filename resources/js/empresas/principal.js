"use strict";

const formEmpresa = '#formEmpresa';
// ===== Logo Upload =====
const $logoInput = $('#logoInput');
const $logoPreview = $('#logoPreview');
const $logoPlaceholder = $('#logoPlaceholder');
const $logoContainer = $('#logoPreviewContainer');
const $logoRemove = $('#logoRemoveBtn');

let logoFile = null;

$(function () {
    iniciarComponentes();
    generalidades.validarFormulario(formEmpresa, enviarDatos);
});

const iniciarComponentes = () => {
    $(document).on('click', '#btnUploadLogo', function () {
        $logoInput.click();
    });

    $logoContainer.on('click', function (e) {
        if (!logoFile && e.target.id !== 'logoRemoveBtn' && !$(e.target).closest('.logo-remove-btn')
            .length) {
            $logoInput.click();
        }
    });

    $logoInput.on('change', function () {
        if (this.files.length) handleLogoFile(this.files[0]);
    });

    // Drag & Drop
    $logoContainer.on('dragover', function (e) {
        e.preventDefault();
        $(this).addClass('dragover');
    }).on('dragleave drop', function (e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        if (e.type === 'drop' && e.originalEvent.dataTransfer.files.length) {
            handleLogoFile(e.originalEvent.dataTransfer.files[0]);
        }
    });

    $logoRemove.on('click', function (e) {
        e.stopPropagation();
        $logoPreview.attr('src', '').removeClass('show');
        $logoPlaceholder.show();
        $logoContainer.removeClass('has-image');
        $logoRemove.removeClass('show');
        $logoInput.val('');
        logoFile = null;
    });

    // Char Counter
    $('#companyDescription').on('input', function () {
        const len = $(this).val().length;
        const $counter = $('#charCounter');
        $counter.text(`${len} / 500 caracteres`);
        $counter.removeClass('warning danger');
        if (len > 450) $counter.addClass('danger');
        else if (len > 350) $counter.addClass('warning');
    });

    generalidades.initTelefonoInput(`${formEmpresa} #companyPhone`);

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
    }).mask(`${formEmpresa} #companyEmail`);
}

const handleLogoFile = (file) => {
    if (!file.type.startsWith('image/')) {
        generalidades.toastrGenerico('error', 'Formato no válido: Solo se permiten archivos de imagen (PNG, JPG).');
        return;
    }
    if (file.size > 2 * 1024 * 1024) {
        generalidades.toastrGenerico('error', 'Archivo demasiado grande: El tamaño máximo permitido es 2 MB.');
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        $logoPreview.attr('src', e.target.result).addClass('show');
        $logoPlaceholder.hide();
        $logoContainer.addClass('has-image');
        $logoRemove.addClass('show');
        logoFile = file;
        generalidades.toastrGenerico('success', 'Logo cargado: La imagen se ha cargado correctamente.');
    };
    reader.readAsDataURL(file);
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formEmpresa"));
    let inputTelefono = generalidades.darTelefonoInput(`${formEmpresa} #companyPhone`);
	let tel = inputTelefono?.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
    tel = tel.replace(/\((\w+)\)/g, "$1");
    tel = tel.replace(/-/g, "");
    tel = tel.replace(/\s/g, "");
	let codigo = inputTelefono?.getSelectedCountryData()?.dialCode ?? '';
	let nombre_tel = inputTelefono?.getSelectedCountryData()?.iso2 ?? '';
    formData.set('telefono', codigo+tel);
    formData.append('estado', $("#toggleStatus").is(":checked") ? 1 : 0)
    formData.append('publicar', $("#togglePublic").is(":checked") ? 1 : 0)
    formData.append('notificacion', $("#toggleNotifications").is(":checked") ? 1 : 0)

    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formEmpresa);
        }
        generalidades.ocultarCargando(formEmpresa);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formEmpresa);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formEmpresa, response.validaciones);
    }
    const ruta = route("negocios.store");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(formEmpresa);
}
