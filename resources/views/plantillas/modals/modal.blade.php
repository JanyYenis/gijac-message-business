<div class="modal fade" tabindex="-1" id="modalEditarPlantilla">
    <form id="formEditarPlantilla" method="POST" enctype="multipart/form-data">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="">
                        <h5 class="modal-title d-flex justify-content-start text-white">{{ __('Editar Plantilla') }}</h5>
                        <p class="modal-subtitle mb-0">
                            {{ __('Configura tu mensaje y mira cómo se verá en WhatsApp en tiempo real.') }}
                        </p>
                    </div>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal"
                        data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x">
                            <i class="las la-times fs-1 text-white"></i>
                        </span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="seccionEditar"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light obalado text-white"
                        data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="submit" class="btn btn-primary obalado guardar">{{ __('Crear') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
