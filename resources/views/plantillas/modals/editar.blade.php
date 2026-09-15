<div class="modal-grid">
    <!-- ======== Columna configuración (65%) ======== -->
    <div class="modal-col-config">
        <!-- Información básica -->
        <section class="sec-card">
            <div class="sec-title">
                <i class="fa-solid fa-circle-info"></i>
                {{ __('Información básica') }}
            </div>
            <p class="sec-hint">
                {{ __('Identifica tu plantilla. El nombre debe ser único, en minúsculas y sin espacios.') }}
            </p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label required" for="tplNameEdit">
                        {{ __('Nombre de la plantilla') }}
                    </label>
                    <input type="text" class="form-control" id="tplNameEdit" name="name"
                        placeholder="{{ __('ej: promocion_especial') }}" autocomplete="off" value="{{ $plantilla->name }}"/>
                    <div class="d-flex justify-content-between">
                        <span class="help-text">
                            {{ __('Solo minúsculas, números y guiones bajos.') }}
                        </span>
                        <span class="counter" data-counter="tplNameEdit">0 / 512</span>
                    </div>
                    <div class="field-error"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label required" for="tplCategoryEdit">
                        {{ __('Categoría') }}
                    </label>
                    <select class="form-select" id="tplCategoryEdit" name="category" data-control="select2"
                        data-placeholder="{{ __('Seleccione la categoría') }}" data-allow-clear="true"
                        data-hide-search="true" data-dropdown-parent="#modalEditarPlantilla">
                        <option value=""></option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->codigo }}" {{ $plantilla->category == $categoria->codigo ? 'selected' : '' }}>
                                {{ __($categoria->nombre) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="field-error"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label required" for="tplLanguageEdit">
                        {{ __('Idioma') }}
                    </label>
                    <select class="form-select" id="tplLanguageEdit" name="language" data-control="select2"
                        data-placeholder="{{ __('Seleccione el idioma') }}" data-allow-clear="true"
                        data-dropdown-parent="#modalEditarPlantilla">
                        <option value=""></option>
                        @foreach ($idiomas as $idioma)
                            <option value="{{ $idioma->codigo }}" {{ $idioma->codigo == $plantilla->language ? 'selected' : '' }}>
                                {{ $idioma->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <div class="field-error"></div>
                </div>
            </div>
            <div id="catDescEdit" class="cat-desc"></div>
        </section>

        <!-- Encabezado (Meta no permite header en AUTHENTICATION: la sección se oculta completa) -->
        <section class="sec-card" id="secHeaderEdit">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="sec-title mb-0">
                        <i class="fa-solid fa-heading"></i>{{ __('Encabezado') }}
                        <span class="sec-optional">{{ __('Opcional') }}</span>
                    </div>
                    <p class="sec-hint mb-0 mt-1">
                        {{ __('Destaca tu mensaje con texto, una imagen, video, documento o ubicación.') }}
                    </p>
                </div>
                <div class="form-check form-switch ms-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="headerSwitchEdit" />
                </div>
            </div>

            <div id="headerOffEdit" class="help-text mt-2">
                <i class="fa-solid fa-circle-info me-1"></i>
                {{ __('Sin encabezado. Activa el interruptor para agregar uno.') }}
            </div>

            <div id="headerOnEdit" class="d-none mt-3">
                <div class="type-grid mb-3" id="headerTypeGridEdit"></div>

                <!-- TEXT -->
                <div class="h-pane" id="hpane-TEXT-Edit">
                    <label class="form-label" for="headerTextEdit">{{ __('Texto del encabezado') }}</label>
                    <input type="text" class="form-control" id="headerTextEdit" name="header_text"
                        placeholder="{{ __('ej: 🔥 Promoción especial para') }} @{{ 1 }}"
                        autocomplete="off" />
                    <div class="d-flex justify-content-between">
                        <span class="help-text">{{ __('Puedes usar 1 variable, ej:') }}
                            @{{ 1 }} {{ __('o') }} {{ langVariable('nombre') }}.</span>
                        <span class="counter" data-counter="headerTextEdit">0 / 60</span>
                    </div>
                    <div class="field-error"></div>
                    <div id="headerVarsEdit" class="mt-2"></div>
                </div>

                <!-- IMAGE / VIDEO / DOCUMENT (dropzones) -->
                <div class="h-pane d-none" id="hpane-IMAGE-Edit">
                    <div class="dropzone" id="dz-IMAGE-Edit">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <div class="dz-title">
                            {{ __('Arrastra una imagen o haz clic') }}
                        </div>
                        <div class="dz-sub">{{ __('JPG o PNG · Máx. 16 MB') }}</div>
                    </div>
                    <input type="file" id="file-IMAGE-Edit" name="header_media" class="d-none" />
                    <div id="chip-IMAGE-Edit" class="d-none"></div>
                    <div class="field-error" id="dzErr-IMAGE-Edit"></div>
                </div>

                <div class="h-pane d-none" id="hpane-VIDEO-Edit">
                    <div class="dropzone" id="dz-VIDEO-Edit">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <div class="dz-title">
                            {{ __('Arrastra un video o haz clic') }}
                        </div>
                        <div class="dz-sub">{{ __('MP4 o 3GPP · Máx. 16 MB') }}</div>
                    </div>
                    <input type="file" id="file-VIDEO-Edit" name="header_media" class="d-none" />
                    <div id="chip-VIDEO-Edit" class="d-none"></div>
                    <div class="field-error" id="dzErr-VIDEO-Edit"></div>
                </div>

                <div class="h-pane d-none" id="hpane-DOCUMENT-Edit">
                    <div class="dropzone" id="dz-DOCUMENT-Edit">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <div class="dz-title">
                            {{ __('Arrastra un documento o haz clic') }}
                        </div>
                        <div class="dz-sub">{{ __('PDF · Máx. 16 MB') }}</div>
                    </div>
                    <input type="file" id="file-DOCUMENT-Edit" name="header_media" class="d-none" />
                    <div id="chip-DOCUMENT-Edit" class="d-none"></div>
                    <div class="field-error" id="dzErr-DOCUMENT-Edit"></div>
                </div>

                <!-- LOCATION -->
                <div class="h-pane d-none" id="hpane-LOCATION-Edit">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label" for="locLatEdit">{{ __('Latitud') }}</label>
                            <input type="text" class="form-control" id="locLatEdit" placeholder="4.60971" />
                            <div class="field-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="locLngEdit">{{ __('Longitud') }}</label>
                            <input type="text" class="form-control" id="locLngEdit" placeholder="-74.08175" />
                            <div class="field-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="locNameEdit">{{ __('Nombre del lugar') }}</label>
                            <input type="text" class="form-control" id="locNameEdit"
                                placeholder="{{ __('Oficina principal') }}" />
                            <div class="field-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="locAddressEdit">{{ __('Dirección') }}</label>
                            <input type="text" class="form-control" id="locAddressEdit"
                                placeholder="{{ __('Cra 7 # 32-16, Bogotá') }}" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cuerpo -->
        <section class="sec-card">
            <div class="sec-title">
                <i class="fa-solid fa-message"></i>{{ __('Cuerpo del mensaje') }}
                <span class="badge-err badge-soft ms-1">{{ __('Obligatorio') }}</span>
            </div>

            <!-- Aviso solo AUTHENTICATION: Meta genera el texto, no se puede editar -->
            <div id="bodyAuthNoticeEdit" class="help-text d-none mb-2">
                <i class="fa-solid fa-lock me-1"></i>
                {{ __('En plantillas de Autenticación, Meta genera el texto del cuerpo automáticamente. Solo puedes decidir si incluye la recomendación de seguridad.') }}
            </div>

            <!-- Panel normal: MARKETING / UTILITY -->
            <div id="bodyFreePaneEdit">
                <p class="sec-hint">
                    {{ __('El texto principal que recibirá tu cliente. Usa variables numeradas como') }}
                    @{{ 1 }}, @{{ 2 }}…
                    {{ __('o con nombre como') }} {{ langVariable('nombre') }}, {{ langVariable('pedido') }}…
                    {{ __('(no mezcles ambos estilos).') }}
                </p>
                <label class="form-label required" for="bodyTextEdit">
                    {{ __('Contenido del mensaje') }}
                </label>
                <textarea class="form-control" id="bodyTextEdit" rows="5"
                    placeholder="{{ __('Hola') }} @{{ 1 }}, {{ __('gracias por contactarnos') }}…"></textarea>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-soft btn-sm" id="addVarBtnEdit">
                            <i class="fa-solid fa-plus me-1"></i>
                            {{ __('Variable') }} @{{ 1 }}
                        </button>
                        <button type="button" class="btn btn-soft btn-sm" id="addVarNamedBtnEdit">
                            <i class="fa-solid fa-plus me-1"></i>
                            {{ __('Variable') }} {{ langVariable('nombre') }}
                        </button>
                    </div>
                    <span class="counter" data-counter="bodyTextEdit">0 / 1024</span>
                </div>
                <div class="field-error"></div>
                <div id="bodyVarsEdit" class="mt-3"></div>
            </div>

            <!-- Panel AUTHENTICATION -->
            <div id="bodyAuthPaneEdit" class="d-none">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="authSecurityRecEdit" checked>
                    <label class="form-check-label" for="authSecurityRecEdit">
                        {{ __('Incluir recomendación de seguridad ("For your security, do not share this code.")') }}
                    </label>
                </div>
                <div class="help-text mt-2" id="authBodyPreviewEdit"></div>
            </div>
        </section>

        <!-- Pie de página -->
        <section class="sec-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="sec-title mb-0">
                        <i class="fa-solid fa-shoe-prints"></i>
                        {{ __('Pie de página') }}
                        <span class="sec-optional" id="footerOptionalLabelEdit">{{ __('Opcional') }}</span>
                    </div>
                    <p class="sec-hint mb-0 mt-1" id="footerHintEdit">
                        {{ __('Una línea breve visible debajo del mensaje.') }}
                    </p>
                </div>
                <div class="form-check form-switch ms-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="footerSwitchEdit" />
                </div>
            </div>
            <div id="footerOffEdit" class="help-text mt-2">
                <i class="fa-solid fa-circle-info me-1"></i>{{ __('Sin pie de página.') }}
            </div>
            <div id="footerOnEdit" class="d-none mt-3">
                <!-- Panel normal: MARKETING / UTILITY -->
                <div id="footerFreePaneEdit">
                    <label class="form-label" for="footerTextEdit">{{ __('Texto del pie') }}</label>
                    <input type="text" class="form-control" id="footerTextEdit"
                        placeholder="{{ __('ej: Promoción válida hasta el 30 de septiembre.') }}"
                        autocomplete="off" />
                    <div class="d-flex justify-content-end">
                        <span class="counter" data-counter="footerTextEdit">0 / 60</span>
                    </div>
                    <div class="field-error"></div>
                </div>
                <!-- Panel AUTHENTICATION: solo minutos de expiración, Meta arma el texto -->
                <div id="footerAuthPaneEdit" class="d-none">
                    <label class="form-label"
                        for="authExpirationEdit">{{ __('Minutos de expiración del código') }}</label>
                    <input type="number" class="form-control" id="authExpirationEdit" min="1" max="90"
                        placeholder="5">
                    <span
                        class="help-text">{{ __('Entre 1 y 90 minutos. Meta genera el texto: "This code expires in N minutes."') }}</span>
                    <div class="field-error"></div>
                </div>
            </div>
        </section>

        <!-- Botones -->
        <section class="sec-card">
            <div class="sec-title">
                <i class="fa-solid fa-hand-pointer"></i>
                {{ __('Botones') }}
                <span class="sec-optional">{{ __('Opcional') }}</span>
            </div>
            <p class="sec-hint" id="buttonsHintEdit">
                {{ __('Agrega llamadas a la acción, enlaces, respuestas rápidas y más. Arrastra para reordenar.') }}
            </p>

            <!-- Aviso solo AUTHENTICATION -->
            <div id="authButtonNoticeEdit" class="help-text d-none mb-2">
                <i class="fa-solid fa-lock me-1"></i>
                {{ __('Las plantillas de Autenticación solo admiten') }} <b>{{ __('un') }}</b>
                {{ __('botón de tipo OTP.') }}
            </div>

            <!--begin::Menu wrapper-->
            <div class="m-0">
                <!--begin::Menu toggle-->
                <button type="button" class="btn btn-soft" data-kt-menu-trigger="click"
                    data-kt-menu-placement="top-start" data-kt-menu-offset="0,5">
                    <i class="fa-solid fa-plus me-1"></i>
                    {{ __('Agregar botón') }}
                </button>
                <!--end::Menu toggle-->

                <!--begin::Menu dropdown-->
                <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="btnTypeMenuEdit">
                </div>
                <!--end::Menu dropdown-->
            </div>
            <!--end::Menu wrapper-->

            <div id="buttonsEmptyEdit" class="help-text mt-3">
                <i class="fa-solid fa-circle-info me-1"></i>
                {{ __('Aún no hay botones. Puedes agregar hasta 10.') }}
            </div>
            <div id="buttonsListEdit" class="mt-3"></div>
            <div class="limit-warn" id="btnLimitWarnEdit"></div>
        </section>
    </div>

    <!-- ======== Columna vista previa (35%) ======== -->
    <aside class="modal-col-preview">
        <div class="preview-wrap">
            <div class="preview-label">
                <i class="fa-solid fa-eye me-1"></i>{{ __('Vista previa en vivo') }}
            </div>
            <div class="phone">
                <div class="phone-screen">
                    <div class="phone-topbar">
                        <div class="avatar">G</div>
                        <div>
                            <div class="nm">{{ __('GIJAC Message') }}</div>
                            <div class="st">{{ __('en línea') }}</div>
                        </div>
                    </div>
                    <div class="phone-body" id="waPreviewEdit"></div>
                </div>
            </div>
            <pre class="preview-json mb-0" id="jsonPreviewEdit"></pre>
        </div>
    </aside>
</div>
