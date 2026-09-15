<input type="hidden" name="id" value="{{ $campana->id }}">
<div id="erroresEdit">
    @component('sistema/div-errores')
    @endcomponent
</div>

<div class="container-fluid main-container">
    <!-- Stepper -->
    <div class="stepper">
        <div class="stepper-item active" data-step="1">
            <div class="stepper-number">1</div>
            <div class="stepper-text">{{ __('Configuración') }}</div>
        </div>
        <div class="stepper-item" data-step="2">
            <div class="stepper-number">2</div>
            <div class="stepper-text">{{ __('Contenido') }}</div>
        </div>
        <div class="stepper-item" data-step="3">
            <div class="stepper-number">3</div>
            <div class="stepper-text">{{ __('Destinatarios') }}</div>
        </div>
        <div class="stepper-item" data-step="4">
            <div class="stepper-number">4</div>
            <div class="stepper-text">{{ __('Confirmar') }}</div>
        </div>
    </div>

    <div class="row">
        <!-- Form Section -->
        <div class="col-lg-8">
            <!-- Step 1: Configuración -->
            <div class="step-content active" id="stepEdit-1">
                <div class="card campaign-card">
                    <div class="card-header card-header-custom">
                        <h5 class="text-white">
                            <i class="bi bi-gear-fill text-white fs-2"></i> {{ __('Datos Generales') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="campaignNameEdit" class="form-label required fw-semibold">
                                    {{ __('Nombre de la Campaña') }}
                                </label>
                                <input type="text" required name="nombre" value="{{ $campana?->nombre ?? '' }}" class="form-control" id="campaignNameEdit"
                                    placeholder="{{ __('Ej: Promoción Black Friday') }} {{ date('Y') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="selectCategoriaEditar" class="form-label fw-semibold required">{{ __('Categoría') }}</label>
                                <select class="form-select" name="categoria" data-control="select2"
                                    data-dropdown-parent="body" data-placeholder="{{ __('Categoría') }}" data-allow-clear="true"
                                    required id="selectCategoriaEditar" data-hide-search="true">
                                    <option value=""></option>
                                    @foreach ($categorias as $item)
                                        <option value="{{ $item?->codigo }}" {{ $item?->codigo == $campana?->categoria ? 'selected' : '' }}>{{ __($item?->nombre) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="campaignDescriptionEdit" class="form-label fw-semibold">{{ __('Descripción (Opcional)') }}</label>
                                <textarea class="form-control" id="campaignDescriptionEdit" name="descripcion" rows="3"
                                    placeholder="{{ __('Describe brevemente el objetivo de esta campaña...') }}"
                                    >{{$campana?->descripcion ?? ''}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card campaign-card">
                    <div class="card-header card-header-custom">
                        <h5 class="text-white"><i class="bi bi-clock-fill fs-2 text-white"></i>
                            {{ __('Configuración de Envío') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="estado" id="sendNowEdit"
                                        value="1">
                                    <label class="form-check-label fw-semibold" for="sendNowEdit">
                                        <i class="las la-paper-plane me-2 fs-3 text-success"></i>
                                        {{ __('Enviar Ahora') }}
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="estado" id="sendScheduledEdit"
                                        value="2" checked>
                                    <label class="form-check-label fw-semibold" for="sendScheduledEdit">
                                        <i class="bi bi-calendar-event me-2 text-warning"></i>
                                        {{ __('Programar Envío') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6" id="scheduledFieldsEdit">
                                <label for="scheduleDateEdit" class="form-label fw-semibold">{{ __('Fecha') }}</label>
                                <input type="date" placeholder="{{ __('DD/MM/AAA') }}" value="{{date("Y-m-d", strtotime($campana->fecha_envio))}}" name="fecha" class="form-control"
                                    id="scheduleDateEdit">
                            </div>
                            <div class="col-md-6" id="scheduledTimeFieldEdit">
                                <label for="scheduleTimeEdit" class="form-label fw-semibold">{{ __('Hora') }}</label>
                                <input type="time" placeholder="00:00" value="{{date("H:i:s", strtotime($campana->fecha_envio))}}" name="hora" class="form-control"
                                    id="scheduleTimeEdit">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Contenido -->
            <div class="step-content" id="stepEdit-2">
                <div class="card campaign-card">
                    <div class="card-header card-header-custom">
                        <h5 class="text-white">
                            <i class="bi bi-chat-text-fill fs-2 text-white"></i>
                            {{ __('Contenido del Mensaje') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="selectPlantillaEditar" class="form-label required fw-semibold">{{ __('Texto del Mensaje') }}</label>
                                <select name="id_plantilla" id="selectPlantillaEditar" class="form-control"
                                    data-control="select2" data-placeholder="{{ __('Plantilla') }}" required
                                    data-dropdown-parent="body" data-allow-clear="true">
                                    <option></option>
                                    @foreach ($plantillas as $plantilla)
                                        <option value="{{ $plantilla['id'] }}" {{ $campana?->id_plantilla == $plantilla['id'] ? 'selected' : '' }}>
                                            {{ $plantilla['name'] . ' - ' . $plantilla['BODY']['text'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="row mb-3 d-none" id="divInputFileEditar">
                                    <div class="col-lg-8 col-md-8">
                                        <input type="file" class="form-control" id="inputFileEditar" accept="image/png"
                                            name="archivo">
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="checkUsarRecursoEditar" />
                                            <label class="form-check-label" for="checkUsarRecursoEditar">
                                                {{ __('Usar recurso de META') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="separator separator-dashed separator-content border-primary my-15 d-none seccionEncabezadoEditar">
                                <span class="h4 text-primary">{{ __('Variable dinámicas del encabezado') }}</span>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="row mb-3 d-none seccionEncabezadoEditar">
                                    <input type="text" name="header_text"
                                        placeholder="{{ __('Ingrese valor de variable del encabezado') }}" class="form-control">
                                </div>
                            </div>
                            <div class="separator separator-dashed separator-content border-primary my-15 d-none"
                                id="divTituloVariableEditar">
                                <span class="h4 text-primary">{{ __('Variables dinámicas del contenido') }}</span>
                            </div>
                            <div class="row mb-7" id="divVariablesEditar">
                            </div>
                            <div class="row mb-7" id="divUrlEditar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Destinatarios -->
            <div class="step-content" id="stepEdit-3">
                <div class="card campaign-card">
                    <div class="card-header card-header-custom">
                        <h5 class="text-white">
                            <i class="bi bi-people-fill fs-2 text-white"></i>
                            {{ __('Selección de Destinatarios') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <select name="etiquetas" id="selectEtiquetaEditar" class="form-control"
                                    data-control="select2" data-placeholder="{{ __('Etiquetas') }}" data-allow-clear="true"
                                    required data-dropdown-parent="body">
                                    <option></option>
                                    @foreach ($etiquetas as $etiqueta)
                                        <option value="{{ $etiqueta->id }}" {{ $campana?->cod_etiqueta == $etiqueta->id ? 'selected' : '' }}>{{ $etiqueta->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="contacts-table">
                            <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto tablasScroll" data-kt-scroll="true"
                                data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                                data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header"
                                data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body"
                                data-kt-scroll-offset="5px" style="max-height: 410px;">
                                <div class="table-responsive m-3">
                                    <table class="table table-hover" id="tablaContactosEditar">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center all">
                                                    <div class="form-check text-center">
                                                        <input class="form-check-input checkSeleccionarTodos"
                                                            type="checkbox" value="" id="seleccionarTodosEditar"
                                                            checked/>
                                                    </div>
                                                </th>
                                                <th width="10%" class="text-center all">{{ __('Contacto') }}</th>
                                                <th width="10%" class="text-center all">{{ __('Telefono') }}</th>
                                                <th width="10%" class="text-center all">{{ __('Etiqueta') }}</th>
                                                <th width="10%" class="text-center all">{{ __('Última Interacción') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Confirmar -->
            <div class="step-content" id="stepEdit-4">
                <div class="card campaign-card">
                    <div class="card-header card-header-custom">
                        <h5 class="text-white">
                            <i class="bi bi-check-circle-fill fs-2 text-white"></i>
                            {{ __('Resumen de la Campaña') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-muted mb-3">{{ __('INFORMACIÓN GENERAL') }}</h6>
                                <div class="mb-2"><strong>{{ __('Nombre:') }}</strong> <span id="summaryNameEdit">-</span></div>
                                <div class="mb-2"><strong>{{ __('Categoría:') }}</strong> <span id="summaryCategoryEdit">-</span>
                                </div>
                                <div class="mb-2"><strong>{{ __('Tipo de Envío:') }}</strong> <span
                                        id="summarySendTypeEdit">-</span></div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-muted mb-3">{{ __('DESTINATARIOS') }}</h6>
                                <div class="mb-2"><strong>{{ __('Total Seleccionados:') }}</strong> <span
                                        id="summaryContactsEdit">0</span></div>
                                <div class="mb-2"><strong>{{ __('Costo Estimado:') }}</strong> <span
                                        class="text-success fw-bold">$0.00</span></div>
                            </div>
                            <div class="col-12">
                                <h6 class="fw-bold text-muted mb-3">{{ __('CONTENIDO') }}</h6>
                                <div class="bg-light p-3 rounded" id="summaryMessageEdit">
                                    <em class="text-muted">{{ __('El mensaje aparecerá aquí...') }}</em>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>{{ __('Importante:') }}</strong> {{ __('Una vez lanzada la campaña, no podrás modificar el contenido ni detener el envío.') }}
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <button type="button"
                    class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary"
                    id="prevBtnEdit" style="display: none;">
                    <i class="bi bi-arrow-left me-2"></i>{{ __('Anterior') }}
                </button>
                <div class="ms-auto">
                    <button type="button" class="btn btn-primary-custom text-white" id="nextBtnEdit">
                        {{ __('Siguiente') }} <i class="bi bi-arrow-right ms-2 text-white"></i>
                    </button>
                    <button type="submit" class="btn btn-secondary-custom" id="launchBtnEdit" style="display: none;">
                        <i class="bi bi-rocket-takeoff-fill me-2"></i>{{ __('Actualizar') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="col-lg-4">
            <div class="phone-preview">
                <h6 class="fw-bold mb-3 text-center">
                    <i class="bi bi-phone-fill fs-2 me-2" style="color: var(--primary-color);"></i>
                    {{ __('Vista Previa') }}
                </h6>
                <div class="phone-mockup">
                    <div class="phone-screen">
                        <div class="phone-header">
                            <div class="contact-avatar">{{ __('JD') }}</div>
                            <div class="contact-info">
                                <h6 class="text-white">{{ __('Juan Pérez') }}</h6>
                                <small>{{ __('en línea') }}</small>
                            </div>
                        </div>
                        <div class="chat-area conversation conversation-container">
                            <div class="message-area" style="background: transparent;">
                                <div class="template-message" id="templatePreviewEdit">
                                    <!-- Template content will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        {{ __('Vista previa aproximada del mensaje') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
