@php
    $config = $config ?? null;
@endphp
<input type="hidden" name="id" value="{{$config->id}}">
<div id="errores">
    @component('sistema/div-errores')
    @endcomponent
</div>
<div class="row mb-7">
    <div class="col-lg-6 col-md-6">
        <label class="required">Version</label>
        <input type="text" name="version" class="form-control" placeholder="Ingrese la version" required value="{{$config?->version}}">
    </div>
    <div class="col-lg-6 col-md-6">
        <label class="required">Waba Id</label>
        <input type="text" name="waba_id" class="form-control" placeholder="Ingrese el Waba Id" required value="{{$config?->waba_id}}">
    </div>
</div>
<div class="row mb-7">
    <div class="col-lg-6 col-md-6">
        <label class="required">App Id</label>
        <input type="text" name="app_id" class="form-control" placeholder="Ingrese el App Id" required value="{{$config?->app_id}}">
    </div>
    <div class="col-lg-6 col-md-6">
        <label class="required">Phone Number Id</label>
        <input type="text" name="phone_number_id" class="form-control" placeholder="Ingrese el Phone Number Id" required value="{{$config?->phone_number_id}}">
    </div>
</div>
<div class="row mb-7">
    <div class="col-lg-12 col-md-12">
        <label class="required">Token META</label>
        <input type="text" name="token" class="form-control" placeholder="Ingrese el token de META" required value="{{$config?->token}}">
    </div>
</div>
<div class="row mb-7">
    <div class="col-lg-6 col-md-6">
        <label class="required">Identificador de verificación</label>
        <input type="text" name="token_1" class="form-control" placeholder="Ingrese el Identificador de verificación" required value="{{$config?->token_1}}">
    </div>
    <div class="col-lg-6 col-md-6">
        <label class="required">Numero</label>
        <input type="text" name="numero" class="form-control" placeholder="Ingrese el Numero" required value="{{$config?->numero}}">
    </div>
</div>