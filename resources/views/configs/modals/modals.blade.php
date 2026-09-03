<div class="modal fade" tabindex="-1" id="modalEditarConfig">
    <form id="formEditarConfig" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-white mulish">{{ __('Editar Configuracion') }}</h1>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal" data-bs-dismiss="modal" aria-label="{{ __('Close') }}">
                        <span class="svg-icon svg-icon-2x">
                        <i class="las la-times fs-1 text-white"></i>
                    </span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="seccionEditar"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Actualizar') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
