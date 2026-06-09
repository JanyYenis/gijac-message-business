<div class="modal fade" tabindex="-1" id="modalCapturarFoto">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">
                    <!-- <i class="fas fa-camera text-danger fs-2tx"></i> -->
                    Camara
                </h1>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <form id="formCamara" enctype="multipart/form-data">
                    <div class="d-flex justify-content-center">
                        <div class="">
                            <div class="border-gray-300 border-dotted">
                                <video id="video" width="600" height="440" autoplay></video>
                                <canvas id="canvas" width="600" height="440" style="display:none;"></canvas>
                                <img id="photo" alt="Tu foto aparecerá aquí" style="display:none;">
                            </div>
                            <div class="mt-3 d-none" id="mensajeInput">
                                <input type="text" class="form-control form-control-solid" name="mensaje" placeholder="Mensaje">
                                <input type="file" class="form-control form-control-solid d-none" name="archivo">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <button type="button" class="btn btn-primary text-white" id="capture" >
                            <i class="fas fa-camera fs-1 text-white"></i>
                        </button>
                        <button type="button" class="btn btn-primary me-2 d-none text-white" id="captureOtra">Cambiar Foto</button>
                        <button type="submit" class="btn btn-primary d-none text-white" id="enviar" >
                            <i class="far fa-paper-plane fs-1 text-white"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger btnClose" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
