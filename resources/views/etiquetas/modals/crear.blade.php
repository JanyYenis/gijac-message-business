<div class="modal fade" tabindex="-1" id="modalCrearEtiqueta">
    <form id="formCrearEtiqueta" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-white mulish">{{ __('Crear Etiqueta') }}</h1>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal" data-bs-dismiss="modal" aria-label="Close">
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
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="tagName" class="form-label required">
                                <i class="fas fa-tag me-1"></i>
                                {{ __('Nombre de la Etiqueta') }}
                            </label>
                            <input type="text" class="form-control" id="tagName" name="nombre" placeholder="{{ __('Ingrese el nombre') }}" required maxlength="50">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-4">
                            <label for="tagColor" class="form-label required">
                                <i class="fas fa-palette me-1"></i>
                                {{ __('Color') }}
                            </label>
                            <div class="color-picker-container">
                                <input type="color" class="form-control color-picker" id="tagColor" name="color" value="#28a745" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="tagDescription" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                {{ __('Descripción') }}
                            </label>
                            <textarea class="form-control" id="tagDescription" name="descripcion" rows="3" maxlength="255" placeholder="{{ __('Descripción opcional de la etiqueta...') }}"></textarea>
                        </div>

                        @php($agentes = \App\Models\Etiqueta::agentesDisponibles())
                        @if ($agentes->isNotEmpty())
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="fas fa-users me-1"></i>
                                    {{ __('Agentes que atienden esta etiqueta') }}
                                </label>
                                <p class="text-muted fs-7 mb-2">{{ __('La IA clasifica los chats nuevos con las etiquetas que tengan agentes y los asigna por turnos.') }}</p>
                                <div class="row g-2">
                                    @foreach ($agentes as $u)
                                        <div class="col-md-6">
                                            <label class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" class="form-check-input mt-0" name="agentes[]" value="{{ $u->uuid }}">
                                                <span>{{ trim($u->nombre . ' ' . $u->apellido) }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Crear') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
