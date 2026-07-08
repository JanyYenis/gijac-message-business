<!-- MODAL TEMPLATE -->
<div class="modal fade" id="modalTpl" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="tplTitle">
                    Plantilla
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div style="font-size:3rem" id="tplEmoji">📩</div>
                </div>
                <p class="text-muted" id="tplDesc"></p>
                <div class="bg-light rounded p-3 text-center mb-3">
                    <i class="bi bi-diagram-3 text-primary fs-1" style="font-size:2.5rem"></i>
                    <div class="small text-muted mt-2">Vista previa del flujo</div>
                </div>
                <p class="mb-0">
                    <strong>Nodos:</strong> <span id="tplNodes"></span>
                </p>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-wa" id="btnDownloadJson">
                    <i class="fa-regular fa-file-code me-1"></i>
                    Descargar JSON
                </button>
            </div>
        </div>
    </div>
</div>
