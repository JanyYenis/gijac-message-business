<div>
    <div class="fw-semibold">{{ $model?->name }}</div>
    <small class="text-muted">{{ $model?->body?->text ?? __('N/A') }}</small>
</div>
