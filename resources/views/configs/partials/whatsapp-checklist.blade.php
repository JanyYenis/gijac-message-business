{{-- Checklist de conexión de WhatsApp. Uso: @include('configs.partials.whatsapp-checklist') --}}
<div id="wa-checklist" class="wa-checklist" data-url="{{ route('whatsapp.checklist') }}">
    <div class="wa-checklist__head">
        <strong>{{ __('Estado de tu conexión con WhatsApp') }}</strong>
        <button type="button" class="wa-checklist__btn" id="wa-checklist-reload">{{ __('Verificar de nuevo') }}</button>
    </div>
    <div class="wa-checklist__summary" id="wa-checklist-summary"></div>
    <ul class="wa-checklist__list" id="wa-checklist-list"></ul>
</div>

<style>
    .wa-checklist {
        border: 1px solid rgba(128, 128, 128, .3);
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
    }

    .wa-checklist__head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .wa-checklist__btn {
        border: 1px solid rgba(128, 128, 128, .5);
        background: transparent;
        color: inherit;
        border-radius: 6px;
        padding: 4px 10px;
        cursor: pointer;
    }

    .wa-checklist__summary {
        margin: 10px 0;
        font-size: .9rem;
        opacity: .85;
    }

    .wa-checklist__list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .wa-checklist__list li {
        display: flex;
        gap: 10px;
        padding: 8px 0;
        border-top: 1px solid rgba(128, 128, 128, .2);
    }

    .wa-checklist__dot {
        flex: 0 0 22px;
        height: 22px;
        border-radius: 50%;
        text-align: center;
        line-height: 22px;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
    }

    .wa-checklist__dot--ok {
        background: #1a9f5c;
    }

    .wa-checklist__dot--pendiente {
        background: #d98a00;
    }

    .wa-checklist__dot--error {
        background: #d03b3b;
    }

    .wa-checklist__txt small {
        display: block;
        opacity: .75;
    }
</style>

<script>
    (function() {
        var root = document.getElementById('wa-checklist');
        if (!root) {
            return;
        }
        var list = document.getElementById('wa-checklist-list');
        var summary = document.getElementById('wa-checklist-summary');
        var icon = {
            ok: '✓',
            pendiente: '!',
            error: '✕'
        };

        function esc(s) {
            var d = document.createElement('div');
            d.textContent = s == null ? '' : s;
            return d.innerHTML;
        }

        function cargar() {
            summary.textContent = @json(__('Verificando con Meta...'));
            list.innerHTML = '';
            fetch(root.dataset.url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(d) {
                    summary.textContent = d.listo ?
                        @json(__('Todo listo: tu cuenta puede enviar y recibir mensajes.')) :
                        @json(__('Hay pasos pendientes para dejar tu cuenta lista.'));
                    list.innerHTML = (d.items || []).map(function(i) {
                        return '<li><span class="wa-checklist__dot wa-checklist__dot--' + esc(i
                            .estado) + '">' + icon[i.estado] + '</span>' +
                            '<span class="wa-checklist__txt">' + esc(i.titulo) + (i.requerido ? '' :
                                ' <em>(' + @json(__('recomendado')) + ')</em>') +
                            '<small>' + esc(i.detalle) + '</small></span></li>';
                    }).join('');
                })
                .catch(function() {
                    summary.textContent = @json(__('No se pudo verificar. Intenta de nuevo.'));
                });
        }

        document.getElementById('wa-checklist-reload').addEventListener('click', cargar);
        cargar();
    })();
</script>
