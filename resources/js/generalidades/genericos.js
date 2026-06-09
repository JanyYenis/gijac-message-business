import Generalidades from '../generalidades';
/**
 * Módulo genérico de generalidades.
 * Aquí se pueden guardar todo lo que corresponda a funcionalidades genéricas, como el
 * DataTable genérico o el SelectGenerico.
 */

/**
 * Máscara correo Institucional
 */
Generalidades.prototype.MASK_CORREO_INSTITUCIONAL = "*{1,}[.*{1,}][.*{1,}][.*{1,}]@usc.edu.co";

Generalidades.prototype.terminosBuscados = {};

/**
 * Función que permite inicializar un repeaterGenerico.
 * @param {string} id - El identificador del div a convertir en repeater.
 * @param {Boolean} initEmpty - Se define si se oculta el formulario del repeater al cargar el documento
 * @param {Boolean} prependItems - Se define si el despliegue del repeater es de forma descendente o sea que cargue primero el elemento que se crea
 * @param {Object} defaultValues - Valores por defecto a agregarse al repeater.
 * @param {Function} callbackAgregado - Función a ejecutarse cuando ya se haya agregado un elemento al repeater.
 * @param {Function} callbackEliminado - Función a ejecutarse cuando ya se haya eliminado un elemento del repeater.
 */
Generalidades.prototype.repeaterGenerico = function (id, initEmpty = false, prependItems = true, defaultValues = {}, callbackAgregado = false, callbackEliminado = false) {
    $(id).repeater({
        initEmpty: initEmpty,
        defaultValues: defaultValues,
        prependItems: prependItems,
        show: function () {
            $(this).slideDown("slow");
            if (callbackAgregado != false && typeof (callbackAgregado) === "function") {
                callbackAgregado(this);
            }
        },
        hide: function (deleteElement) {
            $(this).slideUp(deleteElement, function () {
                if (callbackEliminado != false && typeof (callbackEliminado) === "function") {
                    callbackEliminado(this);
                }
            });
        }
    });
}

/**
 * Función que permite inicializar el touchSpin de bootstrap.
 * @param {string} element Un string que contiene el ID o la clase del elemento.
 */
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

/**
 * Función que permite inicializar la máscara que se va a aplicar al elemento del correo.
 * @param {string} element Un string que contiene el ID o la clase del elemento.
 * @param {string} mask Un string que contiene el tipo de máscara a aplicarse al elemento.
 */
// Generalidades.prototype.maskCorreo = function (element, mask = null) {
//     if (!mask) mask = "*{1,}[.*{1,}][.*{1,}][.*{1,}]@*{1,}[.*{1,}][.*{1,}][.*{1,}][.*{1,}][.*{1,3}]";
//     // if (!mask) mask = "*{1,}[.*{1,}][.*{1,}][.*{1,}]@*{1,}[.*{2,}][.*{2,}]";
//     $(element).inputmask({
//         mask: mask,
//         greedy: false,
//         onBeforePaste: function(pastedValue, opts) {
//             pastedValue = pastedValue.toLowerCase();
//             return pastedValue.replace("mailto:", "");
//         },
//         definitions: {
//             "*": {
//                 validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~-]",
//                 cardinality: 1,
//                 casing: "lower"
//             }
//         }
//     });
// }

/**
 * Función que permite inicializar la máscara que se va a aplicar al elemento del teléfono.
 * @param {string} element Un string que contiene el ID o la clase del elemento.
 * @param {string} mask Un string que contiene el tipo de máscara a aplicarse al elemento.
 */
// Generalidades.prototype.maskTelefono = function (element, mask = null, placeholder = null) {
//     if (!mask) {
//         mask = "(999)999-9999";
//         placeholder = "(000)000-0000";
//     }
//     $(element).inputmask({
//         mask: mask,
//         placeholder: placeholder,
//         greedy: false
//     });
// }

/**
 * Función que permite inicializar la máscara que se va a aplicar a la extensión del teléfono.
 * @param {string} element Un string que contiene el ID o la clase del elemento.
 * @param {string} mask Un string que contiene el tipo de máscara a aplicarse al elemento.
 */
// Generalidades.prototype.maskExtension = function (element, mask = null) {
//     if (!mask) {
//         mask = "[9][9]99";
//     }
//     $(element).inputmask({
//         mask: mask,
//         placeholder: ""
//     });
// }

/**
 * Función que permite inicializar la máscara del sitioWeb.
 * @param {string} element Un string que contiene el ID o la clase del elemento.
 * @param {string} mask Un string que contiene el tipo de máscara a aplicarse al elemento.
 */
Generalidades.prototype.maskSitioWeb = function (element, mask = null) {
    if (!mask) mask = "*{1,}[.*{1,}][.*{1,}][.*{1,3}]";
    $(element).inputmask({
        mask: mask,
        greedy: false,
        definitions: {
            '*': {
                validator: "[0-9A-Za-z!#$%&'*+:/=?^_`{|}~-]",
                cardinality: 1,
                casing: "lower"
            }
        }
    });
}

/**
 * Función que permite dar la instancia de la libreria intl-tel-input.
 * @param {HTMLElement} elemento Elemento HTML a ocultar el cargando.
 */
Generalidades.prototype.darTelefonoInput = function (elemento) {
    var input = document.querySelector(elemento);
    if (input) {
        return window.intlTelInputGlobals.getInstance(input);
    }
}

/**
 * Función que permite cargar el código del pais de la libreria intl-tel-input.
 * @param {HTMLElement} elemento Elemento HTML a ocultar el cargando.
 */
Generalidades.prototype.darCodigoInput = function (elemento) {
    var input = document.querySelector(elemento);
    if (input) {
        let iti = window.intlTelInput(input);
        return iti.getSelectedCountryData();
    }
}

/**
 * Función que permite instanciar la libreria intl-tel-input.
 * @param {HTMLElement} elemento selector html.
 */
Generalidades.prototype.initTelefonoInput = function (elemento, config = null) {
    
    config = config ?? {
        initialCountry: 'CO',
        preferredCountries: ["CO", "US"],
    };

    var input = document.querySelector(elemento);
    if (input) {
        return window.intlTelInput(input, config);
    }
}

/**
 * Funcion para reemplazar valor del parametro de la ruta.
 * @param {string} ruta Variable con el contenido de la ruta.
 * @param {string} parametro Variable con la llave a reemplazar en la ruta.
 * @param {string} valor variable con el valor a reemplazar.
 * @returns {string} Retorna el mismo string de la ruta, con el parámetro reemplazado.
 */
Generalidades.prototype.reemplazarRuta = function (ruta, parametro, valor) {
    return ruta.replace(parametro, valor);
}

/**
 * Filtra en el datatable según la busqueda que realice en la columna del datatable.
 * @param {HTMLTableElement} dataTable El elemento de la tabla.
 * @param {string} filtro Un string, generalmente una clase HTML, sobre la cual permitira iterar.
 */
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

/**
 * Reinicia el SelectPicker genérico. no hay más que explicar :p
 * @param {string} elemento - Identifica el elemento
 */
Generalidades.prototype.reiniciarSelectpicker = function (elemento) {
    $(elemento)
        .val("default")
        .selectpicker("refresh"); /** Reiniciamos el BSelect */
}

/**
 * Refresca el elemento que esté usando el tooltip
 * @param {string} elemento - Identifica el elemento
 */
Generalidades.prototype.refrescarTooltip = function (elemento) {
    //Si se encuentra visible se elimina el elemento.
    if ($('.bs-tooltip-top').find('show')) $('.bs-tooltip-top').remove();
    $(elemento).tooltip('update') /** Reiniciamos el tooltip */
}

/**
 * Sweet alert genérico recibe 4 parámetros
 * @param {[object,string,Function]} validaciones Contiene los mensajes para mostrar.
 * @param {string} type - Tipo de swal.
 * @param {string} title - Título que se mostrará, por defecto, null.
 * @param {string} footer - Texto que se mostrará en la parte final del swal, por defecto, null.
 * @param {Function} accionConfirmar - Acción a ejecutarse cuando le dé click OK. Por defecto, null.
 * @param {boolean} mostrarCancelar - Decide entre mostrar u ocultar el botón de cancelar. Por defecto, false (oculto).
 */
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

/**
 * Función que convierte a capitalize un string
 * @param String str - String a convertir.
 */
Generalidades.prototype.initcap = function (str) {
    return str
        .toLowerCase()
        .split(' ')
        .map(function (word) {
            return word[0].toUpperCase() + word.substr(1);
        })
        .join(' ');
}

/**
 * Función que convierte a capitalize un string
 * @param String str - String a convertir.
 */
Generalidades.prototype.darPrimerItemSelecionadoSelect2 = function (selector) {
    let select = $(selector).select2("data");
    return select ? select.shift() : null;
}

/**
 * Función que permite abrir una nueva ventana emergente.
 * @param {string} ruta a la cuál se va abrir la ventana emergente.
 * @param {string} titulo de la ventana.
 * @param {json} configuracion configuraciones de la ventana emergente.
 */
Generalidades.prototype.ventanaEmergente = function (ruta, titulo = null, configuracion = null) {

    if (!configuracion) {
        let width = window.screen.width;
        let height =  window.screen.height;
        let mitadPantallaY = height/2;
        let mitadPantallaX = width/2;
        let ubicacionY=parseInt(mitadPantallaY/2);
        let ubicacionX = parseInt(mitadPantallaX/2);
        configuracion = {
            width: mitadPantallaX,
            height,
            menubar: 0,
            toolbar: 0,
            scrollbars: "no",
            resizable: "no",
            left: ubicacionX,
            top: ubicacionY,
        };
    }
    let config = "";
    let iterable = Object.entries(configuracion);
    let cantidad = iterable.length - 1;

    iterable.map(function ([index, value], idx) {
        let coma = idx == cantidad ? "" : ",";
        config = `${config}${index}=${value}${coma}`;
    });
    return window.open(ruta, titulo, config);
}

/**
 * Función que permite limpiar el summernote genérico, ya que queda con algunos espacios internamente.
 * @param {string} elemento al cual se va a limpiar el summernote.
 */
Generalidades.prototype.limpiarSummernote = function (elemento) {
    $(elemento).summernote('code', null);
}

/**
 * Función que permite crear un summernote genérico, permitiendo el recuento de caracteres y añadiendo un límite de la misma manera.
 * @param {string} idSummernote ID o clase del summernote a inicializar.
 * @param {number} altura Altura predefinida del summernote.
 * @param {Array|null} toolbar Botones que estarán disponibles en el summernote. Por defecto, se asignarán algunos botones generalmente usados.
 * @param {number|null} limCaracteres Límite de caracteres que tendrá el summernote. Por defecto, nulo.
 * @param {string|null} claseCaracteres Clase o ID en el que se escribirá el recuento de caracteres.
 * @param {Function|null} onChange Función para detectar el onchange.
 */
Generalidades.prototype.summernoteGenerico = function (idSummernote, altura = 150, toolbar = null, limCaracteres = null, claseCaracteres = null, onChange = null) {
    // destruir la instancia actual del summernote.
    $(idSummernote).summernote("destroy");

    if (toolbar == null) {
        toolbar = [
            // [groupName, [list of button]]
            ["style", ["bold", "italic", "underline", "clear"]],
            // ['font', ['bold', 'underline', 'clear']],
            ["fontname", ["fontname"]],
            ["fontsize", ["fontsize"]],
            ["color", ["color"]],
            ["table", ["table"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["height", ["height"]],
            ["insert", ["link"]],
            ["view", ["fullscreen", "codeview"]],
            ["save", ["save"]] // The button
        ];
    }

    // instanciar el summernote de nuevo.
    $(idSummernote).summernote({
        // protección XSS 
        "codeviewFilter": true,
        "codeviewIframeFilter": true,
        // ---
        "height": altura,
        "lang": "es-ES",
        toolbar,
        "callbacks": {
            "onChange": onChange,
            "onChangeCodeView": onChange,
            "onKeydown": function (e) {
                if (limCaracteres != null && claseCaracteres != null) {
                    var textoSummernote = e.currentTarget.innerText; 
                    if (textoSummernote.trim().length >= limCaracteres) {
                        //delete keys, arrow keys, copy, cut, select all
                        if (e.keyCode != 8 && !(e.keyCode >= 37 && e.keyCode <= 40) && e.keyCode != 46 && !(e.keyCode == 88 && e.ctrlKey) && !(e.keyCode == 67 && e.ctrlKey) && !(e.keyCode == 65 && e.ctrlKey)) {
                            e.preventDefault(); 
                        }
                    } 
                }
            },
            "onKeyup": function (e) {
                var textoActual = e.currentTarget.innerText;
                if (limCaracteres != null && claseCaracteres != null) {
                    $(claseCaracteres).text(`Límite de caracteres: ${textoActual.trim().length}/${limCaracteres}`);
                }
                let formularioCercano = $(this).closest("form");
                if (textoActual.trim().length == 0) {
                    if (formularioCercano.data("validator")) {
                        $(idSummernote).parent().find('.note-editor').addClass("line-error");
                    }
                    $(idSummernote).summernote('code', null);
                } else {
                    if (formularioCercano.data("validator")) {
                        $(idSummernote).parent().find('.note-editor').removeClass("line-error");
                    }
                }
            },
            "onPaste": function (e) {
                if (limCaracteres != null && claseCaracteres != null) {
                    var texto = e.currentTarget.innerText;
                    var textPegar = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    var maxPaste = textPegar.length;
                    if(texto.length + textPegar.length > limCaracteres) {
                        maxPaste = limCaracteres - texto.length;
                    }
                    if(maxPaste > 0){
                        document.execCommand('insertText', false, textPegar.substring(0, maxPaste));
                    }
                    $(claseCaracteres).text(`Límite de caracteres: ${texto.trim().length}/${limCaracteres}`);
                }
            }
        }
    });
}

/**
 * Función que genera una notificación. Si no se ha concedido el permiso para notificaciones, se solicitará al usuario y se volverá a llamar
 * a la función.
 * IMPORTANTE: SOLAMENTE FUNCIONARÁ CON HTTPS.
 * @param {NotificationOptions} opciones Opciones que serán usadas para crear la notificación.
 */
Generalidades.prototype.notificacion = function (opciones) {
    if (typeof (window.Notification) == "undefined") {
        console.error("Las notificaciones no son soportadas en este navegador.");
        return;
    }

    // verificar por el permiso al usuario.
    if (Notification.permission == "default" || Notification.permission == "denied") {
        Notification.requestPermission().then((permission) => {
            if (permission == "granted") {
                this.notificacion(opciones);
            }
        });
    }

    // si concedió el permiso, o ya lo tenía, mostramos la notificacion.
    let opcionesDefecto = {
        "lang": "es",
        "silent": false,
        "requireInteraction": false,
        "icon": "/images/logoUSC.png"
    }

    opciones = Object.assign(opciones, opcionesDefecto);
    return new Notification(opciones.titulo, opciones);
}