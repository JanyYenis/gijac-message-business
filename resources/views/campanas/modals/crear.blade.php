<div class="modal fade" tabindex="-1" id="modalCrearCampana">
    <form id="formCrearCampana" enctype="multipart/form-data">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="">
                        <h1 class="modal-title text-white mulish">
                            <i class="bi bi-megaphone-fill fs-2 me-2 text-white"></i>
                            Crear Nueva Campaña
                        </h1>
                        <p class="text-white mb-0">Configura y lanza tu campaña de WhatsApp Business</p>
                    </div>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2x">
                            <i class="las la-times fs-1 text-white"></i>
                        </span>
                    </div>
                </div>

                <div class="modal-body">
                    <div id="errores">
                        @component('sistema/div-errores')
                        @endcomponent
                    </div>

                    <div class="container-fluid main-container">
                        <!-- Stepper -->
                        <div class="stepper">
                            <div class="stepper-item active" data-step="1">
                                <div class="stepper-number">1</div>
                                <div class="stepper-text">Configuración</div>
                            </div>
                            <div class="stepper-item" data-step="2">
                                <div class="stepper-number">2</div>
                                <div class="stepper-text">Contenido</div>
                            </div>
                            <div class="stepper-item" data-step="3">
                                <div class="stepper-number">3</div>
                                <div class="stepper-text">Destinatarios</div>
                            </div>
                            <div class="stepper-item" data-step="4">
                                <div class="stepper-number">4</div>
                                <div class="stepper-text">Confirmar</div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Form Section -->
                            <div class="col-lg-8">
                                <!-- Step 1: Configuración -->
                                <div class="step-content active" id="step-1">
                                    <div class="card campaign-card">
                                        <div class="card-header card-header-custom">
                                            <h5 class="text-white">
                                                <i class="bi bi-gear-fill text-white fs-2"></i> Datos Generales
                                            </h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="row g-3">
                                                <div class="col-md-8">
                                                    <label for="campaignName" class="form-label required fw-semibold">
                                                        Nombre de la Campaña
                                                    </label>
                                                    <input type="text" required name="nombre" class="form-control" id="campaignName"
                                                        placeholder="Ej: Promoción Black Friday {{ date('Y') }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="campaignCategory"
                                                        class="form-label fw-semibold required">Categoría</label>
                                                    <select class="form-select" name="categoria" data-control="select2" data-dropdown-parent="body"
                                                        data-placeholder="Categoría" data-allow-clear="true" required
                                                        id="campaignCategory" data-hide-search="true">
                                                        <option value=""></option>
                                                        @foreach ($categorias as $item)
                                                            <option value="{{ $item?->codigo }}">{{ $item?->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label for="campaignDescription"
                                                        class="form-label fw-semibold">Descripción (Opcional)</label>
                                                    <textarea class="form-control" id="campaignDescription" name="descripcion" rows="3"
                                                        placeholder="Describe brevemente el objetivo de esta campaña..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card campaign-card">
                                        <div class="card-header card-header-custom">
                                            <h5 class="text-white"><i class="bi bi-clock-fill fs-2 text-white"></i> Configuración de Envío</h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="estado"
                                                            id="sendNow" value="1" checked>
                                                        <label class="form-check-label fw-semibold" for="sendNow">
                                                            <i class="las la-paper-plane me-2 fs-3 text-success"></i>Enviar
                                                            Ahora
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="estado"
                                                            id="sendScheduled" value="2">
                                                        <label class="form-check-label fw-semibold"
                                                            for="sendScheduled">
                                                            <i class="bi bi-calendar-event me-2 text-warning"></i>Programar
                                                            Envío
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 d-none" id="scheduledFields">
                                                    <label for="scheduleDate"
                                                        class="form-label fw-semibold">Fecha</label>
                                                    <input type="date" placeholder="DD/MM/AAA" name="fecha" class="form-control" id="scheduleDate">
                                                </div>
                                                <div class="col-md-6 d-none" id="scheduledTimeField">
                                                    <label for="scheduleTime"
                                                        class="form-label fw-semibold">Hora</label>
                                                    <input type="time" placeholder="00:00" name="hora" class="form-control" id="scheduleTime">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Contenido -->
                                <div class="step-content" id="step-2">
                                    <div class="card campaign-card">
                                        <div class="card-header card-header-custom">
                                            <h5 class="text-white">
                                                <i class="bi bi-chat-text-fill fs-2 text-white"></i>
                                                Contenido del Mensaje
                                            </h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label for="messageContent" class="form-label required fw-semibold">Texto
                                                        del Mensaje</label>
                                                    <select name="id_plantilla" id="selectPlantilla" class="form-control" data-control="select2"
                                                        data-placeholder="Plantilla" required data-dropdown-parent="body" data-allow-clear="true">
                                                        <option></option>
                                                        @foreach ($plantillas as $plantilla)
                                                            <option value="{{$plantilla->id}}">{{($plantilla?->name ?? 'N/A')." - ".($plantilla?->body?->text ?? 'N/A')}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="row mb-3 d-none" id="divInputFile">
                                                        <div class="col-lg-8 col-md-8">
                                                            <input type="file" class="form-control" id="inputFile" accept="image/png" name="archivo">
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-check form-switch form-check-custom form-check-solid">
                                                                <input class="form-check-input" type="checkbox" value="" id="checkUsarRecurso"/>
                                                                <label class="form-check-label" for="checkUsarRecurso">
                                                                    Usar recurso de META
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="separator separator-dashed separator-content border-primary my-15 d-none seccionEncabezado">
                                                    <span class="h4 text-primary">Variable dinámicas del encabezado</span>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="row mb-3 d-none seccionEncabezado">
                                                        <input type="text" name="header_text" placeholder="Ingrese valor de variable del encabezado" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="separator separator-dashed separator-content border-primary my-15 d-none" id="divTituloVariable">
                                                    <span class="h4 text-primary">Variables dinámicas del contenido</span>
                                                </div>
                                                <div class="row mb-7" id="divVariables">
                                                </div>
                                                <div class="row mb-7" id="divUrl">
                                                </div>
                                                {{-- <div class="col-12">
                                                    <label class="form-label fw-semibold">Variables Dinámicas</label>
                                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                                        <span class="variable-tag"
                                                            onclick="">{nombre}</span>
                                                        <span class="variable-tag"
                                                            onclick="">{apellido}</span>
                                                        <span class="variable-tag"
                                                            onclick="">{empresa}</span>
                                                        <span class="variable-tag"
                                                            onclick="">{teléfono}</span>
                                                        <span class="variable-tag"
                                                            onclick="">{email}</span>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="attachFile">
                                                        <label class="form-check-label fw-semibold" for="attachFile">
                                                            <i class="bi bi-paperclip me-2"></i>Adjuntar Archivo
                                                        </label>
                                                    </div>
                                                    <div id="fileUpload" style="display: none;" class="mt-3">
                                                        <input type="file" class="form-control"
                                                            accept="image/*,.pdf,.doc,.docx">
                                                        <div class="form-text">Formatos permitidos: JPG, PNG, PDF, DOC,
                                                            DOCX (máx. 16MB)</div>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Destinatarios -->
                                <div class="step-content" id="step-3">
                                    <div class="card campaign-card">
                                        <div class="card-header card-header-custom">
                                            <h5 class="text-white">
                                                <i class="bi bi-people-fill fs-2 text-white"></i>
                                                Selección de Destinatarios
                                            </h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="row g-3 mb-4">
                                                <div class="col-md-12">
                                                    <select name="etiquetas" id="selectEtiqueta" class="form-control" data-control="select2"
                                                        data-placeholder="Etiquetas" data-allow-clear="true" required
                                                        data-dropdown-parent="body">
                                                        <option></option>
                                                        @foreach ($etiquetas as $etiqueta)
                                                            <option value="{{$etiqueta->id}}">{{$etiqueta->nombre}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                {{-- <div class="col-md-3">
                                                    <button class="btn btn-secondary-custom w-100">
                                                        <i class="bi bi-funnel-fill me-2"></i>Filtros Avanzados
                                                    </button>
                                                </div> --}}
                                            </div>

                                            <div class="contacts-table">
                                                <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto tablasScroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header" data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body" data-kt-scroll-offset="5px" style="max-height: 410px;">
                                                    <div class="table-responsive m-3">
                                                        <table class="table table-hover" id="tablaContactos">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5%" class="text-center all">
                                                                        <div class="form-check text-center">
                                                                            <input class="form-check-input checkSeleccionarTodos" type="checkbox" value="" id="seleccionarTodos" checked/>
                                                                        </div>
                                                                    </th>
                                                                    <th width="10%" class="text-center all">Contacto</th>
                                                                    <th width="10%" class="text-center all">Telefono</th>
                                                                    <th width="10%" class="text-center all">Etiqueta</th>
                                                                    <th width="10%" class="text-center all">Última Interacción</th>
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
                                <div class="step-content" id="step-4">
                                    <div class="card campaign-card">
                                        <div class="card-header card-header-custom">
                                            <h5 class="text-white">
                                                <i class="bi bi-check-circle-fill fs-2 text-white"></i>
                                                Resumen de la Campaña
                                            </h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold text-muted mb-3">INFORMACIÓN GENERAL</h6>
                                                    <div class="mb-2"><strong>Nombre:</strong> <span
                                                            id="summaryName">-</span></div>
                                                    <div class="mb-2"><strong>Categoría:</strong> <span
                                                            id="summaryCategory">-</span></div>
                                                    <div class="mb-2"><strong>Tipo de Envío:</strong> <span
                                                            id="summarySendType">-</span></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold text-muted mb-3">DESTINATARIOS</h6>
                                                    <div class="mb-2"><strong>Total Seleccionados:</strong> <span
                                                            id="summaryContacts">0</span></div>
                                                    <div class="mb-2"><strong>Costo Estimado:</strong> <span
                                                            class="text-success fw-bold">$0.00</span></div>
                                                </div>
                                                <div class="col-12">
                                                    <h6 class="fw-bold text-muted mb-3">CONTENIDO</h6>
                                                    <div class="bg-light p-3 rounded" id="summaryMessage">
                                                        <em class="text-muted">El mensaje aparecerá aquí...</em>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info" role="alert">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <strong>Importante:</strong> Una vez lanzada la campaña, no podrás modificar el
                                        contenido ni detener el envío.
                                    </div>
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary" id="prevBtn" style="display: none;">
                                        <i class="bi bi-arrow-left me-2"></i>Anterior
                                    </button>
                                    <div class="ms-auto">
                                        <button type="button" class="btn btn-primary-custom text-white" id="nextBtn">
                                            Siguiente <i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                        <button type="submit" class="btn btn-secondary-custom" id="launchBtn"
                                             style="display: none;">
                                            <i class="bi bi-rocket-takeoff-fill me-2"></i>Lanzar Campaña
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Section -->
                            <div class="col-lg-4">
                                <div class="phone-preview">
                                    <h6 class="fw-bold mb-3 text-center">
                                        <i class="bi bi-phone-fill fs-2 me-2" style="color: var(--primary-color);"></i>
                                        Vista Previa
                                    </h6>
                                    <div class="phone-mockup">
                                        <div class="phone-screen">
                                            <div class="phone-header">
                                                <div class="contact-avatar">JD</div>
                                                <div class="contact-info">
                                                    <h6 class="text-white">Juan Pérez</h6>
                                                    <small>en línea</small>
                                                </div>
                                            </div>
                                            <div class="chat-area conversation conversation-container">
                                                <div class="message-area" style="background: transparent;">
                                                    <div class="template-message" id="templatePreview">
                                                        <!-- Template content will be inserted here -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Vista previa aproximada del mensaje
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
