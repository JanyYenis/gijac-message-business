<input type="hidden" name="id" value="{{$plan->id}}">
<div class="row">
    <!-- Form Fields -->
    <div class="col-lg-8">
        <div class="row g-3">
            <div class="col-md-5">
                <label for="nombreEdit" class="form-label">
                    <i class="fas fa-tag me-1"></i>
                    Nombre del Plan
                </label>
                <input type="text" class="form-control" placeholder="Nombre" id="nombreEdit"
                    name="nombre" required value="{{ $plan?->nombre }}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-2">
                <label for="categoriaEdit" class="form-label">
                    Categoria
                </label>
                <select class="form-select" id="categoriaEdit" name="categoria" data-control="select2"
                    required data-placeholder="Categoria" data-allow-clear="true"
                    data-hide-search="true" data-dropdown-parent="body">
                    <option value=""></option>
                    @foreach ($categorias as $item)
                        <option value="{{ $item?->codigo }}" {{ $plan?->categoria == $item?->codigo ? 'selected' : '' }}>{{ $item?->nombre }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-2">
                <label for="tipoEdit" class="form-label">
                    <i class="fas fa-calendar me-1"></i>
                    Tipo
                </label>
                <select class="form-select" id="tipoEdit" name="tipo" data-control="select2"
                    required data-placeholder="Tipo" data-allow-clear="true" data-hide-search="true"
                    data-dropdown-parent="body">
                    <option value=""></option>
                    @foreach ($tipos as $item)
                        <option value="{{ $item?->codigo }}" {{ $plan?->tipo == $item?->codigo ? 'selected' : '' }}>{{ $item?->nombre }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-3">
                <label for="valorEdit" class="form-label">
                    <i class="fas fa-dollar-sign me-1"></i>
                    Valor ($)
                </label>
                <input type="number" class="form-control" placeholder="$0" id="valorEdit"
                    name="valor" step="0.01" min="0" required value="{{ $plan?->valor ?? 0 }}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" {{ $plan?->max_contactos ? 'checked' : '' }} id="limitarContactosEdit">
                    <label class="form-check-label fw-bold" for="limitarContactosEdit">
                        <i class="fas fa-users me-1"></i>
                        Limitar número de contactos activos
                    </label>
                </div>
            </div>
            <div class="col-md-6" id="maxContactosContainerEdit" style="display: {{ $plan?->max_contactos ? 'block' : 'none' }};">
                <label for="max_contactosEdit" class="form-label">
                    <i class="fas fa-hashtag me-1"></i>
                    Máximo de contactos activos
                </label>
                <input type="number" class="form-control" placeholder="0" id="max_contactosEdit"
                    name="max_contactos" min="1" value="{{ $plan?->max_contactos }}">
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <!-- Services Section -->
        <div class="mt-4">
            <h6 class="fw-bold mb-3">
                <i class="fas fa-cogs me-2"></i>
                Servicios Incluidos
            </h6>
            <div id="serviciosListEdit">
                @foreach ($servicios as $servicio)
                    <div class="service-item {{ in_array($servicio->id, $servicios_seleccionados) ? 'active' : '' }}" data-service-id="{{ $servicio->id }}">
                        <div class="d-flex align-items-center flex-grow-1">
                            <i class="fas fa-puzzle-piece me-3 text-muted"></i>
                            <div>
                                <div class="fw-semibold">{{ $servicio->nombre }}</div>
                                <small class="text-muted">{{ $servicio->slug }}</small>
                            </div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input service-toggle" {{ in_array($servicio->id, $servicios_seleccionados) ? 'checked' : '' }} type="checkbox"
                                id="servicio_edit_{{ $servicio->id }}"
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
            <div class="price-amount" id="previewPriceEdit">$0.00</div>
            <div class="price-period" id="previewPeriodEdit">por mes</div>
            <div class="price-contacts" id="previewContactsEdit">
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
