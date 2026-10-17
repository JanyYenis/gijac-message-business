@php
    $etiqueta = $etiqueta ?? null;
@endphp
<input type="hidden" name="id" value="{{$etiqueta->id}}">
<div id="errores">
    @component('sistema/div-errores')
    @endcomponent
</div>
<div class="row g-3">
    <div class="col-md-8">
        <label for="tagNameEdit" class="form-label required">
            <i class="fas fa-tag me-1"></i>
            {{ __('Nombre de la Etiqueta') }}
        </label>
        <input type="text" class="form-control" id="tagNameEdit" name="nombre" value="{{ $etiqueta?->nombre }}"
            placeholder="{{ __('Ingrese el nombre') }}" required maxlength="50">
        <div class="invalid-feedback"></div>
    </div>

    <div class="col-md-4">
        <label for="tagColorEdit" class="form-label required">
            <i class="fas fa-palette me-1"></i>
            {{ __('Color') }}
        </label>
        <div class="color-picker-container">
            <input type="color" class="form-control color-picker" id="tagColorEdit" name="color"
                value="{{ $etiqueta?->color ?? '#28a745' }}" required>
            <div class="invalid-feedback"></div>
        </div>
    </div>

    <div class="col-12">
        <label for="tagDescriptionEdit" class="form-label">
            <i class="fas fa-align-left me-1"></i>
            {{ __('Descripción') }}
        </label>
        <textarea class="form-control" id="tagDescriptionEdit" name="descripcion" rows="3" maxlength="255"
            placeholder="{{ __('Descripción opcional de la etiqueta...') }}">{{$etiqueta?->descripcion ?? ''}}</textarea>
    </div>

    @if (($usuarios ?? collect())->isNotEmpty())
        <div class="col-12">
            <label class="form-label">
                <i class="fas fa-users me-1"></i>
                {{ __('Agentes que atienden esta etiqueta') }}
            </label>
            <p class="text-muted fs-7 mb-2">{{ __('La IA clasifica los chats nuevos con las etiquetas que tengan agentes y los asigna por turnos.') }}</p>
            <div class="row g-2">
                @foreach ($usuarios as $u)
                    <div class="col-md-6">
                        <label class="form-check d-flex align-items-center gap-2">
                            <input type="checkbox" class="form-check-input mt-0" name="agentes[]" value="{{ $u->uuid }}"
                                @checked(in_array($u->uuid, $asignados ?? []))>
                            <span>{{ trim($u->nombre . ' ' . $u->apellido) }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
