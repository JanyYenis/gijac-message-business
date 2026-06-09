"use strict";

// ============================================
// DATOS DUMMY
// ============================================

var plantillas = [];
var usuarios = [];

// ============================================
// VARIABLES DE ESTADO
// ============================================

let wizard = {
    step: 1,
    selectedTemplate: null,
    selectedUsers: [],
    predictions: []
};

// ============================================
// INICIALIZAR MODAL
// ============================================

const modal = new bootstrap.Modal(document.getElementById('wizardPredictivo'));

document.getElementById('abrirModalPredictivo').addEventListener('click', () => {
    modal.show();
    resetWizard();
});

document.getElementById('btnCerrar').addEventListener('click', () => {
    modal.hide();
    resetWizard();
});

// ============================================
// RENDERIZAR PLANTILLAS (STEP 1)
// ============================================

function renderPlantillas() {
    const container = document.getElementById('templatesContainer');
    const config = {
        'method': 'GET',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
    }

    const success = (response) => {
        if (response.estado == 'success') {
            plantillas = response.plantillas;

            container.innerHTML = plantillas.map(plantilla => `
                <div class="col-md-6 mb-3">
                    <div class="template-card selectPlantillaPrediccion" data-id="${plantilla.id}">
                        <div class="template-name">${plantilla.name}</div>
                        <div class="template-type">${plantilla.info_categoria.nombre}</div>
                        <div class="template-preview">${plantilla.body.text}</div>
                    </div>
                </div>
            `).join('');
        }
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.ocultarCargando('body');
    }

    const error = (response) => {
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    generalidades.get(route('plantillas.buscar'), config, success, error);
    generalidades.mostrarCargando('body');
}

$(document).on('click', '.selectPlantillaPrediccion', function(){
    selectPlantilla(this, $(this).attr('data-id'));
});

function selectPlantilla(elemento, id) {
    wizard.selectedTemplate = plantillas.find(p => parseInt(p.id) === parseInt(id));

    wizard.selectedUsers = [];

    // Actualizar UI
    document.querySelectorAll('.template-card').forEach(card => {
        card.classList.remove('selected');
    });
    $(elemento).addClass('selected');

    // Habilitar botón siguiente
    document.getElementById('btnSiguiente').disabled = false;

    console.log('[v0] Plantilla seleccionada:', wizard.selectedTemplate);
}

// ============================================
// RENDERIZAR USUARIOS (STEP 2)
// ============================================

function renderUsuarios() {
    const tbody = document.getElementById('usersTableBody');
    const config = {
        'method': 'GET',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
    }

    const success = (response) => {
        if (response.estado == 'success') {
            usuarios = response.contactos;
            tbody.innerHTML = usuarios.map(usuario => `
                            <tr>
                                <td>
                                    <input type="checkbox" class="user-checkbox toggleUsuario" value="${usuario.id}" data-id="${usuario.id}">
                                </td>
                                <td><span class="user-name">${usuario.text}</span></td>
                                <td><span class="user-phone">${usuario.numero_cliente}</span></td>
                                <td><span class="user-stats">${formatearFecha(usuario.ultima_apertura)}</span></td>
                                <td><span class="user-stats">${usuario.tasa_apertura}%</span></td>
                            </tr>
                        `).join('');

                // Actualizar información de plantilla seleccionada
                document.getElementById('selectedTemplateInfo').style.display = 'block';
                document.getElementById('selectedTemplateName').textContent = wizard.selectedTemplate.name;
        }
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.ocultarCargando('body');
    }

    const error = (response) => {
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    generalidades.get(route('contactos.buscarPrediccion'), config, success, error);
    generalidades.mostrarCargando('body');
}

$(document).on('change', '.toggleUsuario', function() {
    toggleUsuario(this, $(this).attr('data-id'));
});

function toggleUsuario(elemento, id) {
    const checkbox = document.querySelector(`input[value="${id}"]`);
    if (checkbox.checked) {
        if (!wizard.selectedUsers.includes(id)) {
            wizard.selectedUsers.push(id);
        }
    } else {
        wizard.selectedUsers = wizard.selectedUsers.filter(uid => uid !== id);
    }

    // Actualizar checkbox "Seleccionar todos"
    updateSelectAllCheckbox();

    // Habilitar botón siguiente
    document.getElementById('btnSiguiente').disabled = wizard.selectedUsers.length === 0;

    console.log('[v0] Usuarios seleccionados:', wizard.selectedUsers);
}

document.getElementById('selectAllCheckbox').addEventListener('change', function () {
    if (this.checked) {
        wizard.selectedUsers = usuarios.map(u => u.id);
    } else {
        wizard.selectedUsers = [];
    }

    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.checked = this.checked;
    });

    document.getElementById('btnSiguiente').disabled = wizard.selectedUsers.length === 0;
});

function updateSelectAllCheckbox() {
    const selectAll = document.getElementById('selectAllCheckbox');
    const allChecked = document.querySelectorAll('.user-checkbox:checked').length === usuarios.length;
    selectAll.checked = allChecked;
}

function renderResultados() {
    let formData = new FormData();
    formData.append('contactos_ids', wizard.selectedUsers);
    formData.append('contenido_mensaje', wizard.selectedTemplate.body.text);
    formData.append('nombre_campana', wizard.selectedTemplate.name);

    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.success) {
            // Actualizar información
            document.getElementById('analysisInfo').style.display = 'block';
            document.getElementById('analysisTemplateName').textContent = wizard.selectedTemplate.name;
            document.getElementById('analysisUserCount').textContent = wizard.selectedUsers.length;

            // Calcular estadísticas
            const totalUsers = wizard.selectedUsers.length;
            const willOpen = response?.data?.estadisticas_generales?.contactos_alta_confianza +
                response?.data?.estadisticas_generales?.contactos_media_confianza ?? 0;
            const wontOpen = response?.data?.estadisticas_generales?.contactos_baja_confianza ?? 0;
            const avgProbability = (response?.data?.estadisticas_generales?.probabilidad_promedio * 100) ?? 0;

            // Renderizar estadísticas
            const statsContainer = document.getElementById('resultStats');
            statsContainer.innerHTML = `
                        <div class="stats-card">
                            <div class="stats-label">Total de Usuarios</div>
                            <div class="stats-value">${totalUsers}</div>
                        </div>
                        <div class="stats-card">
                            <div class="stats-label">Probabilidad Promedio</div>
                            <div class="stats-value">${parseFloat(avgProbability.toFixed(2))}%</div>
                        </div>
                        <div class="stats-card">
                            <div class="stats-label">Aperturas Esperadas</div>
                            <div class="stats-value" style="color: var(--success-color);">${willOpen}</div>
                        </div>
                        <div class="stats-card">
                            <div class="stats-label">No Aperturas Esperadas</div>
                            <div class="stats-value" style="color: var(--danger-color);">${wontOpen}</div>
                        </div>
                    `;

            // Renderizar tabla de resultados
            const tbody = document.getElementById('resultsTableBody');
            tbody.innerHTML = response?.data.resultados.map(pred => {
                let badgeClass = 'probability-high';
                if ((pred.probabilidad_apertura * 100) < 50) badgeClass = 'probability-low';
                else if ((pred.probabilidad_apertura * 100) < 75) badgeClass = 'probability-medium';

                let statusClass = 'status-will-open';
                if (pred.nivel_confianza === 'Baja') statusClass = 'status-wont-open';

                return `
                            <tr>
                                <td>${pred.nombre_cliente} - ${pred.telefono}</td>
                                <td>
                                    <div class="probability-badge ${badgeClass}">
                                        ${parseFloat(pred.probabilidad_apertura * 100).toFixed(2)}%
                                    </div>
                                </td>
                                <td>
                                    <div class="probability-bar">
                                        <div class="probability-bar-fill" style="width: ${(pred.probabilidad_apertura * 100).toFixed(2)}%"></div>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge ${statusClass}">
                                        ${pred.nivel_confianza}
                                    </span>
                                </td>
                            </tr>
                        `;
            }).join('');
        }
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones('body', response.validaciones);
    }
    const ruta = route("campanas.predecir");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando('body');

}

// ============================================
// NAVEGACIÓN DEL WIZARD
// ============================================

document.getElementById('btnSiguiente').addEventListener('click', () => {
    if (wizard.step === 1) {
        renderUsuarios();
    } else if (wizard.step === 2) {
        renderResultados();
    }
    wizard.step++;
    updateWizardUI();
});

document.getElementById('btnAnterior').addEventListener('click', () => {
    wizard.step--;
    updateWizardUI();
});

function updateWizardUI() {
    // Ocultar todos los pasos
    document.querySelectorAll('.wizard-step').forEach(step => {
        step.classList.remove('active');
    });

    // Mostrar paso actual
    $(`.wizard-step#step-${wizard.step}`).addClass('active');

    // Actualizar breadcrumbs
    document.querySelectorAll('.breadcrumb-step').forEach((step, idx) => {
        step.classList.remove('active', 'completed');
        const stepNum = idx + 1;
        if (stepNum === wizard.step) {
            step.classList.add('active');
        } else if (stepNum < wizard.step) {
            step.classList.add('completed');
        }
    });

    // Actualizar botones
    document.getElementById('btnAnterior').style.display = wizard.step > 1 ? 'block' : 'none';
    document.getElementById('btnSiguiente').style.display = wizard.step < 3 ? 'block' : 'none';

    // Deshabilitar botón siguiente en último paso
    if (wizard.step === 3) {
        document.getElementById('btnSiguiente').style.display = 'none';
    }

    // Actualizar estado del botón siguiente
    if (wizard.step === 1) {
        document.getElementById('btnSiguiente').disabled = !wizard.selectedTemplate;
    } else if (wizard.step === 2) {
        document.getElementById('btnSiguiente').disabled = wizard.selectedUsers.length === 0;
    }

    console.log('[v0] Paso actual:', wizard.step);
}

// ============================================
// RESET WIZARD
// ============================================

function resetWizard() {
    wizard = {
        step: 1,
        selectedTemplate: null,
        selectedUsers: [],
        predictions: []
    };

    // Limpiar UI
    document.querySelectorAll('.wizard-step').forEach(step => {
        step.classList.remove('active');
    });

    $(".wizard-step#step-1").addClass('active');

    document.querySelectorAll('.breadcrumb-step').forEach((step, idx) => {
        step.classList.remove('active', 'completed');
        if (idx === 0) step.classList.add('active');
    });

    document.getElementById('btnAnterior').style.display = 'none';
    document.getElementById('btnSiguiente').style.display = 'block';
    document.getElementById('btnSiguiente').disabled = true;

    // Renderizar plantillas
    renderPlantillas();

    // Limpiar formularios
    document.getElementById('selectedTemplateInfo').style.display = 'none';
    document.getElementById('analysisInfo').style.display = 'none';
    document.getElementById('selectAllCheckbox').checked = false;
}

function formatearFecha(fechaString) {
    const fecha = new Date(fechaString);
    const opciones = {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    };

    return fecha.toLocaleDateString('es-ES', opciones);
}
