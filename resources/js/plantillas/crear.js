"use strict";

const formCrearPlantilla = '#formCrearPlantilla';
window.cantidadVaribles = 0;
var incremento = 1;

$(function () {
    iniciarComponentes();
    generalidades.validarFormulario(formCrearPlantilla, enviarDatos);
});

const iniciarComponentes = (form = "") => {
    $('#contenidoCampana').maxlength({
        warningClass: "badge badge-primary",
        limitReachedClass: "badge badge-success"
    });
}

$(document).on('change', '#selectEncabezado', function(){
    console.log(this.value);
    if (this.value == 1) {
        $('.seccionTextoEncabezado').removeClass('d-none');
        $('.seccionMultimediaEncabezado').addClass('d-none');
    } else if (this.value == 2) {
        $('.seccionTextoEncabezado').addClass('d-none');
        $('.seccionMultimediaEncabezado').removeClass('d-none');
    } else {
        $('.seccionTextoEncabezado').addClass('d-none');
        $('.seccionMultimediaEncabezado').addClass('d-none');
        $('#divInputFile').addClass('d-none');
        $('#inputFile').val('');
        $('#opcionMultimediaImagen').prop('checked', false);
        $('#opcionMultimediaVideo').prop('checked', false);
        $('#opcionMultimediaDocumento').prop('checked', false);
    }
});

$(document).on('click', '#opcionMultimediaImagen', function(){
    $('#inputFile').val('');
    if (this.checked) {
        $('#nombreDocumento').addClass('d-none');
        $('#result').empty();
        $('.imagenCampana').removeClass('d-none');
        $('.imgCampana').attr('src', window.baseUrl+'img/defecto.png');
        $('#divInputFile').removeClass('d-none');
        $('#inputFile').attr('accept', 'image/png');
    } else {
        $('.imagenCampana').addClass('d-none');
        $('#divInputFile').addClass('d-none');
    }
});

$(document).on('click', '#opcionMultimediaVideo', function(){
    $('#inputFile').val('');
    if (this.checked) {
        $('#nombreDocumento').addClass('d-none');
        $('#result').empty();
        $('.imagenCampana').removeClass('d-none');
        $('#divInputFile').removeClass('d-none');
        $('.imgCampana').attr('src', window.baseUrl+'img/video-defecto.png');
        $('#inputFile').attr('accept', 'video/mp4');
    } else {
        $('.imagenCampana').addClass('d-none');
        $('#divInputFile').addClass('d-none');
        $('#nombreDocumento').addClass('d-none');
        $('#result').empty();
    }
});

$(document).on('click', '#opcionMultimediaDocumento', function(){
    $('#inputFile').val('');
    if (this.checked) {
        $('#nombreDocumento').addClass('d-none');
        $('#result').empty();
        $('.imagenCampana').removeClass('d-none');
        $('#divInputFile').removeClass('d-none');
        $('.imgCampana').attr('src', window.baseUrl+'img/documento-defecto.png');
        $('#inputFile').attr('accept', 'application/pdf');
    } else {
        $('.imagenCampana').addClass('d-none');
        $('#divInputFile').addClass('d-none');
        $('#nombreDocumento').addClass('d-none');
        $('#result').empty();
    }
});

$(document).on("input", "#contenidoCampana", function() {
    $('.inputVariable').remove();
    let inputValue = $(this).val();
    let cantidadV = {};

    // Busca todas las instancias de '{{n}}' en el texto
    let matches = inputValue.match(/{{(\d+)}}/g);

    // Actualiza el cantidadV
    if (matches) {
    matches.forEach(function (match) {
        let numero = parseInt(match.match(/{{(\d+)}}/)[1]);
        cantidadV[numero] = true;
    });
    }

    // Muestra el cantidadV
    let conteo = Object.keys(cantidadV).length;
    // // let palabraBuscada = "{{";

    // let contenido = this.value.toLowerCase(); // Convierte a minúsculas para ser insensible a mayúsculas/minúsculas
    // let conteo = (contenido.match(/{{/g) || []).length;
    window.cantidadVaribles = conteo;
    if (conteo) {
        let variables = '';
        for (let index = 0; index < conteo; index++) {
            variables = variables + `<div class="mb-3 inputVariable">
                <input type="text" name="variables[]" data-numero="${index+1}" placeholder="Ingrese valor de variable {{${index+1}}}" class="inputG variablesCampana">
            </div>`;
        }
        $('#divVariables').html(variables);
    }
    if (conteo) {
        $('#divAlertaVaribles').removeClass('d-none');
    } else {
        $('#divAlertaVaribles').addClass('d-none');
    }

    var texto = $(this).val();

    // if (texto.includes('{{')) {
    // // Encuentra todas las instancias de '{{' en el texto del textarea
    // let coincidencias = texto.match(/{{/g) || [];

    // // Actualiza el contador con la cantidad de coincidencias
    // contador = coincidencias.length + 1;

    // // Reemplaza '{{' con '{{contador}}'
    // let nuevoContenido = texto.replace(/{{/g, '{{' + window.cantidadVaribles);

    // // Establece el nuevo contenido en el textarea
    // $(this).val(nuevoContenido);
    // }

    // var text = $(this).val();
    // var newText = text.replace(/{{/g, function(match) {
    // return match + window.cantidadVaribles++;
    // });
    
    // $(this).val(newText);

    if (texto != '') {
        $(".contenidocampana").text(texto);
    } else {
        $(".contenidocampana").text('Mensaje');
    }
});

$(document).on('change', '#inputFile', function() {
    var file = $(this).prop('files')[0];
    var tipo = file.type;
    var nombre = file.name;

    if (tipo.startsWith('image/')) {
        $('#result').empty();
        $('.imagenCampana').removeClass('d-none');
        $('#nombreDocumento').addClass('d-none');
        // Cargar imagen
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.imgCampana').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
    } else if (tipo.startsWith('video/')) {
        $('#result').empty();
        $('.imagenCampana').addClass('d-none');
        $('#nombreDocumento').addClass('d-none');
        // Cargar video
        var video = document.createElement('video');
        video.src = URL.createObjectURL(file);
        video.controls = true;
        $('#result').html(video);
    } else {
        $('.imagenCampana').addClass('d-none');
        $('#nombreDocumento').addClass('d-none');
        $('#result').empty();
        $('#nombreDocumento').removeClass('d-none').text(nombre);
    }

});

$(document).on('change', '#selectBoton', function(){
    if (this.value) {
        $('.seccionBtn').removeClass('d-none');
        if (this.value == 1) {
            $('.divUrl').removeClass('d-none');
            $('.textoBtn').text('  Sitio web');
        } else {
            $('.divUrl').addClass('d-none');
            $('.textoBtn').text('  Contacte a su visitador');
        }
    } else {
        $('.textoBtn').text('');
        $('.divUrl').addClass('d-none');
        $('.seccionBtn').addClass('d-none');
    }
});

$(document).on('hidden.bs.modal', '#modalCrearPlantilla', function () {
    $('.inputG').val('');
    $('.inputGG').val('');
    window.cantidadVaribles = 0;
});

$(document).on("input", "#nombrePlantilla", function() {
    convertirTexto('nombrePlantilla');
});

const convertirTexto = (nombre) => {
    let inputElement = document.getElementById(nombre);
    let textoOriginal = inputElement.value;
    let textoConvertido = textoOriginal.toLowerCase().replace(/ /g, "_");
    textoConvertido = textoConvertido.replace(/\./g, '');
    inputElement.value = textoConvertido;
}

const remplazarDatos = (cadena, datos) => {
    // Utiliza una expresión regular para encontrar todas las coincidencias de {{algo}}
    return cadena.replace(/\{\{(\d+)\}\}/g, function(match, grupo1) {
        // El grupo1 contendrá el número entre las {{}}, que se usa como índice en el objeto de datos
        const indice = parseInt(grupo1, 10);

        // Verifica si el índice existe en los datos y reemplaza con el valor correspondiente
        if (datos.hasOwnProperty(indice)) {
        return datos[indice];
        }

        // Si el índice no existe en los datos, simplemente deja la cadena original sin cambios
        return match;
    });
}

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formCrearPlantilla"));
    
    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formCrearPlantilla);
        }
        generalidades.ocultarCargando(formCrearPlantilla);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formCrearPlantilla);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formCrearPlantilla, response.validaciones);
    }
    const ruta = route("plantillas.store");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(formCrearPlantilla);
}