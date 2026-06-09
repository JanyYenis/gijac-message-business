<div class="modal fade" tabindex="-1" id="modalRolesPermisos">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-1 text-white">
                    Roles y Permisos
                </h1>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="las la-times fs-1 text-white"></i>
                    </span>
                </div>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id" id="idUsuario">
                <div class="mb-5 hover-scroll-x">
                    <div class="d-grid">
                        <ul class="nav nav-tabs flex-nowrap text-nowrap">
                            <li class="nav-item">
                                <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0 active text-verdoso fs-2" id="seccionTabRoles" data-bs-toggle="tab" href="#tabRoles">Roles</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0 text-verdoso fs-2" data-bs-toggle="tab" id="seccionTabPermisos" href="#tabPermisos">Permisos</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tabRoles" role="tabpanel">
                        <div class="mt-4 mb-4">
                            <!--begin::Alert-->
                            <div class="alert alert-dismissible bg-light-primary dropzone justify-content-start  d-flex flex-column flex-sm-row p-5 mb-10">
                                <i class="fas fa-exclamation fs-2hx text-primary me-4 mb-5 mb-sm-0">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                    <h5 class="mb-1 text-start">Información Importante</h5>
                                    <span class="text-start">Podra encontar el listado de los roles, al lado izquierdo los roles que se encuentran disponibles y en el lado derecho los roles que el usuario tiene asignados.</span>
                                </div>
                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                    <i class="las la-times text-primary fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>
                            </div>
                            <!--end::Alert-->
                        </div>
                        <form id="formRoles">
                            <div class="container">
                                <select multiple="multiple" id="selectRoles" name="roles">
                                </select>
                            </div>
                            <div class="mt-5 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="tabPermisos" role="tabpanel">
                        <div class="mt-4 mb-4">
                            <!--begin::Alert-->
                            <div class="alert alert-dismissible bg-light-primary dropzone justify-content-start  d-flex flex-column flex-sm-row p-5 mb-10">
                                <i class="fas fa-exclamation fs-2hx text-primary me-4 mb-5 mb-sm-0">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                    <h5 class="mb-1 text-start">Información Importante</h5>
                                    <span class="text-start">Podra encontar el listado de los permisos, al lado izquierdo los permisos que se encuentran disponibles y en el lado derecho los permisos que el usuario tiene asignados.</span>
                                </div>
                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                    <i class="las la-times text-primary fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>
                            </div>
                            <!--end::Alert-->
                        </div>
                        <form id="formPermisos">
                            <div class="container">
                                <select multiple="multiple" id="selectPermisos" name="permisos">
                                </select>
                            </div>
                            <div class="mt-5 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                {{-- <button type="submit" class="btn btn-success">Guardar</button> --}}
            </div>
        </div>
    </div>
</div>
