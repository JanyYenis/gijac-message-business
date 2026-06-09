<input type="hidden" name="id" value="{{ $campana->id }}">
{{-- <div class="row">
    <div class="col-lg-7">
        <div class="form-floating row mb-7">
            <select name="etiquetas" id="selectEtiquetaEditar" class="form-control" data-control="select2"
                data-placeholder="Etiquetas" data-allow-clear="true" required
                data-dropdown-parent="body">
                <option></option>
                @foreach ($etiquetas as $etiqueta)
                    <option value="{{$etiqueta->id}}" {{ $etiqueta->id == $etiquetaSeleccionada ? 'selected' : ''}}>{{$etiqueta->nombre}}</option>
                @endforeach
            </select>
        </div>
        <div class="row mb-7">
            <select name="categoria" id="selectCategoriaEditar" class="form-control" data-control="select2" data-placeholder="Categoria" data-allow-clear="true" data-hide-search="true" required>
                <option></option>
                <option value="Info" {{"Info" == $campana->categoria ? 'selected' : ''}}>Información</option>
                <option value="Invi" {{"Invi" == $campana->categoria ? 'selected' : ''}}>Invitación</option>
                <option value="Podcast" {{"Podcast" == $campana->categoria ? 'selected' : ''}}>Podcast</option>
                <option value="Formulario" {{"Formulario" == $campana->categoria ? 'selected' : ''}}>Formulario</option>
            </select>
        </div>
        <div class="row mb-7">
            <select name="id_plantilla" id="selectPlantillaEditar" class="form-control" data-control="select2"
                data-placeholder="Plantilla" required data-dropdown-parent="body" data-allow-clear="true">
                <option></option>
                @foreach ($plantillas as $plantilla)
                    <option value="{{$plantilla['id']}}" {{$plantilla['id'] == $campana->id_plantilla ? 'selected' : ''}}>{{$plantilla['name']." - ".$plantilla['BODY']['text']}}</option>
                @endforeach
            </select>
        </div>
        <div class="row mb-3 d-none" id="divInputFileEditar">
            <div class="col-lg-8 col-md-8">
                <input type="file" class="form-control" id="inputFileEditar" accept="image/png" name="archivo">
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="" id="checkUsarRecursoEditar"/>
                    <label class="form-check-label" for="checkUsarRecursoEditar">
                        Usar recurso de META
                    </label>
                </div>
            </div>
        </div>
        <div class="separator separator-dashed separator-content border-primary my-15 d-none seccionEncabezadoEditar">
            <span class="h4 text-primary">Variable encabezado</span>
        </div>
        <div class="row mb-3 d-none seccionEncabezadoEditar">
            <input type="text" name="header_text" placeholder="Ingrese valor de variable del encabezado" class="form-control">
        </div>
        <div class="separator separator-dashed separator-content border-primary my-15 d-none" id="divTituloVariableEditar">
            <span class="h4 text-primary">Variables del contenido</span>
        </div>
        <div class="row mb-7" id="divVariablesEditar">
        </div>
        <div class="row mb-7" id="divUrlEditar">
        </div>
        <div class="divOpciones mb-3">
            <div class="col-lg-12 mb-7">
                <div class="form-check form-check-inline col-lg-5">
                    <input class="form-check-input" type="radio" name="estado" value="1" id="accionInmediatamenteEditar">
                    <label class="form-check-label" for="accionInmediatamenteEditar">
                        Inmediatamente
                    </label>
                </div>
                <div class="form-check form-check-inline col-lg-5">
                    <input class="form-check-input" type="radio" name="estado" value="2" id="accionProgramarEditar" checked>
                    <label class="form-check-label" for="accionProgramarEditar">
                        Programar
                    </label>
                </div>
            </div>
            <div class="row" id="fechasEditar">
                <div class="col-lg-3 offset-lg-5">
                    <input type="text" placeholder="DD/MM/AAA" value="{{date("Y-m-d", strtotime($campana->fecha_envio))}}" name="fecha" id="fechaProgramacionEditar" class="form-control">
                </div>
                <div class="col-lg-2">
                    <input type="text" placeholder="00:00" id="horaProgramacionEditar" value="{{date("H:i:s", strtotime($campana->fecha_envio))}}" name="hora" class="form-control">
                </div>
            </div>
        </div>
        <div class="row seccionContactosEditar {{ count($campana?->enviosActivos) ? '' : 'd-none'}}">
            <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto tablasScroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header" data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body" data-kt-scroll-offset="5px" style="max-height: 410px;">
                <div class="table-responsive">
                    <table border="1" class="table table-striped table-bordered" id="tablaContactosEditar">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center all">
                                    <div class="form-check text-center">
                                        <input class="form-check-input checkSeleccionarTodos" type="checkbox" value="" id="seleccionarTodosEditar" checked/>
                                    </div>
                                </th>
                                <th width="10%" class="text-center all">Nombre</th>
                                <th width="10%" class="text-center all">Etiqueta</th>
                                <th width="10%" class="text-center all">Telefono</th>
                                <th width="10%" class="text-center all">Identificación</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <!-- <h1>Hola</h1> -->
        <div class="mobile-preview-franco" style="height: 566px; width: 281px; margin: 0px auto; z-index: 5;
            position: relative;">
            <div id="smartphone_id" class="content" style="overflow: auto;
                transform: translate(-38px, -103px) scale(0.7); width: 357px; margin: 0px auto; height: 770px;
                border-radius: 25px; z-index: 1; position: relative;">
                <div class="page">
                    <div class="marvel-device nexus5">
                        <div class="top-bar"></div>
                        <div class="sleep"></div>
                        <div class="volume"></div>
                        <div class="camera"></div>
                        <div class="screen" style="position: relative; top: 3rem;">
                            <div class="screen-container">
                                <div class="status-bar">
                                    <div class="time ml-3" style="float: left; font-size: 16px;">
                                        <a style="color: white;">{{date('H:m')}}</a>
                                    </div>
                                <div class="dynamic-island" style="width: 100px; height: 16px; background: black;
                                    border-radius: 50px; float: left; position: relative; top: 35%;
                                    transform: translateY(-30%); margin: 0px 0px 0px 8px; font-weight: 600;
                                    left: 52px;"></div>
                                <div class="battery">
                                    <i class="fas fa-battery-full mr-3" style="font-size: 20px;"></i>
                                </div>
                            </div>
                            <div class="chat">
                                <div class="chat-container">
                                    <div class="user-bar">
                                        <div class="avatar">
                                            <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPCFET0NUWVBFIHN2ZyAgUFVCTElDICctLy9XM0MvL0RURCBTVkcgMS4xLy9FTicgICdodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQnPgo8c3ZnIHdpZHRoPSI0MDFweCIgaGVpZ2h0PSI0MDFweCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAzMTIuODA5IDAgNDAxIDQwMSIgdmVyc2lvbj0iMS4xIiB2aWV3Qm94PSIzMTIuODA5IDAgNDAxIDQwMSIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgdHJhbnNmb3JtPSJtYXRyaXgoMS4yMjMgMCAwIDEuMjIzIC00NjcuNSAtODQzLjQ0KSI+Cgk8cmVjdCB4PSI2MDEuNDUiIHk9IjY1My4wNyIgd2lkdGg9IjQwMSIgaGVpZ2h0PSI0MDEiIGZpbGw9IiNFNEU2RTciLz4KCTxwYXRoIGQ9Im04MDIuMzggOTA4LjA4Yy04NC41MTUgMC0xNTMuNTIgNDguMTg1LTE1Ny4zOCAxMDguNjJoMzE0Ljc5Yy0zLjg3LTYwLjQ0LTcyLjktMTA4LjYyLTE1Ny40MS0xMDguNjJ6IiBmaWxsPSIjRkZGRkZGIi8+Cgk8cGF0aCBkPSJtODgxLjM3IDgxOC44NmMwIDQ2Ljc0Ni0zNS4xMDYgODQuNjQxLTc4LjQxIDg0LjY0MXMtNzguNDEtMzcuODk1LTc4LjQxLTg0LjY0MSAzNS4xMDYtODQuNjQxIDc4LjQxLTg0LjY0MWM0My4zMSAwIDc4LjQxIDM3LjkgNzguNDEgODQuNjR6IiBmaWxsPSIjRkZGRkZGIi8+CjwvZz4KPC9zdmc+Cg==" alt="Avatar">
                                        </div>
                                        <div class="name">
                                            <span>+{{$numeroTel}}</span>
                                            <span class="status">En linea</span>
                                        </div>
                                        <div class="actions more">
                                            <i class="fas fa-ellipsis-v text-white fs-1"></i>
                                        </div>
                                        <div class="actions">
                                            <i class="fas fa-phone-alt text-white fs-1"></i>
                                        </div>
                                    </div>
                                    <div class="conversation">
                                        <div class="conversation-container">
                                            <div class="message sent">
                                                <div class="image-container texHeaderCampanaEditar d-none">
                                                    <span>
                                                        <b class="textHeaderCampanaEditar"></b>
                                                    </span><br>
                                                </div>
                                                <div class="image-container imagenCampanaEditar {{$campana?->tipo == 'Imagen' ? '' : 'd-none'}}">
                                                    <img src="{{ $campana?->contenido_multimedia ? $campana?->contenido_multimedia : asset('img/defecto.png') }}" class="img-fluid rounded thumbnail-image imgCampanaEditar" style="max-width: 100%; min-width: 100%;">
                                                </div>
                                                <div class="image-container videoCampanaEditar {{$campana?->tipo == 'Video' ? '' : 'd-none'}}">
                                                    <video src="{{ $campana?->contenido_multimedia }}" controls class="videoCampana1Editar" style="max-width: 100%; min-width: 100%;"></video>
                                                </div>
                                                <span class="contenidocampanaEditar">Mensaje</span>
                                                <span class="metadata">
                                                    <span class="time"></span>
                                                    <span class="tick">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck-ack" x="2063" y="2076">
                                                            <path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#4fc3f7">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <div style="height: 61px; background: rgb(255, 255, 255); padding-top: 6px; position: relative; top: -6px; border-radius: 40px;">
                                            <div class="conversation-compose">
                                                <div class="emoji">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" id="smiley" x="3147" y="3209">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.153 11.603c.795 0 1.44-.88 1.44-1.962s-.645-1.96-1.44-1.96c-.795 0-1.44.88-1.44 1.96s.645 1.965 1.44 1.965zM5.95 12.965c-.027-.307-.132 5.218 6.062 5.55 6.066-.25 6.066-5.55 6.066-5.55-6.078 1.416-12.13 0-12.13 0zm11.362 1.108s-.67 1.96-5.05 1.96c-3.506 0-5.39-1.165-5.608-1.96 0 0 5.912 1.055 10.658 0zM11.804 1.01C5.61 1.01.978 6.034.978 12.23s4.826 10.76 11.02 10.76S23.02 18.424 23.02 12.23c0-6.197-5.02-11.22-11.216-11.22zM12 21.355c-5.273 0-9.38-3.886-9.38-9.16 0-5.272 3.94-9.547 9.214-9.547a9.548 9.548 0 0 1 9.548 9.548c0 5.272-4.11 9.16-9.382 9.16zm3.108-9.75c.795 0 1.44-.88 1.44-1.963s-.645-1.96-1.44-1.96c-.795 0-1.44.878-1.44 1.96s.645 1.963 1.44 1.963z" fill="#7d8489"></path>
                                                    </svg>
                                                </div>
                                                <input name="input" placeholder="Mensaje" disabled="disabled" class="input-msg">
                                                <div class="photo">
                                                    <i class="fas fa-camera"></i>
                                                </div>
                                                <button class="send" style="cursor: initial;">
                                                    <div class="circle">
                                                        <i class="fas fa-paper-plane ml-0 mr-1 text-white"></i>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div> --}}
<div id="erroresEdit">
    @component('sistema/div-errores')
    @endcomponent
</div>

<div class="container-fluid main-container">
    <!-- Stepper -->
    <div class="stepper">
        <div class="stepper-item active" data-step="1">
            <div class="stepper-number">1</div>
            <div class="stepper-text">Configuración</div>
        </div>
        <div class="stepper-item" data-step="2">
            <div class="stepper-number">2</div>
            <div class="stepper-text">Contenido</div>
        </div>
        <div class="stepper-item" data-step="3">
            <div class="stepper-number">3</div>
            <div class="stepper-text">Destinatarios</div>
        </div>
        <div class="stepper-item" data-step="4">
            <div class="stepper-number">4</div>
            <div class="stepper-text">Confirmar</div>
        </div>
    </div>

    <div class="row">
        <!-- Form Section -->
        <div class="col-lg-8">
            <!-- Step 1: Configuración -->
            <div class="step-content active" id="stepEdit-1">
                <div class="card campaign-card">
                    <div class="card-header card-header-custom">
                        <h5 class="text-white">
                            <i class="bi bi-gear-fill text-white fs-2"></i> Datos Generales
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="campaignNameEdit" class="form-label required fw-semibold">
                                    Nombre de la Campaña
                                </label>
                                <input type="text" required name="nombre" value="{{ $campana?->nombre ?? '' }}" class="form-control" id="campaignNameEdit"
                                    placeholder="Ej: Promoción Black Friday {{ date('Y') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="selectCategoriaEditar" class="form-label fw-semibold required">Categoría</label>
                                <select class="form-select" name="categoria" data-control="select2"
                                    data-dropdown-parent="body" data-placeholder="Categoría" data-allow-clear="true"
                                    required id="selectCategoriaEditar" data-hide-search="true">
                                    <option value=""></option>
                                    @foreach ($categorias as $item)
                                        <option value="{{ $item?->codigo }}" {{ $item?->codigo == $campana?->categoria ? 'selected' : '' }}>{{ $item?->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="campaignDescriptionEdit" class="form-label fw-semibold">Descripción
                                    (Opcional)</label>
                                <textarea class="form-control" id="campaignDescriptionEdit" name="descripcion" rows="3"
                                    placeholder="Describe brevemente el objetivo de esta campaña...">{{$campana?->descripcion ?? ''}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card campaign-card">
                    <div class="card-header card-header-custom">
                        <h5 class="text-white"><i class="bi bi-clock-fill fs-2 text-white"></i> Configuración de Envío
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="estado" id="sendNowEdit"
                                        value="1">
                                    <label class="form-check-label fw-semibold" for="sendNowEdit">
                                        <i class="las la-paper-plane me-2 fs-3 text-success"></i>Enviar
                                        Ahora
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="estado" id="sendScheduledEdit"
                                        value="2" checked>
                                    <label class="form-check-label fw-semibold" for="sendScheduledEdit">
                                        <i class="bi bi-calendar-event me-2 text-warning"></i>Programar
                                        Envío
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6" id="scheduledFieldsEdit">
                                <label for="scheduleDateEdit" class="form-label fw-semibold">Fecha</label>
                                <input type="date" placeholder="DD/MM/AAA" value="{{date("Y-m-d", strtotime($campana->fecha_envio))}}" name="fecha" class="form-control"
                                    id="scheduleDateEdit">
                            </div>
                            <div class="col-md-6" id="scheduledTimeFieldEdit">
                                <label for="scheduleTimeEdit" class="form-label fw-semibold">Hora</label>
                                <input type="time" placeholder="00:00" value="{{date("H:i:s", strtotime($campana->fecha_envio))}}" name="hora" class="form-control"
                                    id="scheduleTimeEdit">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Contenido -->
            <div class="step-content" id="stepEdit-2">
                <div class="card campaign-card">
                    <div class="card-header card-header-custom">
                        <h5 class="text-white">
                            <i class="bi bi-chat-text-fill fs-2 text-white"></i>
                            Contenido del Mensaje
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="selectPlantillaEditar" class="form-label required fw-semibold">Texto
                                    del Mensaje</label>
                                <select name="id_plantilla" id="selectPlantillaEditar" class="form-control"
                                    data-control="select2" data-placeholder="Plantilla" required
                                    data-dropdown-parent="body" data-allow-clear="true">
                                    <option></option>
                                    @foreach ($plantillas as $plantilla)
                                        <option value="{{ $plantilla['id'] }}" {{ $campana?->id_plantilla == $plantilla['id'] ? 'selected' : '' }}>
                                            {{ $plantilla['name'] . ' - ' . $plantilla['BODY']['text'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="row mb-3 d-none" id="divInputFileEditar">
                                    <div class="col-lg-8 col-md-8">
                                        <input type="file" class="form-control" id="inputFileEditar" accept="image/png"
                                            name="archivo">
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="checkUsarRecursoEditar" />
                                            <label class="form-check-label" for="checkUsarRecursoEditar">
                                                Usar recurso de META
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="separator separator-dashed separator-content border-primary my-15 d-none seccionEncabezadoEditar">
                                <span class="h4 text-primary">Variable dinámicas del encabezado</span>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="row mb-3 d-none seccionEncabezadoEditar">
                                    <input type="text" name="header_text"
                                        placeholder="Ingrese valor de variable del encabezado" class="form-control">
                                </div>
                            </div>
                            <div class="separator separator-dashed separator-content border-primary my-15 d-none"
                                id="divTituloVariableEditar">
                                <span class="h4 text-primary">Variables dinámicas del contenido</span>
                            </div>
                            <div class="row mb-7" id="divVariablesEditar">
                            </div>
                            <div class="row mb-7" id="divUrlEditar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Destinatarios -->
            <div class="step-content" id="stepEdit-3">
                <div class="card campaign-card">
                    <div class="card-header card-header-custom">
                        <h5 class="text-white">
                            <i class="bi bi-people-fill fs-2 text-white"></i>
                            Selección de Destinatarios
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <select name="etiquetas" id="selectEtiquetaEditar" class="form-control"
                                    data-control="select2" data-placeholder="Etiquetas" data-allow-clear="true"
                                    required data-dropdown-parent="body">
                                    <option></option>
                                    @foreach ($etiquetas as $etiqueta)
                                        <option value="{{ $etiqueta->id }}" {{ $campana?->cod_etiqueta == $etiqueta->id ? 'selected' : '' }}>{{ $etiqueta->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-md-3">
                                                    <button class="btn btn-secondary-custom w-100">
                                                        <i class="bi bi-funnel-fill me-2"></i>Filtros Avanzados
                                                    </button>
                                                </div> --}}
                        </div>

                        <div class="contacts-table">
                            <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto tablasScroll" data-kt-scroll="true"
                                data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                                data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header"
                                data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body"
                                data-kt-scroll-offset="5px" style="max-height: 410px;">
                                <div class="table-responsive m-3">
                                    <table class="table table-hover" id="tablaContactosEditar">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center all">
                                                    <div class="form-check text-center">
                                                        <input class="form-check-input checkSeleccionarTodos"
                                                            type="checkbox" value="" id="seleccionarTodosEditar"
                                                            checked/>
                                                    </div>
                                                </th>
                                                <th width="10%" class="text-center all">Contacto</th>
                                                <th width="10%" class="text-center all">Telefono</th>
                                                <th width="10%" class="text-center all">Etiqueta</th>
                                                <th width="10%" class="text-center all">Última Interacción</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Confirmar -->
            <div class="step-content" id="stepEdit-4">
                <div class="card campaign-card">
                    <div class="card-header card-header-custom">
                        <h5 class="text-white">
                            <i class="bi bi-check-circle-fill fs-2 text-white"></i>
                            Resumen de la Campaña
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-muted mb-3">INFORMACIÓN GENERAL</h6>
                                <div class="mb-2"><strong>Nombre:</strong> <span id="summaryNameEdit">-</span></div>
                                <div class="mb-2"><strong>Categoría:</strong> <span id="summaryCategoryEdit">-</span>
                                </div>
                                <div class="mb-2"><strong>Tipo de Envío:</strong> <span
                                        id="summarySendTypeEdit">-</span></div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-muted mb-3">DESTINATARIOS</h6>
                                <div class="mb-2"><strong>Total Seleccionados:</strong> <span
                                        id="summaryContactsEdit">0</span></div>
                                <div class="mb-2"><strong>Costo Estimado:</strong> <span
                                        class="text-success fw-bold">$0.00</span></div>
                            </div>
                            <div class="col-12">
                                <h6 class="fw-bold text-muted mb-3">CONTENIDO</h6>
                                <div class="bg-light p-3 rounded" id="summaryMessageEdit">
                                    <em class="text-muted">El mensaje aparecerá aquí...</em>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Importante:</strong> Una vez lanzada la campaña, no podrás modificar el
                    contenido ni detener el envío.
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <button type="button"
                    class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary"
                    id="prevBtnEdit" style="display: none;">
                    <i class="bi bi-arrow-left me-2"></i>Anterior
                </button>
                <div class="ms-auto">
                    <button type="button" class="btn btn-primary-custom text-white" id="nextBtnEdit">
                        Siguiente <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                    <button type="submit" class="btn btn-secondary-custom" id="launchBtnEdit" style="display: none;">
                        <i class="bi bi-rocket-takeoff-fill me-2"></i>Actualizar
                    </button>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="col-lg-4">
            <div class="phone-preview">
                <h6 class="fw-bold mb-3 text-center">
                    <i class="bi bi-phone-fill fs-2 me-2" style="color: var(--primary-color);"></i>
                    Vista Previa
                </h6>
                <div class="phone-mockup">
                    <div class="phone-screen">
                        <div class="phone-header">
                            <div class="contact-avatar">JD</div>
                            <div class="contact-info">
                                <h6 class="text-white">Juan Pérez</h6>
                                <small>en línea</small>
                            </div>
                        </div>
                        <div class="chat-area conversation conversation-container">
                            <div class="message-area" style="background: transparent;">
                                <div class="template-message" id="templatePreviewEdit">
                                    <!-- Template content will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Vista previa aproximada del mensaje
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
