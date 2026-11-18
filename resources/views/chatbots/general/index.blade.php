@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ mix('/css/gmb.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/chatbot-config.css') }}">
@endsection

@section('content')
    <form id="">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h1 class="text-white">
                        <i class="fas fa-robot"></i>
                        {{ __('Configuración del Chatbot') }}
                    </h1>
                    <p class="subtitle mb-0">{{ __('Administra y configura los diferentes tipos de automatización disponibles para tu negocio.') }}</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <span class="badge-available" id="headState">
                        <i class="bi bi-check2-circle me-1 text-white"></i>{{ __('Activo') }}
                    </span>
                    <button type="button" class="btn btn-light-wa" id="btnSaveGeneral">
                        <i class="bi bi-save text-primary fs-1"></i>
                        {{ __('Guardar cambios') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="page">
            <!-- TABS -->
            <nav class="tabs" id="tabs">
                <a href="#sec-general" class="active">
                    <i class="fa-solid fa-sliders"></i>
                    {{ __('General') }}
                </a>
                <a href="#sec-horario">
                    <i class="fa-regular fa-clock"></i>
                    {{ __('Horario') }}
                </a>
                <a href="#sec-palabras">
                    <i class="fa-solid fa-tags"></i>
                    {{ __('Palabras clave') }}
                </a>
                <a href="#sec-mensajes">
                    <i class="fa-regular fa-comment-dots"></i>
                    {{ __('Mensajes') }}
                </a>
                <a href="#sec-flujo">
                    <i class="fa-solid fa-diagram-project"></i>
                    {{ __('Flujo de atención') }}
                </a>
                <a href="#sec-transfer">
                    <i class="fa-solid fa-headset"></i>
                    {{ __('Transferencias') }}
                </a>
                <a href="#sec-avanzado">
                    <i class="fa-solid fa-gears"></i>
                    {{ __('Avanzado') }}
                </a>
            </nav>

            <div class="row g-4">
                <!-- MAIN COLUMN -->
                <div class="col-lg-8">

                    <!-- GENERAL -->
                    <section class="card-s" id="sec-general">
                        <div class="sec-head">
                            <div class="sec-ico">
                                <i class="fa-solid fa-sliders"></i>
                            </div>
                            <div>
                                <div class="sec-title">{{ __('General') }}</div>
                                <div class="sec-sub">
                                    {{ __('Activa o desactiva tu chatbot y define cómo se presenta ante tus clientes.') }}
                                </div>
                            </div>
                        </div>
                        <div class="sub-card d-flex align-items-center justify-content-between gap-3 flex-wrap">
                            <div>
                                <div style="font-weight:600">
                                    {{ __('Chatbot activo') }}
                                </div>
                                <div class="hint">
                                    {{ __('Cuando está desactivado, todas las conversaciones llegan directamente a tus agentes.') }}
                                </div>
                            </div>
                            <label class="sw big">
                                <input type="checkbox" class="cfg" id="botActive" checked value="1" name="activo">
                                <span class="track"></span>
                            </label>
                        </div>
                    </section>

                    <!-- HORARIO -->
                    <section class="card-s" id="sec-horario">
                        <div class="sec-head">
                            <div class="sec-ico">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <div>
                                <div class="sec-title">{{ __('Horario de atención') }}</div>
                                <div class="sec-sub">
                                    {{ __('Define los días y horarios en los que el chatbot estará disponible para atender conversaciones.') }}
                                </div>
                            </div>
                        </div>

                        <div class="sub-card mb-3 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                            <div>
                                <div style="font-weight:600">{{ __('Aplicar horario de atención') }}</div>
                                <div class="hint" id="scheduleHint">
                                    {{ __('El chatbot solo atenderá dentro de los días y horas configurados.') }}
                                </div>
                            </div>
                            <label class="sw">
                                <input type="checkbox" class="cfg" id="useSchedule" checked value="1" name="respetar_horario">
                                <span class="track"></span>
                            </label>
                        </div>

                        <div id="scheduleBody">
                            <label class="label">{{ __('Días de atención') }}</label>
                            <div class="days mb-2" id="days"></div>
                            <div class="d-flex gap-2 flex-wrap mb-4">
                                <button type="button" class="quick" data-preset="all">{{ __('Todos los días') }}</button>
                                <button type="button" class="quick" data-preset="work">{{ __('Solo días laborales') }}</button>
                            </div>

                            <label class="label">
                                {{ __('Franjas horarias') }}
                                <i class="fa-regular fa-circle-question tip"
                                    title="{{ __('Puedes definir varios intervalos, por ejemplo mañana y tarde.') }}"></i>
                            </label>
                            <div id="ranges"></div>
                            <button type="button" class="btn btn-ghost btn-sm mt-1" id="addRange">
                                <i class="fa-solid fa-plus me-2"></i>
                                {{ __('Agregar horario') }}
                            </button>
                        </div>
                    </section>

                    <!-- PALABRAS CLAVE -->
                    <section class="card-s" id="sec-palabras">
                        <div class="sec-head">
                            <div class="sec-ico">
                                <i class="fa-solid fa-tags"></i>
                            </div>
                            <div>
                                <div class="sec-title">
                                    {{ __('Palabras clave') }}
                                </div>
                                <div class="sec-sub">
                                    {{ __('Si el usuario utiliza alguna de estas palabras o expresiones, la conversación puede ser transferida automáticamente a un agente humano.') }}
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            <input class="form-control" id="kwInput" placeholder="{{ __('Escribe una palabra o frase...') }}"
                                style="max-width:340px" />
                            <button type="button" class="btn btn-ghost" id="kwAdd">
                                <i class="fa-solid fa-plus me-2"></i>
                                {{ __('Agregar') }}
                            </button>
                        </div>
                        <div class="d-flex gap-2 flex-wrap mb-3" id="kwList"></div>
                        <div class="hint mb-4">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            {{ __('Las palabras clave se pueden detectar independientemente de mayúsculas y minúsculas.') }}
                        </div>

                        <label class="label">{{ __('Acción al detectar una palabra clave') }}</label>
                        <div id="kwAction">
                            <label class="opt sel">
                                <input type="radio" name="kwact" value="1" class="cfg" checked>
                                <span>
                                    <span class="t">{{ __('Transferir inmediatamente a un agente') }}</span>
                                    <span class="d">{{ __('La conversación pasa al primer asesor disponible.') }}</span>
                                </span>
                            </label>
                            <label class="opt">
                                <input type="radio" name="kwact" value="2" class="cfg">
                                <span>
                                    <span class="t">{{ __('Enviar mensaje y transferir') }}</span>
                                    <span class="d">{{ __('Avisa al usuario antes de pasar con un asesor.') }}</span>
                                </span>
                            </label>
                            <label class="opt">
                                <input type="radio" name="kwact" value="3" class="cfg">
                                <span>
                                    <span class="t">{{ __('Preguntar si desea hablar con un agente') }}</span>
                                    <span class="d">{{ __('El usuario confirma antes de la transferencia.') }}</span>
                                </span>
                            </label>
                        </div>

                        <div class="sub-card d-flex align-items-center justify-content-between gap-3 flex-wrap mt-3">
                            <div>
                                <div style="font-weight:600">{{ __('Coincidencia aproximada') }}</div>
                                <div class="hint">
                                    {{ __('Detectar palabras similares o variaciones (por ejemplo "cotizacion" o "cotizar").') }}
                                </div>
                            </div>
                            <label class="sw">
                                <input type="checkbox" class="cfg" id="fuzzy" checked>
                                <span class="track"></span>
                            </label>
                        </div>
                    </section>

                    <!-- MENSAJES -->
                    <section class="card-s" id="sec-mensajes">
                        <div class="sec-head">
                            <div class="sec-ico">
                                <i class="fa-regular fa-comment-dots"></i>
                            </div>
                            <div>
                                <div class="sec-title">{{ __('Mensajes especiales') }}</div>
                                <div class="sec-sub">
                                    {{ __('Configura los mensajes que el chatbot utilizará en situaciones especiales.') }}
                                </div>
                            </div>
                        </div>
                        <div id="msgCards"></div>
                    </section>

                    <!-- FLUJO -->
                    <section class="card-s" id="sec-flujo">
                        <div class="sec-head">
                            <div class="sec-ico">
                                <i class="fa-solid fa-diagram-project"></i>
                            </div>
                            <div>
                                <div class="sec-title">{{ __('Flujo de atención') }}</div>
                                <div class="sec-sub">{{ __('Define qué sistema tendrá prioridad para procesar las conversaciones. Arrastra los elementos para definir el orden de prioridad.') }}</div>
                            </div>
                        </div>
                        <div id="engines"></div>
                        <div class="mt-4">
                            <label class="label">{{ __('Orden actual') }}</label>
                            <div class="flowmap" id="flowmap"></div>
                        </div>
                    </section>

                    <!-- TRANSFERENCIAS -->
                    <section class="card-s" id="sec-transfer">
                        <div class="sec-head">
                            <div class="sec-ico">
                                <i class="fa-solid fa-headset"></i>
                            </div>
                            <div>
                                <div class="sec-title">{{ __('Transferencia a agente') }}</div>
                                <div class="sec-sub">{{ __('Controla cuándo y cómo una conversación pasa del chatbot a una persona de tu equipo.') }}</div>
                            </div>
                        </div>

                        <div class="sub-card d-flex align-items-center justify-content-between gap-3 flex-wrap mb-4">
                            <div>
                                <div style="font-weight:600">{{ __('¿Permitir transferencia a un agente?') }}</div>
                                <div class="hint">
                                    {{ __('Si se desactiva, el chatbot atenderá siempre sin intervención humana.') }}
                                </div>
                            </div>
                            <label class="sw">
                                <input type="checkbox" class="cfg" id="allowTransfer" checked>
                                <span class="track"></span>
                            </label>
                        </div>

                        <label class="label">{{ __('Motivos de transferencia') }}</label>
                        <div class="row g-2 mb-4" id="reasons"></div>

                        <div class="row g-3 align-items-end mb-4">
                            <div class="col-sm-6">
                                <label class="label">{{ __('Tiempo máximo de espera para un agente') }}</label>
                                <div class="input-group" style="max-width:220px">
                                    <input type="number" min="0" max="1440" value="10" class="form-control cfg" id="waitMin" />
                                    <span class="input-group-text"
                                        style="border-radius:0 12px 12px 0;border-color:var(--border);background:#F4F7F8;font-size:13px">{{ __('minutos') }}</span>
                                </div>
                            </div>
                        </div>

                        <label class="label">{{ __('Si ningún agente está disponible') }}</label>
                        <label class="opt sel">
                            <input type="radio" name="noagent" value="1" class="cfg" checked>
                            <span>
                                <span class="t">{{ __('Mantener conversación en espera') }}</span>
                                <span class="d">{{ __('El cliente permanece en cola hasta que alguien responda.') }}</span>
                            </span>
                        </label>
                        <label class="opt">
                            <input type="radio" name="noagent" value="2" class="cfg">
                            <span>
                                <span class="t">{{ __('Enviar mensaje alternativo') }}</span>
                                <span class="d">{{ __('Informa que responderán más tarde.') }}</span>
                            </span>
                        </label>
                        <label class="opt">
                            <input type="radio" name="noagent" value="3" class="cfg">
                            <span>
                                <span class="t">{{ __('Cerrar conversación') }}</span>
                                <span class="d">{{ __('Finaliza la atención y registra el caso.') }}</span>
                            </span>
                        </label>
                    </section>

                    <!-- AVANZADO -->
                    <section class="card-s" id="sec-avanzado">
                        <div class="sec-head">
                            <div class="sec-ico"><i class="fa-solid fa-circle-question"></i></div>
                            <div>
                                <div class="sec-title">{{ __('Respuestas no resueltas') }}</div>
                                <div class="sec-sub">{{ __('Define qué debe hacer el chatbot cuando no encuentra información suficiente para responder.') }}</div>
                            </div>
                        </div>
                        <label class="opt">
                            <input type="radio" name="unres" value="1" class="cfg">
                            <span>
                                <span class="t">{{ __('Intentar nuevamente') }}</span>
                                <span class="d">{{ __('Reformula la pregunta al usuario una vez más.') }}</span>
                            </span>
                        </label>
                        <label class="opt sel">
                            <input type="radio" name="unres" value="2" class="cfg" checked>
                            <span>
                                <span class="t">{{ __('Transferir a un agente') }}</span>
                                <span class="d">{{ __('Pasa la conversación a una persona del equipo.') }}</span>
                            </span>
                        </label>
                        <label class="opt">
                            <input type="radio" name="unres" value="3" class="cfg">
                            <span>
                                <span class="t">{{ __('Ejecutar el siguiente motor disponible') }}</span>
                                <span class="d">{{ __('Continúa con el siguiente sistema del flujo de atención.') }}</span>
                            </span>
                        </label>
                        <label class="opt">
                            <input type="radio" name="unres" value="4" class="cfg">
                            <span>
                                <span class="t">{{ __('Enviar un mensaje personalizado') }}</span>
                                <span class="d">{{ __('Responde con el texto que definas abajo.') }}</span>
                            </span>
                        </label>
                        <div class="mt-3">
                            <label class="label">{{ __('Mensaje cuando no se puede resolver la solicitud') }}</label>
                            <textarea class="form-control cfg" id="unresMsg" rows="3">{{ __('No estoy seguro de poder ayudarte con esta solicitud. Permíteme comunicarte con uno de nuestros asesores.') }}</textarea>
                        </div>
                    </section>

                    <section class="card-s">
                        <div class="sec-head">
                            <div class="sec-ico">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <div>
                                <div class="sec-title">{{ __('Conversación y contexto') }}</div>
                                <div class="sec-sub">{{ __('Controla cuánto recuerda el chatbot y cuándo empieza una conversación nueva.') }}</div>
                            </div>
                        </div>
                        <div class="sub-card d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                            <div>
                                <div style="font-weight:600">{{ __('Mantener contexto') }}</div>
                                <div class="hint">
                                    {{ __('El chatbot recuerda lo que se habló antes dentro de la misma conversación.') }}
                                </div>
                            </div>
                            <label class="sw">
                                <input type="checkbox" class="cfg" id="keepCtx" checked>
                                <span class="track"></span>
                            </label>
                        </div>
                        <div class="mb-3">
                            <label class="label">{{ __('Tiempo de sesión') }}</label>
                            <div class="input-group" style="max-width:220px">
                                <input type="number" min="1" value="30" class="form-control cfg" id="sessionMin" />
                                <span class="input-group-text" style="border-radius:0 12px 12px 0;border-color:var(--border);
                                    background:#F4F7F8;font-size:13px">{{ __('minutos') }}</span>
                            </div>
                            <div class="hint">
                                {{ __('Después de este tiempo sin actividad, una nueva interacción se considerará una nueva conversación.') }}
                            </div>
                        </div>
                        <label class="label">{{ __('Reiniciar conversación') }}</label>
                        <label class="opt sel">
                            <input type="radio" name="reset" value="1" class="cfg" checked>
                            <span>
                                <span class="t">{{ __('Cierto tiempo de inactividad') }}</span>
                            </span>
                        </label>
                        <label class="opt">
                            <input type="radio" name="reset" value="2" class="cfg">
                            <span>
                                <span class="t">{{ __('Transferencia a agente') }}</span>
                            </span>
                        </label>
                        <label class="opt">
                            <input type="radio" name="reset" value="3" class="cfg">
                            <span>
                                <span class="t">{{ __('Finalización del flujo') }}</span>
                            </span>
                        </label>
                    </section>

                    <section class="card-s">
                        <div class="sec-head">
                            <div class="sec-ico">
                                <i class="fa-solid fa-wand-magic"></i>
                            </div>
                            <div>
                                <div class="sec-title">{{ __('Comportamiento de respuesta') }}</div>
                                <div class="sec-sub">{{ __('Haz que las respuestas se sientan naturales y humanas.') }}</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="label d-flex justify-content-between">
                                {{ __('Tiempo de espera antes de responder') }}
                                <span class="counter" id="delayVal">2 s</span>
                            </label>
                            <input type="range" min="0" max="10" step="1" value="2" class="cfg" id="delay" />
                            <div class="d-flex justify-content-between hint">
                                <span>0 s</span>
                                <span>10 s</span>
                            </div>
                            <div class="hint">{{ __('Simula un tiempo natural de respuesta.') }}</div>
                        </div>
                        <div class="sub-card d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                            <div>
                                <div style="font-weight:600">{{ __('Mostrar indicador de escritura') }}</div>
                                <div class="hint">{{ __('El cliente verá "escribiendo..." antes de recibir la respuesta.') }}</div>
                            </div>
                            <label class="sw">
                                <input type="checkbox" class="cfg" id="typing" checked>
                                <span class="track"></span>
                            </label>
                        </div>
                        <div>
                            <label class="label">{{ __('Mensajes consecutivos') }}</label>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span style="font-size:13.5px">{{ __('Esperar hasta') }}</span>
                                <input type="number" min="0" max="30" value="3" class="form-control cfg" id="groupSec" style="max-width:90px" />
                                <span style="font-size:13.5px">
                                    {{ __('segundos para agrupar mensajes consecutivos del usuario.') }}
                                </span>
                            </div>
                            <div class="hint">
                                {{ __('Si el cliente escribe "Hola", "Quiero información" y "Sobre el precio", se tratan como una sola consulta.') }}
                            </div>
                        </div>
                    </section>
                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    <div class="side">
                        <div class="status-card">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <div style="font-size:12px;opacity:.75;text-transform:uppercase;letter-spacing:.08em">
                                        {{ __('Estado del chatbot') }}
                                    </div>
                                    <div class="display-font d-flex align-items-center gap-2 mt-1" style="font-size:19px"
                                        id="sideState">
                                        <span class="dot" style="color:#4ADE80"></span>
                                        {{ __('Activo') }}
                                    </div>
                                </div>
                                <label class="sw big">
                                    <input type="checkbox" id="botActiveSide" checked>
                                    <span class="track"></span>
                                </label>
                            </div>
                            <div class="row-i">
                                <span class="k">{{ __('Horario') }}</span>
                                <span class="v" id="sHorario">
                                    {{ __('Lun - Vie') }}<br>08:00 - 18:00
                                </span>
                            </div>
                            <div class="row-i">
                                <span class="k">{{ __('Transferencia') }}</span>
                                <span class="v" id="sTransfer">
                                    {{ __('Activada') }}
                                </span>
                            </div>
                            <div class="row-i">
                                <span class="k">{{ __('Palabras clave') }}</span>
                                <span class="v" id="sKw">
                                    {{ __('5 configuradas') }}
                                </span>
                            </div>
                            <div class="row-i">
                                <span class="k">{{ __('Motor principal') }}</span>
                                <span class="v" id="sEngine">
                                    {{ __('Chatbot por Nodos') }}
                                </span>
                            </div>
                            <div class="row-i">
                                <span class="k">{{ __('IA') }}</span>
                                <span class="v" id="sAI">
                                    {{ __('Activada') }}
                                </span>
                            </div>
                            <div class="row-i">
                                <span class="k">{{ __('Automatización') }}</span>
                                <span class="v">n8n</span>
                            </div>
                        </div>

                        <div class="card-s">
                            <div class="sec-title mb-3" style="font-size:15px">{{ __('Acciones rápidas') }}</div>
                            <button type="button" class="qa" id="qaTest">
                                <i class="fa-solid fa-comment-dots"></i>
                                {{ __('Probar conversación') }}
                            </button>
                            <button type="button" class="qa" data-goto="#sec-horario">
                                <i class="fa-regular fa-clock"></i>
                                {{ __('Editar horario') }}
                            </button>
                            <button type="button" class="qa" data-goto="#sec-palabras">
                                <i class="fa-solid fa-tags"></i>
                                {{ __('Palabras clave') }}
                            </button>
                            <button type="button" class="qa" data-goto="#sec-flujo">
                                <i class="fa-solid fa-diagram-project"></i>
                                {{ __('Prioridad de atención') }}
                            </button>
                            <button type="button" class="qa" id="qaCopy">
                                <i class="fa-solid fa-clone"></i>
                                {{ __('Duplicar configuración') }}
                            </button>
                        </div>

                        <div class="card-s">
                            <div class="sec-title mb-2" style="font-size:15px">{{ __('Resumen de configuración') }}</div>
                            <div class="hint mb-3">{{ __('Vista rápida de cómo quedará tu chatbot.') }}</div>
                            <div id="summary"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SAVE BAR -->
        <div class="savebar" id="savebar">
            <div class="warn">
                <i class="fa-solid fa-triangle-exclamation"></i>
                {{ __('Hay cambios sin guardar') }}
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-discard" id="btnDiscard">
                    {{ __('Descartar cambios') }}
                </button>
                <button type="button" class="btn btn-primary-g" id="btnSave">
                    {{ __('Guardar cambios') }}
                </button>
            </div>
        </div>

        <div class="toast-w" id="toasts"></div>
    </form>
@endsection

@section('modal')
@endsection

@section('scripts')
    <script>
        window.configChatbot = @json($configChatbot);
    </script>
    <script src="{{ asset('js/chatbots/config.js') }}"></script>
@endsection
