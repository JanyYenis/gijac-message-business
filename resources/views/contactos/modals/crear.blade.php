<div class="modal fade" tabindex="-1" id="modalCrearContactos">
    <form class="form w-lg-550px mx-auto" id="formCrearContacto">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-white">Crear Contacto</h1>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 btnCerrarModal" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2x">
                            <i class="las la-times fs-1 text-white"></i>
                        </span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div id="errores">
                        @component('sistema/div-errores')
                        @endcomponent
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="fv-row mb-10">
                                <label class="form-label required">Nombre</label>
                                <input type="text" class="form-control" name="nombre" placeholder="Nombre"
                                    required />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="fv-row mb-10">
                                <label class="form-label">Apellido</label>
                                <input type="text" class="form-control" name="apellido" placeholder="Apellido" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="fv-row mb-10">
                                <label class="form-label">Genero</label>
                                <select name="genero" id="selectGenero" class="form-control" data-control="select2"
                                    data-placeholder="Genero" data-allow-clear="true" data-hide-search="true"
                                    data-dropdown-parent="body">
                                    <option value=""></option>
                                    @foreach ($generos as $item)
                                        <option value="{{ $item->codigo }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="fv-row mb-10">
                                <label class="form-label">Etiquetas</label>
                                <select name="etiquetas[]" id="selectEtiquetas" class="form-control"
                                    data-control="select2" multiple data-placeholder="Etiquetas" data-allow-clear="true"
                                    data-dropdown-parent="body">
                                    <option></option>
                                    @foreach ($etiquetas as $etiquetas)
                                        <option value="{{ $etiquetas->id }}">{{ $etiquetas->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="fv-row mb-10">
                                <label class="required form-label">Telefono</label>
                                <input type="tel" name="telefono" id="tel" class="form-control" maxlength="15"
                                    placeholder="Ingrese el eléfono" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear</button>
                </div>
            </div>
        </div>
    </form>
</div>
