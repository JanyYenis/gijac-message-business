@php
    $disabled = '';
    $readonly = '';
    if (!can('tickets.asignar.responsable')) {
        $disabled = 'disabled';
        $readonly = 'readonly';
    }
@endphp
@extends('layouts.index', ['titulo' => 'Ticktes'])

@section('content')
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-xl-row p-7">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid me-xl-15 mb-20 mb-xl-0">
                    <!--begin::Ticket view-->
                    <div class="mb-0">
                        <!--begin::Heading-->
                        <div class="d-flex align-items-center mb-12">
                            <!--begin::Icon-->
                            <i class="fas fa-file-medical fs-4qx text-success ms-n2 me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <!--end::Icon-->

                            <!--begin::Content-->
                            <div class="d-flex flex-column">
                                <!--begin::Title-->
                                <h1 class="text-gray-800 fw-semibold">{{ $ticket?->titulo }}</h1>
                                <!--end::Title-->

                                <!--begin::Info-->
                                <div class="">
                                    <!--begin::Label-->
                                    <span class="fw-semibold text-muted me-6">Usuario:
                                        <a href="#" class="text-muted text-hover-primary">{{ $ticket?->cliente?->nombre_completo ?? 'N/A' }}</a></span>
                                    <!--end::Label-->

                                    <!--begin::Label-->
                                    <span class="fw-semibold text-muted">Fecha hallazgo:
                                        <span class="fw-bold text-gray-600 me-1">
                                            {{ $ticket->fecha_hallazgo->format('d/m/Y') }}
                                        </span>
                                    </span>
                                    <!--end::Label-->
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Heading-->

                        <!--begin::Details-->
                        <div class="mb-15">
                            <form id="formEditarTicket">
                                <input type="hidden" name="id" value="{{ $ticket->id }}">
                                @if ($ticket?->url_archivo)
                                    <b>Archivo de complemento:</b>
                                    <a href="{{ asset('storage/'.$ticket->url_archivo) }}" target="_blank">Archivo</a>
                                @endif
                                <!--begin::Description-->
                                <div class="mb-15 fs-5 fw-normal text-gray-800">
                                    <!--begin::Text-->
                                    <div class="mb-5 fs-5">
                                        <label class="required form-label">Descripcion:</label>
                                        {{-- <textarea required name="descripcion" class="form-control" cols="30" rows="3">{{ $ticket?->descripcion ?? 'N/A' }}</textarea> --}}
                                        <div id="divDescripcion1" class="form-control form-control-solid fs-4">{!! $ticket?->descripcion ?? '' !!}</div>
                                    </div>
                                    <!--end::Text-->
                                </div>
                                <!--end::Description-->

                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Col-->
                                    <div class="col-md-3 fv-row mb-3">
                                        <label class="fs-6 fw-semibold mb-2">Responsable</label>

                                        <select class="form-select form-select-solid" {{ $disabled }} {{ $readonly }} name="cod_responsable" id="selectResponsable" required data-control="select2" data-placeholder="Seleccione el responsable">
                                            <option value=""></option>
                                            @foreach ($responsables as $item)
                                                <option value="{{ $item->id }}" {{ $item?->id == $ticket?->cod_responsable ? 'selected' : '' }}>{{ $item->text }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-3 fv-row mb-3">
                                        <label class="fs-6 fw-semibold mb-2">Tipo</label>

                                        <select class="form-select form-select-solid" {{ $disabled }} {{ $readonly }} name="tipo" id="selectTipo" required data-control="select2" data-placeholder="Seleccione el tipo" data-hide-search="true">
                                            <option value=""></option>
                                            @foreach ($tipos as $item)
                                                <option value="{{ $item->codigo }}" {{ $ticket?->tipo == $item?->codigo ? 'selected' : '' }}>{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div class="col-sm-3 fv-row mb-3">
                                        <label class="fs-6 fw-semibold mb-2">Estado</label>

                                        <select class="form-select form-select-solid" {{ $disabled }} {{ $readonly }} name="estado" id="selectEstados" required data-control="select2" data-placeholder="Seleccione el estado" data-hide-search="true">
                                            <option value=""></option>
                                            @foreach ($estados as $item)
                                                <option value="{{ $item->codigo }}" {{ $ticket?->estado == $item?->codigo ? 'selected' : '' }}>{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div class="col-sm-3 fv-row mb-3">
                                        <label class="fs-6 fw-semibold mb-2">Priodad</label>

                                        <select class="form-select form-select-solid" {{ $disabled }} {{ $readonly }} name="prioridad" id="selectPrioridad" required data-control="select2" data-placeholder="Seleccione la prioridad" data-hide-search="true">
                                            <option value=""></option>
                                            @foreach ($prioridades as $item)
                                                <option value="{{ $item->codigo }}" {{ $ticket?->prioridad == $item?->codigo ? 'selected' : '' }}>{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <!--begin::Input group-->
                                <div class="mb-0">
                                    <div id="divDescripcion" class="form-control form-control-solid fs-4"></div>

                                    <div class="text-end mt-3">
                                        <!--begin::Submit-->
                                        <button type="submit" class="btn btn-primary">Actualizar</button>
                                        <!--end::Submit-->
                                    </div>
                                </div>
                                <!--end::Input group-->
                            </form>
                        </div>
                        <!--end::Details-->

                        <!--begin::Comments-->
                        <div class="mb-15">
                            <div class="seccionListadoComentarios"></div>
                        </div>
                        <!--end::Comments-->
                    </div>
                    <!--end::Ticket view-->

                </div>
                <!--end::Content-->

                <!--begin::Sidebar-->
                @component('tickets.sider')
                @endcomponent
                <!--end::Sidebar-->
            </div>
            <!--end::Layout-->
        </div>
        <!--end::Card body-->
    </div>
@endsection

@section('modal')
    @component('comentarios.modals.crear')
        @slot('clase', $clase)
        @slot('id', $ticket->id)
    @endcomponent
@endsection

@section('scripts')
    <script>
        window.id_ticket = '{{ $ticket->id }}';
        window.datos = {!! propiedadesModelo($ticket) !!};
    </script>
    <script src="{{ mix('/js/tickets/editar.js') }}"></script>
    <script src="{{ mix('/js/comentarios/principal.js') }}"></script>
@endsection
