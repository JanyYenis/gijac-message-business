import Generalidades from '../generalidades';

Generalidades.prototype.CONTENT_TYPE_JSON = "application/json";
Generalidades.prototype.CONTENT_TYPE_HTML = "text/html";
Generalidades.prototype.CONTENT_TYPE_FORMDATA = "multipart/form-data";

Generalidades.prototype.mostrarCargando = function (elemento = 'body') {
    if (window.blockUI) {
        window.blockUI.destroy();
    }

    if (window.blockUI && window.blockUI.isBlocked()) {
        window.blockUI.release();
    } else {
        var target = document.querySelector(elemento);
        window.blockUI = new KTBlockUI(target);
        window.blockUI.block();
    }
}

Generalidades.prototype.ocultarCargando = function (elemento = 'body') {
    if (window.blockUI.isBlocked()) {
        window.blockUI.release();
        window.blockUI.destroy();
    }
}

// peticiones
Generalidades.prototype.ejecutar = function (method = 'GET', ruta, elemento = 'body', modal = null, div = null, completado = false) {
    setTimeout(function() {
        $.ajax({
            type: method,
            url: ruta,
            success: function(response) {
                if (response.html != undefined) {
                    $(div).html(response.html);
                }
                if (modal) {
                    generalidades.modalActual(modal);
                }
                if (completado != false) {
                    completado(response);
                }
                generalidades.ocultarCargando(elemento);
            }
        });
    },300);
}

// genericos
Generalidades.prototype.dataTables = function (dt, config, filtro = null) {
    var table = $(dt);
    table.DataTable(config);
    // if (filtro) {
    //     this.buscarEnListado(dt, filtro);
    // }
    return table;
}

// formularios
Generalidades.prototype.Select2 = function (
    config = {}
) {
    let id = config.id ?? null;
    let ruta = config.ruta ?? null;
    let params = config.params ?? {};
    let dropdownParent = config.dropdownParent ?? null;
    let allowClear = config.allowClear ?? true;
    let minimumInputLength = config.minimo ?? 3;
    let initialData = config.initialData ?? [];
    let mantenerBusqueda = config.mantenerBusqueda ?? false;
    let processResults = config.processResults ?? false;
    let ajax = null;
    let minimumResultsForSearch = config.minimumResultsForSearch ?? Infinity;

    if (!processResults) {
        processResults = function (data) {
            return {
                results: data
            };
        }
    }
    if (ruta) {
        ajax = {
            url: ruta,
            dataType: "json",
            delay: 250,
            type: "GET",
            data: function (parametros) {
                params.busqueda = parametros.term;
                return params;
            },
            headers: {
                "X-CSRF-TOKEN": this.token,
                "Content-Type": "application/x-www-form-urlencoded"
            },
            processResults: processResults,
            cache: true
        }
    }
    let resultado = $(id);
    let formularioCercano = $(id).closest("form");
        resultado.select2({
            theme: "bootstrap-5",
            dropdownParent: dropdownParent,
            allowClear: allowClear,
            width: "100%",
            data: initialData,
            ajax: ajax,
            minimumResultsForSearch: minimumResultsForSearch,
            minimumInputLength: minimumInputLength,
            language: {
                errorLoading: function () {
                    return "No se pudieron cargar los resultados";
                },
                inputTooLong: function (args) {
                    var remainingChars =
                        args.input.length - args.maximum;

                    var message =
                        "Por favor, elimine " +
                        remainingChars +
                        " car";

                    if (remainingChars == 1) {
                        message += "ácter";
                    } else {
                        message += "acteres";
                    }

                    return message;
                },
                inputTooShort: function (args) {
                    var remainingChars =
                        args.minimum - args.input.length;

                    var message =
                        "Por favor, introduzca " +
                        remainingChars +
                        " car";

                    if (remainingChars == 1) {
                        message += "ácter";
                    } else {
                        message += "acteres";
                    }

                    return message;
                },
                loadingMore: function () {
                    return "Cargando más resultados…";
                },
                maximumSelected: function (args) {
                    var message =
                        "Sólo puede seleccionar " +
                        args.maximum +
                        " elemento";

                    if (args.maximum != 1) {
                        message += "s";
                    }

                    return message;
                },
                noResults: function () {
                    return "No se encontraron resultados";
                },
                searching: function () {
                    return "Buscando…";
                }
            },
            escapeMarkup: function(markup) {
                return markup;
            },
            // templateResult: function(repo){
            //     if (repo.loading) return repo.text;
            //     var markup = "<div class='select2-result-repository clearfix'>" +
            //         "<div class='select2-result-repository__meta'>" +
            //         "<div class='select2-result-repository__title'>" + repo.full_name + "</div>";
            //     if (repo.description) {
            //         markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
            //     }
            //     markup += "<div class='select2-result-repository__statistics'>" +
            //         "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.forks_count + " Forks</div>" +
            //         "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
            //         "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
            //         "</div>" +
            //         "</div></div>";
            //     return markup;
            // },
            // templateSelection: function(repo){
            //     return repo.full_name || repo.text;
            // }
        })
        .on("change", function () {
            let formContenedor = $(this).closest("form");
            if (formContenedor && formContenedor.data("validator")) {
                $(this).valid();
            }
        });

    // Este es un workaround que puede dejar de funcionar en próximas versiones de Select2.
    if (mantenerBusqueda) {
        // Verificar que no haya sido definido ya.
        if (Generalidades.prototype.terminosBuscados == undefined) {
            Generalidades.prototype.terminosBuscados = {};
        }
        // Cuando se abra, asignar el texto de búsqueda.
        resultado.on('select2:open', function () {
            if (Generalidades.prototype.terminosBuscados != undefined && Generalidades.prototype.terminosBuscados[id]) {
                $('.select2-search input')
                    .focus()
                    .val(Generalidades.prototype.terminosBuscados[id])
                    .trigger('input');
            }
        });
        // Cuando se cierre el select2, guardar el término de búsqueda.
        resultado.on('select2:closing', function () {
            Generalidades.prototype.terminosBuscados[id] = $('.select2-search input').prop('value');
        });
    }
    return resultado;
}

Generalidades.prototype.select2Modal = function (
    config = {}
) {
    let id = config.id ?? null;
    let ruta = config.ruta ?? null;
    let params = config.params ?? {};
    let allowClear = config.allowClear ?? true;
    let minimumInputLength = config.minimo ?? 3;
    let initialData = config.initialData ?? [];
    let mantenerBusqueda = config.mantenerBusqueda ?? false;
    let processResults = config.processResults ?? false;
    let ajax = null;
    let minimumResultsForSearch = config.minimumResultsForSearch ?? Infinity;
    let modal  = config?.modal ?? null;

    if (!processResults) {
        processResults = function (data) {
            return {
                results: data
            };
        }
    }
    if (ruta) {
        ajax = {
            url: ruta,
            dataType: "json",
            delay: 250,
            type: "GET",
            data: function (parametros) {
                params.busqueda = parametros.term;
                return params;
            },
            headers: {
                "X-CSRF-TOKEN": this.token,
                "Content-Type": "application/x-www-form-urlencoded"
            },
            processResults: processResults,
            cache: true
        }
    }
    let resultado = $(id);

        resultado.select2({
            theme: "bootstrap-5",
            dropdownParent: $(modal),
            allowClear: allowClear,
            width: "100%",
            data: initialData,
            ajax: ajax,
            minimumResultsForSearch: minimumResultsForSearch,
            minimumInputLength: minimumInputLength,
            language: {
                errorLoading: function () {
                    return "No se pudieron cargar los resultados";
                },
                inputTooLong: function (args) {
                    var remainingChars =
                        args.input.length - args.maximum;

                    var message =
                        "Por favor, elimine " +
                        remainingChars +
                        " car";

                    if (remainingChars == 1) {
                        message += "ácter";
                    } else {
                        message += "acteres";
                    }

                    return message;
                },
                inputTooShort: function (args) {
                    var remainingChars =
                        args.minimum - args.input.length;

                    var message =
                        "Por favor, introduzca " +
                        remainingChars +
                        " car";

                    if (remainingChars == 1) {
                        message += "ácter";
                    } else {
                        message += "acteres";
                    }

                    return message;
                },
                loadingMore: function () {
                    return "Cargando más resultados…";
                },
                maximumSelected: function (args) {
                    var message =
                        "Sólo puede seleccionar " +
                        args.maximum +
                        " elemento";

                    if (args.maximum != 1) {
                        message += "s";
                    }

                    return message;
                },
                noResults: function () {
                    return "No se encontraron resultados";
                },
                searching: function () {
                    return "Buscando…";
                }
            },
        })
        .on("change", function () {
            let formContenedor = $(this).closest("form");
            if (formContenedor && formContenedor.data("validator")) {
                $(this).valid();
            }
        });

    resultado.addClass('kt-select2');

    // Este es un workaround que puede dejar de funcionar en próximas versiones de Select2.
    if (mantenerBusqueda) {
        // Verificar que no haya sido definido ya.
        if (Generalidades.prototype.terminosBuscados == undefined) {
            Generalidades.prototype.terminosBuscados = {};
        }
        // Cuando se abra, asignar el texto de búsqueda.
        resultado.on('select2:open', function () {
            if (Generalidades.prototype.terminosBuscados != undefined && Generalidades.prototype.terminosBuscados[id]) {
                $('.select2-search input')
                    .focus()
                    .val(Generalidades.prototype.terminosBuscados[id])
                    .trigger('input');
            }
        });
        // Cuando se cierre el select2, guardar el término de búsqueda.
        resultado.on('select2:closing', function () {
            Generalidades.prototype.terminosBuscados[id] = $('.select2-search input').prop('value');
        });
    }
    return resultado;
}

// genericos
Generalidades.prototype.toastrGenerico = function (estado, mensaje, configuracion = null) {
    if (!configuracion) {
        configuracion = {
            closeButton: false,
            debug: false,
            newestOnTop: false,
            progressBar: false,
            positionClass: "toastr-bottom-left",
            preventDuplicates: false,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "5000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };
    }
    toastr.options = configuracion;
    if (estado && mensaje) {
        toastr[estado](mensaje);
    }
}

// genericos
 Generalidades.prototype.datePickerGenerico = function (id, fechaMinima = null, fechaMaxima = null) {
    $.fn.datepicker.dates['es'] = {
        days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
        daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
        daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
        months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        today: "Hoy",
        clear: "Cerrar",
        format: "yyyy-mm-dd",
        titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
        weekStart: 0
    };
    $(id)
        .datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            orientation: "bottom left",
            language: "es",
            autoclose: true,
            startDate: fechaMinima,
            maxDate: fechaMaxima,
            calendarWeeks : true,
            clearBtn: true,
            disableTouchKeyboard: true
        })
        .on("change", function () {
            let formContenedor = $(this).closest("form");
            if (formContenedor && formContenedor.data("validator")) {
                $(this).valid();
            }
        });
}

// genericos
Generalidades.prototype.touchSpinGenerico = function (element, prefix = "#", minimo = 1, maximo = 100) {
    $(element).TouchSpin({
        buttondown_class: "btn btn-secondary",
        buttonup_class: "btn btn-secondary",
        min: minimo,
        max: maximo,
        stepinterval: 50,
        maxboostedstep: 10000000,
        prefix,
        firstclickvalueifempty: minimo, // corrige un error en el que el que al dar clic al aumentar o disminuir iniciaba en 51.
        // initval: minimo
    });
}

// // genericos
Generalidades.prototype.maskTelefono = function (element, mask = null, placeholder = null) {
    if (!mask) {
        mask = "(000)000-0000";
        placeholder = "(999)999-9999";
    }
    // $(element).inputmask({
    //     mask: mask,
    //     placeholder: placeholder,
    //     greedy: false
    // });
    $(element).mask(mask, {
		placeholder: placeholder
    });
}

// // genericos
Generalidades.prototype.maskValor = function (element, mask = null, placeholder = null) {
    if (!mask) {
        mask = "000.000.000";
        placeholder = "999.999.999";
    }

    $(element).mask(mask, {
		placeholder: placeholder
    });
}

// // genericos
Generalidades.prototype.maskExtension = function (element, mask = null) {
    if (!mask) {
        mask = "[9][9]99";
    }
    $(element).inputmask({
        mask: mask,
        placeholder: ""
    });
}

Generalidades.prototype.maskFecha = function (element, mask = null, placeholder = null) {
    if (!mask) {
        mask = "0000-00-00";
        placeholder = "yyyy-mm-dd"
    }
    $(element).mask(mask, {
        placeholder: placeholder
    });
}

// // genericos
Generalidades.prototype.maskCorreo = function (element, mask = null) {
    if (!mask) mask = "*{1,}[.*{1,}][.*{1,}][.*{1,}]@*{1,}[.*{1,}][.*{1,}][.*{1,}][.*{1,}][.*{1,3}]";
    // if (!mask) mask = "*{1,}[.*{1,}][.*{1,}][.*{1,}]@*{1,}[.*{2,}][.*{2,}]";
    $(element).inputmask({
        mask: mask,
        greedy: false,
        onBeforePaste: function(pastedValue, opts) {
            pastedValue = pastedValue.toLowerCase();
            return pastedValue.replace("mailto:", "");
        },
        definitions: {
            "*": {
                validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~-]",
                cardinality: 1,
                casing: "lower"
            }
        }
    });
}

// genericos
Generalidades.prototype.darTelefonoInput = function (elemento) {
    var input = document.querySelector(elemento);
    if (input) {
        return window.intlTelInputGlobals.getInstance(input);
    }
}

// genericos
Generalidades.prototype.darCodigoInput = function (elemento) {
    var input = document.querySelector(elemento);
    if (input) {
        let iti = window.intlTelInput(input);
        return iti.getSelectedCountryData();
    }
}

// genericos
Generalidades.prototype.initTelefonoInput = function (elemento, config = null) {

    config = config ?? {
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
        initialCountry: 'CO',
        preferredCountries: ["CO", "US"],
    };

    var input = document.querySelector(elemento);
    if (input) {
        return window.intlTelInput(input, config);
        // var phoneInput = window.intlTelInput(input, config);
        // return window.indicador = phoneInput;
    }

    // config = config ?? {
    //     initialCountry: 'CO',
    //     preferredCountries: ["CO", "US"],
    // };

    // var input = document.querySelector(elemento);
    // if (input) {
    //     return window.Inputmask(input, config);
    // }

    // config = config ?? {
    //     initialCountry: 'CO',
    //     preferredCountries: ["CO", "US"],
    //     utilsScript:
    //         "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    // };

    // var input = document.querySelector(elemento);
    // if (input) {
    //     var phoneInput = window.intlTelInput(input, config);
    //     return window.indicador = phoneInput;
    // }
}

// genericos
Generalidades.prototype.reemplazarRuta = function (ruta, parametro, valor) {
    return ruta.replace(parametro, valor);
}

// genericos
Generalidades.prototype.buscarEnListado = function (dataTable, filtro) {
    let dt = $(dataTable).DataTable();
    $(filtro).each(function (key) {
        dt.column($(this).closest("th").index())
            .search(
                jQuery.fn.DataTable.ext.type.search.string($(this).val())
            );
    });
    dt.draw();
}

// genericos
Generalidades.prototype.mensajeSwal = function (validaciones, type = 'error', title = 'Error', footer = null, accionConfirmar = null, mostrarCancelar = false, accionCancelar = null) {

    if (!validaciones) {
        return;
    }
    let html = '';

    if (typeof validaciones === "object") {
        $.each(validaciones, function (i, value) {
            html += `<li> ${value} </li>`;
        });
    } else {
        html += validaciones;
    }

    let configSwal = {
        type,
        title,
        html,
        footer
    };

    if (accionConfirmar != null) {
        Object.assign(configSwal, {
            "focusConfirm": false,
            "confirmButtonText": '<i class="fa fa-check"></i> Confirmar',
            "confirmButtonAriaLabel": 'Confirmar',
        });
    }

    if (mostrarCancelar) {
        Object.assign(configSwal, {
            "showCloseButton": true,
            "showCancelButton": true,
            "cancelButtonText": '<i class="fa fa-times"></i> Cancelar',
            "cancelButtonAriaLabel": 'Cancelar'
        });
    }

    swal.fire(configSwal).then((resultado) => {
        if (resultado.value && accionConfirmar != null) {
            accionConfirmar();
        } else if (!resultado.value && accionCancelar != null) {
            accionCancelar();
        }
    });
}

Generalidades.prototype.mensajeGeneral = function (titulo, mensaje, color, boton1, boton2, accionConfirmar = null, accionCancelar = null) {

    swal.fire({
        title: titulo,
        text: mensaje,
        icon: color,
        showCancelButton: true,
        confirmButtonText: boton1 ?? "Yes, delete it!",
        cancelButtonText: boton2 ?? "No, cancel!",
        reverseButtons: true
    }).then(function(resultado) {
        if (resultado.value) {
            if (accionConfirmar != null) {
                accionConfirmar();
                swal.fire(
                    "Eliminado",
                    "Se elimino correctamente.",
                    "success"
                )
            }
        } else if (resultado.dismiss === "cancel") {
            if (accionCancelar) {
                accionCancelar();
            }
            swal.fire(
                "Cancelado",
                "Cancelaste la accion de eliminar",
                "error"
            )
        }
    });
}

// genericos
Generalidades.prototype.formatoDinero = function (cantidad) {
    return new Intl.NumberFormat().format(cantidad ?? 0);
}

// genericos
Generalidades.prototype.multiSelect = function (config = {}) {
    let elemento = config.elemento ?? '';
    let allSelectedText = config.allSelectedText ?? 'Todos';
    let selectAllValue = config.selectAllValue ?? 'select-all-value';
    let numberDisplayed = config.numberDisplayed ?? 1;
    let buttonWidth = config.buttonWidth ?? '100%';
    let includeSelectAllOption = config.includeSelectAllOption ?? true;
    let selectableHeaderText = config.selectableHeaderText ?? 'Datos disponibles';
    let selectionHeaderText = config.selectionHeaderText ?? 'Datos asignados';
    let selectableHeaderPlaceholder = config.selectableHeaderPlaceholder ?? 'Escribe el nombre del dato disponible';
    let selectionHeaderPlaceholder = config.selectionHeaderPlaceholder ?? 'Escribe el nombre del dato asignado';

    const afterInit = function (ms) {
        $(ms).css("width", "100%");
        var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

        that.qs1 = $selectableSearch.quicksearch(selectableSearchString, { removeDiacritics: true })
            .on('keydown', function (e) {
                if (e.which === 40) {
                    that.$selectableUl.focus();
                    return false;
                }
            });

        that.qs2 = $selectionSearch.quicksearch(selectionSearchString, { removeDiacritics: true })
            .on('keydown', function (e) {
                if (e.which == 40) {
                    that.$selectionUl.focus();
                    return false;
                }
            });
    };
    if (elemento) {
        $(elemento).multiSelect({
            allSelectedText,
            selectAllValue,
            numberDisplayed,
            buttonWidth,
            includeSelectAllOption,
            selectableHeader: `<h5 class='kt-heading'>${selectableHeaderText}</h5><input type='text' class='search-input form-control' placeholder='${selectableHeaderPlaceholder}'/>`,
            selectionHeader: `<h5 class='kt-heading'>${selectionHeaderText}</h5><input type='text' class='search-input form-control' placeholder='${selectionHeaderPlaceholder}'/>`,
            afterInit,
            afterSelect: function(){
                // this.qs1.cache();
                // this.qs2.cache();
            },
            afterDeselect: function(){
                // this.qs1.cache();
                // this.qs2.cache();
            }
        });
    }
};

// formularios
Generalidades.prototype.validarDatos = function (
    formElement,
    submitHandler,
    invalidHandler = false,
    highlight = false,
    unhighlight = false,
    errorPlacement = false,
    rules = false,
    messages = false
) {
    if (invalidHandler === false) {
        invalidHandler = evt => {
            this.toastrGenerico(
                "error",
                "Ha ocurrido un error de validación, por favor, verifica todos los campos."
            );
            evt.preventDefault();
            return false;
        };
    }
    if (highlight === false) {
        // se ejecuta por cada elemento a validar.
        highlight = element => {
            // resaltar select genericos
            if ($(element).hasClass("selectGenerico")) {
                $(element)
                    .parent()
                    .addClass("is-invalid");
                $(element)
                        .parent()
                        .find(".select2-selection")
                        .css("border-color", "#fd397a");
                return true;
            }

            // resaltar errores de summernote.
            if ($(element).hasClass("summernote")) {
                $(element).parent().find(".note-editor").addClass("line-error");
                return true;
            }

            // no existe clase para resaltar los kt-checkbox. se usará un label.
            if ($(element).closest(".kt-checkbox").length == 1) {
                let contenedorCheckbox = $(element).closest(".kt-checkbox");
                if (contenedorCheckbox.parent().find(".errorCheckbox").length == 0) {
                    $("<span class='errorCheckbox text-danger'><br/>Este campo es requerido.</span>").insertAfter(contenedorCheckbox);
                }
                return true;
            }

            // resaltar un elemento normal.
            $(element)
                .closest(".form-control")
                .addClass("is-invalid");
        };
    }
    if (unhighlight === false) {
        unhighlight = element => {
            // borrar la clase en el select generico.
            if ($(element).hasClass("selectGenerico")) {
                $(element)
                    .parent()
                    .removeClass("is-invalid");
                $(element)
                    .parent()
                    .find(".select2-selection")
                    .css("border-color", "");
                return true;
            }
            // borrar la clase de error de summernote.
            if ($(element).hasClass("summernote")) {
                $(element).parent().find(".note-editor").removeClass("line-error");
                return true;
            }

            // borrar span de validación checkbox.
            if ($(element).closest(".kt-checkbox").parent().find(".errorCheckbox").length >= 1) {
                $(element).closest(".kt-checkbox").parent().find(".errorCheckbox").remove();
                return true;
            }

            $(element)
                .closest(".form-control")
                .removeClass("is-invalid");
        };
    }
    $(formElement).validate({
        ignoreTitle: true,
        ignore: ':hidden:not(.summernote),.note-editable.card-block',
        errorElement: "span", //default input error message container
        errorClass: "help-block help-block-error", // default input error message class
        focusInvalid: true,
        onfocusout: false,
        lang: "es",
        highlight,
        unhighlight,
        invalidHandler,
        submitHandler,
        errorPlacement,
        rules,
        messages
    });
}

// formularios
Generalidades.prototype.validarFormulario = function (formularioId, accion) {
    const submitHandler = (form, e) => {
        e.preventDefault();

        let divValidacion = $(formularioId).find(".div-validacion");
        if (divValidacion.length == 1) {
            divValidacion.addClass("d-none");
        }

        let botonActivador = document.activeElement;
        if (!((botonActivador instanceof HTMLButtonElement) || (botonActivador instanceof HTMLInputElement)) && !botonActivador.getAttribute("type") == "submit") {
            botonActivador = null;
        }
        accion(form, botonActivador);
        return false;
    };
    this.validarDatos(formularioId, submitHandler);
}

Generalidades.prototype.modalActual = function (modal, accion = null) {
    const modalActual = $(".modal:visible");
    // ajustar los eventos necesarios, y ocultar el modal actual si existe.
    if (modalActual.length) {
        $(modal).one("hidden.bs.modal", function () {
            modalActual.modal("show");
        });
        modalActual.one("hidden.bs.modal", () => {
            $(modal).modal("show");
        });
        modalActual.modal("hide");
    } else {
        $(modal).modal("show");
    }
    if (accion != null) {
        accion();
    }
}

Generalidades.prototype.marcarRequeridos = function (form) {
    $(`${form} .requerido .marcadoRequerido`).remove();
    $(`${form} .requerido`).each(function () {
        let label = $(this).text();
        $(this).html(`${label} <span class="text-danger marcadoRequerido">*</span>`);
    });
}

Generalidades.prototype.crearDropzone = function (elemento = '#dropzoneArchivos', config = false) {
    let idDropzone = elemento;
    // verificamos si existe el ID para dropzone
    if (!$(idDropzone).length) {
        return;
    }

    // resetear el posible dropzone que ya esté inicializado.
    const previewNode = $(`${idDropzone} .dropzone-item`);
    const previewTemplate = previewNode.parent(".dropzone-items").html();
    previewNode.remove();

    // inicializar el dropzone.
    const dropzone = new Dropzone(idDropzone, {
        "acceptedFiles": config.extenciones ?? "image/*,application/pdf,.doc,.docx,.xls,.xlsx,.csv,.ppt,.pptx,",
        "maxFiles":  config.maxFiles ?? 1,
        "maxFilesize": config.maxFilesize ?? 1,
        "url": config.ruta,
        "headers": {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        "autoQueue": false,
        "addRemoveLinks": true,
        "previewTemplate": previewTemplate,
        "previewsContainer": `${idDropzone} .dropzone-items`, // contenedor para previsualizar archivos.
        "clickable": `${idDropzone} .dropzone-select` // define el elemento usado para la selección de archivos.
    });

    // si se pasa de la cantidad de soportes permitidos.
    dropzone.on("maxfilesexceeded", function (file) {
        this.removeFile(file);
        generalidades.mensajeSwal("No puedes cargar más soportes.", "error", "Error");
    });

    // no hacer nada si tira error al enviar los archivos.
    dropzone.on("error", config.error ?? false);

    // al agregar un archivo, mostrar y ocultar secciones.
    dropzone.on("addedfile", function (file) {
        // validar el tamaño del archivo.
        if (file.size > ((config.maxFilesize ?? 1) * 1000000)) {
            this.removeFile(file);
            let megabytes = file.size / 1000000; //tamaño dado en bytes, convertimos de Bytes a MB.

            // redondeamos
            megabytes = megabytes.toFixed(2);
            generalidades.mensajeSwal(`El tamaño máximo para subir archivos es de ${config.maxFilesize ?? 1} MB (subiste de ${megabytes} MB)`, 'info', null);
            return false;
        }

        // this.options.acceptedFiles = archivos.darExtensionesPermitidas();
        const elemento = file.previewElement.querySelector(`${idDropzone} .dropzone-start`);
        $(elemento).on("click", () => dropzone.enqueueFile(file));

        $(document).find(`${idDropzone} .dropzone-item`).css("display", "");
        $(`${idDropzone} .dropzone-upload, ${idDropzone} .dropzone-remove-all`).css("display", "inline-block");
        $(`${idDropzone} .dropzone-chagefileName`).removeClass("d-none").find("input").val(file.upload.filename);
    });

    // barra de progreso del dropzone.
    dropzone.on("totaluploadprogress", function (progress) {
        $(`${idDropzone} .progress-bar`).css("width", `${progress}%`);
    });

    // al dar respuesta correcta del controlador, refrescar el listado y
    // resetear todo.
    dropzone.on("success", config.success ?? false);

    // ocultar la barra de progreso cuando se envie todo.
    dropzone.on("complete", function () {
        const barra = `${idDropzone} .dz-complete`;
        setTimeout(function () {
            $(`${barra} .progress-bar, ${barra} .progress, ${barra} .dropzone-start`).css("opacity", "0");
        }, 300);
    });

    // si se da click en el botón de eliminar todo, quitar todos los archivos y ocultar botones de subida y eliminar todo.
    $(`${idDropzone} .dropzone-remove-all`).on("click", function () {
        $(`${idDropzone} .dropzone-upload, ${idDropzone} .dropzone-remove-all`).css("display", "none");
        dropzone.removeAllFiles(true);
    });

    // Cuando se complete la subida de los archivos, ocultar indicador de subida.
    dropzone.on("queuecomplete", function () {
        $(`${idDropzone} .dropzone-upload`).css("display", "none");
    });

    // cuando se eliminen archivos y no quede ninguno, ocultar varias secciones del dropzone.
    dropzone.on("removedfile", function () {
        if (dropzone.files.length == 0) {
            $(`${idDropzone} .dropzone-upload, ${idDropzone} .dropzone-remove-all`).css("display", "none");
            $(`${idDropzone} .dropzone-chagefileName`).addClass("d-none").find("input").attr("placeholder", "");
        }
    });

    return dropzone;
}
