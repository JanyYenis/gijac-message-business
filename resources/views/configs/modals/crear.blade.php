<div class="modal fade" tabindex="-1" id="modalCrearConfig">
    <form id="formCrearConfig">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-white">Crear Configuración</h1>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x">
                            <i class="las la-times fs-1 text-white"></i>
                        </span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div id="errores">
                        @component('sistema/div-errores')
                        @endcomponent
                    </div>
                    <div class="row mb-7">
                        <div class="col-lg-6 col-md-6">
                            <label class="required">Version</label>
                            <input type="text" name="version" class="form-control" placeholder="Ingrese la version" required>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label class="required">Waba Id</label>
                            <input type="text" name="waba_id" class="form-control" placeholder="Ingrese el Waba Id" required>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <div class="col-lg-6 col-md-6">
                            <label class="required">App Id</label>
                            <input type="text" name="app_id" class="form-control" placeholder="Ingrese el App Id" required>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label class="required">Phone Number Id</label>
                            <input type="text" name="phone_number_id" class="form-control" placeholder="Ingrese el Phone Number Id" required>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <div class="col-lg-12 col-md-12">
                            <label class="required">Token META</label>
                            <input type="text" name="token" class="form-control" placeholder="Ingrese el token de META" required>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <div class="col-lg-6 col-md-6">
                            <label class="required">Identificador de verificación</label>
                            <input type="text" name="token_1" class="form-control" placeholder="Ingrese el Identificador de verificación" required>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label class="required">Numero</label>
                            <input type="text" name="numero" class="form-control" placeholder="Ingrese el Numero" required>
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
