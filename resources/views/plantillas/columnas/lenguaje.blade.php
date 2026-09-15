<div class="d-flex align-items-center">
    <img src="{{ $model?->idioma?->pais?->bandera ?? 'https://flagcdn.com/w20/es.png' }}" alt="ES" class="me-2" width="20">
    <span>{{ $model?->language ?? __('Español') }}</span>
</div>
