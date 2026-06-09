<!--begin::Step 1-->
<div id="errores3">
    @component('sistema/div-errores')
    @endcomponent
</div>
<input type="hidden" id="idContacto" name="id" value="{{ $contacto->id }}">
<div class="row">
    <div class="col-lg-6">
        <div class="fv-row mb-10">
            <label class="form-label required">Nombre</label>
            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre"
                value="{{ $contacto->nombre }}" required />
        </div>
    </div>
    <div class="col-lg-6">
        <div class="fv-row mb-10">
            <label class="form-label">Apellido</label>
            <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellido"
                value="{{ $contacto->apellido }}" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="fv-row mb-10">
            <label class="form-label">Genero</label>
            <select name="genero" id="selectGeneroEdit" class="form-control" data-control="select2"
                data-placeholder="Genero" data-allow-clear="true" data-hide-search="true" data-dropdown-parent="body">
                <option value=""></option>
                @foreach ($generos as $item)
                    <option value="{{ $item->codigo }}" {{ $item->codigo == $contacto->genero ? 'selected' : '' }}>
                        {{ $item->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="fv-row mb-10">
            <label class="form-label">Etiquetas</label>
            <select name="etiquetas" id="selectEtiquetasEdit" class="form-control selectEtiquetasEdit"
                data-control="select2" multiple data-placeholder="Etiquetas" data-allow-clear="true"
                data-dropdown-parent="body">
                <option></option>
                @foreach ($etiquetas as $etiqueta)
                    <option value="{{ $etiqueta->id }}"
                        {{ in_array($etiqueta->id, $clientesEtiquetas) ? 'selected' : '' }}>{{ $etiqueta->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6">
        <div class="fv-row mb-10">
            <label class="required form-label">Telefono</label>
            <input type="tel" name="telefono" id="telEdit" class="form-control" maxlength="15"
                value="{{ '+' . $contacto->numero_completo }}" placeholder="Ingrese el teléfono" required>
        </div>
    </div>
</div>
