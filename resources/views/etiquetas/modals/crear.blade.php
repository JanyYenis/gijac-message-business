<div class="modal fade" tabindex="-1" id="modalCrearEtiqueta">
    <form id="formCrearEtiqueta" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-white mulish">Crear Etiqueta</h1>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x">
                        <i class="las la-times fs-1 text-white"></i>
                    </span>
                    </div>
                </div>

                <div class="modal-body">
                    <div id="errores">
                        @component('sistema/div-errores')
                        @endcomponent
                    </div>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="tagName" class="form-label required">
                                <i class="fas fa-tag me-1"></i>
                                Nombre de la Etiqueta
                            </label>
                            <input type="text" class="form-control" id="tagName" name="nombre" placeholder="Ingrese el nombre" required maxlength="50">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-4">
                            <label for="tagColor" class="form-label required">
                                <i class="fas fa-palette me-1"></i>
                                Color
                            </label>
                            <div class="color-picker-container">
                                <input type="color" class="form-control color-picker" id="tagColor" name="color" value="#28a745" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="tagDescription" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Descripción
                            </label>
                            <textarea class="form-control" id="tagDescription" name="descripcion" rows="3" maxlength="255" placeholder="Descripción opcional de la etiqueta..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear</button>
                </div>
            </div>
        </div>
    </form>
</div>
