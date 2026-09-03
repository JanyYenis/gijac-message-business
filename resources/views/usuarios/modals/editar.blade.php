@php
    $usuario = $usuario ?? null;
@endphp
<input type="hidden" name="uuid" value="{{$usuario->uuid}}">
<div id="errores">
    @component('sistema/div-errores')
    @endcomponent
</div>
<div class="row mb-7">
    <div class="col-lg-6 col-md-6">
        <label class="required">{{ __('Nombre') }}</label>
        <input type="text" name="nombre" class="form-control" value="{{$usuario->nombre}}" placeholder="{{ __('Ingrese el nombre') }}" required>
    </div>
    <div class="col-lg-6 col-md-6">
        <label class="required">{{ __('Apellido') }}</label>
        <input type="text" name="apellido" class="form-control" placeholder="{{ __('Ingrese el apellido') }}" value="{{$usuario->apellido}}" required>
    </div>
</div>
<div class="row mb-7">
    <div class="col-lg-6 col-md-6">
        <label class="required">{{ __('Tipo Identificación') }}</label>
        <select name="tipo_identificacion" id="selectTipoIdentificacionEdit" data-control="select2" data-placeholder="{{ __('Seleccione el tipo de identificación') }}" data-allow-clear="true" data-hide-search="true" class="form-control" required data-dropdown-parent="body">
            <option value=""></option>
            @foreach ($tiposDocumentos as $item)
                <option value="{{$item->codigo}}" {{$item?->codigo == $usuario?->tipo_identificacion ? 'selected' : ''}}>{{$item->nombre_corto}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-6 col-md-6">
        <label class="required">{{ __('Identificación') }}</label>
        <input type="text" name="identificacion" class="form-control" placeholder="{{ __('Ingrese la identificación') }}" value="{{$usuario->identificacion}}" required>
    </div>
</div>
<div class="row mb-7">
    <div class="col-lg-6 col-md-6">
        <label class="required">{{ __('Genero') }}</label>
        <select name="genero" id="selectGeneroEdit" data-control="select2" data-placeholder="{{ __('Seleccione el genero') }}" data-allow-clear="true" data-hide-search="true" class="form-control" required data-dropdown-parent="body">
            <option value=""></option>
            @foreach ($generos as $item)
                <option value="{{$item->codigo}}" {{$item?->codigo == $usuario?->genero ? 'selected' : ''}}>{{$item->nombre}}</option>
            @endforeach
        </select>
    </div>
    {{-- <div class="col-lg-6 col-md-6">
        <label class="required">{{ __('Telefono') }}</label>
        <input class="form-control" required placeholder="{{ __('Telefono') }}" name="telefono"/>
    </div> --}}
</div>
<div class="row mb-7">
    <div class="col-lg-6 col-md-6">
        <label class="required">{{ __('País') }}</label>
        <select class="form-control" name="pais_id" placeholder="{{ __('...') }}" id="selectPaisEdit" required data-dropdown-parent="body">
            <option value="">{{ __('Seleccione un país') }}</option>
            @foreach ($paises as $pais)
                <option value="{{$pais->id}}" {{$pais?->id == $usuario?->ciudad?->id_pais ? 'selected' : ''}} data-kt-select2-country="{{$pais->bandera}}">{{$pais->nombre}} - {{$pais->nombre_corto}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-6 col-md-6">
        <label class="required">{{ __('Ciudad') }}</label>
        <select name="cod_ciudad" id="selectCiudadEdit" data-ciudad={{$usuario->cod_ciudad}} disabled data-control="select2" data-placeholder="{{ __('Seleccione una ciudad') }}" data-allow-clear="true" class="form-control" required data-dropdown-parent="body">
        </select>
    </div>
</div>
<div class="row mb-7">
    <div class="col-lg-6 col-md-6">
        <label class="required">{{ __('Email') }}</label>
        <input type="text" class="form-control" name="email" placeholder="Email" value="{{$usuario->email}}" id="inputEmail" required/>
    </div>
    <div class="col-lg-6 col-md-6">
        <label class="required">{{ __('Telefono') }}</label><br>
        <input type="tel" name="telefono" id="tel" class="form-control" maxlength="15" value="{{'+'.$usuario->numero_completo}}" placeholder="{{ __('Ingrese el teléfono') }}" required data-default-country="co">
        {{-- <input type="tel" name="telefono" id="tel" class="form-control" maxlength="15" value="{{$usuario->telefono}}" placeholder="{{ __('Ingrese el teléfono') }}" required data-default-country="co"> --}}
    </div>
</div>
