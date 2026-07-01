<div class="card  mb-5 mb-xl-10">
    <!--begin::Card body-->
    <div class="card-body">

        <!--begin::Notice-->
        @if ($ultimaTransaccion?->x_response != 'Aceptada' && !$validarFechaVencimiento)
            <div class="notice d-flex bg-light-info rounded border-info border border-dashed mb-12 p-6">
                <i class="fas fa-circle-info fs-2tx text-info me-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>

                <!--begin::Wrapper-->
                <div class="d-flex flex-stack flex-grow-1 ">
                    <!--begin::Content-->
                    <div class=" fw-semibold">
                        <h4 class="text-gray-900 fw-bold">¡Necesitamos su atención!</h4>

                        <div class="fs-6 text-gray-700 ">El estado de tu ultima transaccion fue {{ $ultimaTransaccion?->x_response }}
                            <a href="{{ route('precios') }}" class="fw-bold">consultar plan</a>.
                        </div>
                    </div>
                    <!--end::Content-->

                </div>
                <!--end::Wrapper-->
            </div>
        @endif
        <!--end::Notice-->

        @if ($ultimaFacturaPagada && $plan)
            <!--begin::Row-->
            <div class="row">
                <!--begin::Col-->
                <div class="col-lg-7">
                    <!--begin::Heading-->
                    <h3 class="mb-2">Válido hasta el {{ $ultimaFacturaPagada?->fecha_vencimiento->translatedFormat('j \d\e F \d\e\l Y') }}</h3>
                    <p class="fs-6 text-gray-600 fw-semibold mb-6 mb-lg-15">
                        Le enviaremos una notificación al vencimiento de su suscripción.</p>
                    <!--end::Heading-->

                    <!--begin::Info-->
                    <div class="fs-5 mb-2">
                        <span class="text-gray-800 fw-bold me-1">${{formatoMiles($ultimaFacturaPagada?->value)}}</span>
                        <span class="text-gray-600 fw-semibold">por {{ $ultimaFacturaPagada?->tiempo }} {{ $ultimaFacturaPagada?->tiempo == 1 ? 'mes' : 'meses' }}</span>
                    </div>
                    <!--end::Info-->

                    {{-- <!--begin::Notice-->
                    <div class="fs-6 text-gray-600 fw-semibold">
                        Extended Pro Package. Up to 100 Agents &amp; 25 Projects
                    </div>
                    <!--end::Notice--> --}}
                </div>
                <!--end::Col-->

                <!--begin::Col-->
                <div class="col-lg-5">
                    <!--begin::Heading-->
                    <div class="d-flex text-muted fw-bold fs-5 mb-3">
                        <span class="flex-grow-1 text-gray-800">Contactos</span>
                        @if ($plan?->max_contactos)
                            <span class="text-gray-800">{{ $cantidad_contactos_activos }} de {{ $plan?->max_contactos ?? 'Ilimitado' }} contactos</span>
                        @else
                            <span class="text-gray-800">Ilimitados</span>
                        @endif
                    </div>
                    <!--end::Heading-->

                    <!--begin::Progress-->
                    <div class="progress h-8px bg-light-primary mb-2">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $plan?->max_contactos && $cantidad_contactos_activos ? ($cantidad_contactos_activos / ($plan?->max_contactos ?? 0)) * 100 : 0 }}%" aria-valuenow="{{ $plan?->max_contactos && $cantidad_contactos_activos ? ($cantidad_contactos_activos / ($plan?->max_contactos ?? 0)) * 100 : 0 }}"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <!--end::Progress-->

                    @if ($plan?->max_contactos)
                        <!--begin::Description-->
                        <div class="fs-6 text-gray-600 fw-semibold mb-10">
                            Quedan {{ ($plan?->max_contactos ?? 0) && $cantidad_contactos_activos ? ($plan?->max_contactos ?? 0) - $cantidad_contactos_activos : 0 }} contactos hasta que su plan requiera actualización
                        </div>
                        <!--end::Description-->
                    @endif

                    <!--begin::Action-->
                    <div class="d-flex justify-content-end pb-0 px-0">
                        <a href="javascript:;" class="btn btn-light btn-active-light-primary me-2">
                            Cancelar Subscripción
                        </a>
                        <a type="button" class="btn btn-primary" href="{{ route('precios') }}">Actualizar plan</a>
                    </div>
                    <!--end::Action-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
        @else
            <div class="text-center">
                <h1>No cuenta con un pago realizado y aprobado.</h1>
            </div>
        @endif
    </div>
    <!--end::Card body-->
</div>

<div class="card mb-5 mb-xl-10">
    <div class="card-header card-header-stretch pb-0">
        <div class="card-title">
            <h3 class="m-0">Historial de facturación</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="scroll-y me-n5 pe-5 h-lg-auto tablasScroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header" data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body" data-kt-scroll-offset="5px" style="max-height: 410px;">
            <div class="table-responsive">
                <table class="table table-white" id="tablaFacturas">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center all">#</th>
                            <th width="10%" class="text-center all">Referencia</th>
                            <th width="10%" class="text-center all">Plan</th>
                            <th width="10%" class="text-center all">Banco</th>
                            <th width="10%" class="text-center all">Franquicia</th>
                            <th width="10%" class="text-center all">Valor</th>
                            <th width="10%" class="text-center all">Estado</th>
                            <th width="10%" class="text-center all">Meses</th>
                            <th width="10%" class="text-center all">Fecha de Vencimiento</th>
                            <th width="10%" class="text-center all">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
