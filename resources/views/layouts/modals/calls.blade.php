<!-- Modal de llamada entrante -->
<div class="modal fade" id="incomingCallModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('📞 Llamada entrante') }}</h5>
            </div>
            <div class="modal-body">
                <div class="caller-info">
                    <div class="caller-avatar">👤</div>
                    <div class="caller-name" id="callerName">{{ __('Usuario desconocido') }}</div>
                    <div class="caller-status">{{ __('Llamando...') }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-accept" id="acceptCallBtn">
                    <i class="fas fa-phone"></i>{{ __('Aceptar') }}</button>
                <button type="button" class="btn btn-reject" id="rejectCallBtn">
                    <i class="fas fa-times"></i>{{ __('Rechazar') }}</button>
            </div>
        </div>
    </div>
</div>
