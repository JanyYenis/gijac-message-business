/* ============================================================
   GIJAC Message Business · Constructor de plantillas WhatsApp
   Componentes: HeaderBuilder, BodyBuilder, VariableManager,
                FooterBuilder, ButtonBuilder, WhatsAppPreview
   Solo jQuery + Bootstrap 5.

   Cambios respecto a la versión anterior (reglas oficiales Meta):
   - CATEGORIES ahora usa las claves reales de Meta (MARKETING /
     UTILITY / AUTHENTICATION) en vez de IDs numéricos.
   - Soporte completo para AUTHENTICATION: sin header, cuerpo y
     pie generados por Meta (solo se elige recomendación de
     seguridad y minutos de expiración), y un único botón OTP
     (COPY_CODE / ONE_TAP / ZERO_TAP).
   - Botón COPY_CODE (no-auth, cupón) corregido: no lleva "text"
     libre, lleva "example" (máx. 20 caracteres), como indica Meta.
   - Límite de 1 botón COPY_CODE por plantilla.
   - Validación: los botones de Respuesta Rápida deben quedar
     agrupados (regla de oro de Meta).
   - Validación: la variable de un botón URL debe ir al final de
     la URL y solo puede haber una.
   - buildMetaPayload() arma el payload exacto que espera la API
     de Meta (distinto para AUTHENTICATION vs MARKETING/UTILITY),
     separado del modelo que usa la vista previa/formulario.
   ============================================================ */
(function ($) {
    'use strict';

    const formCrearPlantilla = '#formCrearPlantilla';
    const modalCrearPlantilla = '#modalCrearPlantilla';

    /* ---------------- Constantes ---------------- */
    var LIMITS = {
        name: 512, headerText: 60, body: 1024, footer: 60, btnText: 25,
        maxButtons: 10, maxQuickReply: 10, maxUrl: 2, maxPhone: 1,
        maxCopyCode: 1, maxOtp: 1, copyCodeExample: 20
    };

    // Códigos reales del backend (App\Models\... constantes PHP):
    //   const AUTENTICACION = 1;
    //   const MARKETING     = 2;
    //   const SERVICIOS     = 3;   // <- equivale a UTILITY en la API de Meta
    var CATEGORY_AUTH = 1;
    var CATEGORY_MARKETING = 2;
    var CATEGORY_UTILITY = 3;

    // Traducción del código interno al valor que espera la API de Meta.
    var CATEGORY_META_MAP = { 1: 'AUTHENTICATION', 2: 'MARKETING', 3: 'UTILITY' };

    var CATEGORY_LABELS = { 1: 'Autenticación', 2: 'Marketing', 3: 'Servicios' };

    var CATEGORIES = {
        2: 'Promociones, ofertas y comunicaciones comerciales.',
        3: 'Actualizaciones, confirmaciones y mensajes relacionados con servicios.',
        1: 'Códigos de autenticación y verificación. Meta genera el texto automáticamente.'
    };

    // Tipos de botón disponibles para MARKETING / UTILITY.
    var BUTTON_TYPES = {
        QUICK_REPLY: { label: 'Respuesta rápida', icon: 'fa-reply', fields: ['text'] },
        URL: { label: 'URL', icon: 'fa-link', fields: ['text', 'url'] },
        PHONE_NUMBER: { label: 'Número de teléfono', icon: 'fa-phone', fields: ['text', 'phone'] },
        COPY_CODE: { label: 'Copiar código (cupón)', icon: 'fa-copy', fields: ['example'] },
        CATALOG: { label: 'Catálogo', icon: 'fa-store', fields: ['text'] },
        MPM: { label: 'Multi Product Message', icon: 'fa-boxes-stacked', fields: ['text'] }
    };

    // Botón exclusivo de AUTHENTICATION (no aparece en el menú normal).
    var OTP_TYPE_META = { label: 'OTP (autenticación)', icon: 'fa-key' };

    var HEADER_TYPES = [
        { key: 'TEXT', label: 'Texto', icon: 'fa-font' },
        { key: 'IMAGE', label: 'Imagen', icon: 'fa-image' },
        { key: 'VIDEO', label: 'Video', icon: 'fa-video' },
        { key: 'DOCUMENT', label: 'Documento', icon: 'fa-file-pdf' },
        { key: 'LOCATION', label: 'Ubicación', icon: 'fa-location-dot' }
    ];

    /* ---------------- Estado ---------------- */
    var state = {
        mode: 'create',
        id: null,
        dirty: false,
        buttons: [],
        seq: 0,
        media: { IMAGE: null, VIDEO: null, DOCUMENT: null }
    };

    function esc(s) { return $('<i>').text(s == null ? '' : s).html(); }
    function human(bytes) {
        if (!bytes && bytes !== 0) return '';
        var u = ['B', 'KB', 'MB', 'GB'], i = 0, n = bytes;
        while (n >= 1024 && i < u.length - 1) { n /= 1024; i++; }
        return n.toFixed(n < 10 && i > 0 ? 1 : 0) + ' ' + u[i];
    }
    function toast(msg, isErr) {
        var $t = $('<div class="gj-toast"><i class="fa-solid ' + (isErr ? 'fa-circle-exclamation' : 'fa-circle-check') + ' me-2"></i>' + esc(msg) + '</div>')
            .toggleClass('err', !!isErr).appendTo('#tplToasts');
        setTimeout(function () { $t.fadeOut(250, function () { $(this).remove(); }); }, 3400);
    }
    function markDirty() { state.dirty = true; }
    function isAuth() { return Number($('#tplCategory').val()) === CATEGORY_AUTH; }

    /* ============================================================
       VariableManager — detecta {{n}} y administra ejemplos
       ============================================================ */
    var VariableManager = {
        // Meta admite dos estilos de variable: numeradas {{1}}, {{2}}… o con
        // nombre {{nombre}}, {{pedido}}… Se detectan ambas por igual; el
        // "kind" abajo determina cuál estilo se está usando.
        detect: function (text) {
            var found = [], re = /\{\{\s*([A-Za-z0-9_]+)\s*\}\}/g, m;
            while ((m = re.exec(text || '')) !== null) {
                if (found.indexOf(m[1]) === -1) found.push(m[1]);
            }
            return found;
        },
        isNumeric: function (v) { return /^\d+$/.test(v); },
        // 'numeric' | 'named' | 'mixed' | 'none' — Meta no permite mezclar
        // ambos estilos dentro del mismo componente.
        kind: function (vars) {
            if (!vars.length) return 'none';
            var numeric = vars.filter(VariableManager.isNumeric).length;
            if (numeric === vars.length) return 'numeric';
            if (numeric === 0) return 'named';
            return 'mixed';
        },
        isSequential: function (vars) {
            if (VariableManager.kind(vars) !== 'numeric') return true; // no aplica a variables con nombre
            var nums = vars.map(function (v) { return parseInt(v, 10); }).sort(function (a, b) { return a - b; });
            for (var i = 0; i < nums.length; i++) if (nums[i] !== i + 1) return false;
            return true;
        },
        next: function (text) {
            var nums = VariableManager.detect(text).filter(VariableManager.isNumeric).map(function (v) { return parseInt(v, 10); });
            return nums.length ? Math.max.apply(null, nums) + 1 : 1;
        },
        render: function ($container, vars, prefix) {
            var existing = {};
            $container.find('input[data-var]').each(function () { existing[$(this).data('var')] = this.value; });
            if (!vars.length) {
                $container.html('<div class="var-empty">Aún no hay variables. Usa <b>{{1}}</b> (numerada) o <b>{{nombre}}</b> (con nombre), o los botones de arriba.</div>');
                return;
            }
            var html = '';
            if (VariableManager.kind(vars) === 'mixed') {
                html += '<div class="field-error show mb-2">Meta no permite mezclar variables numeradas ({{1}}) y con nombre ({{nombre}}) en el mismo texto. Usa un solo estilo.</div>';
            }
            html += '<table class="var-table"><thead><tr><th style="width:120px">Variable</th><th>Ejemplo</th></tr></thead><tbody>';
            vars.forEach(function (v) {
                html += '<tr><td><span class="var-token">{{' + esc(v) + '}}</span></td><td>' +
                    '<input type="text" class="form-control form-control-sm var-example" data-var="' + esc(v) +
                    '" data-prefix="' + prefix + '" placeholder="Ej: Carlos" value="' + esc(existing[v] || '') + '"></td></tr>';
            });
            html += '</tbody></table><div class="field-error" data-error="' + prefix + '-examples">Completa un ejemplo para cada variable.</div>';
            $container.html(html);
        },
        values: function ($container, vars) {
            return vars.map(function (v) { return ($container.find('input[data-var="' + v + '"]').val() || '').trim(); });
        }
    };

    /* ============================================================
       HeaderBuilder — Meta no admite header en AUTHENTICATION
       ============================================================ */
    var HeaderBuilder = {
        init: function () {
            var html = '';
            HEADER_TYPES.forEach(function (t) {
                html += '<div class="type-opt' + (t.key === 'TEXT' ? ' active' : '') + '" data-htype="' + t.key +
                    '" data-bs-toggle="tooltip" title="Encabezado de ' + t.label.toLowerCase() + '"><i class="fa-solid ' + t.icon + '"></i>' + t.label + '</div>';
            });
            $('#headerTypeGrid').html(html);

            $('#headerSwitch').on('change', function () {
                $('#headerOff').toggleClass('d-none', this.checked);
                $('#headerOn').toggleClass('d-none', !this.checked);
                markDirty(); Preview.render();
            });

            $('#headerTypeGrid').on('click', '.type-opt', function () {
                $('#headerTypeGrid .type-opt').removeClass('active');
                $(this).addClass('active');
                HeaderBuilder.showPane($(this).data('htype'));
                markDirty(); Preview.render();
            });

            $('#headerText').on('input', function () {
                Counter.update($(this), LIMITS.headerText);
                VariableManager.render($('#headerVars'), VariableManager.detect(this.value), 'header');
                markDirty(); Preview.render();
            });

            $('#headerOn').on('input', '.var-example, #locLat, #locLng, #locName, #locAddress', function () {
                markDirty(); Preview.render();
            });

            ['IMAGE', 'VIDEO', 'DOCUMENT'].forEach(function (kind) { HeaderBuilder.bindDrop(kind); });
            HeaderBuilder.showPane('TEXT');
        },
        showPane: function (type) {
            $('#headerOn .h-pane').addClass('d-none');
            $('#hpane-' + type).removeClass('d-none');
        },
        accept: function (kind) {
            return kind === 'IMAGE' ? 'image/jpeg,image/jpg,image/png'
                : kind === 'VIDEO' ? 'video/mp4,video/3gpp' : 'application/pdf';
        },
        bindDrop: function (kind) {
            var $dz = $('#dz-' + kind), $input = $('#file-' + kind);
            $input.attr('accept', HeaderBuilder.accept(kind));
            $dz.on('click', function () { $input.trigger('click'); })
                .on('dragover', function (e) { e.preventDefault(); $dz.addClass('dragover'); })
                .on('dragleave drop', function () { $dz.removeClass('dragover'); })
                .on('drop', function (e) {
                    e.preventDefault();
                    var f = e.originalEvent.dataTransfer.files[0];
                    if (f) HeaderBuilder.setFile(kind, f);
                });
            $input.on('change', function () { if (this.files[0]) HeaderBuilder.setFile(kind, this.files[0]); });
            $(document).on('click', '#chip-' + kind + ' .icon-btn', function () {
                state.media[kind] = null; $('#chip-' + kind).empty().addClass('d-none'); $input.val('');
                markDirty(); Preview.render();
            });
        },
        setFile: function (kind, file) {
            var ok = HeaderBuilder.accept(kind).split(',');
            if (ok.indexOf(file.type) === -1) { toast('Formato no permitido para ' + kind.toLowerCase() + '.', true); return; }
            if (file.size > 16 * 1024 * 1024) { toast('El archivo supera 16 MB.', true); return; }
            var url = URL.createObjectURL(file);
            state.media[kind] = { name: file.name, size: file.size, type: file.type, url: url, file: file };
            var media = kind === 'IMAGE' ? '<img src="' + url + '" alt="">'
                : kind === 'VIDEO' ? '<video src="' + url + '" muted></video>'
                    : '<i class="fa-solid fa-file-pdf fa-2x text-danger"></i>';
            $('#chip-' + kind).removeClass('d-none').html(
                '<div class="file-chip">' + media +
                '<div class="flex-grow-1"><div class="fname">' + esc(file.name) + '</div><div class="fsize">' + human(file.size) + '</div></div>' +
                '<button type="button" class="icon-btn" title="Quitar archivo"><i class="fa-solid fa-trash-can"></i></button></div>'
            );
            markDirty(); Preview.render();
        },
        data: function () {
            // Meta no permite header en AUTHENTICATION.
            if (isAuth()) return null;
            if (!$('#headerSwitch').is(':checked')) return null;
            var type = $('#headerTypeGrid .type-opt.active').data('htype');
            if (type === 'TEXT') {
                var text = ($('#headerText').val() || '').trim();
                var vars = VariableManager.detect(text);
                var h = { format: 'TEXT', text: text };
                if (vars.length) h.examples = VariableManager.values($('#headerVars'), vars);
                return h;
            }
            if (type === 'LOCATION') {
                return {
                    format: 'LOCATION',
                    latitude: ($('#locLat').val() || '').trim(),
                    longitude: ($('#locLng').val() || '').trim(),
                    name: ($('#locName').val() || '').trim(),
                    address: ($('#locAddress').val() || '').trim()
                };
            }
            var f = state.media[type];
            return { format: type, file: f ? { name: f.name, size: f.size, type: f.type } : null, url: f ? f.url : null };
        },
        load: function (h) {
            $('#headerSwitch').prop('checked', !!h).trigger('change');
            if (!h) return;
            $('#headerTypeGrid .type-opt').removeClass('active').filter('[data-htype="' + h.format + '"]').addClass('active');
            HeaderBuilder.showPane(h.format);
            if (h.format === 'TEXT') {
                $('#headerText').val(h.text || '').trigger('input');
                (h.examples || []).forEach(function (v, i) { $('#headerVars input[data-var="' + (i + 1) + '"]').val(v); });
            } else if (h.format === 'LOCATION') {
                $('#locLat').val(h.latitude || ''); $('#locLng').val(h.longitude || '');
                $('#locName').val(h.name || ''); $('#locAddress').val(h.address || '');
            }
        }
    };

    /* ============================================================
       Counter
       ============================================================ */
    var Counter = {
        update: function ($el, max) {
            var $c = $('[data-counter="' + $el.attr('id') + '"]');
            var len = ($el.val() || '').length;
            $c.text(len + ' / ' + max).toggleClass('over', len > max);
        }
    };

    /* ============================================================
       BodyBuilder — en AUTHENTICATION el texto lo genera Meta
       ============================================================ */
    var BodyBuilder = {
        init: function () {
            $('#bodyText').on('input', function () {
                Counter.update($(this), LIMITS.body);
                BodyBuilder.sync();
                markDirty(); Preview.render();
            });
            $('#addVarBtn').on('click', function () {
                var $t = $('#bodyText'), el = $t[0], token = '{{' + VariableManager.next($t.val()) + '}}';
                var start = el.selectionStart, end = el.selectionEnd, v = $t.val();
                $t.val(v.slice(0, start) + token + v.slice(end)).trigger('input').focus();
                el.selectionStart = el.selectionEnd = start + token.length;
            });
            $('#addVarNamedBtn').on('click', function () {
                var name = window.prompt('Nombre de la variable (solo letras, números y guion bajo):', 'nombre');
                if (!name) return;
                name = name.trim().replace(/[^A-Za-z0-9_]/g, '');
                if (!name) return;
                var $t = $('#bodyText'), el = $t[0], token = '{{' + name + '}}';
                var start = el.selectionStart, end = el.selectionEnd, v = $t.val();
                $t.val(v.slice(0, start) + token + v.slice(end)).trigger('input').focus();
                el.selectionStart = el.selectionEnd = start + token.length;
            });
            $('#bodyVars').on('input', '.var-example', function () { markDirty(); Preview.render(); });
            $('#authSecurityRec').on('change', function () { markDirty(); Preview.render(); });
            BodyBuilder.sync();
        },
        sync: function () {
            VariableManager.render($('#bodyVars'), VariableManager.detect($('#bodyText').val()), 'body');
        },
        // Texto exacto que Meta arma para AUTHENTICATION (solo para la vista previa).
        authText: function () {
            var withSec = $('#authSecurityRec').is(':checked');
            return '{{1}} is your verification code.' + (withSec ? ' For your security, do not share this code.' : '');
        },
        data: function () {
            if (isAuth()) {
                return { text: BodyBuilder.authText(), examples: ['123456'], add_security_recommendation: $('#authSecurityRec').is(':checked') };
            }
            var text = ($('#bodyText').val() || '').trim();
            var vars = VariableManager.detect(text);
            return { text: text, examples: VariableManager.values($('#bodyVars'), vars) };
        },
        load: function (b) {
            $('#bodyText').val(b && b.text ? b.text : '').trigger('input');
            ((b && b.examples) || []).forEach(function (v, i) { $('#bodyVars input[data-var="' + (i + 1) + '"]').val(v); });
            $('#authSecurityRec').prop('checked', !b || b.add_security_recommendation !== false);
        }
    };

    /* ============================================================
       FooterBuilder — en AUTHENTICATION solo se define el
       tiempo de expiración; el texto lo arma Meta.
       ============================================================ */
    var FooterBuilder = {
        init: function () {
            $('#footerSwitch').on('change', function () {
                $('#footerOff').toggleClass('d-none', this.checked);
                $('#footerOn').toggleClass('d-none', !this.checked);
                markDirty(); Preview.render();
            });
            $('#footerText').on('input', function () {
                Counter.update($(this), LIMITS.footer);
                markDirty(); Preview.render();
            });
            $('#authExpiration').on('input', function () { markDirty(); Preview.render(); });
        },
        data: function () {
            if (isAuth()) {
                var min = parseInt($('#authExpiration').val(), 10);
                if (!min) return null;
                return { text: 'This code expires in ' + min + ' minutes.', code_expiration_minutes: min };
            }
            if (!$('#footerSwitch').is(':checked')) return null;
            return { text: ($('#footerText').val() || '').trim() };
        },
        load: function (f) {
            $('#footerSwitch').prop('checked', !!f).trigger('change');
            if (f) {
                $('#footerText').val(f.text || '').trigger('input');
                if (f.code_expiration_minutes) $('#authExpiration').val(f.code_expiration_minutes);
            }
        }
    };

    /* ============================================================
       ButtonBuilder
       ============================================================ */
    var ButtonBuilder = {
        init: function () {
            ButtonBuilder.buildMenu();

            $('#btnTypeMenu').on('click', '[data-btype]', function (e) {
                e.preventDefault();
                ButtonBuilder.add($(this).data('btype'));
            });

            $('#buttonsList')
                .on('input change', 'input, select', function () {
                    var idx = $(this).closest('.btn-item').index();
                    var field = $(this).data('field');
                    if (this.type === 'checkbox') state.buttons[idx][field] = this.checked;
                    else state.buttons[idx][field] = this.value;
                    if (field === 'url') ButtonBuilder.renderUrlVars($(this).closest('.btn-item'), idx);
                    if (field === 'otp_type') ButtonBuilder.render(); // re-render para mostrar/ocultar campos ONE_TAP/ZERO_TAP
                    markDirty(); Preview.render();
                })
                .on('click', '.btn-remove', function () {
                    var $item = $(this).closest('.btn-item'), idx = $item.index();
                    if (!window.confirm('¿Eliminar este botón? Esta acción no se puede deshacer.')) return;
                    state.buttons.splice(idx, 1);
                    ButtonBuilder.render(); markDirty(); Preview.render();
                })
                .on('dragstart', '.btn-item', function (e) {
                    $(this).addClass('dragging');
                    e.originalEvent.dataTransfer.setData('text/plain', $(this).index());
                })
                .on('dragend', '.btn-item', function () { $(this).removeClass('dragging'); })
                .on('dragover', '.btn-item', function (e) { e.preventDefault(); })
                .on('drop', '.btn-item', function (e) {
                    e.preventDefault();
                    var from = parseInt(e.originalEvent.dataTransfer.getData('text/plain'), 10), to = $(this).index();
                    if (isNaN(from) || from === to) return;
                    state.buttons.splice(to, 0, state.buttons.splice(from, 1)[0]);
                    ButtonBuilder.render(); markDirty(); Preview.render();
                });
        },
        // El menú de botones cambia según la categoría: solo OTP para
        // AUTHENTICATION, el resto de tipos para MARKETING/UTILITY.
        buildMenu: function () {
            var menu = '';
            if (isAuth()) {
                menu += `<div class="menu-item px-3">
                    <a href="javascript:;" class="menu-link fs-5 px-3 text-dark" data-btype="OTP">
                        <i class="fa-solid ${OTP_TYPE_META.icon} me-2 text-info"></i>
                        ${OTP_TYPE_META.label}
                    </a>
                </div>`;
            } else {
                Object.keys(BUTTON_TYPES).forEach(function (k) {
                    menu += `<div class="menu-item px-3">
                        <a href="javascript:;" class="menu-link fs-5 px-3 text-dark" data-btype="${k}">
                            <i class="fa-solid ${BUTTON_TYPES[k].icon} me-2 text-info"></i>
                            ${BUTTON_TYPES[k].label}
                        </a>
                    </div>`;
                });
            }
            $('#btnTypeMenu').html(menu);
        },
        add: function (type) {
            if (state.buttons.length >= LIMITS.maxButtons) { ButtonBuilder.warn('Meta permite un máximo de ' + LIMITS.maxButtons + ' botones por plantilla.'); return; }

            if (type === 'OTP') {
                if (!isAuth()) return; // el menú ya lo filtra, esto es solo un resguardo
                if (state.buttons.length >= LIMITS.maxOtp) { ButtonBuilder.warn('Autenticación solo admite 1 botón OTP.'); return; }
                state.buttons.push({ id: ++state.seq, type: 'OTP', otp_type: 'COPY_CODE', text: '', package: '', signature: '', zeroTermsAccepted: false });
                ButtonBuilder.render(); markDirty(); Preview.render();
                return;
            }

            if (isAuth()) return; // AUTHENTICATION solo admite el botón OTP

            var count = state.buttons.filter(function (b) { return b.type === type; }).length;
            if (type === 'URL' && count >= LIMITS.maxUrl) { ButtonBuilder.warn('Solo se permiten ' + LIMITS.maxUrl + ' botones de tipo URL.'); return; }
            if (type === 'PHONE_NUMBER' && count >= LIMITS.maxPhone) { ButtonBuilder.warn('Solo se permite 1 botón de llamada.'); return; }
            if (type === 'COPY_CODE' && count >= LIMITS.maxCopyCode) { ButtonBuilder.warn('Solo se permite 1 botón de copiar código.'); return; }
            state.buttons.push({ id: ++state.seq, type: type, text: '', url: '', phone: '', example: '' });
            ButtonBuilder.render(); markDirty(); Preview.render();
        },
        warn: function (msg) { $('#btnLimitWarn').text(msg).addClass('show'); setTimeout(function () { $('#btnLimitWarn').removeClass('show'); }, 5000); },
        field: function (b, field) {
            var map = {
                text: ['Texto del botón', 'Ej: Confirmar', LIMITS.btnText],
                url: ['URL', 'https://ejemplo.com/promocion', 2000],
                phone: ['Número telefónico', '+573001234567', 20],
                example: ['Código de ejemplo', 'Ej: SUMMER20', LIMITS.copyCodeExample],
                package: ['Package name', 'com.empresa.app', 200],
                signature: ['Signature hash', 'K8a/AINcGX7', 200]
            }[field];
            return '<div class="col-md-6 mb-2"><label class="form-label">' + map[0] + '</label>' +
                '<input type="text" class="form-control form-control-sm" data-field="' + field + '" maxlength="' + map[2] +
                '" placeholder="' + map[1] + '" value="' + esc(b[field] || '') + '">' +
                '<div class="field-error"></div></div>';
        },
        otpTypeField: function (b) {
            var opts = ['COPY_CODE', 'ONE_TAP', 'ZERO_TAP'].map(function (v) {
                return '<option value="' + v + '"' + (b.otp_type === v ? ' selected' : '') + '>' + v + '</option>';
            }).join('');
            return '<div class="col-12 mb-2"><label class="form-label">Tipo de OTP</label>' +
                '<select class="form-select form-select-sm" data-field="otp_type">' + opts + '</select></div>' +
                '<div class="col-12 mb-2"><label class="form-label">Texto del botón (opcional, Meta lo traduce)</label>' +
                '<input type="text" class="form-control form-control-sm" data-field="text" maxlength="' + LIMITS.btnText +
                '" placeholder="Copy Code" value="' + esc(b.text || '') + '"></div>';
        },
        oneTapFields: function (b) {
            return '<div class="col-md-6 mb-2"><label class="form-label">Package name</label>' +
                '<input type="text" class="form-control form-control-sm" data-field="package" placeholder="com.empresa.app" value="' + esc(b.package || '') + '">' +
                '<div class="field-error"></div></div>' +
                '<div class="col-md-6 mb-2"><label class="form-label">Signature hash</label>' +
                '<input type="text" class="form-control form-control-sm" data-field="signature" placeholder="K8a/AINcGX7" value="' + esc(b.signature || '') + '">' +
                '<div class="field-error"></div></div>';
        },
        zeroTapFields: function (b) {
            return ButtonBuilder.oneTapFields(b) +
                '<div class="col-12 mb-2"><div class="form-check">' +
                '<input type="checkbox" class="form-check-input" id="zeroTapTerms" data-field="zeroTermsAccepted"' + (b.zeroTermsAccepted ? ' checked' : '') + '>' +
                '<label class="form-check-label" for="zeroTapTerms">Acepto los términos de Zero Tap</label></div>' +
                '<div class="field-error"></div></div>';
        },
        render: function () {
            if (!state.buttons.length) { $('#buttonsList').html(''); $('#buttonsEmpty').removeClass('d-none'); return; }
            $('#buttonsEmpty').addClass('d-none');
            var html = '';
            state.buttons.forEach(function (b) {
                if (b.type === 'OTP') {
                    var fields = ButtonBuilder.otpTypeField(b);
                    if (b.otp_type === 'ONE_TAP') fields += ButtonBuilder.oneTapFields(b);
                    if (b.otp_type === 'ZERO_TAP') fields += ButtonBuilder.zeroTapFields(b);
                    html += '<div class="btn-item" data-id="' + b.id + '">' +
                        '<div class="btn-item-head"><i class="fa-solid ' + OTP_TYPE_META.icon + ' text-info"></i>' +
                        '<span class="bi-type-1">' + OTP_TYPE_META.label + '</span>' +
                        '<button type="button" class="icon-btn ms-auto btn-remove" title="Eliminar botón"><i class="fa-solid fa-trash-can"></i></button></div>' +
                        '<div class="row g-2">' + fields + '</div></div>';
                    return;
                }
                var meta = BUTTON_TYPES[b.type];
                html += '<div class="btn-item" draggable="true" data-id="' + b.id + '">' +
                    '<div class="btn-item-head"><i class="fa-solid fa-grip-vertical drag-h" title="Arrastra para reordenar"></i>' +
                    '<i class="fa-solid ' + meta.icon + ' text-info"></i>' +
                    '<span class="bi-type-1">' + meta.label + '</span>' +
                    '<button type="button" class="icon-btn ms-auto btn-remove" title="Eliminar botón"><i class="fa-solid fa-trash-can"></i></button></div>' +
                    '<div class="row g-2">' + meta.fields.map(function (f) { return ButtonBuilder.field(b, f); }).join('') + '</div>' +
                    '<div class="btn-url-vars mt-1"></div></div>';
            });
            $('#buttonsList').html(html);
            state.buttons.forEach(function (b, i) {
                if (b.type === 'URL') ButtonBuilder.renderUrlVars($('#buttonsList .btn-item').eq(i), i);
            });
        },
        renderUrlVars: function ($item, idx) {
            var b = state.buttons[idx];
            var vars = VariableManager.detect(b.url);
            var $box = $item.find('.btn-url-vars');
            if (!vars.length) { $box.empty(); return; }
            if (!$box.find('input').length) {
                $box.html('<label class="form-label">Ejemplo de la URL dinámica</label>' +
                    '<input type="text" class="form-control form-control-sm" data-field="urlExample" placeholder="https://ejemplo.com/promo-123" value="' + esc(b.urlExample || '') + '">');
            }
        },
        // Solo para vista previa / formulario (no es el payload final de Meta).
        data: function () {
            return state.buttons.map(function (b) {
                if (b.type === 'OTP') return { type: 'OTP', text: (b.text || 'Copiar código').trim() };
                var out = { type: b.type, text: (b.text || '').trim() };
                if (b.type === 'URL') {
                    out.url = (b.url || '').trim();
                    if (VariableManager.detect(out.url).length) out.example = [(b.urlExample || '').trim()];
                }
                if (b.type === 'PHONE_NUMBER') out.phone_number = (b.phone || '').trim();
                if (b.type === 'COPY_CODE') { out.text = 'Copiar código'; out.example = [(b.example || '').trim()]; }
                return out;
            });
        },
        load: function (list) {
            state.buttons = (list || []).map(function (b) {
                if (b.type === 'otp' || b.type === 'OTP') {
                    return {
                        id: ++state.seq, type: 'OTP', otp_type: b.otp_type || 'COPY_CODE', text: b.text || '',
                        package: b.package_name || '', signature: b.signature_hash || '', zeroTermsAccepted: !!b.zero_tap_terms_accepted
                    };
                }
                return {
                    id: ++state.seq, type: b.type, text: b.text || '', url: b.url || '',
                    urlExample: (b.example && b.example[0]) || '', phone: b.phone_number || '',
                    example: (b.example && b.example[0]) || ''
                };
            });
            ButtonBuilder.render();
        }
    };

    /* ============================================================
       WhatsAppPreview
       ============================================================ */
    var Preview = {
        fill: function (text, examples) {
            return (text || '').replace(/\{\{(\d+)\}\}/g, function (m, n) {
                var v = examples[parseInt(n, 10) - 1];
                return v ? esc(v) : '<span class="wa-var">' + m + '</span>';
            });
        },
        render: function () {
            var d = getTemplateData();
            var html = '';

            if (d.header) {
                if (d.header.format === 'TEXT' && d.header.text) {
                    html += '<div class="wa-header-text">' + Preview.fill(d.header.text, d.header.examples || []) + '</div>';
                } else if (d.header.format === 'IMAGE') {
                    html += '<div class="wa-media">' + (d.header.url ? '<img src="' + d.header.url + '" alt="Encabezado">' : '<i class="fa-solid fa-image fa-2x"></i>') + '</div>';
                } else if (d.header.format === 'VIDEO') {
                    html += '<div class="wa-media">' + (d.header.url ? '<video src="' + d.header.url + '" muted controls></video>' : '<i class="fa-solid fa-video fa-2x"></i>') + '</div>';
                } else if (d.header.format === 'DOCUMENT') {
                    html += '<div class="wa-doc"><i class="fa-solid fa-file-pdf"></i><span>' + esc(d.header.file ? d.header.file.name : 'documento.pdf') + '</span></div>';
                } else if (d.header.format === 'LOCATION') {
                    html += '<div class="wa-media"><i class="fa-solid fa-location-dot fa-2x"></i></div>' +
                        '<div class="wa-header-text">' + esc(d.header.name || 'Ubicación') + '</div>' +
                        '<div class="wa-footer mb-1">' + esc(d.header.address || '') + '</div>';
                }
            }

            html += '<div class="wa-body">' + (d.body.text ? Preview.fill(d.body.text, d.body.examples) :
                '<span class="text-muted-3">El contenido del mensaje aparecerá aquí…</span>') + '</div>';

            if (d.footer && d.footer.text) html += '<div class="wa-footer">' + esc(d.footer.text) + '</div>';
            html += '<div class="wa-time">' + new Date().toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' }) + ' ✓✓</div>';

            var btns = '';
            d.buttons.forEach(function (b) {
                var ic = b.type === 'URL' ? 'fa-arrow-up-right-from-square'
                    : b.type === 'PHONE_NUMBER' ? 'fa-phone'
                        : (b.type === 'COPY_CODE' || b.type === 'OTP') ? 'fa-copy' : 'fa-reply';
                btns += '<div class="wa-btn"><i class="fa-solid ' + ic + '"></i>' + esc(b.text || 'Botón') + '</div>';
            });

            $('#waPreview').html('<div class="wa-bubble">'
                + html + (btns ? '<div class="wa-btns" style="max-width:90%">' + btns + '</div>' : '') + '</div>');
            $('#jsonPreview').text(JSON.stringify(buildMetaPayload(), null, 2));
        }
    };

    /* ============================================================
       Validación
       ============================================================ */
    function clearErrors() {
        $('#tplForm .is-invalid').removeClass('is-invalid');
        $('#tplForm .field-error').removeClass('show');
    }
    function fail($input, msg) {
        $input.addClass('is-invalid');
        var $e = $input.siblings('.field-error').first();
        if (!$e.length) $e = $input.closest('.mb-3, .col-md-6, .mb-2, .col-12').find('.field-error').first();
        $e.text(msg).addClass('show');
    }

    // Los botones de Respuesta Rápida deben quedar agrupados (regla de Meta):
    // no se permite [QR, URL, QR], sí [URL, QR, QR] o [QR, QR, URL].
    function quickRepliesAreGrouped(buttons) {
        var idxs = [];
        buttons.forEach(function (b, i) { if (b.type === 'QUICK_REPLY') idxs.push(i); });
        if (idxs.length < 2) return true;
        for (var i = 1; i < idxs.length; i++) if (idxs[i] !== idxs[i - 1] + 1) return false;
        return true;
    }

    function validate() {
        clearErrors();
        var ok = true, d = getTemplateData(), category = $('#tplCategory').val();

        var $name = $('#tplName');
        if (!d.name) { fail($name, 'El nombre es obligatorio.'); ok = false; }
        else if (!/^[a-z0-9_]+$/.test(d.name)) { fail($name, 'Solo letras minúsculas, números y guiones bajos.'); ok = false; }
        else if (d.name.length > LIMITS.name) { fail($name, 'Máximo ' + LIMITS.name + ' caracteres.'); ok = false; }

        if (!category) { fail($('#tplCategory'), 'Selecciona una categoría.'); ok = false; }
        if (!d.language) { fail($('#tplLanguage'), 'Selecciona un idioma.'); ok = false; }

        if (Number(category) === CATEGORY_AUTH) {
            // Header: nunca debe existir (la UI ya lo oculta y lo desactiva).
            var otp = state.buttons[0];
            if (state.buttons.length !== 1 || !otp || otp.type !== 'OTP') {
                ButtonBuilder.warn('Autenticación requiere exactamente 1 botón OTP.'); ok = false;
            } else if (otp.otp_type === 'ONE_TAP' || otp.otp_type === 'ZERO_TAP') {
                var $item = $('#buttonsList .btn-item').eq(0);
                if (!otp.package) { fail($item.find('[data-field="package"]'), 'Package name obligatorio.'); ok = false; }
                if (!otp.signature) { fail($item.find('[data-field="signature"]'), 'Signature hash obligatorio.'); ok = false; }
                if (otp.otp_type === 'ZERO_TAP' && !otp.zeroTermsAccepted) {
                    fail($item.find('[data-field="zeroTermsAccepted"]'), 'Debes aceptar los términos de Zero Tap.'); ok = false;
                }
            }
            var min = $('#authExpiration').val();
            if (min && (min < 1 || min > 90)) { fail($('#authExpiration'), 'Debe estar entre 1 y 90 minutos.'); ok = false; }

            if (!ok) toast('Revisa los campos marcados en rojo.', true);
            return ok;
        }

        var $body = $('#bodyText');
        if (!d.body.text) { fail($body, 'El contenido del mensaje es obligatorio.'); ok = false; }
        else if (d.body.text.length > LIMITS.body) { fail($body, 'Máximo ' + LIMITS.body + ' caracteres.'); ok = false; }
        else {
            var bvars = VariableManager.detect(d.body.text);
            if (VariableManager.kind(bvars) === 'mixed') { fail($body, 'No mezcles variables numeradas ({{1}}) y con nombre ({{nombre}}).'); ok = false; }
            else if (!VariableManager.isSequential(bvars)) { fail($body, 'Las variables numeradas deben ir consecutivas desde {{1}}.'); ok = false; }
            if (d.body.examples.some(function (v) { return !v; })) {
                $('#bodyVars [data-error="body-examples"]').addClass('show'); ok = false;
            }
        }

        if (d.header) {
            if (d.header.format === 'TEXT') {
                var $h = $('#headerText');
                var hvars = VariableManager.detect(d.header.text);
                if (!d.header.text) { fail($h, 'Escribe el texto del encabezado.'); ok = false; }
                else if (d.header.text.length > LIMITS.headerText) { fail($h, 'Máximo ' + LIMITS.headerText + ' caracteres.'); ok = false; }
                else if (hvars.length > 1) { fail($h, 'El encabezado admite máximo 1 variable.'); ok = false; }
                else if (VariableManager.kind(hvars) === 'mixed') { fail($h, 'No mezcles estilos de variable.'); ok = false; }
                else if (!VariableManager.isSequential(hvars)) { fail($h, 'Numera la variable como {{1}}.'); ok = false; }
                else if ((d.header.examples || []).some(function (v) { return !v; })) {
                    $('#headerVars [data-error="header-examples"]').addClass('show'); ok = false;
                }
            } else if (d.header.format === 'LOCATION') {
                if (!/^-?\d+(\.\d+)?$/.test(d.header.latitude)) { fail($('#locLat'), 'Latitud inválida.'); ok = false; }
                if (!/^-?\d+(\.\d+)?$/.test(d.header.longitude)) { fail($('#locLng'), 'Longitud inválida.'); ok = false; }
                if (!d.header.name) { fail($('#locName'), 'Indica el nombre del lugar.'); ok = false; }
            } else if (!d.header.file) {
                $('#dzErr-' + d.header.format).text('Selecciona un archivo válido.').addClass('show'); ok = false;
            }
        }

        if (d.footer && !d.footer.text) { fail($('#footerText'), 'Escribe el texto del pie de página.'); ok = false; }

        if (!quickRepliesAreGrouped(state.buttons)) {
            ButtonBuilder.warn('Los botones de Respuesta rápida deben ir agrupados entre sí (ej: [URL, QR, QR], no [QR, URL, QR]).');
            ok = false;
        }

        d.buttons.forEach(function (b, i) {
            var $item = $('#buttonsList .btn-item').eq(i);
            if (b.type !== 'COPY_CODE' && !b.text) { fail($item.find('[data-field="text"]'), 'El texto es obligatorio.'); ok = false; }
            else if (b.text && b.text.length > LIMITS.btnText) { fail($item.find('[data-field="text"]'), 'Máximo ' + LIMITS.btnText + ' caracteres.'); ok = false; }
            if (b.type === 'URL') {
                var raw = (state.buttons[i].url || '');
                var u = raw.replace(/\{\{\d+\}\}/g, 'x');
                if (!/^https?:\/\/[^\s]+\.[^\s]+/.test(u)) { fail($item.find('[data-field="url"]'), 'Ingresa una URL válida (https://…).'); ok = false; }
                var urlVars = VariableManager.detect(raw);
                if (urlVars.length > 1) { fail($item.find('[data-field="url"]'), 'Un botón URL solo admite 1 variable.'); ok = false; }
                else if (urlVars.length === 1 && !/\{\{\d+\}\}$/.test(raw.trim())) {
                    fail($item.find('[data-field="url"]'), 'La variable debe ir al final de la URL.'); ok = false;
                }
                if (urlVars.length && !state.buttons[i].urlExample) { fail($item.find('[data-field="urlExample"]'), 'Agrega un ejemplo de la URL.'); ok = false; }
            }
            if (b.type === 'PHONE_NUMBER' && !/^\+?\d{7,15}$/.test(b.phone_number || '')) {
                fail($item.find('[data-field="phone"]'), 'Número inválido. Ej: +573001234567'); ok = false;
            }
            if (b.type === 'COPY_CODE') {
                var code = state.buttons[i].example || '';
                if (!code) { fail($item.find('[data-field="example"]'), 'Agrega el código de ejemplo.'); ok = false; }
                else if (code.length > LIMITS.copyCodeExample) { fail($item.find('[data-field="example"]'), 'Máximo ' + LIMITS.copyCodeExample + ' caracteres.'); ok = false; }
            }
        });

        if (!ok) {
            var $first = $('#tplForm .is-invalid, #tplForm .field-error.show').first();
            if ($first.length) $first[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            toast('Revisa los campos marcados en rojo.', true);
        }
        return ok;
    }

    /* ============================================================
       API pública
       ============================================================ */
    // Modelo uniforme usado por el formulario y la vista previa
    // (header/body/footer/buttons), igual para todas las categorías.
    function getTemplateData(clean) {
        var data = {
            name: ($('#tplName').val() || '').trim(),
            language: $('#tplLanguage').val(),
            category: $('#tplCategory').val(),
            header: HeaderBuilder.data(),
            body: BodyBuilder.data(),
            footer: FooterBuilder.data(),
            buttons: ButtonBuilder.data()
        };
        if (state.mode === 'edit' && state.id) data.id = state.id;
        if (clean && data.header && data.header.url) { data.header = $.extend({}, data.header); delete data.header.url; }
        return data;
    }

    // Payload real que espera la API de Meta. Para AUTHENTICATION el
    // formato es completamente distinto al de MARKETING/UTILITY
    // (ver sección 6 de las reglas: body sin "text", footer sin
    // "text", un único botón "otp").
    function buildMetaPayload() {
        var name = ($('#tplName').val() || '').trim();
        var language = $('#tplLanguage').val();
        var categoryCode = Number($('#tplCategory').val());
        var metaCategory = CATEGORY_META_MAP[categoryCode]; // string que espera la API de Meta

        if (categoryCode === CATEGORY_AUTH) {
            var authComponents = [
                { type: 'BODY', add_security_recommendation: $('#authSecurityRec').is(':checked') }
            ];
            var min = parseInt($('#authExpiration').val(), 10);
            if (min) authComponents.push({ type: 'FOOTER', code_expiration_minutes: min });

            var otp = state.buttons[0] || { otp_type: 'COPY_CODE' };
            var otpBtn = { type: 'OTP', otp_type: otp.otp_type || 'COPY_CODE' };
            if (otp.text) otpBtn.text = otp.text;
            if (otp.otp_type === 'ONE_TAP' || otp.otp_type === 'ZERO_TAP') {
                otpBtn.package_name = otp.package || '';
                otpBtn.signature_hash = otp.signature || '';
            }
            if (otp.otp_type === 'ZERO_TAP') otpBtn.zero_tap_terms_accepted = !!otp.zeroTermsAccepted;
            authComponents.push({ type: 'BUTTONS', buttons: [otpBtn] });

            return { name: name, language: language, category: metaCategory, components: authComponents };
        }

        // MARKETING / UTILITY: se arma el árbol real de "components" que
        // espera la API de Meta (mismo formato que consume TemplatesService
        // en el backend). El header_handle de archivos (IMAGE/VIDEO/DOCUMENT)
        // NO se resuelve aquí: el backend lo agrega después de subir el
        // archivo, por eso ese componente sale sin "example".
        var components = [];

        var h = HeaderBuilder.data();
        if (h) {
            if (h.format === 'TEXT') {
                var hc = { type: 'HEADER', format: 'TEXT', text: h.text };
                if (h.examples && h.examples.length) hc.example = { header_text: h.examples };
                components.push(hc);
            } else if (h.format === 'LOCATION') {
                components.push({ type: 'HEADER', format: 'LOCATION' });
            } else {
                components.push({ type: 'HEADER', format: h.format });
            }
        }

        var b = BodyBuilder.data();
        var bodyComponent = { type: 'BODY', text: b.text };
        var bvars = VariableManager.detect(b.text);
        if (bvars.length) {
            if (VariableManager.kind(bvars) === 'named') {
                bodyComponent.example = {
                    body_text_named_params: bvars.map(function (v, i) { return { param_name: v, example: b.examples[i] }; })
                };
            } else {
                bodyComponent.example = { body_text: [b.examples] };
            }
        }
        components.push(bodyComponent);

        var f = FooterBuilder.data();
        if (f && f.text) components.push({ type: 'FOOTER', text: f.text });

        var buttons = state.buttons.map(function (bt) {
            if (bt.type === 'URL') {
                var out = { type: 'URL', text: bt.text, url: bt.url };
                if (VariableManager.detect(bt.url).length) out.example = [bt.urlExample];
                return out;
            }
            if (bt.type === 'PHONE_NUMBER') return { type: 'PHONE_NUMBER', text: bt.text, phone_number: bt.phone };
            if (bt.type === 'COPY_CODE') return { type: 'COPY_CODE', example: [bt.example] };
            return { type: bt.type, text: bt.text }; // QUICK_REPLY, CATALOG, MPM
        });
        if (buttons.length) components.push({ type: 'BUTTONS', buttons: buttons });

        return { name: name, language: language, category: metaCategory, components: components };
    }

    // Muestra/oculta secciones según la categoría elegida.
    function applyCategoryUI() {
        var auth = isAuth();

        $('#secHeader').toggleClass('d-none', auth);
        if (auth) $('#headerSwitch').prop('checked', false).trigger('change');

        $('#bodyAuthNotice').toggleClass('d-none', !auth);
        $('#bodyFreePane').toggleClass('d-none', auth);
        $('#bodyAuthPane').toggleClass('d-none', !auth);

        $('#footerFreePane').toggleClass('d-none', auth);
        $('#footerAuthPane').toggleClass('d-none', !auth);
        $('#footerOptionalLabel').text(auth ? 'Opcional' : 'Opcional');
        $('#footerHint').text(auth ? 'Solo se define el tiempo de expiración del código; Meta arma el texto.' : 'Una línea breve visible debajo del mensaje.');

        $('#authButtonNotice').toggleClass('d-none', !auth);
        $('#buttonsHint').toggleClass('d-none', auth);

        // Si se cambia de categoría con botones ya cargados que ya no
        // son válidos para la nueva categoría, se limpian para evitar
        // enviar un payload inconsistente.
        var keep = state.buttons.filter(function (b) { return auth ? b.type === 'OTP' : b.type !== 'OTP'; });
        if (keep.length !== state.buttons.length) state.buttons = keep;

        ButtonBuilder.buildMenu();
        ButtonBuilder.render();

        if (auth) $('#authBodyPreview').text(BodyBuilder.authText());
    }

    function openTemplateModal(template) {
        template = template || null;
        state.mode = template ? 'edit' : 'create';
        state.id = template ? (template.id || null) : null;
        state.buttons = []; state.media = { IMAGE: null, VIDEO: null, DOCUMENT: null };

        $('#tplForm')[0].reset();
        clearErrors();
        $('#buttonsList').empty(); $('#buttonsEmpty').removeClass('d-none');
        ['IMAGE', 'VIDEO', 'DOCUMENT'].forEach(function (k) { $('#chip-' + k).empty().addClass('d-none'); });

        $('#headerSwitch').prop('checked', false).trigger('change');
        $('#footerSwitch').prop('checked', false).trigger('change');
        $('#bodyText').val('').trigger('input');
        $('#authSecurityRec').prop('checked', true);
        $('#authExpiration').val('');
        $('#tplCategory').val(CATEGORY_MARKETING).trigger('change');
        $('#tplLanguage').val('es');

        if (template) {
            $('#tplName').val(template.name || '').trigger('input');
            $('#tplCategory').val(template.category || CATEGORY_MARKETING).trigger('change');
            $('#tplLanguage').val(template.language || 'es');
            HeaderBuilder.load(template.header);
            BodyBuilder.load(template.body);
            FooterBuilder.load(template.footer);
            ButtonBuilder.load(template.buttons);
            applyCategoryUI();
        }

        $('#tplModalTitle').text(template ? 'Editar plantilla' : 'Crear plantilla');
        $('#tplSubmitLabel').text(template ? 'Guardar cambios' : 'Crear plantilla');

        state.dirty = false;
        Preview.render();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('tplModal')).show();
    }

    function submitTemplate() {
        if (!validate()) return;
        var data = buildMetaPayload();
        var $btn = $('#tplSubmit');
        $btn.prop('disabled', true);
        // $('#tplSpinner').removeClass('d-none');

        // Integración Laravel: descomenta para conectar el backend real.
        // $.ajax({ url: '/whatsapp/templates', method: state.mode === 'edit' ? 'PUT' : 'POST', data: data })
        //   .done(onSuccess).fail(onError).always(reset);

        setTimeout(function () {
            $btn.prop('disabled', false);
            $('#tplSpinner').addClass('d-none');
            state.dirty = false;
            bootstrap.Modal.getInstance(document.getElementById('tplModal')).hide();
            TemplatesList.upsert($.extend({ id: state.id || Date.now(), status: 'PENDING' }, getTemplateData()));
            toast(state.mode === 'edit' ? 'Plantilla actualizada correctamente.' : 'Plantilla enviada a revisión de Meta.');
        }, 900);
    }

    /* ============================================================
       Listado de plantillas (demo)
       ============================================================ */
    var TemplatesList = {
        items: [
            {
                id: 1, name: 'promocion_especial', language: 'es', category: CATEGORY_MARKETING, status: 'APPROVED',
                header: { format: 'TEXT', text: '🔥 Promoción especial' },
                body: { text: 'Hola {{nombre}}, obtén {{descuento}} de descuento durante esta semana.', examples: ['Carlos', '20%'] },
                footer: { text: 'Promoción válida hasta el 30 de septiembre.' },
                buttons: [{ type: 'URL', text: 'Ver promoción', url: 'https://ejemplo.com/promocion' }, { type: 'QUICK_REPLY', text: 'No me interesa' }]
            },
            {
                id: 2, name: 'confirmacion_pedido', language: 'es', category: CATEGORY_UTILITY, status: 'APPROVED',
                header: null,
                body: { text: 'Hola {{1}}, tu pedido {{2}} fue confirmado y llegará el {{3}}.', examples: ['Ana', '#12345', '30 de agosto'] },
                footer: null, buttons: [{ type: 'QUICK_REPLY', text: 'Rastrear pedido' }]
            },
            {
                id: 3, name: 'codigo_verificacion', language: 'es', category: CATEGORY_AUTH, status: 'PENDING',
                header: null, body: { text: '{{1}} is your verification code. For your security, do not share this code.', add_security_recommendation: true },
                footer: { code_expiration_minutes: 10 },
                buttons: [{ type: 'otp', otp_type: 'COPY_CODE', text: 'Copiar código' }]
            }
        ],
        statusMeta: {
            APPROVED: ['badge-ok', 'Aprobada'], PENDING: ['badge-warn', 'En revisión'], REJECTED: ['badge-err', 'Rechazada']
        },
        upsert: function (tpl) {
            var i = TemplatesList.items.findIndex(function (t) { return t.id === tpl.id; });
            if (i > -1) TemplatesList.items[i] = tpl; else TemplatesList.items.unshift(tpl);
            TemplatesList.render();
        },
        render: function () {
            var html = '';
            TemplatesList.items.forEach(function (t) {
                var st = TemplatesList.statusMeta[t.status] || TemplatesList.statusMeta.PENDING;
                html += '<div class="col-12 col-md-6 col-xl-4"><div class="tpl-card h-100 p-3">' +
                    '<div class="d-flex align-items-start gap-2 mb-2">' +
                    '<span class="badge-soft ' + st[0] + '">' + st[1] + '</span>' +
                    '<span class="badge-soft">' + esc(CATEGORY_LABELS[Number(t.category)] || t.category) + '</span>' +
                    '<span class="badge-soft ms-auto">' + esc(t.language) + '</span></div>' +
                    '<div class="fw-bold">' + esc(t.name) + '</div>' +
                    '<div class="text-muted-2 small mt-1" style="min-height:44px">' + esc((t.body && t.body.text || '').slice(0, 110)) + '</div>' +
                    '<div class="d-flex gap-2 mt-3">' +
                    '<button class="btn btn-soft btn-sm flex-grow-1" data-edit="' + t.id + '"><i class="fa-solid fa-pen me-1"></i>Editar</button>' +
                    '</div></div></div>';
            });
            $('#tplGrid').html(html);
        }
    };

    /* ============================================================
       Init
       ============================================================ */
    $(function () {
        HeaderBuilder.init();
        BodyBuilder.init();
        FooterBuilder.init();
        ButtonBuilder.init();

        $('#tplName').on('input', function () {
            this.value = this.value.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
            Counter.update($(this), LIMITS.name);
            markDirty();
        });
        $('#tplCategory').on('change', function () {
            $('#catDesc').text(CATEGORIES[this.value] || '');
            applyCategoryUI();
            markDirty(); Preview.render();
        }).trigger('change');
        $('#tplLanguage').on('change', markDirty);

        $('#tplSubmit').on('click', submitTemplate);

        $('#tplModal').on('hide.bs.modal', function (e) {
            if (state.dirty && !window.confirm('Tienes cambios sin guardar. ¿Deseas cerrar el modal?')) {
                e.preventDefault();
            }
        });

        $('#tplGrid').on('click', '[data-edit]', function () {
            var id = $(this).data('edit');
            var tpl = TemplatesList.items.filter(function (t) { return t.id === id; })[0];
            openTemplateModal(tpl);
        });
        $('#newTemplateBtn').on('click', function () { openTemplateModal(null); });

        $('[data-bs-toggle="tooltip"]').each(function () { new bootstrap.Tooltip(this); });
        TemplatesList.render();
        Preview.render();
    });

    const enviarDatos = (form) => {
        if (!validate()) return;

        let formData = new FormData(document.getElementById("formCrearPlantilla"));
        formData.append('payload', JSON.stringify(buildMetaPayload()));

        // Si el header es de tipo archivo (IMAGE/VIDEO/DOCUMENT), se adjunta
        // el archivo real bajo la clave "archivo" para que el backend lo
        // suba a Meta y arme el header_handle.
        if ($('#headerSwitch').is(':checked')) {
            var headerType = $('#headerTypeGrid .type-opt.active').data('htype');
            var media = state.media[headerType];
            if (media && media.file) formData.append('archivo', media.file);
        }

        const config = {
            'method': 'POST',
            'headers': {
                'Accept': generalidades.CONTENT_TYPE_JSON,
            },
            'body': formData
        }

        const success = (response) => {
            if (response.estado == 'success') {
                $('.btnCerrarModal').trigger('click');
                generalidades.ocultarValidaciones(formCrearPlantilla);
                window.sincronizarPlantillas();
            }
            generalidades.ocultarCargando(formCrearPlantilla);
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
        }

        const error = (response) => {
            generalidades.ocultarCargando(formCrearPlantilla);
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
            generalidades.mostrarValidaciones(formCrearPlantilla, response.validaciones);
        }
        const ruta = route("plantillas.store");
        generalidades.create(ruta, config, success, error);
        generalidades.mostrarCargando(formCrearPlantilla);
    }

    $(document).on('hidden.bs.modal', modalCrearPlantilla, function (e) {
        generalidades.resetValidate(formCrearPlantilla);
    });


    /* Exponer API */
    window.openTemplateModal = openTemplateModal;
    window.getTemplateData = getTemplateData;
    window.buildMetaPayload = buildMetaPayload;
    window.validateTemplate = validate;
    generalidades.validarFormulario(formCrearPlantilla, enviarDatos);
})(jQuery);
