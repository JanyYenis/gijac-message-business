@php
    $totalMaximoEnvios = $totalMaximoEnvios ?? 0;
    $respuestas = $respuestas ?? 0;
    $soporteTicket = $soporteTicket ?? 0;
    $soporteWhatsapp = $soporteWhatsapp ?? 0;
    $api = $api ?? 0;
    $chatbotsAi = $chatbotsAi ?? 0;
    $chatbot = $chatbot ?? 0;
    $flows = $flows ?? 0;
@endphp
<table class="table align-middle table-striped gy-7">
    <!--begin::Table head-->
    <thead class="align-middle">
        <tr id="kt_pricing">
            <th colspan="3" class="text-center min-w-200px">
                <div class="min-w-200px bg-primary card-rounded py-12 mb-15">
                    <div class="text-white fs-3 fw-bold mb-7 nombrePlan" id="nombrePlan">{{ $nombre ?? 'N/A' }}</div>

                    <div class="fs-5x text-white d-flex justify-content-center align-items-start">
                        <span class="fs-2 mt-3">$</span>

                        <span class="lh-sm fw-semibold valorPlan" id="valorPlan">
                            {{ $valor ?? 0 }}
                        </span>
                    </div>
                </div>
            </th>
        </tr>
    </thead>
    <!--end::Table head-->

    <!--begin::Table body-->
    <tbody>
        <tr>
            <th class="card-rounded-start">
                <div class="fw-bold d-flex align-items-center ps-9 fs-3 textoMaximo" id="textoMaximo">Máximo {{ $totalMaximoEnvios ?? 0 }} contactos activos
                </div>
            </th>

            <td>
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input checkMaximo" checked type="checkbox" value="1" name="maximo_envios"
                        id="checkMaximo" disabled/>
                </div>
            </td>
            <td>
                <input type="number" class="form-control campoValorDetalle" id="campoValorDetalle"
                    placeholder="Valor" name="valor_maximo_envio" value="{{$totalMaximoEnvios ?? 0}}"/>
            </td>

            <td>
                <div class="flex-root d-flex justify-content-center">
                    <span class="h-40px w-40px d-flex flex-center d-block">
                        <i class="fas fa-check fs-2x text-success"></i>
                    </span>
                </div>
            </td>
        </tr>
        <tr>
            <th class="card-rounded-start">
                <div class="fw-bold d-flex align-items-center ps-9 fs-3">Respuestas automáticas</div>
            </th>

            <td colspan="2">
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input checkRespuestas" {{$respuestas ? 'checked' : ''}} type="checkbox" value="1" name="respuesta_automatica"
                        id="checkRespuestas" />
                </div>
            </td>

            <td>
                <div class="flex-root d-flex justify-content-center">
                    <span class="h-40px w-40px d-flex flex-center d-block">
                        <i class="{{$respuestas ? 'fas fa-check text-success' :'las la-times text-danger'}} fs-2x iconoRespuesta" id="iconoRespuesta">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </div>
            </td>
        </tr>
        <tr>
            <th class="card-rounded-start">
                <div class="fw-bold d-flex align-items-center ps-9 fs-3">Soporte vía ticket
                </div>
            </th>

            <td colspan="2">
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input checkTicket" {{$soporteTicket ? 'checked' : ''}} type="checkbox" value="1" name="soporteTicket" id="checkTicket"/>
                </div>
            </td>

            <td>
                <div class="flex-root d-flex justify-content-center">
                    <span class="h-40px w-40px d-flex flex-center d-block">
                        <i class="{{$soporteTicket ? 'fas fa-check text-success' :'las la-times text-danger'}} fs-2x iconoTicket" id="iconoTicket">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </div>
            </td>
        </tr>
        <tr>
            <th class="card-rounded-start">
                <div class="fw-bold d-flex align-items-center ps-9 fs-3">Soporte VIP vía WhatsApp
                </div>
            </th>

            <td colspan="2">
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input checkWhatsapp" {{$soporteWhatsapp ? 'checked' : ''}} type="checkbox" value="1" name="soporteWhatsapp" id="checkWhatsapp"/>
                </div>
            </td>

            <td>
                <div class="flex-root d-flex justify-content-center">
                    <span class="h-40px w-40px d-flex flex-center d-block">
                        <i class="{{$soporteWhatsapp ? 'fas fa-check text-success' :'las la-times text-danger'}} fs-2x iconoWhatsapp" id="iconoWhatsapp">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </div>
            </td>
        </tr>
        <tr>
            <th class="card-rounded-start">
                <div class="fw-bold d-flex align-items-center ps-9 fs-3">Acceso a API
                </div>
            </th>

            <td colspan="2">
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input checkApi" {{$api ? 'checked' : ''}} type="checkbox" value="1" name="api"
                        id="checkApi" />
                </div>
            </td>

            <td>
                <div class="flex-root d-flex justify-content-center">
                    <span class="h-40px w-40px d-flex flex-center d-block">
                        <i class="{{$api ? 'fas fa-check text-success' :'las la-times text-danger'}} fs-2x iconoApi" id="iconoApi">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </div>
            </td>
        </tr>
        <tr>
            <th class="card-rounded-start">
                <div class="fw-bold d-flex align-items-center ps-9 fs-3">Chatbots Conversacionales con Inteligencia Artificial
                </div>
            </th>

            <td colspan="2">
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input checkChatbotsIA" {{$chatbotsAi ? 'checked' : ''}} type="checkbox" value="1" name="chatbotsAi" id="checkChatbotsIA"/>
                </div>
            </td>

            <td>
                <div class="flex-root d-flex justify-content-center">
                    <span class="h-40px w-40px d-flex flex-center d-block">
                        <i class="{{$chatbotsAi ? 'fas fa-check text-success' :'las la-times text-danger'}} fs-2x iconoChatbotsIA" id="iconoChatbotsIA">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            </i>
                    </span>
                </div>
            </td>
        </tr>
        <tr>
            <th class="card-rounded-start">
                <div class="fw-bold d-flex align-items-center ps-9 fs-3">Chatbots Avanzados
                </div>
            </th>

            <td colspan="2">
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input checkChatbots" {{$chatbot ? 'checked' : ''}} type="checkbox" value="1" name="chatbot" id="checkChatbots"/>
                </div>
            </td>

            <td>
                <div class="flex-root d-flex justify-content-center">
                    <span class="h-40px w-40px d-flex flex-center d-block">
                        <i class="{{$chatbot ? 'fas fa-check text-success' :'las la-times text-danger'}} fs-2x iconoChatbots" id="iconoChatbots">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </div>
            </td>
        </tr>
        <tr>
            <th class="card-rounded-start">
                <div class="fw-bold d-flex align-items-center ps-9 fs-3">Integración con Flows de WhatsApp 
                </div>
            </th>

            <td colspan="2">
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input checkFlows" {{$flows ? 'checked' : ''}} type="checkbox" value="1" name="flows" id="checkFlows"/>
                </div>
            </td>

            <td>
                <div class="flex-root d-flex justify-content-center">
                    <span class="h-40px w-40px d-flex flex-center d-block">
                        <i class="{{$flows ? 'fas fa-check text-success' :'las la-times text-danger'}} fs-2x iconoFlows" id="iconoFlows">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </div>
            </td>
        </tr>
    </tbody>
    <!--end::Table body-->
</table>
