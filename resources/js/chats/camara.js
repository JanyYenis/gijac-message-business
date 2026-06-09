"use strict";

const formCamara = '#formCamara';
const para = '';
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const photo = document.getElementById('photo');
const captureButton = document.getElementById('capture');

$(function () {
    //
});

$(document).on('shown.bs.modal', '#modalCapturarFoto', function () {
    // Solicitar acceso a la cámara
    $('#video').removeClass('d-none');
    photo.style.display = 'none';
    $('#captureOtra').addClass('d-none');
    $('#enviar').addClass('d-none');
    $('#capture').removeClass('d-none');
    $('#mensajeInput').addClass('d-none');
    iniciarCamara();
    generalidades.validarFormulario(formCamara, enviarDatosCamara);
});

$(document).on('hidden.bs.modal', '#modalCapturarFoto', function () {
    if (video.srcObject) {
        let tracks = video.srcObject.getTracks();
        tracks.forEach(track => track.stop());
        video.srcObject = null;
    }
});

// Capturar foto
$(document).on('click', '#capture', function () {
    $('#video').addClass('d-none');
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    photo.src = canvas.toDataURL('image/png');
    photo.style.display = 'block';
    $('#captureOtra').removeClass('d-none');
    $('#enviar').removeClass('d-none');
    $('#mensajeInput').removeClass('d-none');
    $('#capture').addClass('d-none');
    if (video.srcObject) {
        let tracks = video.srcObject.getTracks();
        tracks.forEach(track => track.stop());
        video.srcObject = null;
    }
    // console.log(canvas.toDataURL());
});

$(document).on('click', '#captureOtra', function () {
    iniciarCamara();
    $('#video').removeClass('d-none');
    photo.style.display = 'none';
    $('#captureOtra').addClass('d-none');
    $('#enviar').addClass('d-none');
    $('#mensajeInput').addClass('d-none');
    $('#capture').removeClass('d-none');
});

const iniciarCamara = () => {
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function (stream) {
            video.srcObject = stream;
        })
        .catch(function (err) {
            console.error("Error al acceder a la cámara: ", err);
        });
}

const enviarDatosCamara = (form) => {
    let formData = new FormData(document.getElementById("formCamara"));
    formData.append('id', parseInt($('#idContacto').val()));
    formData.append('imagen', canvas.toDataURL());

    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            $('.textInput').val('');
            $('#modalCapturarFoto .btnClose').trigger('click');
            if (response.html != undefined && response.html != '') {
                $('.seccionchat').html(response.html);
                window.iniciarAudio();
                window.iniciarEmoji();
                $(".messanger").scrollTop($(".messanger").prop("scrollHeight"));
            }
        }
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }
    const ruta = route("chats.store");
    generalidades.create(ruta, config, success, error);
}
