<div class="modal fade" tabindex="-1" id="modalCrearAPIKey">
    <form id="formCrearAPIKey">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content ">
                <div class="modal-header">
                    <h1 class="modal-title">{{ __('Crear API Key') }}</h1>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x">
                        <i class="las la-times fs-1 text-white"></i>
                    </span>
                    </div>
                </div>

                <div class="modal-body scroll px-5">
                    <div id="errores">
                        @component('sistema/div-errores')
                        @endcomponent
                    </div>
                    <div class="row mb-7">
                        <div class="col-lg-12 col-md-12">
                            <label class="required fs-5">{{ __('Etiqueta') }}</label>
                            <input type="text" name="etiqueta" class="form-control" placeholder="{{ __('Ingrese una etiqueta') }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="fs-5 mb-1">{{ __('Permisos de la llave') }}</label>
                            <div class="text-muted fs-7 mb-3">{{ __('Da solo los permisos que necesita tu integración.') }}</div>
                            @foreach (\App\Models\ApiKey::SCOPES as $scope => $texto)
                                <div class="form-check form-check-custom form-check-solid mb-3">
                                    <input class="form-check-input" type="checkbox" name="scopes[]" value="{{ $scope }}" id="scope_{{ $loop->index }}" checked>
                                    <label class="form-check-label" for="scope_{{ $loop->index }}">{{ __($texto) }} <code class="ms-2">{{ $scope }}</code></label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('Crear') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
