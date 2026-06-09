<div class="card text-white mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
        <!--begin::Card title-->
        <div class="card-title m-0">
            <h3 class="fw-bold m-0 text-verdoso">Perfil</h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->

    <!--begin::Content-->
    <div id="kt_account_settings_profile_details" class="collapse show">
        <!--begin::Form-->
        <form id="formEditarUsuario" enctype="multipart/form-data" class="form fv-plugins-bootstrap5 fv-plugins-framework">
            <div id="errores">
                @component('sistema/div-errores')
                @endcomponent
            </div>
            <input type="hidden" name="id" value="{{$usuario->id}}">
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Avatar</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Image input-->
                        <div class="image-input image-input-outline" data-kt-image-input="true"
                            style="background-image: url({{ $usuario->foto ? asset($usuario->foto) : asset('assets/media/avatars/150-2.jpg') }})">
                            <!--begin::Preview existing avatar-->
                            <div class="image-input-wrapper w-125px h-125px"
                                style="background-image: url({{ $usuario->foto ? asset($usuario->foto) : asset('assets/media/avatars/150-2.jpg') }})">
                            </div>
                            <!--end::Preview existing avatar-->

                            <!--begin::Label-->
                            <label
                                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click"
                                title="Cargar Foto">
                                <i class="las la-pencil-alt fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="file" name="avatar" id="avatarUsuario" accept="image/png, image/jpeg, image/jpg" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                            <!--end::Label-->

                            <!--begin::Cancel-->
                            <span
                                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click"
                                title="Cancel avatar">
                                <i class="las la-times fs-3"></i>
                            </span>
                            <!--end::Cancel button-->

                            <!--begin::Remove button-->
                            <span
                                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click"
                                title="Remove avatar">
                                <i class="las la-times fs-3"></i>
                            </span>
                            <!--end::Remove-->
                        </div>
                        <!--end::Image input-->

                        <!--begin::Hint-->
                        <div class="form-text">Tipos de archivos permitidos: png, jpg, jpeg.</div>
                        <!--end::Hint-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-semibold fs-5">Nombre Completo</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                <input type="text" name="nombre" class="form-control mb-3 mb-lg-0" placeholder="Nombre"
                                    value="{{ $usuario?->nombre ?? '' }}">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                <input type="text" name="apellido" class="form-control" placeholder="Apellido"
                                    value="{{ $usuario?->apellido }}">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-semibold fs-5">Identificación</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                <select name="tipo_identificacion" id="selectTipoIdentificacionEdit" data-control="select2" data-placeholder="Seleccione el tipo de identificación" data-allow-clear="true" data-hide-search="true" class="form-control" required>
                                    <option value=""></option>
                                    @foreach ($tiposDocumentos as $item)
                                        <option value="{{$item->codigo}}" {{$item?->codigo == $usuario?->tipo_identificacion ? 'selected' : ''}}>{{$item->nombre_corto}}</option>
                                    @endforeach
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                <input type="text" name="identificacion" class="form-control" placeholder="Identificación"
                                    value="{{ $usuario?->identificacion }}">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-semibold fs-5">
                        <span class="required">Genero</span></label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                        <select name="genero" id="selectGeneroEdit" data-control="select2" data-placeholder="Seleccione el genero" data-allow-clear="true" data-hide-search="true" class="form-control" required>
                            <option value=""></option>
                            @foreach ($generos as $item)
                                <option value="{{$item->codigo}}" {{$item?->codigo == $usuario?->genero ? 'selected' : ''}}>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        <div
                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-semibold fs-5">
                        <span class="required">Telefono</span>
                        <span class="ms-1" data-bs-toggle="tooltip" aria-label="El número de teléfono debe estar activo"
                            data-bs-original-title="El número de teléfono debe estar activo">
                            <i class="fas fa-info-circle fs-7">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                        <input type="tel" name="telefono" id="tel" class="form-control" placeholder="Telefono" value="+{{ $usuario?->numero_completo }}">
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <div class="col-lg-4 col-form-label fw-semibold fs-5">
                        <label class="required">
                            Ciudad y País
                            <span class="ms-1" data-bs-toggle="tooltip" aria-label="Ciudad y País de origen" data-bs-original-title="Ciudad y País de origen">
                                <i class="fas fa-info-circle fs-10">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </label>
                    </div>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row">
                        <div class="row">
                            <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                <select class="form-control" name="pais_id" placeholder="..." id="selectPaisEdit" required>
                                    <option value="">Seleccione un país</option>
                                    @foreach ($paises as $pais)
                                        <option value="{{$pais->id}}" {{$pais?->id == $usuario?->ciudad?->id_pais ? 'selected' : ''}} data-kt-select2-country="{{$pais->bandera}}">{{$pais->nombre}} - {{$pais->nombre_corto}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                <select name="cod_ciudad" id="selectCiudadEdit" data-ciudad={{$usuario->cod_ciudad}} disabled data-control="select2" data-placeholder="Seleccione una ciudad" data-allow-clear="true" class="form-control" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6 d-none">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-semibold fs-5">Comunicación</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                        <!--begin::Options-->
                        <div class="d-flex align-items-center mt-3">
                            <!--begin::Option-->
                            <label class="form-check form-check-custom form-check-inline form-check-solid me-5">
                                <input class="form-check-input" name="cominicacion[]" type="checkbox"
                                    value="1">
                                <span class="fw-semibold ps-2 fs-6 text-dark">
                                    Email
                                </span>
                            </label>
                            <!--end::Option-->

                            <!--begin::Option-->
                            <label class="form-check form-check-custom form-check-inline form-check-solid">
                                <input class="form-check-input" name="cominicacion[]" type="checkbox"
                                    value="2">
                                <span class="fw-semibold ps-2 fs-6 text-dark">
                                    Telefono
                                </span>
                            </label>
                            <!--end::Option-->
                        </div>
                        <!--end::Options-->
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card body-->

            <!--begin::Actions-->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Actualizar</button>
            </div>
            <!--end::Actions-->
            <input type="hidden">
        </form>
        <!--end::Form-->
    </div>
    <!--end::Content-->
</div>

<div class="card text-white mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_signin_method">
        <div class="card-title m-0">
            <h3 class="fw-bold m-0 text-verdoso">Método de inicio de sesión</h3>
        </div>
    </div>
    <!--end::Card header-->

    <!--begin::Content-->
    <div class="seccionMetodoInicio">
        @component('perfil.componentes.metodo-inicio')
            @slot('usuario', $usuario)
        @endcomponent
    </div>
    <!--end::Content-->
</div>
