<div class="menu-item px-3">
    <a href="javascript:;" class="menu-link fs-5 px-3 btnCambiarEstado" data-bs-toggle="modal" data-bs-target="#modalCambiarEstado" data-registro="">
        <i class="las la-cogs text-info fs-4 m-2"></i>
        Cambiar estado
    </a>
</div>

<div class="modal fade" tabindex="-1" id="modalCambiarEstado">
    <form id="formCambiarEstado" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white mulish">Cambiar Estado</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x">
                        <i class="las la-times fs-1 text-white"></i>
                    </span>
                    </div>
                </div>

                <div class="modal-body">
                    <div id="seccionCambiarEstado">
                        <input type="hidden" name="id" value="{{$id}}">
                        <input type="hidden" name="modelo" value="{{$modelo}}">
                        <div class="row mb-7">
                            <label class="required">Estado</label>
                            <select name="" id="">
                                @foreach ($estados as $estado)
                                    <option value="{{$estado->codigo}}">{{$estado->nombre_largo}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btnG btnG-light obalado text-white" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btnG btnG-primary obalado guardar">Cambiar</button>
                </div>
            </div>
        </div>
    </form>
</div>
