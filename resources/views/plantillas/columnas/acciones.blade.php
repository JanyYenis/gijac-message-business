<div class="action-buttons">
    <button type="button" class="btn-action btn-view btnVer" data-plantilla="{{ $model?->id }}" title="Ver previsualización">
        <i class="fas fa-eye"></i>
    </button>
    <button type="button" disabled class="btn-action btn-edit btnEditar" data-plantilla="{{ $model?->id }}" title="Editar">
        <i class="fas fa-edit"></i>
    </button>
    <button type="button" disabled class="btn-action btn-delete btnEliminar" data-plantilla="{{ $model?->id }}" title="Eliminar">
        <i class="fas fa-trash"></i>
    </button>
</div>
