<div class="modal fade" id="cargarContactosModal" tabindex="-1" aria-labelledby="cargarContactosModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h1 class="modal-title text-white" id="cargarContactosModalLabel">
                    <i class="fas fa-upload fs-2"></i>
                    Cargar Contactos
                </h1>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal" data-bs-dismiss="modal"
                    aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="las la-times fs-1 text-white"></i>
                    </span>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Alert Informativa -->
                <div class="alert alert-info-custom alert-dismissible fade show" role="alert" id="infoAlert">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle alert-icon flex-shrink-0"></i>
                        <div>
                            <strong>Información importante:</strong> Por favor, no cambie el orden de los campos que va
                            a cargar.
                            Si desea tener un ejemplo de la estructura de los archivos, puede descargarlos dando clic en
                            el botón de Excel que aparece a continuación.
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Cerrar alerta"></button>
                </div>

                <!-- Botón de descarga de plantilla -->
                <div class="mb-4">
                    <a type="button" href="{{ asset('estructuras/contactos.xlsx') }}" class="btn btn-excel"
                        id="downloadTemplate">
                        <i class="fas fa-file-excel excel-icon"></i>
                        Excel
                    </a>
                </div>

                <!-- Checkbox Eliminar Encabezado -->
                <div class="checkbox-container">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked disabled value=""
                            id="removeHeader">
                        <label class="form-check-label" for="removeHeader">
                            <i class="fas fa-header me-2"></i>
                            Eliminar encabezado
                        </label>
                    </div>
                </div>

                <form id="kt_dropzonejs_example_1" enctype="multipart/form-data" class="dropzone">
                    <div class="fv-row">
                        <div class="dz-message needsclick">
                            <i class="las la-cloud-upload-alt fs-3x text-primary">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="ms-4">
                                <h3 class="fs-3x fw-bold text-gijac mb-1">Cargar Archivo Excel</h3>
                                <span class="fs-3 fw-semibold text-gray-400">Maximo 1 Archivos</span>
                            </div>
                        </div><br>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelBtn">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="enviarButton">
                    <i class="fas fa-upload me-2"></i>
                    Cargar
                </button>
            </div>
        </div>
    </div>
</div>
