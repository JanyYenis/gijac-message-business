<!-- MODAL TEST -->
<div class="modal fade" id="modalProbar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white">
                    <i class="fa-solid fa-paper-plane me-2 text-white fs-1"></i>
                    Probar Webhook
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="form-label">Número</label>
                    <input type="text" class="form-control" id="tNum"
                        value="{{ auth()->user()->numero_completo }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="tName" value="{{ auth()->user()->nombre_completo }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mensaje</label>
                    <input type="text" class="form-control" id="tMsg" value="Hola">
                </div>
                <button class="btn btn-wa w-100 mb-3" id="btnEnviarPrueba">
                    <i class="bi bi-send me-1"></i>
                    Enviar Prueba
                </button>
                <div id="testOut" style="display:none">
                    <label class="form-label">Request enviado</label>
                    <div class="req-box mb-2" id="reqBox"></div>
                    <label class="form-label">Response recibida</label>
                    <div class="req-box mb-2" id="resBox"></div>
                    <span class="badge bg-success" id="timeBadge"></span>
                </div>
            </div>
        </div>
    </div>
</div>
