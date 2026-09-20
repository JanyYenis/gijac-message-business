$.extend($.validator.messages, {
    required: __("Este campo es obligatorio."),
    remote: __("Por favor, rellena este campo."),
    email: __("Por favor, escribe una dirección de correo válida."),
    url: __("Por favor, escribe una URL válida."),
    date: __("Por favor, escribe una fecha válida."),
    dateISO: __("Por favor, escribe una fecha (ISO) válida."),
    number: __("Por favor, escribe un número válido."),
    digits: __("Por favor, escribe sólo dígitos."),
    creditcard: __("Por favor, escribe un número de tarjeta válido."),
    equalTo: __("Por favor, escribe el mismo valor de nuevo."),
    maxlength: $.validator.format(__("Por favor, no escribas más de ") + '{0}' + __(" caracteres.")),
    minlength: $.validator.format(__("Por favor, no escribas menos de ") + '{0}' + __(" caracteres.")),
    rangelength: $.validator.format(__("Por favor, escribe un valor entre ") + '{0}' + " y " + "{1}" + __(" caracteres.")),
    range: $.validator.format(__("Por favor, escribe un valor entre ") + '{0}' + " y " + "{1}."),
    max: $.validator.format(__("Por favor, escribe un valor menor o igual a ") + '{0}.'),
    min: $.validator.format(__("Por favor, escribe un valor mayor o igual a ") + '{0}.')
});
