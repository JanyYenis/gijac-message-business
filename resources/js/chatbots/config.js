$(function () {
    /* ---------- DATOS GUARDADOS ---------- */
    var CFG = window.configChatbot;
    var DAYS = [['L', 'Lunes'], ['M', 'Martes'], ['M', 'Miércoles'], ['J', 'Jueves'], ['V', 'Viernes'], ['S', 'Sábado'], ['D', 'Domingo']]
        .map(function (d, i) {
            return { k: i + 1, l: d[0], full: d[1], on: CFG.dias.indexOf(i + 1) !== -1 };
        });
    var RANGES = CFG.rangos.map(function (r) { return { a: r.a, b: r.b }; });
    var KEYWORDS = CFG.palabras.slice();
    var MSG_INFO = {
        1: ['Mensaje de bienvenida', 'Se envía cuando un usuario inicia una nueva conversación.'],
        2: ['Mensaje fuera de horario', 'Se envía cuando un usuario escribe fuera del horario de atención.'],
        3: ['Mensaje de transferencia', 'Se envía cuando la conversación pasa de un chatbot a un agente humano.']
    };
    var MSGS = CFG.mensajes.map(function (m) {
        return { id: m.tipo, title: MSG_INFO[m.tipo][0], desc: MSG_INFO[m.tipo][1], on: m.activo, text: m.mensaje };
    });
    var ENGINE_INFO = {
        1: { name: 'Chatbot por Nodos', desc: 'Flujos visuales configurados mediante nodos y condiciones.', badge: 'Visual', icon: 'fa-diagram-project', c: '#2563EB', bg: 'rgba(37,99,235,.10)' },
        2: { name: 'Asistente IA', desc: 'Permite responder utilizando inteligencia artificial y conocimiento configurado.', badge: 'IA', icon: 'fa-brain', c: '#7C3AED', bg: 'rgba(124,58,237,.10)' },
        3: { name: 'Automatización', desc: 'Envía la conversación o información a una automatización externa como n8n.', badge: 'Automatización', icon: 'fa-bolt', c: '#D97706', bg: 'rgba(217,119,6,.12)' }
    };
    var ENGINES = CFG.motores.map(function (t) { return $.extend({ id: t }, ENGINE_INFO[t]); });
    var REASONS = [
        { id: 1, t: 'Palabra clave detectada' },
        { id: 2, t: 'Usuario solicita un asesor' },
        { id: 3, t: 'El chatbot no puede resolver la consulta' },
        { id: 4, t: 'Error en el chatbot' },
        { id: 5, t: 'Usuario solicita hablar con una persona' }
    ].map(function (r) { r.on = !!CFG.motivos[r.id]; return r; });
    function esc(t) { return $('<div>').text(t == null ? '' : t).html(); }
    var dirty = false;

    /* ---------- RENDER: DAYS ---------- */
    function renderDays() {
        $('#days').html(DAYS.map(function (d, i) {
            return '<button type="button" class="day' + (d.on ? ' on' : '') + '" data-i="' + i + '" title="' +
                d.full + '">' + d.l + '</button>';
        }).join(''));
    }
    $('#days').on('click', '.day', function () {
        var i = +$(this).data('i');
        DAYS[i].on = !DAYS[i].on;
        renderDays();
        touch();
    });
    $('.quick').on('click', function () {
        var p = $(this).data('preset');
        DAYS.forEach(function (d, i) {
            d.on = p === 'all' ? true : i < 5;
        });
        renderDays();
        touch();
    });

    /* ---------- RENDER: RANGES ---------- */
    function renderRanges() {
        $('#ranges').html(RANGES.map(function (r, i) {
            return '<div class="range-row" data-i="' + i + '">' +
                '<input type="time" class="form-control r-a" value="' + r.a + '">' +
                '<span class="muted">a</span>' +
                '<input type="time" class="form-control r-b" value="' + r.b + '">' +
                (RANGES.length > 1 ?
                    '<button type="button" class="icon-btn rm"><i class="fa-solid fa-trash-can"></i></button>' :
                    '') +
                '</div>';
        }).join(''));
    }
    $('#addRange').on('click', function () {
        RANGES.push({
            a: '14:00',
            b: '18:00'
        });
        renderRanges();
        touch();
    });
    $('#ranges').on('click', '.rm', function () {
        RANGES.splice($(this).closest('.range-row').data('i'), 1);
        renderRanges();
        touch();
    });
    $('#ranges').on('change', 'input', function () {
        var row = $(this).closest('.range-row'),
            i = row.data('i');
        RANGES[i] = {
            a: row.find('.r-a').val(),
            b: row.find('.r-b').val()
        };
        touch();
    });

    $('#useSchedule').on('change', function () {
        var on = this.checked;
        $('#scheduleBody').slideToggle(on, 200);
        $('#scheduleHint').text(on ?
            'El chatbot solo atenderá dentro de los días y horas configurados.' :
            'El chatbot estará disponible las 24 horas.');
        touch();
    });

    /* ---------- RENDER: KEYWORDS ---------- */
    function renderKw(isNew) {
        $('#kwList').html(KEYWORDS.map(function (k, i) {
            return '<span class="tag' + (isNew && i === KEYWORDS.length - 1 ? ' new' : '') +
                '">' + esc(k) + '<button type="button" data-i="' + i +
                '"><i class="fa-solid fa-xmark"></i></button></span>';
        }).join(''));
        $('#sKw').text(KEYWORDS.length + ' configuradas');
    }

    function addKw() {
        var v = $.trim($('#kwInput').val());
        if (!v) return;
        if (KEYWORDS.some(function (k) {
            return k.toLowerCase() === v.toLowerCase();
        })) {
            toast('Esa palabra ya existe.', 'amber');
            return;
        }
        KEYWORDS.push(v);
        $('#kwInput').val('');
        renderKw(true);
        touch();
        summary();
    }
    $('#kwAdd').on('click', addKw);
    $('#kwInput').on('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addKw();
        }
    });
    $('#kwList').on('click', 'button', function () {
        KEYWORDS.splice(+$(this).data('i'), 1);
        renderKw();
        touch();
        summary();
    });

    /* ---------- RENDER: MESSAGES ---------- */
    function renderMsgs() {
        $('#msgCards').html(MSGS.map(function (m) {
            return `<div class="sub-card mb-3" data-id="${m.id}">
                                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
                                    <div>
                                        <div style="font-weight:600;font-size:14.5px">
                                            ${m.title}
                                        </div>
                                        <div class="hint">${m.desc}</div>
                                    </div>
                                    <label class="sw">
                                        <input type="checkbox" class="msg-on" ${(m.on ? 'checked' : '')}>
                                        <span class="track"></span>
                                        <span class="txt muted">Activo</span>
                                    </label>
                                </div>
                                <textarea class="form-control msg-text" rows="3">${esc(m.text)}</textarea>
                                <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap gap-2">`+
                //     <div class="dropdown">
                //         <button type="button" class="btn btn-ghost btn-sm dropdown-toggle"
                //             data-bs-toggle="dropdown">
                //             <i class="fa-solid fa-code me-2"></i>
                //             Insertar variable
                //         </button>
                //         <ul class="dropdown-menu" style="border-radius:12px;border-color:var(--border)">` +
                //             [`{ nombre }`, `{ empresa }`, `{ fecha }`].map(function(v) {
                //                 return `<li>
                //                             <a class="dropdown-item var-i" href="#" data-v="${v}">
                //                                 ${v}
                //                             </a>
                //                         </li>`;
                //             }).join(``) +
                //         `</ul>
                //     </div>
                `<span class="counter">${m.text.length} / 1024 caracteres</span>
                                </div>
                            </div>`;
        }).join(``));
    }
    $('#msgCards').on('input', '.msg-text', function () {
        var card = $(this).closest('.sub-card'),
            len = this.value.length;
        card.find('.counter').text(len + ' / 1024 caracteres').toggleClass('warn', len > 1024);
        MSGS.forEach(function (m) {
            if (m.id === card.data('id')) m.text = this.value;
        }.bind(this));
        touch();
    });
    $('#msgCards').on('change', '.msg-on', function () {
        var id = $(this).closest('.sub-card').data('id'), on = this.checked;
        MSGS.forEach(function (m) { if (m.id === id) m.on = on; });
        touch();
    });
    $('#msgCards').on('click', '.var-i', function (e) {
        e.preventDefault();
        var ta = $(this).closest('.sub-card').find('.msg-text')[0];
        var v = $(this).data('v'),
            s = ta.selectionStart || ta.value.length;
        ta.value = ta.value.slice(0, s) + v + ta.value.slice(s);
        $(ta).trigger('input').focus();
    });

    /* ---------- RENDER: ENGINES (drag & drop) ---------- */
    function renderEngines() {
        $('#engines').html(ENGINES.map(function (e, i) {
            return '<div class="engine" draggable="true" data-i="' + i + '">' +
                '<i class="fa-solid fa-grip-vertical handle"></i>' +
                '<span class="eng-num">' + (i + 1) + '</span>' +
                '<span class="eng-ico" style="background:' + e.bg + ';color:' + e.c +
                '"><i class="fa-solid ' + e.icon + '"></i></span>' +
                '<div class="flex-grow-1"><div style="font-weight:600;font-size:14px">' + e
                    .name + '</div><div class="hint" style="margin-top:2px">' + e.desc +
                '</div></div>' +
                '<span class="badge-s" style="background:' + e.bg + ';color:' + e.c + '">' + e
                    .badge + '</span>' +
                '</div>';
        }).join(''));
        var nodes = [
            '<span class="flow-node"><i class="fa-solid fa-user" style="color:var(--muted)"></i>Usuario</span>'
        ]
            .concat(ENGINES.map(function (e) {
                return '<span class="flow-node"><i class="fa-solid ' + e.icon + '" style="color:' +
                    e.c + '"></i>' + e.name + '</span>';
            }));
        $('#flowmap').html(nodes.join(
            '<div class="flow-arrow"><i class="fa-solid fa-arrow-down"></i></div>'));
        $('#sEngine').text(ENGINES[0].name);
        summary();
    }
    var dragIdx = null;
    $('#engines').on('dragstart', '.engine', function (e) {
        dragIdx = +$(this).data('i');
        $(this).addClass('dragging');
        e.originalEvent.dataTransfer.effectAllowed = 'move';
        e.originalEvent.dataTransfer.setData('text/plain', dragIdx);
    });
    $('#engines').on('dragend', '.engine', function () {
        $('.engine').removeClass('dragging over');
    });
    $('#engines').on('dragover', '.engine', function (e) {
        e.preventDefault();
        $('.engine').removeClass('over');
        $(this).addClass('over');
    });
    $('#engines').on('drop', '.engine', function (e) {
        e.preventDefault();
        var to = +$(this).data('i');
        if (dragIdx === null || dragIdx === to) return;
        var moved = ENGINES.splice(dragIdx, 1)[0];
        ENGINES.splice(to, 0, moved);
        dragIdx = null;
        renderEngines();
        touch();
        toast('Prioridad actualizada: ' + ENGINES[0].name);
    });

    /* ---------- REASONS ---------- */
    $('#reasons').html(REASONS.map(function (r, i) {
        return '<div class="col-md-6"><label class="opt' + (r.on ? ' sel' : '') +
            '" style="margin-bottom:0">' +
            '<input type="checkbox" class="cfg chk" data-id="' + r.id + '" style="accent-color:var(--blue)"' + (r.on ?
                ' checked' : '') + '>' +
            '<span class="t">' + r.t + '</span></label></div>';
    }).join(''));

    /* ---------- OPT visual state ---------- */
    $(document).on('change', '.opt input', function () {
        var $i = $(this);
        if ($i.attr('type') === 'radio') {
            $('input[name="' + $i.attr('name') + '"]').closest('.opt').removeClass('sel');
        }
        $i.closest('.opt').toggleClass('sel', this.checked);
    });

    /* ---------- Active switches ---------- */
    function setActive(on) {
        $('#botActive,#botActiveSide').prop('checked', on);
        $('#headState').html(
            '<i class="bi bi-check2-circle me-1 text-white"></i>' + (on ? 'Activo' : 'Inactivo'));
        $('#sideState').html('<span class="dot" style="color:' + (on ? '#4ADE80' : '#94A3B8') +
            '"></span> ' + (on ? 'Activo' : 'Inactivo'));
    }
    $('#botActive,#botActiveSide').on('change', function () {
        setActive(this.checked);
        touch();
    });
    $('#allowTransfer').on('change', function () {
        $('#sTransfer').text(this.checked ? 'Activada' : 'Desactivada');
        touch();
    });
    $('#delay').on('input', function () {
        $('#delayVal').text(this.value + ' s');
        touch();
    });

    /* ---------- SUMMARY ---------- */
    function summary() {
        var on = DAYS.filter(function (d) {
            return d.on;
        });
        var dstr = on.length === 7 ? 'Todos los días' : (on.length === 0 ? 'Sin días' : on.map(function (d) {
            return d.full.slice(0, 3);
        }).join(', '));
        var hstr = $('#useSchedule').prop('checked') ? RANGES.map(function (r) {
            return r.a + ' - ' + r.b;
        }).join(' · ') : 'Disponible 24 h';
        $('#sHorario').html(dstr + '<br>' + hstr);
        var items = [
            ['fa-regular fa-clock', 'Horario', dstr + ' · ' + hstr],
            ['fa-solid fa-tags', 'Palabras clave', KEYWORDS.length + ' términos'],
            ['fa-regular fa-comment-dots', 'Mensajes activos', $('.msg-on:checked').length + ' de 3'],
            ['fa-solid fa-diagram-project', 'Prioridad', ENGINES.map(function (e) {
                return e.name;
            }).join(' → ')],
            ['fa-solid fa-headset', 'Transferencia', $('#allowTransfer').prop('checked') ? 'Activada' :
                'Desactivada'
            ]
        ];
        $('#summary').html(items.map(function (it) {
            return '<div class="d-flex gap-3 align-items-start py-2" style="border-bottom:1px solid var(--border)">' +
                '<i class="' + it[0] +
                '" style="color:var(--accent);width:18px;margin-top:3px"></i>' +
                '<div><div style="font-size:12.5px;font-weight:600">' + it[1] +
                '</div><div class="hint" style="margin-top:1px">' + it[2] +
                '</div></div></div>';
        }).join(''));
    }

    /* ---------- DIRTY / SAVE ---------- */
    function touch() {
        dirty = true;
        $('#savebar').addClass('show');
        summary();
    }
    $(document).on('change input', '.cfg, #msgCards input, #msgCards textarea, #days .day', function () {
        touch();
    });

    function payload() {
        var motivos = {};
        $('#reasons .chk').each(function () { motivos[$(this).data('id')] = this.checked; });
        return {
            activo: $('#botActive').prop('checked'),
            respetar_horario: $('#useSchedule').prop('checked'),
            accion_palabra_clave: +$('input[name=kwact]:checked').val(),
            coincidencia_aproximada: $('#fuzzy').prop('checked'),
            permitir_transferencia: $('#allowTransfer').prop('checked'),
            tiempo_espera_agente: +$('#waitMin').val() || 0,
            accion_sin_agente: +$('input[name=noagent]:checked').val(),
            mantener_contexto: $('#keepCtx').prop('checked'),
            tiempo_sesion: +$('#sessionMin').val() || 30,
            tipo_reinicio_conversacion: +$('input[name=reset]:checked').val(),
            tiempo_respuesta: +$('#delay').val(),
            mostrar_escribiendo: $('#typing').prop('checked'),
            agrupar_mensajes: +$('#groupSec').val() || 0,
            accion_respuesta_no_resuelta: +$('input[name=unres]:checked').val(),
            mensaje_no_resuelto: $('#unresMsg').val(),
            dias: DAYS.filter(function (d) { return d.on; }).map(function (d) { return d.k; }),
            rangos: RANGES,
            palabras: KEYWORDS,
            mensajes: MSGS.map(function (m) { return { tipo: m.id, activo: m.on, mensaje: m.text }; }),
            motores: ENGINES.map(function (e) { return e.id; }),
            motivos: motivos
        };
    }

    function save() {
        var $b = $('#btnSave, #btnSaveTop').prop('disabled', true);
        $.ajax({
            url: route('chatbots.configuracion.guardar'),
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload()),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        }).done(function (response) {
            dirty = false;
            $('#savebar').removeClass('show');
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
        }).fail(function (xhr) {
            var errs = xhr.responseJSON && xhr.responseJSON.errors;
            var msg = errs ? Object.values(errs)[0][0] : ((xhr.responseJSON && xhr.responseJSON.message) || 'No se pudo guardar la configuración.');
            generalidades.toastrGenerico('error', msg);
        }).always(function () { $b.prop('disabled', false); });
    }
    $('#btnSave, #btnSaveTop, #btnSaveGeneral').on('click', save);
    $('#btnDiscard').on('click', function () {
        location.reload();
    });
    $('#btnTest,#qaTest').on('click', function () {
        generalidades.toastrGenerico('info', 'Abriendo simulador de conversación…');
    });
    $('#qaCopy').on('click', function () {
        generalidades.toastrGenerico('info', 'Configuración duplicada como borrador.');
    });

    function toast(msg, tone) {
        var color = tone === 'amber' ? '#D97706' : (tone === 'blue' ? '#2563EB' : '#16A34A');
        var icon = tone === 'amber' ? 'fa-triangle-exclamation' : (tone === 'blue' ? 'fa-circle-info' :
            'fa-circle-check');
        var $t = $('<div class="toast-i" style="border-left-color:' + color + '"><i class="fa-solid ' +
            icon + '" style="color:' + color + '"></i><span>' + msg + '</span></div>');
        $('#toasts').append($t);
        setTimeout(function () {
            $t.fadeOut(220, function () {
                $t.remove();
            });
        }, 2800);
    }

    /* ---------- TABS / SCROLLSPY ---------- */
    $('#tabs a, [data-goto]').on('click', function (e) {
        e.preventDefault();
        var t = $(this).attr('href') || $(this).data('goto');
        var el = $(t);
        if (!el.length) return;
        $('html,body').animate({
            scrollTop: el.offset().top - 90
        }, 420);
    });
    var secs = $('#tabs a').map(function () {
        return $(this).attr('href');
    }).get();
    $(window).on('scroll', function () {
        var y = $(window).scrollTop() + 120,
            cur = secs[0];
        secs.forEach(function (s) {
            var el = $(s);
            if (el.length && el.offset().top <= y) cur = s;
        });
        $('#tabs a').removeClass('active').filter('[href="' + cur + '"]').addClass('active');
    });

    /* ---------- INIT ---------- */
    renderDays();
    renderRanges();
    renderKw();
    renderMsgs();
    renderEngines();
    setActive(CFG.activo);
    $('#useSchedule').prop('checked', CFG.respetar_horario);
    $('#scheduleBody').toggle(CFG.respetar_horario);
    $('#scheduleHint').text(CFG.respetar_horario ? 'El chatbot solo atenderá dentro de los días y horas configurados.' : 'El chatbot estará disponible las 24 horas.');
    $('input[name=kwact][value=' + CFG.accion_palabra_clave + ']').prop('checked', true);
    $('#fuzzy').prop('checked', CFG.coincidencia_aproximada);
    $('#allowTransfer').prop('checked', CFG.permitir_transferencia);
    $('#sTransfer').text(CFG.permitir_transferencia ? 'Activada' : 'Desactivada');
    $('#waitMin').val(CFG.tiempo_espera_agente);
    $('input[name=noagent][value=' + CFG.accion_sin_agente + ']').prop('checked', true);
    $('input[name=unres][value=' + CFG.accion_respuesta_no_resuelta + ']').prop('checked', true);
    $('#unresMsg').val(CFG.mensaje_no_resuelto || '');
    $('#keepCtx').prop('checked', CFG.mantener_contexto);
    $('#sessionMin').val(CFG.tiempo_sesion);
    $('input[name=reset][value=' + CFG.tipo_reinicio_conversacion + ']').prop('checked', true);
    $('#delay').val(CFG.tiempo_respuesta);
    $('#delayVal').text(CFG.tiempo_respuesta + ' s');
    $('#typing').prop('checked', CFG.mostrar_escribiendo);
    $('#groupSec').val(CFG.agrupar_mensajes);
    $('.opt').each(function () { $(this).toggleClass('sel', $(this).find('input').prop('checked')); });
    summary();
    $('#savebar').removeClass('show');
    dirty = false;
    setTimeout(function () {
        $('#savebar').removeClass('show');
    }, 100);
    $('[title]').each(function () {
        new bootstrap.Tooltip(this);
    });
});

require('./tutorial_config');
