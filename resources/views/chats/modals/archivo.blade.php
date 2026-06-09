<div class="modal fade" tabindex="-1" id="modalArchivo">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-white fs-1">
                    Archivo
                </h1>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times fs-1 text-white">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <!--begin::Dropzone-->
                <div class="dropzone dz-clickable" id="archivoCargar">
                    <!--begin::Message-->
                    <div class="dz-message needsclick align-items-center">
                        <!--begin::Icon-->
                        <i class="las la-cloud-upload-alt fs-3hx text-primary"></i>                                <!--end::Icon-->

                        <!--begin::Info-->
                        <div class="ms-4">
                            <h3 class="fs-5 fw-bold text-gray-900 mb-1">Suelte los archivos aquí o haga clic para cargar.</h3>
                            <span class="fw-semibold fs-7 text-gray-500">Subir hasta 1 archivo</span>
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
                <!--end::Dropzone-->
                <div class="mt-3 d-none" id="mensajeInputArchivo">
                    <input type="text" class="form-control form-control-solid" name="mensaje" placeholder="Mensaje">
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-primary d-none text-white" id="btnEnviarArchivo">
                        <i class="far fa-paper-plane fs-1 text-white"></i>
                    </button>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger btnClose" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
