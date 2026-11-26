@auth
    @php
        // $pasos y $accesos los entrega el view composer de 'layouts.componentes.guia'
        $definicion = [
            ['bi-buildings', 'Registra tu empresa', 'Registrar mi empresa', route('negocios.index')],
            ['bi-facebook', 'Conecta tu cuenta de Facebook', 'Conectar con Facebook', route('configs.index')],
            ['bi-person-plus', 'Crea tu primer contacto', 'Crear mi primer contacto', route('contactos.index')],
            ['bi-megaphone', 'Envía una campaña de prueba', 'Crear mi primera campaña', route('campanas.index')],
            ['bi-gem', 'Adquiere un plan', 'Conocer nuestros planes', route('precios')],
        ];
        $total       = count($definicion);
        $completados = collect($pasos)->filter()->count();
        $pendientes  = $total - $completados;
        $porcentaje  = (int) round($completados / $total * 100);
        $completo    = $completados === $total;
        $siguiente   = collect($pasos)->search(fn ($v) => ! $v); // índice del primer paso pendiente (false si no hay)
    @endphp

    <div id="onbWidget" class="onb-w" data-onb-widget="" data-onb-w-endpoint="{{ route('guia.estado') }}" data-onb-w-cooldown="30">
        <div class="onb-w-capsule-slot" data-onb-w="capsule">
            <button type="button" class="btn onb-w-capsule {{ $completo ? 'is-complete' : '' }}" data-onb-w-toggle=""
                aria-expanded="false" aria-controls="onbWidgetPanel"
                aria-label="Tu configuración: {{ $completados }} de {{ $total }} pasos completados. Abrir panel de configuración">
                <span class="onb-w-orb {{ $completo ? 'is-complete' : '' }}" aria-hidden="true"
                    style="--onb-w-percent: {{ $porcentaje }};">
                    <i class="bi {{ $completo ? 'bi-trophy-fill' : 'bi-rocket-takeoff-fill' }}"></i>
                </span>
                <span class="onb-w-capsule-body">
                    <span class="onb-w-capsule-title">{{ $completo ? '¡Configuración completa!' : 'Tu configuración' }}</span>
                    <span class="onb-w-progress-mini" aria-hidden="true">
                        <span class="onb-w-progress-mini-bar" style="width: {{ $porcentaje }}%"></span>
                    </span>
                </span>
                <span class="onb-w-count {{ $completo ? 'is-complete' : '' }}">{{ $completados }}/{{ $total }}</span>
                <i class="bi bi-chevron-up onb-w-caret" aria-hidden="true"></i>
            </button>
        </div>

        <div class="onb-w-panel-slot" data-onb-w="panel">
            <section id="onbWidgetPanel" class="onb-w-panel" role="region"
                aria-label="Progreso de la configuración inicial" data-onb-w-panel="" tabindex="-1">
                <button type="button" class="btn onb-w-close" data-onb-w-close=""
                    aria-label="Cerrar panel de configuración">
                    <i class="bi bi-x-lg" aria-hidden="true" style="position: relative; right: 0.5rem; top: -0.1rem;"></i>
                </button>

                @if ($completo)
                    <header class="onb-w-head is-done-head">
                        <div class="onb-w-done-badge" aria-hidden="true"><i class="bi bi-trophy-fill"></i></div>
                        <h2 class="onb-w-title is-done">¡Excelente! Tu empresa ya está configurada</h2>
                        <p class="onb-w-sub">Has completado los {{ $total }} pasos de configuración inicial de GIJAC MESSAGE BUSINESS</p>
                        <div class="onb-w-meta">
                            <span class="onb-w-meta-item">
                                <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                {{ $total }} de {{ $total }} pasos completados
                            </span>
                            <span class="onb-w-meta-item onb-w-meta-percent">100%</span>
                        </div>
                        <div class="progress onb-w-progress" role="progressbar" aria-label="Progreso de configuración"
                            aria-valuemin="0" aria-valuemax="{{ $total }}" aria-valuenow="{{ $total }}">
                            <div class="progress-bar onb-w-progress-bar is-complete" style="width: 100%"></div>
                        </div>
                        <div class="onb-w-done-actions">
                            <a class="btn onb-w-btn-ghost" href="{{ url('/') }}" data-onb-w-nav="">
                                <i class="bi bi-speedometer2 me-1" aria-hidden="true"></i>Ir al panel principal
                            </a>
                        </div>
                    </header>
                @else
                    <header class="onb-w-head">
                        <h2 class="onb-w-title">Continúa configurando tu cuenta</h2>
                        <p class="onb-w-sub">Completa estos pasos para aprovechar GIJAC MESSAGE BUSINESS</p>
                        <div class="onb-w-meta">
                            <span class="onb-w-meta-item">
                                <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                {{ $completados }} {{ $completados === 1 ? 'completado' : 'completados' }}
                            </span>
                            <span class="onb-w-meta-item">
                                <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                                {{ $pendientes }} {{ $pendientes === 1 ? 'pendiente' : 'pendientes' }}
                            </span>
                            <span class="onb-w-meta-item onb-w-meta-percent">{{ $porcentaje }}%</span>
                        </div>
                        <div class="progress onb-w-progress" role="progressbar" aria-label="Progreso de configuración"
                            aria-valuemin="0" aria-valuemax="{{ $total }}" aria-valuenow="{{ $completados }}">
                            <div class="progress-bar onb-w-progress-bar" style="width: {{ $porcentaje }}%"></div>
                        </div>
                    </header>
                @endif

                <div class="onb-w-list">
                    @foreach ($definicion as $i => [$icono, $titulo, $boton, $ruta])
                        @php $hecho = $pasos[$i]; $esSiguiente = $siguiente === $i; @endphp
                        <div class="onb-w-step {{ $hecho ? 'is-completed' : 'is-pending' }} {{ $esSiguiente ? 'is-next' : '' }}">
                            <span class="onb-w-step-marker" aria-hidden="true">
                                @if ($hecho)
                                    <i class="bi bi-check2"></i>
                                @else
                                    {{ $i + 1 }}
                                @endif
                            </span>
                            <span class="onb-w-step-icon" aria-hidden="true"><i class="bi {{ $icono }}"></i></span>
                            <div class="onb-w-step-body">
                                <div class="onb-w-step-title-row">
                                    <span class="onb-w-step-title">{{ $titulo }}</span>
                                    @if ($esSiguiente)
                                        <span class="onb-w-next-chip">
                                            <i class="bi bi-lightning-charge-fill" aria-hidden="true"></i>
                                            Siguiente
                                        </span>
                                    @endif
                                </div>
                                @if ($hecho)
                                    <span class="onb-w-step-status onb-w-step-status-done">
                                        <i class="bi bi-check2-circle" aria-hidden="true"></i>Completado
                                    </span>
                                @else
                                    <span class="onb-w-step-status onb-w-step-status-pending">
                                        <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                                        Pendiente
                                    </span>
                                @endif
                            </div>
                            <div class="onb-w-step-action">
                                @if ($hecho)
                                    <span class="onb-w-step-check" aria-hidden="true"><i class="bi bi-patch-check-fill"></i></span>
                                @else
                                    <a class="btn {{ $esSiguiente ? 'onb-w-btn-primary' : 'onb-w-btn-ghost' }}" href="{{ $ruta }}" data-onb-w-nav="">
                                        {{ $boton }}
                                        <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    <script>
        // Tras guardar algo por Ajax (POST/PUT/PATCH/DELETE) se pide al widget que se actualice (sin esperar el cooldown)
        (function ($) {
            if (!$) return;
            var t;
            $(document).ajaxSuccess(function (e, xhr, opts) {
                var metodo = (opts.type || 'GET').toUpperCase();
                if (metodo === 'GET' || String(opts.url).indexOf('guia/estado') !== -1) return;
                clearTimeout(t);
                t = setTimeout(function () { $(document).trigger('onboarding:refresh'); }, 1500);
            });
        })(window.jQuery);
    </script>
@endauth
