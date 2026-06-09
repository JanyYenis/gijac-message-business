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
            Nombre de la Etiqueta
        </label>
        <input type="text" class="form-control" id="tagNameEdit" name="nombre" value="{{ $etiqueta?->nombre }}"
            placeholder="Ingrese el nombre" required maxlength="50">
        <div class="invalid-feedback"></div>
    </div>

    <div class="col-md-4">
        <label for="tagColorEdit" class="form-label required">
            <i class="fas fa-palette me-1"></i>
            Color
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
            Descripción
        </label>
        <textarea class="form-control" id="tagDescriptionEdit" name="descripcion" rows="3" maxlength="255"
            placeholder="Descripción opcional de la etiqueta...">{{$etiqueta?->descripcion ?? ''}}</textarea>
    </div>
</div>
