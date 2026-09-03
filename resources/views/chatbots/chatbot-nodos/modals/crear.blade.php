<!-- ====== MODAL NUEVO FLUJO ====== -->
<div class="modal fade" id="modalNuevoFlujo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i>{{ __('Nuevo Flujo') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body prop-form">
                <div class="prop-group">
                    <label>{{ __('Nombre del flujo') }}</label>
                    <input type="text" class="form-control" placeholder="{{ __('Ej. Atención al cliente') }}" />
                </div>
                <div class="prop-group">
                    <label>{{ __('Descripción') }}</label>
                    <textarea class="form-control" rows="2" placeholder="{{ __('Describe el propósito del flujo...') }}"></textarea>
                </div>
                <div class="prop-group">
                    <label>{{ __('Canal') }}</label>
                    <select class="form-select">
                        <option>{{ __('WhatsApp Business') }}</option>
                    </select>
                </div>
                <div class="prop-group">
                    <label>{{ __('Estado') }}</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="swEstado" checked />
                        <label class="form-check-label" for="swEstado">{{ __('Activo') }}</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-toolbar" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                <button class="btn btn-white" style="background:var(--wa-green); color:#fff;" data-bs-dismiss="modal"><i
                        class="bi bi-check-lg me-1"></i>{{ __('Crear Flujo') }}</button>
            </div>
        </div>
    </div>
</div>
