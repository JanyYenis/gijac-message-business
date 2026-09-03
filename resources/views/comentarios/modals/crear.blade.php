<div class="modal fade" tabindex="-1" id="modalCrearComentario">
    <form id="formCrearComentario">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="position: fixed; bottom: 20px; right: 20px; width: 65rem; margin: 0;">
                <div class="modal-header">
                    <h1 class="modal-title">{{ __('Comentario') }}</h1>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal" data-bs-dismiss="modal" aria-label="{{ __('Close') }}">
                        <span class="svg-icon svg-icon-2x">
                        <i class="las la-times fs-1 text-white"></i>
                    </span>
                    </div>
                </div>

                <div class="modal-body scroll h-300px px-5">
                    <div id="errores">
                        @component('sistema/div-errores')
                        @endcomponent
                    </div>
                    <input type="hidden" name="comentariable_type" value="{{ $clase }}">
                    <input type="hidden" name="comentariable_id" value="{{ $id }}">
                    <div class="container">
                        <div class="h-175px">
                            <div class="seccionListadoComentarios"></div>
                        </div>
                        <div class="row align-items-end">
                            <div name="descripcion" id="textareaQuill" class=""></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="submit" class="btn btn-primary-gijac">{{ __('Crear') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
