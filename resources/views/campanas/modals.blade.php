{{-- <div class="modal fade" tabindex="-1" id="modalCargarCampana">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" style="font-size: 30px;">Cargar contactos de campaña</h5>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="las la-times fs-1 text-white"></i>
                    </span>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <!--begin::Alert-->
                <div id="alertaError" class="alert alert-dismissible bg-light-danger border border-danger border-dotted d-flex flex-column flex-sm-row p-5 mb-10 d-none">
                    <i class="fas fa-exclamation fs-2hx text-danger me-4 mb-5 mb-sm-0">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <div class="d-flex flex-column pe-0 pe-sm-10">
                        <h5 class="mb-1">Información importante.</h5>
                        <span>Los siguientes campos son requeridos para enviar la campaña.</span>
                        <div class="container">
                            <div id="seccionError"></div>
                        </div>
                    </div>
                    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                        <i class="las la-times fs-1 text-danger">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </button>
                </div>
                <!--end::Alert-->
                <!--begin::Alert-->
                <div class="alert alert-dismissible bg-light-primary border border-primary d-flex flex-column flex-sm-row p-5 mb-10">
                    <i class="fas fa-exclamation fs-2hx text-primary me-4 mb-5 mb-sm-0">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <div class="d-flex flex-column pe-0 pe-sm-10">
                        <h5 class="mb-1">Información importante</h5>
                        <span>Por favor, no cambie el orden de los campos que a cargar. Si desea tener un ejemplo de la estructura de los archivos, puede descargarlos dando clic en el botón de Excel que aparece a continuación:</span>
                        <div class="container py-4">
                            <a href="{{ asset('estructuras/estructura-campañas.csv') }}" class="btn btn-success hover-scale">
                                <i class="fas fa-file-csv"></i>
                                CSV
                            </a>
                            <a href="{{ asset('estructuras/estructura-campañas.xlsx') }}" class="btn btn-success hover-scale">
                                <i class="fas fa-file-excel"></i>
                                Excel
                            </a>
                        </div>
                    </div>
                    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                        <i class="las la-times fs-1 text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </button>
                </div>
                <!--end::Alert-->
                <div class="row mb-7">
                    <select name="marca" id="selectMarca1" class="form-control" data-control="select2" data-placeholder="Marca" data-allow-clear="true" required data-dropdown-parent="body">
                        <option></option>
                        @foreach ($marcas as $marca)
                            <option value="{{$marca->id}}">{{$marca->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row mb-7">
                    <select name="material" id="selectMaterial" class="form-control" data-control="select2" data-placeholder="Material" data-allow-clear="true"data-hide-search="true" required>
                        <option></option>
                        <option value="Info">Información</option>
                        <option value="Invi">Invitación</option>
                        <option value="Podcast">Podcast</option>
                    </select>
                </div>
                <div class="row mb-7">
                    <select name="id_plantilla" id="selectPlantilla1" class="form-control" data-control="select2"
                        data-placeholder="Plantilla" required data-dropdown-parent="body" data-allow-clear="true">
                        <option></option>
                        @foreach ($plantillas as $plantilla)
                            <option value="{{$plantilla['id']}}">{{$plantilla['name']." - ".$plantilla['BODY']['text']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group mb-3 d-none" id="divInputFile1">
                    <input type="file" class="form-control" id="inputFile1" accept="image/png" name="archivo">
                </div>
                <div class="row" id="divVariables1">
                </div>
                <div class="form-check py-3">
                    <input class="form-check-input" type="checkbox" value="1" name="encabezado" id="tiene_encabezado" checked disabled/>
                    <label class="form-label" for="tiene_encabezado">
                        Eliminar encabezado
                    </label>
                </div>
                <form id="kt_dropzonejs_example_1" enctype="multipart/form-data" class="dropzone">
                    <div class="fv-row">
                        <div class="dz-message needsclick">
                            <i class="las la-cloud-upload-alt fs-3x text-primary">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="ms-4">
                                <h3 class="fs-5 fw-bold text-gray-900 mb-1">Cargar Archivo Excel</h3>
                                <span class="fs-7 fw-semibold text-gray-400">Maximo 1 Archivos</span>
                            </div>
                        </div><br>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btnG btnG-light obalado text-white" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btnG btnG-primary obalado guardar" id="enviarButton">Cargar</button>
            </div>
        </div>
    </div>
</div> --}}
