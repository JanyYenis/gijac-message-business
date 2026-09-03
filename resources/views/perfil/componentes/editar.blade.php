<div class="card-body">

    <div id="errores">
        @component('sistema/div-errores')
        @endcomponent
    </div>

    <form id="formEditarUsuario">
        <input type="hidden" name="id" value="{{ $usuario->id }}">
        <input type="hidden" name="uuid" value="{{ $usuario?->uuid }}">
        <div class="row g-4">
            <!-- Nombre -->
            <div class="col-md-6">
                <label for="nombre" class="form-label fs-4">
                    <i class="bi bi-person text-dark fs-4 me-1"></i>{{ __('Nombre') }}</label>
                <input type="text" class="form-control" value="{{ $usuario?->nombre ?? '' }}" id="nombre"
                    placeholder="{{ __('Ingresa tu nombre') }}" required name="nombre">
            </div>

            <!-- Apellido -->
            <div class="col-md-6">
                <label for="apellido" class="form-label fs-4">
                    <i class="bi bi-person text-dark fs-4 me-1"></i>{{ __('Apellido') }}</label>
                <input type="text" class="form-control" value="{{ $usuario?->apellido ?? '' }}" id="apellido"
                    placeholder="{{ __('Ingresa tu apellido') }}" required name="apellido">
            </div>

            <!-- Genero -->
            <div class="col-md-6">
                <label for="genero" class="form-label fs-4">
                    <i class="bi bi-gender-ambiguous text-dark fs-4 me-1"></i>{{ __('Genero') }}</label>
                <select class="form-select" id="genero" data-control="select2"
                    data-placeholder="{{ __('Seleccione el genero') }}" data-allow-clear="true" data-hide-search="true"
                    name="genero" required>
                    <option value=""></option>
                    @foreach ($generos as $item)
                        <option value="{{ $item?->codigo }}"
                            {{ $item?->codigo == $usuario?->genero ? 'selected' : '' }}>{{ $item?->nombre ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tipo de Identificacion -->
            <div class="col-md-6">
                <label for="tipoId" class="form-label fs-4">
                    <i class="bi bi-card-text text-dark fs-4 me-1"></i>{{ __('Tipo de Identificacion') }}</label>
                <select class="form-select" id="tipoId" data-control="select2"
                    data-placeholder="{{ __('Seleccione el tipo de identificación') }}" data-allow-clear="true"
                    data-hide-search="true" name="tipo_identificacion" required>
                    <option value=""></option>
                    @foreach ($tiposDocumentos as $item)
                        <option value="{{ $item?->codigo }}"
                            {{ $item?->codigo == $usuario?->tipo_identificacion ? 'selected' : '' }}>
                            {{ $item?->nombre ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Numero de Identificacion -->
            <div class="col-md-6">
                <label for="identificacion" class="form-label fs-4">
                    <i class="bi bi-hash text-dark fs-4 me-1"></i>{{ __('Numero de Identificacion') }}</label>
                <input type="text" class="form-control" id="identificacion" name="identificacion" required
                    placeholder="{{ __('Ingresa tu identificacion') }}" value="{{ $usuario?->identificacion ?? '' }}">
            </div>

            <!-- Telefono -->
            <div class="col-md-6">
                <label for="tel" class="form-label fs-4">{{ __('Telefono') }}</label>
                <input type="tel" class="form-control" required name="telefono" id="tel"
                    value="{{ __('+{{ $usuario?->numero_completo }}') }}" placeholder="{{ __('300 123 4567') }}">
            </div>

            <!-- Pais -->
            <div class="col-md-6">
                <label for="pais" class="form-label fs-4">{{ __('Pais') }}</label>
                <select class="form-select" id="selectPaisEdit" name="pais_id" placeholder="{{ __('...') }}" required>
                    <option value="">{{ __('Seleccione un país') }}</option>
                    @foreach ($paises as $pais)
                        <option value="{{ $pais->id }}"
                            {{ $pais?->id == $usuario?->ciudad?->id_pais ? 'selected' : '' }}
                            data-kt-select2-country="{{ $pais->bandera }}">{{ $pais->nombre }} -
                            {{ $pais->nombre_corto }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Ciudad -->
            <div class="col-md-6">
                <label for="ciudad" class="form-label fs-4">{{ __('Ciudad') }}</label>
                <select class="form-select" id="selectCiudadEdit" name="cod_ciudad"
                    data-ciudad={{ $usuario->cod_ciudad }} disabled data-control="select2"
                    data-placeholder="{{ __('Seleccione una ciudad') }}" data-allow-clear="true" required>
                </select>
            </div>
        </div>

        <!-- Save Button -->
        <div class="form-actions mt-4">
            <button type="submit" class="btn btn-primary" id="savePersonalBtn">
                <span class="btn-text">
                    <i class="bi bi-check-lg me-2"></i>{{ __('Guardar Cambios') }}</span>
                <span class="btn-loader d-none">
                    <span class="spinner-border spinner-border-sm me-2"></span>{{ __('Guardando...') }}</span>
            </button>
            <button type="button" class="btn btn-outline-secondary" id="cancelPersonalBtn">{{ __('Cancelar') }}</button>
        </div>
    </form>
</div>
