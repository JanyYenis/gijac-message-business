<div class="card mb-5">
    <!--begin::Header-->
    <div class="card-header card-header-stretch">
        <!--begin::Title-->
        <div class="card-title">
            <h3 class="text-gijac">Información General</h3>
        </div>
        <!--end::Title-->
        <div class="card-toolbar">
        </div>
    </div>
    <!--end::Header-->

    <!--begin::Body-->
    <div class="card-body m-5">
        
    </div>
    <!--end::Body-->
</div>

<div class="card mb-5">
    <!--begin::Header-->
    <div class="card-header card-header-stretch">
        <!--begin::Title-->
        <div class="card-title">
            <h3 class="text-gijac">API Keys</h3>
        </div>
        <!--end::Title-->
        <div class="card-toolbar">
            <div class="m-5">
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalCrearAPIKey">
                    <i class="fas fa-key fs-2"></i>
                    Generar una API key
                </button>
            </div>
        </div>
    </div>
    <!--end::Header-->

    <!--begin::Body-->
    <div class="card-body m-5">
        <!--begin::Table wrapper-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9" id="kt_api_keys_table">
                <!--begin::Thead-->
                <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                    <tr>
                        <th class="text-center all">#</th>
                        <th class="text-center all">Etiqueta</th>
                        <th class="text-center all">API Keys</th>
                        {{-- <th class="text-center all">Copiar</th> --}}
                        <th class="text-center all">Fecha creación</th>
                        <th class="text-center all">Estado</th>
                        <th class="text-center all">Acciones</th>
                    </tr>
                </thead>
                <!--end::Thead-->

                <!--begin::Tbody-->
                <tbody class="fs-6 fw-semibold text-gray-600"></tbody>
                <!--end::Tbody-->
            </table>
            <!--end::Table-->
        </div>
        <!--end::Table wrapper-->
    </div>
    <!--end::Body-->
</div>

<div class="card mb-5 mb-xxl-10">
    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Heading-->
        <div class="card-title">
            <h3 class="text-gijac">Sesiones de inicio de sesión</h3>
        </div>
        <!--end::Heading-->
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body m-5">
        <!--begin::Table wrapper-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9" id="tablaApiKeyLog">
                <!--begin::Thead-->
                <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                    <tr>
                        <th class="text-center all">#</th>
                        <th class="text-center all">Ubicación</th>
                        <th class="text-center all">Dirección IP</th>
                        <th class="text-center all">Dispositivo</th>
                        <th class="text-center all">Fecha</th>
                        <th class="text-center all">Estado</th>
                    </tr>
                </thead>
                <!--end::Thead-->

                <!--begin::Tbody-->
                <tbody class="fw-6 fw-semibold text-gray-600"></tbody>
                <!--end::Tbody-->
            </table>
            <!--end::Table-->
        </div>
        <!--end::Table wrapper-->
    </div>
    <!--end::Card body-->
</div>
