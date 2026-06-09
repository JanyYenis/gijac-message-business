<!-- Test Chatbot Modal -->
    <div class="modal fade" id="testChatbotModal" tabindex="-1" aria-labelledby="testChatbotModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testChatbotModalLabel">
                        <i class="fas fa-play-circle me-2"></i>
                        Simulador de Chatbot
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="whatsapp-preview" style="height: 500px; border-radius: 0;">
                        <div class="whatsapp-header">
                            <div class="whatsapp-avatar">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Mi Chatbot</div>
                                <small class="opacity-75">Simulación activa</small>
                            </div>
                        </div>
                        <div id="simulatorMessages">
                            <!-- Simulator messages will appear here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" onclick="resetSimulator()">
                        <i class="fas fa-redo me-2"></i>
                        Reiniciar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
