<div class="card mb-5">
    <!--begin::Header-->
    <div class="card-header card-header-stretch">
        <!--begin::Title-->
        <div class="card-title">
            <h3 class="text-gijac">{{ __('Información General') }}</h3>
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
            <h3 class="text-gijac">{{ __('API Keys') }}</h3>
        </div>
        <!--end::Title-->
        <div class="card-toolbar">
            <div class="m-5">
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalCrearAPIKey">
                    <i class="fas fa-key fs-2"></i>{{ __('Generar una API key') }}</button>
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
                        <th class="text-center all">{{ __('Etiqueta') }}</th>
                        <th class="text-center all">{{ __('API Keys') }}</th>
                        {{-- <th class="text-center all">{{ __('Copiar') }}</th> --}}
                        <th class="text-center all">{{ __('Fecha creación') }}</th>
                        <th class="text-center all">{{ __('Estado') }}</th>
                        <th class="text-center all">{{ __('Acciones') }}</th>
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
            <h3 class="text-gijac">{{ __('Sesiones de inicio de sesión') }}</h3>
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
                        <th class="text-center all">{{ __('Ubicación') }}</th>
                        <th class="text-center all">{{ __('Dirección IP') }}</th>
                        <th class="text-center all">{{ __('Dispositivo') }}</th>
                        <th class="text-center all">{{ __('Fecha') }}</th>
                        <th class="text-center all">{{ __('Estado') }}</th>
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
