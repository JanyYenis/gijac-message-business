<div class="row">
    <div class="col-lg-6 col-md-6">
        <label for="" class="form-label">Wamid</label>
        <input type="text" readonly class="form-control" value="{{ $error?->wamid ?? '' }}">
    </div>
    <div class="col-lg-6 col-md-6">
        <label for="" class="form-label">Codigo</label>
        <input type="text" readonly class="form-control" value="{{ $error?->code ?? '' }}">
    </div>
</div>
<div class="row mt-3">
    <div class="col-lg-6 col-md-6">
        <label for="" class="form-label">Titulo</label>
        <input type="text" readonly class="form-control" value="{{ $error?->title ?? '' }}">
    </div>
    <div class="col-lg-6 col-md-6">
        <label for="" class="form-label">Mensaje</label>
        <input type="text" readonly class="form-control" value="{{ $error?->message ?? '' }}">
    </div>
</div>
<div class="row mt-3">
    <div class="col-lg-12 col-md-12">
        <label for="" class="form-label">Detalle</label>
        <textarea class="form-control" readonly cols="30" rows="3">{{$error?->details}}</textarea>
    </div>
</div>
