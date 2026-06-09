<div class="modal fade" id="modalCrearTickets" tabindex="-1">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-750px">
        <!--begin::Modal content-->
        <div class="modal-content rounded">
            <!--begin::Modal header-->
            <div class="modal-header pb-0 border-0 justify-content-end">
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal" data-bs-dismiss="modal"
                    aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="las la-times fs-1 text-white"></i>
                    </span>
                </div>
                <!--end::Close-->
            </div>
            <!--begin::Modal header-->

            <!--begin::Modal body-->
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <!--begin:Form-->
                <form id="formCrearTickets"  enctype="multipart/form-data" class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <!--begin::Heading-->
                    <div class="mb-13 text-center">
                        <!--begin::Title-->
                        <h1 class="mb-3">Nuevo Ticket</h1>
                        <!--end::Title-->

                        <!--begin::Description-->
                        <div class="text-gray-500 fw-semibold fs-5">
                            Si necesita más información, consulte <a href="#" class="fw-bold link-primary">Directrices de soporte</a>.
                        </div>
                        <!--end::Description-->
                    </div>
                    <!--end::Heading-->

                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span class="required">Titulo</span>
                            <span class="ms-2" data-bs-toggle="tooltip" aria-label="Especifique un tema para su problema" data-bs-original-title="Especifique un tema para su problema">
                                <i class="fas fa-info-circle fs-7"></i>
                            </span>
                        </label>
                        <!--end::Label-->

                        <input type="text" class="form-control form-control-solid" placeholder="Ingrese un titulo de su ticket" name="titulo">
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row g-9 mb-8">
                        @can('tickets.asignar.responsable')
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row fv-plugins-icon-container">
                                <label class="required fs-6 fw-semibold mb-2">Responsable</label>

                                <select class="form-select form-select-solid" name="cod_responsable" id="selectResponsable" required data-control="select2" data-placeholder="Seleccione el responsable">
                                    <option value=""></option>
                                    @foreach ($responsables as $item)
                                        <option value="{{ $item->id }}">{{ $item->text }}</option>
                                    @endforeach
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Estado</label>

                                <select class="form-select form-select-solid" name="estado" id="selectEstados" required data-control="select2" data-placeholder="Seleccione el estado" data-hide-search="true">
                                    <option value=""></option>
                                    @foreach ($estados as $item)
                                        <option value="{{ $item->codigo }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Tipo</label>

                                <select class="form-select form-select-solid" name="tipo" id="selectTipo" required data-control="select2" data-placeholder="Seleccione el tipo" data-hide-search="true">
                                    <option value=""></option>
                                    @foreach ($tipos as $item)
                                        <option value="{{ $item->codigo }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Prioridad</label>

                                <select class="form-select form-select-solid" name="prioridad" id="selectPrioridad" required data-control="select2" data-placeholder="Seleccione la prioridad" data-hide-search="true">
                                    <option value=""></option>
                                    @foreach ($prioridades as $item)
                                        <option value="{{ $item->codigo }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Col-->
                        @endcan

                        <!--begin::Col-->
                        <div class="col-md-6 fv-row fv-plugins-icon-container">
                            <label class="required fs-6 fw-semibold mb-2">Fecha hallazgo</label>

                            <!--begin::Input-->
                            <div class="position-relative d-flex align-items-center">
                                <!--begin::Icon-->
                                <div class="symbol symbol-20px me-4 position-absolute ms-4">
                                    <span class="symbol-label bg-secondary">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Datepicker-->
                                <input id="fecha_hallazgo" class="form-control form-control-solid ps-12 flatpickr-input" placeholder="Fecha hallazgo" name="fecha_hallazgo" type="text">
                                <!--end::Datepicker-->
                            </div>
                            <!--end::Input-->
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                        <label class="fs-6 fw-semibold mb-2">Descripción</label>

                        <div class="form-control form-control-solid" id="divDescripcion"></div>
                        {{-- <textarea class="form-control form-control-solid" rows="4" name="descripcion" placeholder="Escribe la descripción de tu ticket"></textarea> --}}
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row mb-8">
                        <label class="fs-6 fw-semibold mb-2">Archivo</label>

                        <!--begin::Dropzone-->
                        <div class="dropzone dz-clickable" id="kt_modal_create_ticket_attachments">
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
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mb-15 fv-row fv-plugins-icon-container">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-stack">
                            <!--begin::Label-->
                            <div class="fw-semibold me-5">
                                <label class="fs-6">Notificaciones</label>

                                <div class="fs-7 text-gray-500">Permitir notificaciones por teléfono o correo electrónico</div>
                            </div>
                            <!--end::Label-->

                            <!--begin::Checkboxes-->
                            <div class="d-flex align-items-center">
                                <!--begin::Checkbox-->
                                <label class="form-check form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-20px" readonly disabled type="checkbox" name="notifications[]" value="1" checked="checked">

                                    <span class="form-check-label fw-semibold">
                                        Email
                                    </span>
                                </label>
                                <!--end::Checkbox-->

                                {{-- <!--begin::Checkbox-->
                                <label class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-20px" type="checkbox" name="notifications[]" value="2">

                                    <span class="form-check-label fw-semibold">
                                        Telefono
                                    </span>
                                </label>
                                <!--end::Checkbox--> --}}
                            </div>
                            <!--end::Checkboxes-->
                        </div>
                        <!--end::Wrapper-->
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
                    <!--end::Input group-->

                    <!--begin::Actions-->
                    <div class="text-center">
                        <button type="reset" id="modalCrearTickets_cancel" class="btn btn-light me-3">
                            Cancelar
                        </button>

                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">
                                Guardar
                            </span>
                            <span class="indicator-progress">
                                Cargando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end:Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
