<div class="modal fade" tabindex="-1" id="modalCrearPlantilla">
    <form id="formCrearPlantilla" method="POST" enctype="multipart/form-data">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="">
                        <h5 class="modal-title d-flex justify-content-start text-white">Crear Plantilla</h5>
                        <p class="modal-subtitle mb-0">
                            Configura tu mensaje y mira cómo se verá en WhatsApp en tiempo
                            real.
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
                    <div class="modal-grid">
                        <!-- ======== Columna configuración (65%) ======== -->
                        <div class="modal-col-config">
                            <!-- Información básica -->
                            <section class="sec-card">
                                <div class="sec-title">
                                    <i class="fa-solid fa-circle-info"></i>
                                    Información básica
                                </div>
                                <p class="sec-hint">
                                    Identifica tu plantilla. El nombre debe ser único, en
                                    minúsculas y sin espacios.
                                </p>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label required" for="tplName">
                                            Nombre de la plantilla
                                        </label>
                                        <input type="text" class="form-control" id="tplName" name="name"
                                            placeholder="ej: promocion_especial" autocomplete="off"/>
                                        <div class="d-flex justify-content-between">
                                            <span class="help-text">
                                                Solo minúsculas, números y guiones bajos.
                                            </span>
                                            <span class="counter" data-counter="tplName">0 / 512</span>
                                        </div>
                                        <div class="field-error"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label required" for="tplCategory">
                                            Categoría
                                        </label>
                                        <select class="form-select" id="tplCategory" name="category" data-control="select2"
                                            data-placeholder="Seleccione la categoría" data-allow-clear="true" data-hide-search="true"
                                            data-dropdown-parent="#modalCrearPlantilla">
                                            <option value=""></option>
                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $categoria->codigo }}">{{ $categoria->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <div class="field-error"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label required" for="tplLanguage">
                                            Idioma
                                        </label>
                                        <select class="form-select" id="tplLanguage" name="language" data-control="select2"
                                            data-placeholder="Seleccione el idioma" data-allow-clear="true"
                                            data-dropdown-parent="#modalCrearPlantilla">
                                            <option value=""></option>
                                            @foreach ($idiomas as $idioma)
                                                <option value="{{ $idioma->codigo }}">{{ $idioma->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <div class="field-error"></div>
                                    </div>
                                </div>
                                <div id="catDesc" class="cat-desc"></div>
                            </section>

                            <!-- Encabezado (Meta no permite header en AUTHENTICATION: la sección se oculta completa) -->
                            <section class="sec-card" id="secHeader">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="sec-title mb-0">
                                            <i class="fa-solid fa-heading"></i>Encabezado
                                            <span class="sec-optional">Opcional</span>
                                        </div>
                                        <p class="sec-hint mb-0 mt-1">
                                            Destaca tu mensaje con texto, una imagen, video,
                                            documento o ubicación.
                                        </p>
                                    </div>
                                    <div class="form-check form-switch ms-3">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="headerSwitch" />
                                    </div>
                                </div>

                                <div id="headerOff" class="help-text mt-2">
                                    <i class="fa-solid fa-circle-info me-1"></i>
                                    Sin encabezado. Activa el interruptor para agregar uno.
                                </div>

                                <div id="headerOn" class="d-none mt-3">
                                    <div class="type-grid mb-3" id="headerTypeGrid"></div>

                                    <!-- TEXT -->
                                    <div class="h-pane" id="hpane-TEXT">
                                        <label class="form-label" for="headerText">Texto del encabezado</label>
                                        <input type="text" class="form-control" id="headerText" name="header_text"
                                            placeholder="ej: 🔥 Promoción especial para @{{ 1 }}"
                                            autocomplete="off" />
                                        <div class="d-flex justify-content-between">
                                            <span class="help-text">Puedes usar 1 variable, ej:
                                                @{{ 1 }} o @{{ nombre }}.</span>
                                            <span class="counter" data-counter="headerText">0 / 60</span>
                                        </div>
                                        <div class="field-error"></div>
                                        <div id="headerVars" class="mt-2"></div>
                                    </div>

                                    <!-- IMAGE / VIDEO / DOCUMENT (dropzones) -->
                                    <div class="h-pane d-none" id="hpane-IMAGE">
                                        <div class="dropzone" id="dz-IMAGE">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                            <div class="dz-title">
                                                Arrastra una imagen o haz clic
                                            </div>
                                            <div class="dz-sub">JPG o PNG · Máx. 16 MB</div>
                                        </div>
                                        <input type="file" id="file-IMAGE" name="header_media" class="d-none" />
                                        <div id="chip-IMAGE" class="d-none"></div>
                                        <div class="field-error" id="dzErr-IMAGE"></div>
                                    </div>

                                    <div class="h-pane d-none" id="hpane-VIDEO">
                                        <div class="dropzone" id="dz-VIDEO">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                            <div class="dz-title">
                                                Arrastra un video o haz clic
                                            </div>
                                            <div class="dz-sub">MP4 o 3GPP · Máx. 16 MB</div>
                                        </div>
                                        <input type="file" id="file-VIDEO" name="header_media" class="d-none" />
                                        <div id="chip-VIDEO" class="d-none"></div>
                                        <div class="field-error" id="dzErr-VIDEO"></div>
                                    </div>

                                    <div class="h-pane d-none" id="hpane-DOCUMENT">
                                        <div class="dropzone" id="dz-DOCUMENT">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                            <div class="dz-title">
                                                Arrastra un documento o haz clic
                                            </div>
                                            <div class="dz-sub">PDF · Máx. 16 MB</div>
                                        </div>
                                        <input type="file" id="file-DOCUMENT" name="header_media" class="d-none" />
                                        <div id="chip-DOCUMENT" class="d-none"></div>
                                        <div class="field-error" id="dzErr-DOCUMENT"></div>
                                    </div>

                                    <!-- LOCATION -->
                                    <div class="h-pane d-none" id="hpane-LOCATION">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label" for="locLat">Latitud</label>
                                                <input type="text" class="form-control" id="locLat"
                                                    placeholder="4.60971" />
                                                <div class="field-error"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="locLng">Longitud</label>
                                                <input type="text" class="form-control" id="locLng"
                                                    placeholder="-74.08175" />
                                                <div class="field-error"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="locName">Nombre del lugar</label>
                                                <input type="text" class="form-control" id="locName"
                                                    placeholder="Oficina principal" />
                                                <div class="field-error"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="locAddress">Dirección</label>
                                                <input type="text" class="form-control" id="locAddress"
                                                    placeholder="Cra 7 # 32-16, Bogotá" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Cuerpo -->
                            <section class="sec-card">
                                <div class="sec-title">
                                    <i class="fa-solid fa-message"></i>Cuerpo del mensaje
                                    <span class="badge-err badge-soft ms-1">Obligatorio</span>
                                </div>

                                <!-- Aviso solo AUTHENTICATION: Meta genera el texto, no se puede editar -->
                                <div id="bodyAuthNotice" class="help-text d-none mb-2">
                                    <i class="fa-solid fa-lock me-1"></i>
                                    En plantillas de Autenticación, Meta genera el texto del cuerpo automáticamente. Solo puedes decidir si incluye la recomendación de seguridad.
                                </div>

                                <!-- Panel normal: MARKETING / UTILITY -->
                                <div id="bodyFreePane">
                                    <p class="sec-hint">
                                        El texto principal que recibirá tu cliente. Usa variables
                                        numeradas como @{{ 1 }}, @{{ 2 }}… o con nombre como
                                        @{{ nombre }}, @{{ pedido }}… (no mezcles ambos estilos).
                                    </p>
                                    <label class="form-label required" for="bodyText">
                                        Contenido del mensaje
                                    </label>
                                    <textarea class="form-control" id="bodyText" rows="5"
                                        placeholder="Hola @{{ 1 }}, gracias por contactarnos…"></textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-soft btn-sm" id="addVarBtn">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Variable @{{ 1 }}
                                            </button>
                                            <button type="button" class="btn btn-soft btn-sm" id="addVarNamedBtn">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Variable @{{ nombre }}
                                            </button>
                                        </div>
                                        <span class="counter" data-counter="bodyText">0 / 1024</span>
                                    </div>
                                    <div class="field-error"></div>
                                    <div id="bodyVars" class="mt-3"></div>
                                </div>

                                <!-- Panel AUTHENTICATION -->
                                <div id="bodyAuthPane" class="d-none">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="authSecurityRec" checked>
                                        <label class="form-check-label" for="authSecurityRec">
                                            Incluir recomendación de seguridad ("For your security, do not share this code.")
                                        </label>
                                    </div>
                                    <div class="help-text mt-2" id="authBodyPreview"></div>
                                </div>
                            </section>

                            <!-- Pie de página -->
                            <section class="sec-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="sec-title mb-0">
                                            <i class="fa-solid fa-shoe-prints"></i>
                                            Pie de página
                                            <span class="sec-optional" id="footerOptionalLabel">Opcional</span>
                                        </div>
                                        <p class="sec-hint mb-0 mt-1" id="footerHint">
                                            Una línea breve visible debajo del mensaje.
                                        </p>
                                    </div>
                                    <div class="form-check form-switch ms-3">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="footerSwitch" />
                                    </div>
                                </div>
                                <div id="footerOff" class="help-text mt-2">
                                    <i class="fa-solid fa-circle-info me-1"></i>Sin pie de
                                    página.
                                </div>
                                <div id="footerOn" class="d-none mt-3">
                                    <!-- Panel normal: MARKETING / UTILITY -->
                                    <div id="footerFreePane">
                                        <label class="form-label" for="footerText">Texto del pie</label>
                                        <input type="text" class="form-control" id="footerText"
                                            placeholder="ej: Promoción válida hasta el 30 de septiembre."
                                            autocomplete="off" />
                                        <div class="d-flex justify-content-end">
                                            <span class="counter" data-counter="footerText">0 / 60</span>
                                        </div>
                                        <div class="field-error"></div>
                                    </div>
                                    <!-- Panel AUTHENTICATION: solo minutos de expiración, Meta arma el texto -->
                                    <div id="footerAuthPane" class="d-none">
                                        <label class="form-label" for="authExpiration">Minutos de expiración del código</label>
                                        <input type="number" class="form-control" id="authExpiration" min="1" max="90" placeholder="5">
                                        <span class="help-text">Entre 1 y 90 minutos. Meta genera el texto: "This code expires in N minutes."</span>
                                        <div class="field-error"></div>
                                    </div>
                                </div>
                            </section>

                            <!-- Botones -->
                            <section class="sec-card">
                                <div class="sec-title">
                                    <i class="fa-solid fa-hand-pointer"></i>
                                    Botones
                                    <span class="sec-optional">Opcional</span>
                                </div>
                                <p class="sec-hint" id="buttonsHint">
                                    Agrega llamadas a la acción, enlaces, respuestas rápidas y
                                    más. Arrastra para reordenar.
                                </p>

                                <!-- Aviso solo AUTHENTICATION -->
                                <div id="authButtonNotice" class="help-text d-none mb-2">
                                    <i class="fa-solid fa-lock me-1"></i>
                                    Las plantillas de Autenticación solo admiten <b>un</b> botón de tipo OTP.
                                </div>

                                <!--begin::Menu wrapper-->
                                <div class="m-0">
                                    <!--begin::Menu toggle-->
                                    <button type="button" class="btn btn-soft" data-kt-menu-trigger="click" data-kt-menu-placement="top-start" data-kt-menu-offset="0,5">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Agregar botón
                                    </button>
                                    <!--end::Menu toggle-->

                                    <!--begin::Menu dropdown-->
                                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="btnTypeMenu">
                                    </div>
                                    <!--end::Menu dropdown-->
                                </div>
                                <!--end::Menu wrapper-->

                                <div id="buttonsEmpty" class="help-text mt-3">
                                    <i class="fa-solid fa-circle-info me-1"></i>Aún no hay
                                    botones. Puedes agregar hasta 10.
                                </div>
                                <div id="buttonsList" class="mt-3"></div>
                                <div class="limit-warn" id="btnLimitWarn"></div>
                            </section>
                        </div>

                        <!-- ======== Columna vista previa (35%) ======== -->
                        <aside class="modal-col-preview">
                            <div class="preview-wrap">
                                <div class="preview-label">
                                    <i class="fa-solid fa-eye me-1"></i>Vista previa en vivo
                                </div>
                                <div class="phone">
                                    <div class="phone-screen">
                                        <div class="phone-topbar">
                                            <div class="avatar">G</div>
                                            <div>
                                                <div class="nm">GIJAC Message</div>
                                                <div class="st">en línea</div>
                                            </div>
                                        </div>
                                        <div class="phone-body" id="waPreview"></div>
                                    </div>
                                </div>
                                <pre class="preview-json mb-0" id="jsonPreview"></pre>
                            </div>
                        </aside>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light obalado text-white"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary obalado guardar">Crear</button>
                </div>
            </div>
        </div>
    </form>
</div>
