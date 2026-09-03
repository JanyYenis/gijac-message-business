<!-- Modal Wizard -->
<div class="modal fade" id="wizardPredictivo" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: none; border-radius: 12px; overflow: hidden;">
            <!-- Header -->
            <div class="modal-header-wizard">
                <h5 id="modalTitle" class="text-white">
                    <i class="fas fa-chart-line"></i>{{ __('Análisis Predictivo de Campañas') }}</h5>
            </div>

            <!-- Body -->
            <div class="modal-body-wizard">
                <!-- Breadcrumb -->
                <div class="breadcrumb-wizard">
                    <div class="breadcrumb-step active" id="breadcrumb-1">
                        <div class="breadcrumb-step-circle">1</div>
                        <div class="breadcrumb-step-label">{{ __('Plantilla') }}</div>
                    </div>
                    <div class="breadcrumb-step" id="breadcrumb-2">
                        <div class="breadcrumb-step-circle">2</div>
                        <div class="breadcrumb-step-label">{{ __('Usuarios') }}</div>
                    </div>
                    <div class="breadcrumb-step" id="breadcrumb-3">
                        <div class="breadcrumb-step-circle">3</div>
                        <div class="breadcrumb-step-label">{{ __('Resultados') }}</div>
                    </div>
                </div>

                <!-- Step 1: Seleccionar Plantilla -->
                <div class="wizard-step active" id="step-1">
                    <div class="step-title">
                        <i class="bi bi-card-text text-dark"></i>{{ __('Selecciona una Plantilla') }}</div>
                    <div class="step-description">{{ __('Elige la plantilla de campaña que deseas analizar para predecir la tasa de apertura.') }}</div>
                    <div class="row" id="templatesContainer">
                        <!-- Las plantillas se cargarán aquí con JavaScript -->
                    </div>
                </div>

                <!-- Step 2: Seleccionar Usuarios -->
                <div class="wizard-step" id="step-2">
                    <div class="step-title">
                        <i class="bi bi-people-fill text-dark"></i>{{ __('Selecciona Usuarios') }}</div>
                    <div class="step-description">{{ __('Elige los usuarios a los que deseas analizar la predicción de apertura.') }}</div>
                    <div class="selected-info" id="selectedTemplateInfo" style="display: none;">{{ __('Plantilla seleccionada:') }}<strong id="selectedTemplateName"></strong>
                    </div>
                    <div class="users-table-container">
                        <div class="select-all-container">
                            <input type="checkbox" id="selectAllCheckbox">
                            <label for="selectAllCheckbox">{{ __('Seleccionar todos') }}</label>
                        </div>
                        <table class="table users-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px;"></th>
                                    <th>{{ __('Nombre') }}</th>
                                    <th>{{ __('Teléfono') }}</th>
                                    <th>{{ __('Última Apertura') }}</th>
                                    <th>{{ __('Tasa Histórica') }}</th>
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
                    <!--begin::Alert-->
                    <div class="alert alert-dismissible bg-light-info border border-info d-flex flex-column flex-sm-row p-5 mb-10">
                        <i class="fas fa-info-circle fs-2hx text-info me-4 mb-5 mb-sm-0">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="d-flex flex-column pe-0 pe-sm-10">
                            <h5 class="mb-1">{{ __('Reporte de predicción por correo') }}</h5>
                            <span>{{ __('Al generar la predicción de la campaña, el análisis se procesa en segundo plano y puede tardar unos minutos. Cuando esté listo, recibirás un correo con el reporte completo en Excel, incluyendo la probabilidad de apertura, el nivel de confianza y la mejor hora de envío por cada contacto.') }}</span>
                        </div>
                        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                            <i class="las la-times fs-1 text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>
                    </div>
                    <!--end::Alert-->
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer-wizard">
                <button type="button" class="btn-custom btn-secondary-wizard" id="btnAnterior" style="display: none;">
                    <i class="bi bi-chevron-left text-dark"></i>{{ __('Anterior') }}</button>
                <button type="button" class="btn-custom btn-secondary-wizard" id="btnCerrar">{{ __('Cerrar') }}</button>
                <button type="button" class="btn-custom btn-primary-wizard" id="btnSiguiente" disabled>{{ __('Siguiente') }}<i class="bi bi-chevron-right text-white"></i>
                </button>
            </div>
        </div>
    </div>
</div>
