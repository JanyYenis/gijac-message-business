<!-- Modal Wizard -->
<div class="modal fade" id="wizardPredictivo" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: none; border-radius: 12px; overflow: hidden;">
            <!-- Header -->
            <div class="modal-header-wizard">
                <h5 id="modalTitle" class="text-white">
                    <i class="fas fa-chart-line"></i> Análisis Predictivo de Campañas
                </h5>
            </div>

            <!-- Body -->
            <div class="modal-body-wizard">
                <!-- Breadcrumb -->
                <div class="breadcrumb-wizard">
                    <div class="breadcrumb-step active" id="breadcrumb-1">
                        <div class="breadcrumb-step-circle">1</div>
                        <div class="breadcrumb-step-label">Plantilla</div>
                    </div>
                    <div class="breadcrumb-step" id="breadcrumb-2">
                        <div class="breadcrumb-step-circle">2</div>
                        <div class="breadcrumb-step-label">Usuarios</div>
                    </div>
                    <div class="breadcrumb-step" id="breadcrumb-3">
                        <div class="breadcrumb-step-circle">3</div>
                        <div class="breadcrumb-step-label">Resultados</div>
                    </div>
                </div>

                <!-- Step 1: Seleccionar Plantilla -->
                <div class="wizard-step active" id="step-1">
                    <div class="step-title">
                        <i class="bi bi-card-text"></i> Selecciona una Plantilla
                    </div>
                    <div class="step-description">
                        Elige la plantilla de campaña que deseas analizar para predecir la tasa de apertura.
                    </div>
                    <div class="row" id="templatesContainer">
                        <!-- Las plantillas se cargarán aquí con JavaScript -->
                    </div>
                </div>

                <!-- Step 2: Seleccionar Usuarios -->
                <div class="wizard-step" id="step-2">
                    <div class="step-title">
                        <i class="bi bi-people-fill"></i> Selecciona Usuarios
                    </div>
                    <div class="step-description">
                        Elige los usuarios a los que deseas analizar la predicción de apertura.
                    </div>
                    <div class="selected-info" id="selectedTemplateInfo" style="display: none;">
                        Plantilla seleccionada: <strong id="selectedTemplateName"></strong>
                    </div>
                    <div class="users-table-container">
                        <div class="select-all-container">
                            <input type="checkbox" id="selectAllCheckbox">
                            <label for="selectAllCheckbox">Seleccionar todos</label>
                        </div>
                        <table class="table users-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px;"></th>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Última Apertura</th>
                                    <th>Tasa Histórica</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <!-- Los usuarios se cargarán aquí con JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Step 3: Resultados del Análisis -->
                <div class="wizard-step" id="step-3">
                    <div class="step-title">
                        <i class="bi bi-bar-chart-fill"></i> Análisis Predictivo
                    </div>
                    <div class="step-description">
                        Resultados del análisis de predicción para los usuarios seleccionados.
                    </div>
                    <div class="selected-info" id="analysisInfo" style="display: none;">
                        Plantilla: <strong id="analysisTemplateName"></strong> | Usuarios: <strong
                            id="analysisUserCount">0</strong>
                    </div>
                    <div class="results-stats" id="resultStats">
                        <!-- Las estadísticas se cargarán aquí -->
                    </div>
                    <div class="results-table-container">
                        <table class="table results-table">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Probabilidad</th>
                                    <th>Progreso</th>
                                    <th>Estado Probable</th>
                                </tr>
                            </thead>
                            <tbody id="resultsTableBody">
                                <!-- Los resultados se cargarán aquí con JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer-wizard">
                <button type="button" class="btn-custom btn-secondary-wizard" id="btnAnterior" style="display: none;">
                    <i class="bi bi-chevron-left"></i> Anterior
                </button>
                <button type="button" class="btn-custom btn-secondary-wizard" id="btnCerrar">
                    Cerrar
                </button>
                <button type="button" class="btn-custom btn-primary-wizard" id="btnSiguiente" disabled>
                    Siguiente <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
