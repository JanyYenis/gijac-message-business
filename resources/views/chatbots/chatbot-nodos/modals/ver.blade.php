<div class="modal fade" id="modalVerVersion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de versión <span id="mvvNumero"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">
                    <strong>Publicado:</strong> <span id="mvvFecha"></span> —
                    <strong>Usuario:</strong> <span id="mvvUsuario"></span>
                </p>
                <div id="drawflow-ver" style="position:relative; width:100%; height:500px; border:1px solid #E5E7EB; border-radius:8px; background:#fafafa; overflow:hidden;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning" id="btnRestaurarDesdeModal">
                    <i class="bi bi-arrow-counterclockwise"></i> Restaurar esta versión
                </button>
            </div>
        </div>
    </div>
</div>
