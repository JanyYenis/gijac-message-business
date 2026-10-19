<div class="state-box">
    <div class="ic ic-teal">
        <i class="bi bi-shop"></i>
    </div>
    <h5 class="fw-bold">{{ __('Aún no tienes un catálogo') }}</h5>
    <p class="text-muted">{{ __('Crea tu catálogo para publicar productos y venderlos por WhatsApp. Lo creamos en Meta y lo conectamos a tu número automáticamente.') }}</p>
    <div class="d-flex justify-content-center gap-2 flex-wrap" style="max-width: 460px; margin: 0 auto;">
        <input type="text" class="form-control" id="nuevoCatalogoNombre" maxlength="100"
            placeholder="{{ __('Nombre del catálogo (opcional)') }}">
        <button type="button" class="btn-gj js-crear-catalogo">
            <i class="bi bi-plus-lg me-1"></i>{{ __('Crear catálogo') }}
        </button>
    </div>
</div>
