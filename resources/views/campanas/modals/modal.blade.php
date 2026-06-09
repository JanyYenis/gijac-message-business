<div class="modal fade" tabindex="-1" id="modalEditarCampana">
    <form id="formEditarCampana" enctype="multipart/form-data">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-white mulish">Editar Campaña</h1>
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
                    <div class="seccionEditar"></div>
                </div>
            </div>
        </div>
    </form>
</div>
