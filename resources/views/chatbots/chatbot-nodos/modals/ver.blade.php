<!-- ====== MODAL PROBAR FLUJO ====== -->
<div class="modal fade" id="modalProbar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:14px; overflow:hidden;">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-play-circle me-1 text-white"></i>
                    Probar Flujo — Simulador
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="sim-grid">
                    <!-- Panel izquierdo: flujo -->
                    <div class="sim-left">
                        <h6 class="fw-bold mb-2 fs-4">Flujo</h6>
                        <div class="sim-flow-item active">
                            <span class="dn-icon dn-start" style="width:24px;height:24px;">
                                <i class="bi bi-play-fill"></i>
                            </span>
                            Inicio
                        </div>
                        <div class="sim-flow-item">
                            <span class="dn-icon dn-text" style="width:24px;height:24px;">
                                <i class="bi bi-chat-left-text"></i>
                            </span>
                            Mensaje Bienvenida
                        </div>
                        <div class="sim-flow-item">
                            <span class="dn-icon dn-buttons" style="width:24px;height:24px;">
                                <i class="bi bi-ui-checks-grid"></i>
                            </span>
                            Botones Acepto / No
                        </div>
                        <div class="sim-flow-item">
                            <span class="dn-icon dn-list" style="width:24px;height:24px;">
                                <i class="bi bi-list-ul"></i>
                            </span>
                            Lista Principal
                        </div>
                    </div>
                    <!-- Panel derecho: chat -->
                    <div class="wa-chat">
                        <div class="wa-chat-head">
                            <div class="avatar">
                                <i class="fa-solid fa-robot"></i>
                            </div>
                            <div>
                                <div style="font-weight:600; font-size:0.9rem;">Chatbot GIJAC</div>
                                <div style="font-size:0.72rem; opacity:.85;">en línea</div>
                            </div>
                        </div>
                        <div class="wa-chat-body" id="simChat">
                            <div class="wa-msg bot">
                                ¡Hola! Bienvenido a GIJAC MESSAGE BUSINESS. ¿En qué podemos ayudarte hoy?
                            </div>
                            <div class="wa-msg bot">Por favor acepta nuestros términos para continuar:</div>
                        </div>
                        <div class="wa-chat-foot">
                            <input type="text" class="form-control" id="simInput"
                                placeholder="Escribe un mensaje..." />
                            <button class="btn btn-wa" id="simSend" style="background:var(--wa-green); color:#fff;">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
