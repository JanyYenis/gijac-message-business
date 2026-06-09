<!-- Add Node Modal -->
    <div class="modal fade" id="addNodeModal" tabindex="-1" aria-labelledby="addNodeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="addNodeModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>
                        Agregar Nodo
                    </h5>
                    <button type="button" class="btn-close btnCerrarModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">Selecciona el tipo de nodo:</label>
                        <div class="node-type-selector">
                            <div class="node-type-card" data-type="text">
                                <div class="node-type-icon">
                                    <i class="fas fa-comment"></i>
                                </div>
                                <h6>Texto Simple</h6>
                                <small class="text-muted">Mensaje de texto básico</small>
                            </div>
                            <div class="node-type-card" data-type="image">
                                <div class="node-type-icon">
                                    <i class="fas fa-image"></i>
                                </div>
                                <h6>Texto + Imagen</h6>
                                <small class="text-muted">Mensaje con imagen adjunta</small>
                            </div>
                            <div class="node-type-card" data-type="video">
                                <div class="node-type-icon">
                                    <i class="fas fa-video"></i>
                                </div>
                                <h6>Texto + Video</h6>
                                <small class="text-muted">Mensaje con video adjunto</small>
                            </div>
                            <div class="node-type-card" data-type="document">
                                <div class="node-type-icon">
                                    <i class="fas fa-file"></i>
                                </div>
                                <h6>Texto + Documento</h6>
                                <small class="text-muted">Mensaje con documento adjunto</small>
                            </div>
                            <div class="node-type-card" data-type="buttons">
                                <div class="node-type-icon">
                                    <i class="fas fa-mouse-pointer"></i>
                                </div>
                                <h6>Texto + Botones</h6>
                                <small class="text-muted">Mensaje con botones de acción</small>
                            </div>
                            <div class="node-type-card" data-type="list">
                                <div class="node-type-icon">
                                    <i class="fas fa-list"></i>
                                </div>
                                <h6>Lista de Opciones</h6>
                                <small class="text-muted">Mensaje con lista seleccionable</small>
                            </div>
                            <div class="node-type-card" data-type="input">
                                <div class="node-type-icon">
                                    <i class="fas fa-keyboard"></i>
                                </div>
                                <h6>Capturar Datos</h6>
                                <small class="text-muted">Pregunta para respuesta libre</small>
                            </div>
                        </div>
                    </div>

                    <div id="nodeConfigForm">
                        <!-- Node configuration form will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="saveNodeBtn">
                        <i class="fas fa-save me-2"></i>
                        Agregar Nodo
                    </button>
                </div>
            </div>
        </div>
    </div>
