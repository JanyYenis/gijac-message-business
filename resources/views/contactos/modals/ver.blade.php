
<div class="modal fade" tabindex="-1" id="modalVerContacto">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-white">Ver Contacto</h1>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="las la-times fs-1 text-white"></i>
                    </span>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto tablasScroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header" data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body" data-kt-scroll-offset="5px" style="max-height: 480px !important;">
                    <div class="table-responsive">
                        <table border="1" class="table table-striped table-bordered" id="tablaVerContactos">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center all">#</th>
                                    <th width="10%" class="text-center all">Nombre</th>
                                    <th width="10%" class="text-center all">Telefono</th>
                                    <th width="10%" class="text-center all">Etiqueta</th>
                                    <th width="10%" class="text-center all">Estado</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
