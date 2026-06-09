<div class="modal fade" id="kt_modal_two_factor_authentication" tabindex="-1" style="display: none;" aria-hidden="true">
    <!--begin::Modal header-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header flex-stack">
                <!--begin::Title-->
                <h1 class="text-white fw-bold m-0 fs-1">Elija un método de autenticación</h1>
                <!--end::Title-->

                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="las la-times fs-1 text-white"></i>
                </div>
                <!--end::Close-->
            </div>
            <!--begin::Modal header-->

            <!--begin::Modal body-->
            <div class="modal-body scroll-y pt-10 pb-15 px-lg-17">
                <!--begin::Options-->
                <div data-kt-element="options" class="">
                    <!--begin::Notice-->
                    <p class="text-muted fs-5 fw-semibold mb-10">
                        Además de su nombre de usuario y contraseña, deberá ingresar un código (entregado a través de la aplicación) para iniciar sesión en su cuenta.
                    </p>
                    <!--end::Notice-->

                    <!--begin::Wrapper-->
                    <div class="pb-10">
                        <!--begin::Option-->
                        <input type="radio" class="btn-check" name="auth_option" value="apps" checked="checked" id="kt_modal_two_factor_authentication_option_1">
                        <label class="btn dropzone  btn-active-light-primary p-7 d-flex align-items-center mb-5"
                            for="kt_modal_two_factor_authentication_option_1">
                            <i class="fas fa-cog fs-4x me-4 text-primary">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="d-block fw-semibold text-start">
                                <span class="fw-bold d-block fs-3 text-blue">Aplicación Authenticator</span>
                                <span class="text-muted fw-semibold fs-6">
                                    Obtenga códigos de una aplicación como Google Authenticator, Microsoft Authenticator, Authy o 1Password.
                                </span>
                            </span>
                        </label>
                        <!--end::Option-->

                        {{-- <!--begin::Option-->
                        <input type="radio" class="btn-check" name="auth_option" value="whatsapp" id="kt_modal_two_factor_authentication_option_3">
                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center mb-5"
                            for="kt_modal_two_factor_authentication_option_3">
                            <i class="fab fa-whatsapp fs-4x me-4 text-success">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="d-block fw-semibold text-start">
                                <span class="text-gray-900 fw-bold d-block fs-3">WhatsApp</span>
                                <span class="text-muted fw-semibold fs-6">
                                    Le enviaremos un código por WhatsApp si necesita usarlo
                                    su método de inicio de sesión de respaldo.
                                </span>
                            </span>
                        </label>
                        <!--end::Option-->

                        <!--begin::Option-->
                        <input type="radio" class="btn-check" name="auth_option" value="sms" id="kt_modal_two_factor_authentication_option_2">
                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center"
                            for="kt_modal_two_factor_authentication_option_2">
                            <i class="fas fa-sms fs-4x me-4 text-primary">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <span class="d-block fw-semibold text-start">
                                <span class="text-gray-900 fw-bold d-block fs-3">SMS</span>
                                <span class="text-muted fw-semibold fs-6">Le enviaremos un código por SMS si necesita usarlo
                                    su método de inicio de sesión de respaldo.</span>
                            </span>
                        </label>
                        <!--end::Option--> --}}
                    </div>
                    <!--end::Options-->

                    <!--begin::Action-->
                    <button class="btn btn-primary w-100" data-kt-element="options-select">Continuar</button>
                    <!--end::Action-->
                </div>
                <!--end::Options-->

                <!--begin::Apps-->
                <div class="d-none" data-kt-element="apps">
                    <h1 class="text-verdoso fw-bold mb-7 fs-2">
                        Authenticator Apps
                    </h1>
                    <div class="text-gray-500 fw-semibold fs-6 mb-10">
                        Usando una aplicación de autenticación como
                        <a href="https://support.google.com/accounts/answer/1066447?hl=en" target="_blank">Google Authenticator</a>,
                        <a href="https://www.microsoft.com/en-us/account/authenticator" target="_blank">Microsoft Authenticator</a>,
                        <a href="https://authy.com/download/" target="_blank">Authy</a>, o
                        <a href="https://support.1password.com/one-time-passwords/" target="_blank">1Password</a>,
                        escanea el código QR. Generará un código de 6 dígitos para que lo ingrese a continuación.

                        <div class="pt-5 text-center">
                            @if ($qr)
                                {!! $qr !!}
                            @endif
                        </div>
                    </div>

                    <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-10 p-6">
                        <i class="fas fa-info fs-2tx text-warning me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="d-flex flex-stack flex-grow-1 ">
                            <div class=" fw-semibold">
                                <div class="fs-6 text-gray-700">
                                    Si tiene problemas para usar el código QR, seleccione la entrada manual en su aplicación e ingrese su nombre de usuario y el código:
                                    <div class="fw-bold text-gray-900 pt-2">{{ $secret ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form data-kt-element="apps-form" class="form fv-plugins-bootstrap5 fv-plugins-framework" id="formVerificacionApps">
                        <input type="hidden" name="secret" value="{{ $secret }}">
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <input type="text" class="form-control" placeholder="Ingrese el código" name="code" value="">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <!--end::Input group-->

                        <div class="d-flex flex-center">
                            <button type="reset" data-kt-element="apps-cancel" class="btn btn-cancelar me-3">
                                Cancelar
                            </button>

                            <button type="submit" data-kt-element="apps-submit" class="btn btn-primary">
                                <span class="indicator-label">
                                    Confirmar
                                </span>
                                <span class="indicator-progress">
                                    Cargando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
                <!--end::Apps-->

                <!--begin::WhatsApp-->
                <div class="d-none" data-kt-element="whatsapp">
                    <!--begin::Heading-->
                    <h3 class="text-gray-900 fw-bold mb-7">
                        WhatsApp
                    </h3>
                    <!--end::Heading-->

                    <!--begin::Notice-->
                    <div class="text-muted fw-semibold mb-10">
                        Ingrese su número de teléfono móvil con el código de país y le enviaremos un código de verificación al
                        pedido.
                    </div>
                    <!--end::Notice-->

                    <!--begin::Form-->
                    <form data-kt-element="apps-form" class="form fv-plugins-bootstrap5 fv-plugins-framework" id="formVerificacionWhatspp">
                        <input type="hidden" name="id" value="{{$usuario->id}}">
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <input type="text" class="form-control form-control-lg form-control-solid" placeholder="Telefono" id="telefonoWhatsapp" name="telefono" value="{{'+'.$usuario?->numero_completo ?? ''}}">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            </div>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Actions-->
                        <div class="d-flex flex-center">
                            <button type="reset" data-kt-element="apps-cancel" class="btn btn-light me-3">
                                Cancelar
                            </button>

                            <button type="submit" data-kt-element="apps-submit" class="btn btn-primary">
                                <span class="indicator-label">
                                    Guardar
                                </span>
                                <span class="indicator-progress">
                                    Cargando... <span
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::WhatsApp-->

                <!--begin::SMS-->
                <div class="d-none" data-kt-element="sms">
                    <!--begin::Heading-->
                    <h3 class="text-gray-900 fw-bold fs-3 mb-5">
                        SMS: Verifique su número de móvil
                    </h3>
                    <!--end::Heading-->

                    <!--begin::Notice-->
                    <div class="text-muted fw-semibold mb-10">
                        Ingrese su número de teléfono móvil con el código de país y le enviaremos un código de verificación al
                        pedido.
                    </div>
                    <!--end::Notice-->

                    <!--begin::Form-->
                    <form data-kt-element="sms-form" class="form fv-plugins-bootstrap5 fv-plugins-framework" id="formVerificacionSms">
                        <input type="hidden" name="id" value="{{$usuario->id}}">
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <input type="text" class="form-control form-control-lg form-control-solid" placeholder="Telefono" name="telefono" value="{{$usuario?->telefono ?? ''}}">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            </div>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Actions-->
                        <div class="d-flex flex-center">
                            <button type="reset" data-kt-element="sms-cancel" class="btn btn-light me-3">
                                Cancelar
                            </button>

                            <button type="submit" data-kt-element="sms-submit" class="btn btn-primary">
                                <span class="indicator-label">
                                    Guardar
                                </span>
                                <span class="indicator-progress">
                                    Cargando...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::SMS-->
                <!--end::Options-->
            </div>
            <!--begin::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal header-->
</div>
