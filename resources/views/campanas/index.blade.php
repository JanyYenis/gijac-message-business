@extends('layouts.index')

@section('tutorial')
    @component('layouts.componentes.tutorial')
    @endcomponent
@endsection

@section('css')
    <link rel="stylesheet" href="{{ mix('css/campanas.css') }}">
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h1 class="text-white">
                    <i class="bi bi-megaphone-fill fs-2 text-white"></i>
                    {{ __('Mis Campañas') }}
                </h1>
                <p class="subtitle mb-0">{{ __('Configura y lanza tu campaña de WhatsApp Business') }}</p>
            </div>
            <div class="mt-3 mt-md-0">
                @can('campana.crear')
                    @if (servicioPlan('analisis.ia'))
                        <button type="button" class="btn btn-new-template" id="abrirModalPredictivo">
                            <i class="fas fa-rocket"></i>
                            {{ __('Análisis Predictivo') }}
                        </button>
                    @endif
                    <button type="button" class="btn btn-new-template" id="btnCrearCampana" data-bs-toggle="modal"
                        data-bs-target="#modalCrearCampana">
                        <i class="fas fa-plus fs-1"></i>
                        {{ __('Crear Campaña') }}
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="toolbar" id="sectionToolbar">
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input class="form-control" id="q" placeholder="{{ __('Buscar campañas...') }}" />
            </div>
            <div style="max-width: 170px !important;">
                <select class="form-select" id="fType" data-control="select2"
                    data-placeholder="{{ __('Tipo de plantilla') }}" data-allow-clear="true" data-hide-search="true">
                    <option value=""></option>
                    @foreach ($tipos as $tipo)
                        <option value="{{ $tipo->codigo }}">{{ __($tipo->nombre) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ms-auto viewtoggle">
                <button type="button" class="active" id="btnTabListadoTarjetas">
                    <i class="fa-solid fa-grip"></i>
                    {{ __('Tarjetas') }}
                </button>
                <button type="button" id="toTable">
                    <i class="fa-solid fa-list"></i>
                    {{ __('Tabla') }}
                </button>
            </div>
        </div>
        <div class="chips" id="quickChips">
            <span class="chip active" data-quick="10">{{ __('Todas') }}</span>
            @foreach ($estados as $estado)
                <span class="chip" data-quick="{{ $estado->codigo }}">{{ __($estado->nombre) }}</span>
            @endforeach
        </div>
    </div>

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="contenedor" id="kt_content_container">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 mb-10 mb-lg-0">
                    <div class="card card-flush">
                        <div class="card-body pt-5" id="kt_chat_contacts_body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade" id="tabListadoCampanasTabla"
                                            role="tabpanel">
                                            <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto tablasScroll"
                                                data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                                                data-kt-scroll-max-height="auto"
                                                data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header"
                                                data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body"
                                                data-kt-scroll-offset="5px" style="max-height: 410px;">
                                                <div class="table-responsive">
                                                    <table border="1" class="table table-striped table-bordered"
                                                        id="tablaCampanas">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%" class="text-center all">#</th>
                                                                <th width="10%" class="text-center all">
                                                                    {{ __('Nombre') }}</th>
                                                                <th width="10%" class="text-center all">
                                                                    {{ __('Descripción') }}</th>
                                                                <th width="10%" class="text-center all">
                                                                    {{ __('Enviado por') }}</th>
                                                                <th width="10%" class="text-center all">
                                                                    {{ __('Estado') }}</th>
                                                                <th width="10%" class="text-center all">
                                                                    {{ __('Fecha envio') }}</th>
                                                                <th width="10%" class="text-center none">
                                                                    {{ __('Fecha creación') }}</th>
                                                                <th width="10%" class="text-center none">
                                                                    {{ __('Plantilla') }}</th>
                                                                <th width="10%" class="text-center none">
                                                                    {{ __('Tipo') }}</th>
                                                                <th width="10%" class="text-center all">
                                                                    {{ __('Acciones') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade show active" id="tabListadoCampanasTarjeta" role="tabpanel">
                                            <div class="seccionListadoCampanas"></div>
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
@endsection

@section('modal')
    @can('campana.crear')
        @component('campanas.modals.crear')
            @slot('etiquetas', $etiquetas)
            @slot('plantillas', $plantillas)
            @slot('numeroTel', $numeroTel)
            @slot('categorias', $categorias)
        @endcomponent

        @component('campanas.modals.prediccion')
        @endcomponent
    @endcan

    @component('campanas.modals.detalle')
    @endcomponent

    @can('campana.editar')
        @component('campanas.modals.modal')
        @endcomponent
    @endcan

    @component('campanas.modals.modal-respuesta')
    @endcomponent
    @component('sistema.modales.modal-errores')
    @endcomponent
    @component('campanas.modals.modal-links')
    @endcomponent
@endsection

@section('scripts')
    <script src="{{ mix('/js/campanas/principal.js') }}"></script>
    <script src="{{ mix('/js/campanas/prediccion.js') }}"></script>
@endsection
