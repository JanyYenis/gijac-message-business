<div class="action-buttons">
    <button type="button" class="btn-action btn-view btnVer" data-plantilla="{{ $model?->id }}"
        title="{{ __('Ver previsualización') }}">
        <i class="fas fa-eye"></i>
    </button>
    <button type="button" class="btn-action btn-edit btnEditar" data-plantilla="{{ $model?->id }}"
        title="{{ __('Editar plantilla') }}">
        <i class="fas fa-edit"></i>
    </button>
    <button type="button" class="btn-action btn-delete btnEliminar" data-plantilla="{{ $model?->id }}"
        title="{{ __('Eliminar plantilla') }}">
        <i class="fas fa-trash"></i>
    </button>
</div>
