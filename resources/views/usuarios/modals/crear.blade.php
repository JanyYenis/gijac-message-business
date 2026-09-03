<div class="modal fade" tabindex="-1" id="modalCrearUsuario">
    <form id="formCrearUsuario" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content ">
                <div class="modal-header">
                    <h1 class="modal-title text-white">{{ __('Crear Usuario') }}</h1>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal" data-bs-dismiss="modal" aria-label="{{ __('Close') }}">
                        <span class="svg-icon svg-icon-2x">
                            <i class="las la-times fs-1 text-white"></i>
                        </span>
                    </div>
                </div>

                <div class="modal-body scroll px-5">
                    <div id="errores">
                        @component('sistema/div-errores')
                        @endcomponent
                    </div>
                    <div class="row mb-7">
                        <div class="col-lg-6 col-md-6">
                            <label class="required fs-5">{{ __('Nombre') }}</label>
                            <input type="text" name="nombre" class="form-control" placeholder="{{ __('Ingrese el nombre') }}" required>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label class="required fs-5">{{ __('Apellido') }}</label>
                            <input type="text" name="apellido" class="form-control" placeholder="{{ __('Ingrese el apellido') }}" required>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <div class="col-lg-6 col-md-6">
                            <label class="required fs-5">{{ __('Tipo Identificación') }}</label>
                            <select name="tipo_identificacion" id="selectTipoIdentificacion" data-control="select2"
                                data-placeholder="{{ __('Seleccione el tipo de identificación') }}" data-allow-clear="true"
                                data-hide-search="true" class="form-control" required data-dropdown-parent="body">
                                <option value=""></option>
                                @foreach ($tiposDocumentos as $item)
                                    <option value="{{$item->codigo}}">{{$item->nombre_corto}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label class="required fs-5">{{ __('Identificación') }}</label>
                            <input type="text" name="identificacion" class="form-control" placeholder="{{ __('Ingrese la identificación') }}" required>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <div class="col-lg-6 col-md-6">
                            <label class="required fs-5">{{ __('Genero') }}</label>
                            <select name="genero" id="selectGenero" data-control="select2"
                                data-placeholder="{{ __('Seleccione el genero') }}" data-allow-clear="true" data-hide-search="true"
                                class="form-control" required data-dropdown-parent="body">
                                <option value=""></option>
                                @foreach ($generos as $item)
                                    <option value="{{$item->codigo}}">{{$item->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-lg-6 col-md-6">
                            <label class="required">{{ __('Telefono') }}</label>
                            <input class="form-control" required placeholder="{{ __('Telefono') }}" name="telefono"/>
                        </div> --}}
                    </div>
                    <div class="row mb-7">
                        <div class="col-lg-6 col-md-6">
                            <label class="required fs-5">{{ __('País') }}</label>
                            <select class="form-control" name="pais_id" placeholder="{{ __('...') }}" id="selectPais" required
                                data-dropdown-parent="#modalCrearUsuario">
                                <option value="">{{ __('Seleccione un país') }}</option>
                                @foreach ($paises as $pais)
                                    <option value="{{$pais->id}}" data-kt-select2-country="{{$pais->bandera}}">{{$pais->nombre}} - {{$pais->nombre_corto}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label class="required fs-5">{{ __('Ciudad') }}</label>
                            <select name="cod_ciudad" id="selectCiudad" disabled data-control="select2"
                                data-placeholder="{{ __('Seleccione una ciudad') }}" data-allow-clear="true" class="form-control"
                                required data-dropdown-parent="#modalCrearUsuario">
                            </select>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <div class="col-lg-6 col-md-6">
                            <label class="required fs-5">{{ __('Email') }}</label>
                            <input type="text" class="form-control" name="email" placeholder="Email" id="inputEmail" required/>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label class="required fs-5">{{ __('Telefono') }}</label><br>
                            <input type="tel" name="telefono" id="tel" class="form-control" maxlength="15" placeholder="{{ __('Ingrese el teléfono') }}" required data-default-country="co">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Crear') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
