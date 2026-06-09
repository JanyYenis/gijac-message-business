"use strict";

const formMensaje = '#formMensaje';
var typingTimer;                // Timer identifier
var doneTypingInterval = 1000;  // Time in ms (1 second)

let mediaRecorder;
let audioChunks = [];
let isRecording = false;
let wasCancelled = false;
let recordingInterval;
let seconds = 0;
window.recordedAudioBlob = null;

/*JS isnt my experty 😉*/
$(function () {
    if (Notification.permission !== 'granted') {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                console.log('Permiso concedido para notificaciones.');
            } else {
                console.log('Permiso denegado para notificaciones.');
            }
        });
    }
});

window.iniciarEmoji = () => {
    const emojiBtn = $('#emoji-btn');
    const emojiPicker = $('#emoji-picker');
    const input = $('#message-box');

    // Alternar visibilidad del picker
    emojiBtn.on('click', function(e) {
        e.stopPropagation();
        const offset = emojiBtn.offset();

        // Calcular la altura del picker para colocarlo arriba
        const pickerHeight = emojiPicker.outerHeight() || 350; // altura estimada
        emojiPicker.css({
            display: emojiPicker.is(':visible') ? 'none' : 'block',
            top: offset.top - pickerHeight - 5, // 👈 lo coloca arriba del botón
            left: offset.left
        });
    });

    // Cerrar picker si se hace clic fuera
    $(document).on('click', function(e) {
        if (!emojiPicker.is(e.target) && emojiPicker.has(e.target).length === 0 && !emojiBtn.is(e.target)) {
            emojiPicker.hide();
        }
    });

    // Insertar emoji en el input
    document.querySelector('#emoji-picker').addEventListener('emoji-click', event => {
        const emoji = event.detail.unicode;
        const inputElem = input[0];

        // Insertar en la posición del cursor o al final
        const start = inputElem.selectionStart;
        const end = inputElem.selectionEnd;
        const text = inputElem.value;
        inputElem.value = text.slice(0, start) + emoji + text.slice(end);

        // Mover el cursor al final del emoji insertado
        const newPos = start + emoji.length;
        inputElem.setSelectionRange(newPos, newPos);
        inputElem.focus();

        // Ocultar el picker
        emojiPicker.hide();
    });
}

/* image empty error replace with emoji */
document.addEventListener("DOMContentLoaded", function (event) {
    document.querySelectorAll('img').forEach(function (img) {
        img.onerror = function () {
            this.src = `data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='50' height='50'
            viewport='0 0 100 100' style='fill:black;font-size:50px; opacity:0.5;filter:grayscale(1)'>
            <filter id='grayscale'><feColorMatrix type='saturate' values='0.10'/></filter><text y='85%'>👶</text>
            </svg>`;
        };
    })
});

$(document).on('click', '.btnIrChat', function () {
    let contacto = $(this).attr('data-contacto');
    marcarComoLeido(contacto);
    $('#infoColumn').addClass('d-none');
});

$(document).on('input', '#message-box', function () {
    if ($(this).val()) {
        $('#voice-button').addClass('d-none');
        $('.seccion-texto').removeClass('d-none');
    } else {
        $('#voice-button').removeClass('d-none');
        $('#submit-button').addClass('d-none');
    }
});

const enviarDatos = (form) => {
    let formData = new FormData(document.getElementById("formMensaje"));
    formData.append('mensaje', $('#message-box').val());

    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            $('#message-box').val('');
            if (response.html != undefined && response.html != '') {
                $('.messanger-list').html(response.html);
                window.iniciarAudio();
                window.iniciarEmoji();
                $(".chat-messages").scrollTop($(".chat-messages").prop("scrollHeight"));
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

$(document).on('click', '.btnGaleria', function () {
    $('#idArchivo').trigger('click');
});

const marcarComoLeido = (de) => {
    const config = {
        'method': 'GET',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.refrescarSeccion(null, route('chats.actualizarContactos'), '#seccionListadoContactos', function () {
                generalidades.refrescarSeccion(null, route('chats.contacto', {contacto: de}), '.main-content', function () {
                    generalidades.validarFormulario(formMensaje, enviarDatos);
                    KTMenu.createInstances();
                    window.iniciarAudio();
                    window.iniciarEmoji();
                    $('.common-message-content').each(function() {
                        let texto = $(this).text()
                            .replace(/\*([^*]+)\*/g, '<strong>$1</strong>')
                            .replace(/\n/g, '<br>');

                        $(this).html(texto);
                    });
                    setTimeout(() => {
                        // Reiniciamos la detección de scroll
                        resetScrollDetection();
                        $(".chat-messages").scrollTop($(".chat-messages").prop("scrollHeight"));
                    }, 150);
                    $(".chat-messages").scrollTop($(".chat-messages").prop("scrollHeight"));
                }, false);
            }, false);
        }
    }

    const error = (response) => {
    }

    generalidades.get(route('chats.macar-mensale-leido', {de: de, para: $('#MyTelefono').val()}), config, success, error);
}

$(document).on('keyup', '#inputSearchContactos', function() {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(buscarContactos(this.value), doneTypingInterval);
});

// On keydown, clear the countdown
$(document).on('keydown', '#inputSearchContactos', function() {
    clearTimeout(typingTimer);
});

const buscarContactos = (texto) => {
    generalidades.refrescarSeccion(null, route('chats.actualizarContactos', {valor: texto}), '#seccionListadoContactos', function () {
    }, false);
}


const updateRecordingTime = () => {
    seconds++;
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    $('.recording-time').text(`${minutes}:${remainingSeconds.toString().padStart(2, '0')}`);
};

async function startRecording() {
    try {
        audioChunks = [];
        wasCancelled = false;

        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        const mimeType = 'audio/webm; codecs=opus';

        if (!MediaRecorder.isTypeSupported(mimeType)) {
            alert('Tu navegador no soporta grabación de audio.');
            return;
        }

        mediaRecorder = new MediaRecorder(stream, { mimeType });

        mediaRecorder.ondataavailable = (event) => {
            if (event.data.size > 0) {
                audioChunks.push(event.data);
            }
        };

        mediaRecorder.onstop = () => {
            if (wasCancelled) {
                console.log("Grabación cancelada. No se enviará audio.");
                return;
            }

            if (audioChunks.length === 0) {
                alert('No se grabó ningún audio.');
                return;
            }

            const audioBlob = new Blob(audioChunks, { type: mimeType });
            console.log("Blob size:", audioBlob.size);

            const audioUrl = URL.createObjectURL(audioBlob);
            document.querySelector('#audioPlayer').src = audioUrl;

            sendRecordingToServer(audioBlob);
        };

        mediaRecorder.start();
        isRecording = true;

        // UI
        $('#voice-button').addClass('d-none');
        $('.text-input').addClass('d-none');
        $('.recording-indicator').removeClass('d-none').addClass('d-flex');
        $('.recording-actions').removeClass('d-none').addClass('d-block');
        recordingInterval = setInterval(updateRecordingTime, 1000);
    } catch (err) {
        console.error('Error al iniciar la grabación:', err);
        alert('Error al acceder al micrófono. Revisa los permisos.');
    }
}

const stopRecording = () => {
    if (mediaRecorder && isRecording) {
        mediaRecorder.stop();
        isRecording = false;
        $('#voice-button').removeClass('d-none');
        $('.text-input').removeClass('d-none');
        $('.recording-indicator').addClass('d-none').removeClass('d-flex');
        $('.recording-actions').addClass('d-none').removeClass('d-block');
        clearInterval(recordingInterval);
        seconds = 0;
        $('.recording-time').text('0:00');
        mediaRecorder.stream.getTracks().forEach(track => track.stop());
    }
};

const sendRecordingToServer = (audioBlob) => {
    if (!audioBlob || audioBlob.size === 0) {
        alert('No hay audio para enviar.');
        return;
    }

    let formData = new FormData();
    formData.append('audio', audioBlob, 'grabacion.webm');
    formData.append('id', $('#idContacto').val());

    const config = {
        method: 'POST',
        headers: { 'Accept': generalidades.CONTENT_TYPE_JSON },
        body: formData
    };

    const success = (response) => {
        if (response.estado == 'success') {
            $('#message-box').val('');
            $('#modalCapturarFoto .btnClose').trigger('click');
            if (response.html) {
                $('.messanger-list').html(response.html);
                window.iniciarAudio();
                window.iniciarEmoji();
                $(".chat-messages").scrollTop($(".chat-messages").prop("scrollHeight"));
            }
        }
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    };

    const error = (response) => {
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    };

    const ruta = route("chats.store");
    generalidades.create(ruta, config, success, error);
};

$(document).on('click', '#voice-button', function(){
    startRecording();
    $('.seccion-texto').addClass('d-none');
});

$(document).on('click', '.cancel-button', function(){
    wasCancelled = true; // 🚫 marcar que se canceló
    audioChunks = [];
    stopRecording();
    $('#audioPlayer').addClass('d-none');
    $('.seccion-texto').removeClass('d-none');
    $('#submit-button').addClass('d-none');
});

$(document).on('click', '.send-button', function(){
    wasCancelled = false; // ✅ marcar que se debe enviar
    stopRecording();
    $('.seccion-texto').removeClass('d-none');
    $('#submit-button').addClass('d-none');
});

$(document).on('click', '.btnVerError', function() {
    let id = $(this).attr('data-mensaje');
    generalidades.refrescarSeccion(null, route('campanas.ver-errores', {wamid: id}), '.seccionVerErrores', function(){
        // $('#modalVerErrores').modal('show');
        generalidades.modalActual('#modalVerErrores');
    });
});

let loadingMessages = false;
let currentOffset = 0;
let currentLimit = 50; // Ajusta según necesidad
let currentContacto = '';

// Variables globales para mantener el estado
let scrollState = {
    lastPosition: 0,
    timeout: null,
    isInitialized: false
};

function initInfiniteScroll() {
    currentContacto = $('#idContacto').val() || '';

    // Solo inicializar una vez
    if (scrollState.isInitialized) return;

    // Remover cualquier manejador previo
    $(".messanger").off('scroll');

    $(".messanger").on('scroll', function() {
        const messenger = $(this)[0];
        const currentPosition = messenger.scrollTop;
        const scrollHeight = messenger.scrollHeight;
        const clientHeight = messenger.clientHeight;

        // Detectar dirección
        const direction = currentPosition > scrollState.lastPosition ? 'down' : 'up';
        scrollState.lastPosition = currentPosition;

        // Detectar posiciones clave
        const atTop = currentPosition <= 10; // Pequeño margen
        const nearBottom = scrollHeight - clientHeight <= currentPosition + 100;

        // Usar debounce
        clearTimeout(scrollState.timeout);
        scrollState.timeout = setTimeout(() => {
            if (direction === 'up' && atTop) {
                loadOlderMessages();
            }

            if (direction === 'down' && nearBottom) {
            }
        }, 150);
    });

    scrollState.isInitialized = true;
}

// Reiniciar el scroll cuando cambias de chat
function resetScrollDetection() {
    scrollState.isInitialized = false;
    initInfiniteScroll();
}

async function loadOlderMessages() {

    if (loadingMessages || !currentContacto) return;

    loadingMessages = true;
    try {
        currentLimit += currentLimit;
        const response = await fetch(route('chats.estado-mensaje', {
            contacto: currentContacto,
            offset: currentOffset,
            limit: currentLimit
        }));

        const data = await response.json();

        if (data.estado === 'success') {
            // Guardar posición actual del scroll
            const scrollHeightBefore = $(".messanger")[0].scrollHeight;

            // Insertar los mensajes antiguos al principio
            $(".messanger-list").html(data.html);
            // $(".messanger-list").prepend(data.html);

            // Ajustar el scroll para mantener la posición
            const scrollHeightAfter = $(".messanger")[0].scrollHeight;
            $(".messanger").scrollTop(scrollHeightAfter - scrollHeightBefore);
            currentLimit = 50 == data.limit ? 50 : currentLimit;
        }
    } catch (error) {
        console.error('Error loading older messages:', error);
    } finally {
        loadingMessages = false;
    }
}

$(document).on('keydown', function(event) {
    // Verificar si la tecla presionada es Escape (código 27)
    if (event.key === "Escape" || event.keyCode === 27) {
        $('.main-content').html(`<div style="display: flex; justify-content: center; align-items: center;">
                <img src="../../img/bg-chat.png"  width="70%">
            </div>`);

        // Opcional: prevenir el comportamiento por defecto
        event.preventDefault();
    }
});

$(document).on('click', '.btnVerFormulario', function() {
    let id = $(this).attr('data-mensaje');
    generalidades.refrescarSeccion(null, route('campanas.ver-formulario', {wamid: id}), '.seccionRespuesta', function(){
        generalidades.modalActual('#modalVerRespuestaFormulario');
    });
});

$(document).on('click', '#btnShowInfo', function() {
    $('#infoColumn').removeClass('d-none');

    const config = {
        'method': 'GET',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
    }

    const success = (response) => {
        if (response.estado == 'success') {
            let contacto = response.contacto;

            $('#infoProfileName').text(contacto?.nombre_contacto ?? 'N/A');
            $('.info-profile-subtitle').text('Cliente desde '+contacto.fecha_creacion);
            $('#infoPhone').text('+'+contacto.numero);
            if (contacto.estado_chatbot) {
                $('#toggleTraditional').prop('checked', true);
            } else {
                $('#toggleTraditional').prop('checked', false);
            }
            if (contacto.estado_chatbot_ia) {
                $('#toggleAI').prop('checked', true);
            } else {
                $('#toggleAI').prop('checked', false);
            }

            if (contacto?.nombre_contacto) {
                const initials = contacto.nombre_contacto.split(' ').map(n => n[0]).join('').substring(0, 2);
                $('#infoProfileAvatar').text(initials);
            } else {
                $('#infoProfileAvatar').text('N/A');
            }
            $('.btnMasInfomacion').attr('href', route('contactos.showInfo', {contacto: contacto.id}))

            $('#toggleTraditional').on('click', function() {
                actualizarDatosContacto(this, 'estado_chatbot', contacto.id);
            });

            $('#toggleAI').on('click', function() {
                actualizarDatosContacto(this, 'estado_chatbot_ia', contacto.id);
            });
        }
    }

    const error = (response) => {
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    generalidades.get(route('chats.infoContacto', {contacto: $('#idContactoGenereal').val()}), config, success, error);
});

const actualizarDatosContacto = (checkbox, campo, id) => {
    let formData = new FormData();
    formData.set(campo, checkbox.checked ? 1 : 0);

    const config = {
        'method': 'PUT',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
        }
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }
    const rutaActualizar = route("contactos.update", { "contacto": id });
    generalidades.edit(rutaActualizar, config, success, error);
}

$(document).on('click', '#btnCloseInfo', function() {
    $('#infoColumn').addClass('d-none');
});

require('./audio');
require('./camara');
require('./documentos');

// ----------------------------------------------------------------------------------------------
// Echo
Echo.join(`chat.${window.numeroTelefono}`).listen('MensajeSent', (e) => {
    // console.log('Mensaje', e);

    generalidades.refrescarSeccion(null, route('chats.actualizarContactos'), '#seccionListadoContactos', function () {
    }, false);

    let contacto = e?.mensaje?.wa_from ?? 0;
    if (parseInt(contacto) == parseInt($('#idContacto').val())) {
        generalidades.refrescarSeccion(null, route('chats.estado-mensaje', contacto), '.messanger-list', function () {
            marcarComoLeido(contacto);
            generalidades.validarFormulario(formMensaje, enviarDatos);
            KTMenu.createInstances();
            window.iniciarAudio();
            window.iniciarEmoji();
            $(".chat-messages").scrollTop($(".chat-messages").prop("scrollHeight"));
            $('.seccionSinConversacion').addClass('d-none');
            $('.seccionEnviarMensaje').removeClass('d-none');
        }, false);
    }
}).joining(user => {
    // actualizarEstadoUsuario();
}).leaving(user => {
    // actualizarEstadoUsuario();
}).here(users => {
    // actualizarEstadoUsuario();
});

Echo.join(`chat.leido.${window.numeroTelefono}`).listen('MensajeLeido', (e) => {
    // console.log('Leer mensaje', e);

    let contacto = e?.mensaje?.wa_to ?? 0;
    if (parseInt($('#idContacto').val()) && parseInt($('#idContacto').val()) == parseInt(contacto)) {
        generalidades.refrescarSeccion(null, route('chats.estado-mensaje', {contacto: parseInt($('#idContacto').val())}), '.messanger-list', function () {
            generalidades.validarFormulario(formMensaje, enviarDatos);
            KTMenu.createInstances();
            window.iniciarAudio();
            window.iniciarEmoji();
            $(".chat-messages").scrollTop($(".chat-messages").prop("scrollHeight"));
        }, false);
    }
});
