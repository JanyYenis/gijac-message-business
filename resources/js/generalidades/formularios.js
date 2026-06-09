import Generalidades from '../generalidades';
/**
 * Módulo formularios de generalidades.
 * Aquí se pueden guardar todo lo que corresponda a funcionalidades que
 * permitan interacturar con los formularios.
 */

/**
 * Función que permite habilitar/deshabilitar un botón dependiendo del estado que se le pase.
 * @param {HTMLFormElement} form Elemento del formulario.
 * @param {Boolean} estado ¿Habilitar el botón? Por defecto, true.
 * @param {string} nombreClase Nombre de la clase de botón a habilitar/deshabilitar.
 */
Generalidades.inHabilitarBoton = function (form, estado = true, nombreClase = null) {
    const clase = nombreClase ?? "class";
    $(form).find(clase).prop("disabled", estado);
}
// Generalidades.prototype.inHabilitarBoton = function (form, estado = true, nombreClase = null) {
//     const clase = nombreClase ?? "class";
//     $(form).find(clase).prop("disabled", estado);
// }

/**
 * Función que permite resetear el validate genérico.
 * @param {string|HTMLFormElement} idForm String con el ID del formulario a resetearle el validate, o el elemento del formulario como tal.
 */
Generalidades.prototype.resetValidate = function (idForm) {
    let validator = $(idForm).validate(); 
    //Se reinicia los select2
    $(idForm).find(".form-control")
        .val([])
        .trigger("change");
    
    $(idForm)
        .find(".form-control")
        .val("");

    $(idForm)
        .find(".touchspin")
        .trigger("touchspin.updatesettings", { "initval": 1 })
    
    $(idForm)
        .find(".div-validacion")
        .addClass("d-none");
    
    validator.resetForm();
}

/**
 * Método genérico para mostrar los mensajes en el div-validacion que retorna el validate del FormRequest
 * @param {HTMLFormElement} form Formulario que contienen el div para las validaciones
 * @param {Object} validaciones Validaciones con los mensajes que retorna el FormRequest
 * @param {bool} scroll define si se le hace enfoque del scroll al body de la página. 
 */
Generalidades.prototype.mostrarValidaciones = function (form, validaciones, scroll = false) {
    if (!validaciones || (validaciones && !Array.isArray(validaciones))) {
        return;
    }

    // buscar dentro del formulario el div que contendrá los mensajes y hacerlo visible.
    let divContainerError = $(form).find(".div-validacion");
    divContainerError.removeClass("d-none").addClass("d-block");

    // buscar el componente donde se listan los mensajes de validación.
    let mensajesError = divContainerError.find(".mensaje-validacion");
    mensajesError.html("");

    // recorrer las validaciones y listar los mensajes de validación.
    validaciones.forEach((error) => {
        mensajesError.append(`<li>${error}</li>`);
    });

    // hacer scroll to para mostrar al usuario.
    let modal = divContainerError.closest(".modal");
    $(modal).animate({
        "scrollTop": 0
    });
    //se valida si se hace el scroll al cuerpo de la página.
    if (scroll) {
        $('html, body').animate({ scrollTop: $(divContainerError).offset().top - 150 }, 'slow');
    }
}

/**
 * Método genérico para ocultar los mensajes en el div-validacion que retorna el validate del FormRequest
 * @param {HTMLFormElement} form Formulario que contienen el div para las validaciones
 */
Generalidades.prototype.ocultarValidaciones = function (form) {
    // buscar dentro del formulario el div que contendrá los mensajes y ocultarlo.
    let divContainerError = $(form).find(".div-validacion");
    divContainerError.addClass("d-none").removeClass("d-block");
    // buscar el componente donde se listan los mensajes de validación.
    let mensajesError = divContainerError.find(".mensaje-validacion");
    mensajesError.html("");
}

Generalidades.prototype.erroresClave = [];
// window.usuario.LONGITUD_MIN_CLAVE = window.usuario.LONGITUD_MIN_CLAVE; // 13
/**
 * Función que realiza la validación de una contraseña.
 * @param {string} campoClave Campo de contraseña a validar.
 * @param {string} divContenedorErrores Div pariente que contiene el divRequisitos.
 * @param {string} divRequisitos Div que los requisitos de la clave, que se van borrando a medida que el usuario llena la clave.
 */
Generalidades.prototype.validarClave = function (campoClave = "#clave", divContenedorErrores = "#div-clave", divRequisitos = "#div-erroresclave") {
    const validacion = function () {
        Generalidades.prototype.erroresClave = [];
        let clave = this.value;
		if (clave.length < window.usuario.LONGITUD_MIN_CLAVE) {
			Generalidades.prototype.erroresClave.push("Debe de ser mayor a " + window.usuario.LONGITUD_MIN_CLAVE + " caracteres.");
		}

		// regex final ^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$
		if (!clave.match(/(?=.*[a-z])[a-zA-Z\d]{1,}/)) {
			Generalidades.prototype.erroresClave.push("Debe contener al menos una letra minúscula.");
		}

		if (!clave.match(/(?=.*[A-Z])[a-zA-Z\d]{1,}/)) {
			Generalidades.prototype.erroresClave.push("Debe contener al menos una letra mayúscula.");
		}

		if (!clave.match(/(?=.*\d)[a-zA-Z\d]{1,}/)) {
			Generalidades.prototype.erroresClave.push("Debe de contener al menos un número.");
		}

		if (Generalidades.prototype.erroresClave.length >= 1) {
			let ul = "<ul>";
			Generalidades.prototype.erroresClave.forEach((error) => {
				ul = ul + "<li>" + error + "</li>";
			});
			ul = ul + "</ul>";
			$(divRequisitos).removeClass("d-none");
			$(divRequisitos).html(ul);
			$(divContenedorErrores).removeClass("d-none");
		} else {
			$(divRequisitos).html("");
			$(divRequisitos).addClass("d-none");
			$(divContenedorErrores).addClass("d-none");
		}
    };
    
    $(campoClave).on("keyup change", validacion);
};

/**
 * Función que genera un string aleatorio.
 * @param {number} extension Cantidad de caracteres que tendrá el string como máximo
 * @param {string} caracteres Caracteres disponibles que tiene para usarse. Se pasa como a, A, # o ! para cada tipo.
 * @returns {string} Retorna un string aleatoriamente generado.
 */
Generalidades.prototype.randomString = function (extension, caracteres) {
    let disponibles = '';
    if (caracteres.indexOf('a') > -1) disponibles += 'abcdefghijklmnopqrstuvwxyz';
    if (caracteres.indexOf('A') > -1) disponibles += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if (caracteres.indexOf('#') > -1) disponibles += '0123456789';
    if (caracteres.indexOf('!') > -1) disponibles += '~`!@#$%^&*()_+-={}[]:";\'<>?,./|\\';
    let result = '';
    
    for (let i = extension; i > 0; --i)
        result += disponibles[Math.floor(Math.random() * disponibles.length)];
    
    return result;
};

Generalidades.prototype.clavesReveladas = {};

/**
 * Función que permite revelar un campo de contraseña, cambiando el
 * type que posee.
 */
Generalidades.prototype.revelarClave = function (campoId, btnRevelarClave, revelar = undefined) {
    if (revelar == undefined) {
        revelar = $(campoId).prop("type") != "text";
    }

    this.clavesReveladas[campoId] = revelar;
    let elementoI = $(btnRevelarClave).find("i");
    let claveUsuario = $(campoId);
    
    if (this.clavesReveladas[campoId]) {
        claveUsuario.prop("type", "text");
        elementoI.removeClass("fa-eye");
        elementoI.addClass("fa-eye-slash");
        $(btnRevelarClave).prop("title", "Haz click aquí para ocultar la contraseña");
    } else {
        claveUsuario.prop("type", "password");
        elementoI.addClass("fa-eye");
        elementoI.removeClass("fa-eye-slash");
        $(btnRevelarClave).prop("title", "Haz click aquí para mostrar la contraseña");
    }
}

/**
 * Función que permite generar una clave aleatoria, y validando que cumplca con los requisitos de la
 * clave.
 * @return {string} Retorna un string random validado.
 */
Generalidades.prototype.generarClave = function () {
    let string = this.randomString(window.usuario.LONGITUD_MIN_CLAVE, "aA#");
    if (!string.match(/(?=.*[a-z])[a-zA-Z\d]{1,}/)) {
        let index = Math.random() * string.length;
        const disponibles = 'abcdefghijklmnopqrstuvwxyz';
        let reemplazarPor = disponibles.charAt(Math.random() * disponibles.length);
        string = string.substring(0, index) + reemplazarPor + string.substring(index + 1);
    }
    if (!string.match(/(?=.*[A-Z])[a-zA-Z\d]{1,}/)) {
        let index = Math.random() * string.length;
        const disponibles = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let reemplazarPor = disponibles.charAt(Math.random() * disponibles.length);
        string = string.substring(0, index) + reemplazarPor + string.substring(index + 1);
    }
    if (!string.match(/(?=.*\d)[a-zA-Z\d]{1,}/)) {
        let index = Math.random() * string.length;
        const disponibles = '0123456789';
        let reemplazarPor = disponibles.charAt(Math.random() * disponibles.length);
        string = string.substring(0, index) + reemplazarPor + string.substring(index + 1);
    }
    return string;
}

/**
 * Función que realiza la copia del texto, en caso de que no se tenga permiso, se solicitará el permiso
 * para realizar la acción.
 * Nota: Para Firefox, se recomienda que se use esta función al dar click un botón, todavía no está soportado el
 * permiso. Vease: https://dxr.mozilla.org/mozilla-central/source/dom/webidl/Permissions.webidl#10
 * @param {string} texto Texto que será copiado al usuario.
 * @param {boolean} mostrarToast Indica si mostrar un toastr indicando si se pudo copiar u ocurrió un problema al realizar la copia.
 * @param {CallableFunction} enConcedido Función callback si se logró copiar el texto al portapapeles.
 * @param {CallableFunction} enDenegado Función callback si no se logró copiar el texto al portapapeles.
 * @returns {boolean} Retorna TRUE si se pudo copiar correctamente. False si ocurrió algún error.
 */
Generalidades.prototype.copiarTexto = async function (texto, mostrarToast = false, enConcedido = null, enDenegado = null) {
    navigator.clipboard.writeText(texto).then(() => {
        // en caso de que haya podido copiarse correctamente.
        if (mostrarToast) {
            this.toastrGenerico("info", "Se ha copiado el texto al portapapeles.");
        }

        if (enConcedido) {
            enConcedido();
        }

        return true;
    }, () => {
        // en caso de que la solicitud se haya rechazado, solicitamos el permiso, esto solamente funciona en Chrome.
        // en Firefox, aún no se ha generado el permiso de clipboard-write
        // Chrome
        navigator.permissions.query({ "name": "clipboard-write", "allowWithoutGesture": true }).then((result) => {
            switch (result.state) {
                case "granted":
                    // si es concedido, llamamos de nuevo la función
                    return this.copiarTexto(texto, mostrarToast, enConcedido, enDenegado);
                case "denied":
                    if (enDenegado) {
                        enDenegado(result);
                    }
                    return false;
                case "prompt":
                    // se está solicitando el permiso, si se solicita, deberíamos de llamar de nuevo esta función
                    result.onchange = function (evt) {
                        return Generalidades.copiarTexto(texto, mostrarToast, enConcedido, enDenegado);
                    }
                    break;
            }
        }).catch((error) => {
            let mensajeError = "El navegador actual no tiene este permiso disponible.";
            console.error(mensajeError + " " + error);
            if (mostrarToast) {
                this.toastrGenerico("error", mensajeError);
            }
            return false;
        });
    }).catch((error) => {
        let mensajeError = "El navegador no ha concedido los permisos, o no tiene soporte para ello.";
        console.error(mensajeError + " " + error);
        if (mostrarToast) {
            this.toastrGenerico("error", mensajeError);
        }
        return false;
    });
}
