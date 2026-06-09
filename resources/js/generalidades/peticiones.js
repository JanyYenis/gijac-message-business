import Generalidades from '../generalidades';

Generalidades.prototype.CONTENT_TYPE_JSON = "application/json";
Generalidades.prototype.CONTENT_TYPE_HTML = "text/html";
Generalidades.prototype.CONTENT_TYPE_FORMDATA = "multipart/form-data";


/**
 * Módulo peticiones de generalidades.
 * Aquí se pueden guardar todo lo que corresponda a funcionalidades que
 * permitan realizar conexiones o peticiones a servidores.
 */

/**
 * Función que permite realizar una petición HTTP.
 * @param {string} url Ruta a la cual hacer la petición HTTP.
 * @param {Object} config JSON con la configuración de la petición, ej: headers, token...
 * @param {Function} success Función callback al tener éxito.
 * @param {Function} error Función callback al no cumplirse.
 * @param {string} tipo Tipo de contenido esperado, JSON, HTML, etc...
 */
Generalidades.prototype.peticionHttp = async function (url, config, success, error = null, tipo = this.CONTENT_TYPE_JSON) {
    const headers = new Headers(config.headers); // Crea un objeto de tipo Headers dado el parámetro.
    if (!headers.has("X-CSRF-TOKEN")) {
        // Valida si el token está en el objeto, sino lo agrega al header.
        headers.append("X-CSRF-TOKEN", this.token);
    }

    if (headers.has("Content-Type")) {
        const content = headers.get("Content-Type");
        // console.error(content)

        if (config.body && content == this.CONTENT_TYPE_JSON) {
            // Asignar el ContentType para que siempre sea JSON.
            // headers.append("Content-Type", "application/json");

            // Verificar si se trata de un formData
            if (config.body instanceof FormData) {
                config.body = this.formDataAJson(config.body, true);
            } else {
                // Es un JSON, por lo que solamente es necesario ponerlo como string.
                config.body = JSON.stringify(config.body);
            }
        }
    }
    let accept = headers.has("Accept") ? headers.get("Accept") : this.CONTENT_TYPE_JSON;

    config.headers = headers;
    
    //si envia un cuerpo de la petición.
    const request = new Request(url, config);
    try {
        const response = await fetch(request);
        
        // 401 - unauthorized (sesión expirada)
        if (response.status == 401) {
            return this.mensajeSwal("Tu sesión ha expirado. Inicia sesión para continuar.", "info", "Sesión expirada", null, () => {window.location.reload()}, false, () => {window.location.reload()});
        }

        let respuesta;
        switch (accept) {
            case this.CONTENT_TYPE_HTML:
                respuesta = await response.text();
                break;
            case this.CONTENT_TYPE_JSON:
                respuesta = await response.json();
                if (respuesta.statusCode === 422 && error != null) {
                    return error(respuesta);
                }
                break;
            default:
                respuesta = await response.json();
                break;

        }
        success(respuesta);
    } catch (ex) {
        if (error == null)
            error = ex => {
                console.error(ex);
            };
        error(ex);
    }
}

/**
 * Función que permite realizar una petición de tipo POST.
 * @param {string} url Dirección a la cual hacer la petición.
 * @param {Object} config JSOn con un FormData con la configuración de la petición.
 * @param {Function} success Función callback en caso de éxito.
 * @param {Function} error Función callback en caso de error.
 */
Generalidades.prototype.create = function (url, config, success, error = null, tipo = this.CONTENT_TYPE_JSON) {
    if (config.body) {
        config.method = "POST";
        this.peticionHttp(url, config, success, error, tipo);
        return true;
    }
    console.error("Debes enviar datos.");
    return false;
}

/**
 * Refresca el listado de la sección.
 * @param {object} btnAccion botón de la sección
 * @param {string} ruta ruta de la sección
 * @param {string} div div de la sección
 */
Generalidades.prototype.refrescarSeccion = function (btnAccion, ruta, div, completado = false, mostrarCargando = true) {
    if (btnAccion) {
        btnAccion.prop("disabled", true);
    }

    if (mostrarCargando) {
        this.mostrarCargando(div);
    }
    const success = response => {
        if (btnAccion) {
            btnAccion.prop("disabled", false);
        }
        if (mostrarCargando) {
            this.ocultarCargando(div);
        }
        if (response.html != undefined && response.html != '') {
            $(div).html(response.html);
        }
        if (response.estado && response.mensaje) {
            this.toastrGenerico(response.estado, response.mensaje);
        }
        if (completado != false) {
            completado(response);
        }
    };
    const error = response => {
        if (btnAccion) {
            btnAccion.prop("disabled", false);
        }
        if (mostrarCargando) {
            this.ocultarCargando(div);
        }
    };
    
    const config = {
        "headers": {
            "Content-Type": generalidades.CONTENT_TYPE_JSON,
            "Accept": generalidades.CONTENT_TYPE_JSON
        }
    };
    
    this.get(ruta, config, success, error);
}

/**
 * Función que permite refrescar múltiples secciones. Estas secciones son definidas desde el backend.
 * @param {object} btnAccion botón que realizó la carga de la sección.
 * @param {string} ruta Ruta para cargar las secciones.
 * @param {Function} completado Función callback ejecutada al completar el cargado.
 */
Generalidades.prototype.refrescarSecciones = function (btnAccion, ruta, completado = false) {
    if (btnAccion && btnAccion.jquery === undefined) {
        btnAccion = $(btnAccion);
    }

    const config = {
        "headers": {
            "Accept": generalidades.CONTENT_TYPE_JSON,
            "Content-Type": generalidades.CONTENT_TYPE_JSON
        }
    };

    const success = (response) => {
        this.ocultarCargando("body");
        if (btnAccion) {
            btnAccion.prop("disabled", false);
        }

        if (response.estado == "success" && response?.html) {
            let html = response?.html ?? {};
            // converitmos el objeto a un array iterable llave => valor
            let iterableHtml = Object.entries(html);
            // iteramos y reemplazamos el html de esos indices.
            iterableHtml.forEach(([index, val]) => $(index).html(val));
            generalidades.toastrGenerico(response.estado, response.mensaje);
            if (completado != null) {
                completado(response);
            }
        }
    }

    const error = (response) => {
        if (btnAccion) {
            btnAccion.prop("disabled", false);
        }
        this.ocultarCargando("body");
    }

    this.mostrarCargando("body");
    this.get(ruta, config, success, error);
}

/**
 * Función que permite realizar una petición de tipo GET.
 * @param {string} url Dirección a la cual hacer la petición.
 * @param {Object} config JSON con la configuración de la petición.
 * @param {Function} success Función callback en caso de éxito.
 * @param {Function} error Función callback en caso de error.
 */
Generalidades.prototype.get = function (url, config, success, error = null, tipo = this.CONTENT_TYPE_JSON) {
    if (!config.body) {
        config.method = "GET";
        this.peticionHttp(url, config, success, error, tipo);
        return true;
    }
    console.error("Una peticion GET no debe tener body");
    return false;
}

/**
 * Función que permite realizar una petición de tipo POST.
 * @param {string} url Dirección a la cual hacer la petición.
 * @param {Object} config JSON con la configuración de la petición.
 * @param {Function} success Función callback en caso de éxito.
 * @param {Function} error Función callback en caso de error.
 */
Generalidades.prototype.post = function (url, config, success, error = null, tipo = this.CONTENT_TYPE_JSON) {
    config.method = "POST";
    this.peticionHttp(url, config, success, error, tipo);
    return true;
}

/**
 * Función que permite realizar una petición de tipo DELETE.
 * @param {string} url Dirección a la cual hacer la petición.
 * @param {Object} config JSON con la configuración de la petición.
 * @param {Function} success Función callback en caso de éxito.
 * @param {Function} error Función callback en caso de error.
 */
Generalidades.prototype.delete = function (url, config, success, error = null, tipo = this.CONTENT_TYPE_JSON) {
    if (config.method === "DELETE") {
        this.peticionHttp(url, config, success, error, tipo);
        return true;
    }
    console.error("Error al eliminar.");
    return false;
}

/**
 * Función que permite realizar una petición de tipo PUT o PATCH.
 * @param {string} url Dirección a la cual hacer la petición.
 * @param {Object} config JSON con la configuración de la petición.
 * @param {Function} success Función callback en caso de éxito.
 * @param {Function} error Función callback en caso de error.
 */
Generalidades.prototype.edit = function (url, config, success, error = null, tipo = this.CONTENT_TYPE_JSON) {
    
    const headers = new Headers(config.headers); // Crea un objeto de tipo Headers dado el parámetro.
    if (!headers.has("Content-Type")) {
        headers.append("Content-Type", this.CONTENT_TYPE_JSON);
    }
    config.headers = headers;

    
    if (config.body && (config.method === "PUT" || config.method === "PATCH")) {
        this.peticionHttp(url, config, success, error, tipo);
        return true;
    }
    // console.error("Error al editar");
    return false;
}

/**
 * Función que permite inicializar un canal de Laravel Echo para escuchar los eventos transmitidos desde
 * el backend.
 * @param {string} canal Canal por el que transmitira el evento.
 * @param {string} evento Tipo de evento transmitido.
 * @param {boolean} esPrivado Especifica si se trata de un canal privado (true) o de un canal público (false). Los canales privados requieren haber iniciado sesión.
 * @param {Function} funcion Función a ejecutarse tras ejecutar el evento.
 */
Generalidades.prototype.escucharEvento = function (canal, evento, esPrivado = false, funcion = null) {
    if (!funcion) {
        funcion = response => {
            // console.log(response);
            if (response.mensaje) {
                generalidades.toastrGenerico("info", response.mensaje);
            }
        };
    }

    
    if (esPrivado) {
        // se trata de un canal privado.
        // console.log(`Escuchando evento ${evento} en el canal ${canal} (privado)`);
        window.Echo.private(canal).listen(evento, funcion);
    } else {
        // se trata de un canal público.
        // console.log(`Escuchando evento ${evento} en el canal ${canal}`);
        window.Echo.channel(canal).listen(evento, funcion);
    }
}

/**
 * Esta función permite desconectarse de todos los canales a los que esté registrado la
 * instancia actual de Laravel Echo, sin importar si son privados o públicos.
 */
Generalidades.prototype.pararEscuchaEventos = function () {
    return window.Echo.disconnect();
}

/**
 * Función que permite transformar un formData a JSON.
 * @param {FormData} formData formData a transformar a JSON.
 * @param {boolean} stringify Retornar como un string JSON o no.
 * @returns {string|Object} Retorna un Objeto o un string.
 */
Generalidades.prototype.formDataAJson = function (formData, stringify = false) {
    let json = {};
    formData.forEach((value, key) => {
        json[key] = value;
    });
    if (stringify) {
        return JSON.stringify(json);
    }
    return json;
}

/**
 * Función que permite transformar un formData a JSON.
 * @param {FormData} formData formData a transformar a JSON.
 * @param {boolean} stringify Retornar como un string JSON o no.
 * @returns {string|Object} Retorna un Objeto o un string.
 */
Generalidades.prototype.formToJson = function (formData, stringify = false) {

    let json= Array.from(formData.keys()).reduce((result, key) => {
        if (result[key]) {
          result[key] = formData.getAll(key)
          return result
        }
        result[key] = formData.get(key);
        return result;
      }, {});
    if (stringify) {
        return JSON.stringify(json);
    }
    return json;
}


/**
 * Función que permite hacer una petición a los datatables. Anteriomente se usaba ajax
 * @param {string} url Dirección URL para consultar la información
 * @param {object} data Información que sera enviada en la petición
 * @param {Function} callback Respuesta de la petición http
 * @param {object} settings Configuración del datatable
 * @param {string} method Tipo de metodo que sera la petición
 * @param {Function} success Accion Adicional cuando da la respuesta la petición
 * @param {Function} error accion cuando se presenta un error.
 */
Generalidades.prototype.peticionDT = function (url, data, callback, settings, method = "POST", success = null, error = null) {

    const idTable = settings.sTableId;

    const config = {
        method,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: data
    };

    generalidades.mostrarCargando(`#${idTable}`);

    if (!error)
        error = ex => {
            console.error(ex);
        };

    if (!success)
        success = response => {
            // console.log(response);
        };

    const complete = response => {
        generalidades.ocultarCargando(`#${idTable}`);
        success(response);
        callback(response)
    }

    generalidades.peticionHttp(url, config, complete, error);
}

/**
 * Función para refrescar un Datatable
 * @param {string} Dt especifica a que tabla se referirá. (Id del Datatable)
 */
Generalidades.prototype.refrescarDT = function (Dt) {

    if ($.fn.DataTable.isDataTable(Dt)) {
        $(Dt).DataTable().ajax.reload(null, false);
    }

}


/**
 * Función que permite inicializar el dataTable genérico.
 * @param {HTMLTableElement} id selector con el identificador del datatable
 * @param {Object} config objeto con la configuración del datatable
 * @param {string} filtro selector para realizar filtro.
 */
Generalidades.prototype.formularioEditar  = function (ruta, div, completado = false, error= false) {
 
    // if (btnAccion) {
    //     btnAccion.prop("disabled", true);
    // }

    // this.mostrarCargando(div);
    const success = response => {
        // if (btnAccion) {
        //     btnAccion.prop("disabled", false);
        // }
        // this.ocultarCargando(div);
        if (response.html != undefined) {
            $(div).html(response.html);
        }
        if (response.estado && response.mensaje) {
            this.toastrGenerico(response.estado, response.mensaje);
        }
        if (completado != false) {
            completado(response);
        }
    };
    const errors = response => {
        // if (btnAccion) {
        //     btnAccion.prop("disabled", false);
        // }
        // this.ocultarCargando(div);
        error(response);

    };
    const config = {
        headers:
        {
            // "accept":this.CONTENT_TYPE_JSON,
            "Content-Type":this.CONTENT_TYPE_JSON
        }
    };
    // console.log(config)
    this.get(ruta, config, success, errors);

}

/**
 * Función que permite salir de un canal específico.
 * @param {*} canal que se está escuchando.
 * @returns {void}
 */
Generalidades.prototype.dejarCanal = function (canal) {
    return window.Echo.leaveChannel(canal);
}
