<!-- MODAL TEMPLATE -->
<div class="modal fade" id="modalTpl" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="tplTitle">{{ __('Plantilla') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div style="font-size:3rem" id="tplEmoji">📩</div>
                </div>
                <p class="text-muted" id="tplDesc"></p>
                <div class="flow-preview-container">
                    <div id="drawflowPreview"></div>
                </div>
                <p class="mb-0">
                    <strong>{{ __('Nodos:') }}</strong> <span id="tplNodes"></span>
                </p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-wa" id="btnDownloadJson">
                    <i class="fa-regular fa-file-code me-1"></i>{{ __('Descargar JSON') }}</button>
            </div>
        </div>
    </div>
</div>
