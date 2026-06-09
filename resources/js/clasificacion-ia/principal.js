"use strict";

$(function () {
    iniciarComponentes();
    setupEventListeners();
    loadSavedPrompt();
    updateCharCounter();
});

const iniciarComponentes = (form = "") => {
    //
}

// Global variables
let isSubmitting = false;
const API_ENDPOINT = 'http://127.0.0.1:8001/configurar_prompt';
const MAX_CHARS = 5000;

// Setup event listeners
function setupEventListeners() {
    // Form submission
    // $('#promptForm').on('submit', function(e) {
    //     e.preventDefault();
    //     handleSubmit();
    // });

    // Character counter
    $('#promptTextarea').on('input', function() {
        updateCharCounter();
    });

    // Example prompts click
    $('.example-item').on('click', function() {
        const exampleText = $(this).text().trim();
        $('#promptTextarea').val(exampleText);
        updateCharCounter();

        // Highlight animation
        $(this).css('background', 'rgba(13, 110, 253, 0.15)');
        setTimeout(() => {
            $(this).css('background', '');
        }, 300);

        // Show info alert
        showAlert('info', 'Ejemplo cargado', 'Puedes modificar este prompt según tus necesidades.');
    });

    // Auto-save on blur (optional)
    $('#promptTextarea').on('blur', function() {
        const prompt = $(this).val().trim();
        if (prompt) {
            localStorage.setItem('draft_prompt', prompt);
        }
    });

    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            if (!isSubmitting) {
                $('#promptForm').submit();
            }
        }

        // Ctrl/Cmd + K to clear
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            clearForm();
        }
    });
}

// Update character counter
function updateCharCounter() {
    const text = $('#promptTextarea').val();
    const length = text.length;
    const counter = $('#charCounter');

    counter.text(`${length} / ${MAX_CHARS} caracteres`);

    // Update counter color based on length
    counter.removeClass('warning danger');
    if (length > MAX_CHARS * 0.9) {
        counter.addClass('danger');
    } else if (length > MAX_CHARS * 0.75) {
        counter.addClass('warning');
    }
}

// Handle form submission
function handleSubmit() {
    if (isSubmitting) return;

    const prompt = $('#promptTextarea').val().trim();

    // Validation
    if (!prompt) {
        showAlert('warning', 'Campo vacío', 'Por favor, escribe un prompt antes de guardar.');
        $('#promptTextarea').addClass('shake');
        setTimeout(() => {
            $('#promptTextarea').removeClass('shake');
        }, 500);
        $('#promptTextarea').focus();
        return;
    }

    if (prompt.length < 20) {
        showAlert('warning', 'Prompt muy corto', 'El prompt debe tener al menos 20 caracteres para ser efectivo.');
        $('#promptTextarea').focus();
        return;
    }

    // Show loading state
    setLoadingState(true);

    // Prepare data
    const data = {
        prompt_usuario: prompt
    };

    // Send POST request
    $.ajax({
        url: API_ENDPOINT,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        timeout: 10000,
        success: function(response) {
            handleSuccess(response);
        },
        error: function(xhr, status, error) {
            handleError(xhr, status, error);
        },
        complete: function() {
            setLoadingState(false);
        }
    });
}

// Handle successful response
function handleSuccess(response) {
    // Save to localStorage
    localStorage.setItem('saved_prompt', $('#promptTextarea').val().trim());
    localStorage.removeItem('draft_prompt');

    // Show success message
    showAlert('success', '¡Prompt guardado exitosamente!', 'La configuración de clasificación ha sido actualizada correctamente.');

    // Success animation
    $('#saveBtn').html('<i class="fas fa-check"></i> ¡Guardado!');
    setTimeout(() => {
        $('#saveBtn').html('<i class="fas fa-save"></i> Guardar Prompt');
    }, 3000);

    // Log response
    console.log('[v0] Prompt saved successfully:', response);
}

// Handle error response
function handleError(xhr, status, error) {
    let errorMessage = 'No se pudo conectar con el servidor. Verifica que el endpoint esté disponible.';

    if (xhr.status === 400) {
        errorMessage = 'Datos inválidos. Por favor, revisa el formato del prompt.';
    } else if (xhr.status === 500) {
        errorMessage = 'Error interno del servidor. Intenta nuevamente más tarde.';
    } else if (status === 'timeout') {
        errorMessage = 'La solicitud ha excedido el tiempo de espera. Verifica tu conexión.';
    }

    showAlert('danger', 'Error al guardar', errorMessage);

    // Shake animation
    $('.config-card').addClass('shake');
    setTimeout(() => {
        $('.config-card').removeClass('shake');
    }, 500);

    // Log error
    console.error('[v0] Error saving prompt:', {
        status: xhr.status,
        statusText: status,
        error: error,
        response: xhr.responseText
    });
}

// Set loading state
function setLoadingState(loading) {
    isSubmitting = loading;
    const $saveBtn = $('#saveBtn');
    const $textarea = $('#promptTextarea');

    if (loading) {
        $saveBtn.prop('disabled', true);
        $saveBtn.html('<span class="loading-spinner"></span> Guardando...');
        $textarea.prop('disabled', true);
    } else {
        $saveBtn.prop('disabled', false);
        $saveBtn.html('<i class="fas fa-save"></i> Guardar Prompt');
        $textarea.prop('disabled', false);
    }
}

// Show alert
window.showAlert = (type, title, message) => {
    const alertContainer = $('#alertContainer');

    // Clear previous alerts
    alertContainer.empty();

    // Icon mapping
    const icons = {
        success: 'fa-check-circle',
        danger: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };

    // Create alert
    const alert = $(`
        <div class="alert-custom alert-${type}-custom" role="alert">
            <i class="fas ${icons[type]}"></i>
            <div>
                <strong>${title}</strong>
                <div>${message}</div>
            </div>
        </div>
    `);

    // Add to container
    alertContainer.append(alert);

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        alert.fadeOut(300, function() {
            $(this).remove();
        });
    }, 5000);

    // Scroll to alert
    $('html, body').animate({
        scrollTop: alertContainer.offset().top - 20
    }, 300);
}

// Load saved prompt
function loadSavedPrompt() {
    // Try to load from localStorage
    const savedPrompt = localStorage.getItem('saved_prompt');
    const draftPrompt = localStorage.getItem('draft_prompt');

    if (draftPrompt && !savedPrompt) {
        $('#promptTextarea').val(draftPrompt);
        showAlert('info', 'Borrador recuperado', 'Se ha cargado tu último borrador no guardado.');
    } else if (savedPrompt) {
        $('#promptTextarea').val(savedPrompt);
    }

    updateCharCounter();
}

// Clear form
function clearForm() {
    if (confirm('¿Estás seguro de que deseas limpiar el formulario?')) {
        $('#promptTextarea').val('');
        updateCharCounter();
        localStorage.removeItem('draft_prompt');
        showAlert('info', 'Formulario limpiado', 'El contenido ha sido eliminado.');
    }
}

// Handle online/offline status
window.addEventListener('online', function() {
    showAlert('success', 'Conexión restaurada', 'Ya puedes guardar tu configuración.');
});

window.addEventListener('offline', function() {
    showAlert('warning', 'Sin conexión', 'No podrás guardar cambios hasta que se restaure la conexión.');
});

// Focus textarea on page load
$(window).on('load', function() {
    $('#promptTextarea').focus();
});

require('./crear');
