<div class="modal fade" id="modalCrearPlanes" tabindex="-1" aria-labelledby="modalCrearPlanesLabel" aria-hidden="true">
    <form id="formCrearPlan">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white mulish" id="modalCrearPlanesLabel">
                        <i class="fas fa-plus-circle"></i>
                        <span id="modalTitle">Crear Nuevo Plan</span>
                    </h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal"
                        data-bs-dismiss="modal" aria-label="Close">
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
                    <div class="row">
                        <!-- Form Fields -->
                        <div class="col-lg-8">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label for="nombre" class="form-label">
                                        <i class="fas fa-tag me-1"></i>
                                        Nombre del Plan
                                    </label>
                                    <input type="text" class="form-control" placeholder="Nombre" id="nombre"
                                        name="nombre" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-2">
                                    <label for="categoria" class="form-label">
                                        Categoria
                                    </label>
                                    <select class="form-select" id="categoria" name="categoria" data-control="select2"
                                        required data-placeholder="Categoria" data-allow-clear="true"
                                        data-hide-search="true" data-dropdown-parent="body">
                                        <option value=""></option>
                                        @foreach ($categorias as $item)
                                            <option value="{{ $item?->codigo }}">{{ $item?->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-2">
                                    <label for="tipo" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>
                                        Tipo
                                    </label>
                                    <select class="form-select" id="tipo" name="tipo" data-control="select2"
                                        required data-placeholder="Tipo" data-allow-clear="true" data-hide-search="true"
                                        data-dropdown-parent="body">
                                        <option value=""></option>
                                        @foreach ($tipos as $item)
                                            <option value="{{ $item?->codigo }}">{{ $item?->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-3">
                                    <label for="valor" class="form-label">
                                        <i class="fas fa-dollar-sign me-1"></i>
                                        Valor ($)
                                    </label>
                                    <input type="number" class="form-control" placeholder="$0" id="valor"
                                        name="valor" step="0.01" min="0" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="limitarContactos">
                                        <label class="form-check-label fw-bold" for="limitarContactos">
                                            <i class="fas fa-users me-1"></i>
                                            Limitar número de contactos activos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6" id="maxContactosContainer" style="display: none;">
                                    <label for="max_contactos" class="form-label">
                                        <i class="fas fa-hashtag me-1"></i>
                                        Máximo de contactos activos
                                    </label>
                                    <input type="number" class="form-control" placeholder="0" id="max_contactos"
                                        name="max_contactos" min="1">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Services Section -->
                            <div class="mt-4">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-cogs me-2"></i>
                                    Servicios Incluidos
                                </h6>
                                <div id="serviciosList">
                                    @foreach ($servicios as $servicio)
                                        <div class="service-item" data-service-id="{{ $servicio->id }}">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <i class="fas fa-puzzle-piece me-3 text-muted"></i>
                                                <div>
                                                    <div class="fw-semibold">{{ $servicio->nombre }}</div>
                                                    <small class="text-muted">{{ $servicio->slug }}</small>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input service-toggle" type="checkbox"
                                                    id="servicio_{{ $servicio->id }}"
                                                    name="servicios[]" value="{{ $servicio->id }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Price Preview -->
                        <div class="col-lg-4">
                            <div class="price-preview">
                                <h6 class="mb-3">
                                    <i class="fas fa-eye me-2"></i>
                                    Vista Previa
                                </h6>
                                <div class="price-amount" id="previewPrice">$0.00</div>
                                <div class="price-period" id="previewPeriod">por mes</div>
                                <div class="price-contacts" id="previewContacts">
                                    <i class="fas fa-users me-2"></i>
                                    Contactos ilimitados
                                </div>
                                <div class="mt-3">
                                    <small class="opacity-75">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Los precios se actualizan en tiempo real
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="savePlanBtn">
                        <i class="fas fa-save me-2"></i>
                        <span id="saveBtnText">Guardar Plan</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
