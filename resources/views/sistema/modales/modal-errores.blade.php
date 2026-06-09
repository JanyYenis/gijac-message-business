<div class="modal fade" tabindex="-1" id="modalVerErrores">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-white mulish">Detalle Error</h1>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="las la-times fs-1 text-white"></i>
                    </span>
                </div>
            </div>

            <div class="modal-body">
                <!--begin::Alert-->
                <div class="alert alert-dismissible bg-light-success border border-success d-flex flex-column flex-sm-row p-5 mb-10">
                    <!--begin::Icon-->
                    <i class="las la-question-circle fs-2hx text-success me-4 mb-5 mb-sm-0">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <!--end::Icon-->

                    <!--begin::Wrapper-->
                    <div class="d-flex flex-column pe-0 pe-sm-10">
                        <!--begin::Title-->
                        <h5 class="mb-1 fs-1">Información</h5>
                        <!--end::Title-->

                        <!--begin::Content-->
                        <span>Si deseas conocer más información acerca de los errores, te invitamos a consultar
                            el código del error en la
                            <a href="https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes/#error-codes"
                                class="text-primary fw-bold" target="_blank" type="button">
                                Documentación de WhatsApp Business
                            </a>.
                        </span>
                        <!--end::Content-->
                    </div>
                    <!--end::Wrapper-->

                    <!--begin::Close-->
                    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                        <i class="las la-times fs-1 text-success">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </button>
                    <!--end::Close-->
                </div>
                <!--end::Alert-->
                <div class="seccionVerErrores"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
