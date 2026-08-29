/* ============================================================
   GIJAC Message Business · Centro de Recursos
   Interacciones: jQuery + JavaScript vanilla
   ============================================================ */
$(function () {
    'use strict';

    var REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var IS_TOUCH = window.matchMedia('(max-width: 991.98px)').matches;

    /* ---------- Navbar dinámico + barra de lectura + back to top ---------- */
    var $nav = $('#gjNav'), $bar = $('#readbar'), $top = $('#backTop');
    function onScroll() {
        var y = window.scrollY || document.documentElement.scrollTop;
        $nav.toggleClass('scrolled', y > 40);
        $top.toggleClass('show', y > 600);
        var h = document.documentElement.scrollHeight - window.innerHeight;
        $bar.css('width', (h > 0 ? (y / h) * 100 : 0) + '%');
    }
    $(window).on('scroll', onScroll); onScroll();
    $top.on('click', function () { window.scrollTo({ top: 0, behavior: REDUCED ? 'auto' : 'smooth' }); });

    /* ---------- Ripple en botones ---------- */
    $(document).on('click', '.btn-gj, .btn-ghost, .btn-outline-gj', function (e) {
        var $b = $(this), o = $b.offset(), d = Math.max($b.outerWidth(), $b.outerHeight());
        $('<span class="ripple"></span>').css({
            width: d, height: d, left: e.pageX - o.left - d / 2, top: e.pageY - o.top - d / 2
        }).appendTo($b).on('animationend', function () { $(this).remove(); });
    });

    /* ---------- Scroll reveal ---------- */
    var revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && !REDUCED) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en, i) {
                if (en.isIntersecting) {
                    setTimeout(function () { en.target.classList.add('in'); }, (en.target.dataset.delay || 0) * 1);
                    io.unobserve(en.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
        revealEls.forEach(function (el) { io.observe(el); });
    } else {
        revealEls.forEach(function (el) { el.classList.add('in'); });
    }

    /* ---------- Tilt 3D en cards ---------- */
    if (!IS_TOUCH && !REDUCED) {
        $(document).on('mousemove', '.tilt', function (e) {
            var r = this.getBoundingClientRect();
            var px = (e.clientX - r.left) / r.width - 0.5;
            var py = (e.clientY - r.top) / r.height - 0.5;
            this.style.transform = 'perspective(900px) rotateY(' + (px * 7).toFixed(2) + 'deg) rotateX(' +
                (-py * 7).toFixed(2) + 'deg) translateY(-8px) scale(1.02)';
        }).on('mouseleave', '.tilt', function () { this.style.transform = ''; });
    }

    /* ---------- Parallax del elemento 3D del hero ---------- */
    var deck = document.getElementById('heroDeck');
    if (deck && !IS_TOUCH && !REDUCED) {
        document.querySelector('.hero').addEventListener('mousemove', function (e) {
            var w = window.innerWidth, h = window.innerHeight;
            var x = (e.clientX / w - 0.5), y = (e.clientY / h - 0.5);
            deck.style.transform = 'rotateX(' + (12 - y * 10).toFixed(2) + 'deg) rotateY(' + (-16 + x * 14).toFixed(2) + 'deg)';
        });
    }

    /* ---------- Contadores animados ---------- */
    function animateCounter($el) {
        var target = parseFloat($el.data('count')), suf = $el.data('suffix') || '', start = null, dur = 1400;
        if (REDUCED) { $el.text(target + suf); return; }
        function step(ts) {
            if (!start) start = ts;
            var p = Math.min((ts - start) / dur, 1);
            var val = Math.floor(target * (1 - Math.pow(1 - p, 3)));
            $el.text(val.toLocaleString('es-CO') + suf);
            if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }
    if ('IntersectionObserver' in window) {
        var cio = new IntersectionObserver(function (en) {
            en.forEach(function (e) {
                if (e.isIntersecting) { animateCounter($(e.target)); cio.unobserve(e.target); }
            });
        }, { threshold: 0.5 });
        $('[data-count]').each(function () { cio.observe(this); });

        var pio = new IntersectionObserver(function (en) {
            en.forEach(function (e) {
                if (e.isIntersecting) {
                    var $s = $(e.target).find('span');
                    $s.css('width', $s.data('value') + '%');
                    pio.unobserve(e.target);
                }
            });
        }, { threshold: 0.4 });
        $('.progress-gj').each(function () { pio.observe(this); });
    }

    /* ---------- Buscador dinámico ---------- */
    var INDEX = [
        { g: 'Cursos', i: 'fa-graduation-cap', t: 'Crea tu primera campaña', s: '8 lecciones · 32 min', k: 'campana campaña campañas whatsapp envio masivo' },
        { g: 'Cursos', i: 'fa-graduation-cap', t: 'Primeros pasos con GIJAC Message Business', s: '12 lecciones · 45 min', k: 'primeros pasos inicio cuenta configuracion' },
        { g: 'Cursos', i: 'fa-graduation-cap', t: 'Automatiza tu atención con Chatbots', s: '10 lecciones · 55 min', k: 'chatbot bot flujo atencion automatica' },
        { g: 'Cursos', i: 'fa-graduation-cap', t: 'Asistentes IA para atención al cliente', s: '9 lecciones · 50 min', k: 'ia inteligencia artificial asistente' },
        { g: 'Videos', i: 'fa-circle-play', t: '¿Cómo crear una campaña?', s: '04:32 · Campañas', k: 'campana campaña campañas video' },
        { g: 'Videos', i: 'fa-circle-play', t: 'Configura tu chatbot en 6 minutos', s: '06:14 · Chatbots', k: 'chatbot bot configuracion' },
        { g: 'Videos', i: 'fa-circle-play', t: 'Entrena tu asistente IA', s: '08:05 · IA', k: 'ia asistente entrenamiento base conocimiento' },
        { g: 'Documentación', i: 'fa-book', t: 'Crear una campaña', s: 'Campañas · 12 artículos', k: 'campana campaña campañas documentacion' },
        { g: 'Documentación', i: 'fa-book', t: 'Importar contactos', s: 'Contactos · 9 artículos', k: 'contactos importar csv segmentar' },
        { g: 'Documentación', i: 'fa-book', t: 'Conectar n8n', s: 'Automatizaciones · 7 artículos', k: 'automatizacion automatizaciones n8n webhook' },
        { g: 'Guías', i: 'fa-bolt', t: 'Lanza tu primera campaña en 5 minutos', s: '⏱ 5 min · Principiante', k: 'campana campaña campañas guia rapida' },
        { g: 'Guías', i: 'fa-bolt', t: 'Configura tu primer chatbot', s: '⏱ 8 min · Principiante', k: 'chatbot guia rapida' },
        { g: 'Guías', i: 'fa-bolt', t: 'Conecta una automatización', s: '⏱ 10 min · Intermedio', k: 'automatizacion automatizaciones n8n integracion' }
    ];
    function norm(s) { return s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, ''); }
    var $input = $('#heroSearch'), $res = $('#searchResults');

    function renderSearch(q) {
        if (!q || q.length < 2) { $res.hide().empty(); return; }
        var nq = norm(q);
        var hits = INDEX.filter(function (it) { return norm(it.t + ' ' + it.k).indexOf(nq) > -1; });
        if (!hits.length) {
            $res.html('<div class="sr-empty"><i class="fa-regular fa-face-frown mb-2 d-block fs-4"></i>Sin resultados para <b>' + $('<i>').text(q).html() + '</b></div>').show();
            return;
        }
        var groups = {};
        hits.forEach(function (h) { (groups[h.g] = groups[h.g] || []).push(h); });
        var html = '';
        Object.keys(groups).forEach(function (g) {
            html += '<div class="sr-group">' + g + '</div>';
            groups[g].slice(0, 4).forEach(function (h) {
                html += '<div class="sr-item"><i class="fa-solid ' + h.i + '"></i><div><div class="fw-semibold">' +
                    h.t + '</div><small>' + h.s + '</small></div></div>';
            });
        });
        $res.html(html).show();
    }
    var tmr;
    $input.on('input', function () {
        var v = this.value;
        clearTimeout(tmr); tmr = setTimeout(function () { renderSearch(v); }, 140);
    }).on('focus', function () { if (this.value.length > 1) renderSearch(this.value); });
    $('.tag-chip').on('click', function () { $input.val($(this).text()).trigger('input').focus(); });
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.searchbox').length) $res.hide();
    });
    $(document).on('click', '.sr-item', function () {
        toast('Abriendo: ' + $(this).find('.fw-semibold').text());
        $res.hide();
    });
    $('#navSearch').on('click', function () {
        window.scrollTo({ top: 0, behavior: REDUCED ? 'auto' : 'smooth' });
        setTimeout(function () { $input.focus(); }, 500);
    });

    /* ---------- Documentación: sidebar expandible + panel ---------- */
    var DOCS = {
        intro: { title: 'Introducción', desc: 'Conoce la plataforma, la estructura de tu cuenta y los primeros ajustes recomendados.', count: 6, items: ['Qué es GIJAC Message Business', 'Estructura de la cuenta', 'Conectar tu número de WhatsApp', 'Roles y permisos', 'Panel principal', 'Glosario'] },
        campanas: { title: 'Campañas', desc: 'Todo sobre la creación, programación y análisis de campañas de WhatsApp.', count: 12, items: ['Crear una campaña', 'Programar una campaña', 'Seleccionar contactos', 'Utilizar plantillas', 'Consultar resultados', 'Buenas prácticas de envío'] },
        contactos: { title: 'Contactos', desc: 'Importa, organiza y segmenta tu base de contactos con etiquetas y listas.', count: 9, items: ['Importar contactos desde CSV', 'Crear listas', 'Etiquetas y segmentos', 'Campos personalizados', 'Exportar contactos'] },
        chatbots: { title: 'Chatbots', desc: 'Diseña flujos conversacionales automáticos y define transferencias a agentes.', count: 11, items: ['Crear un chatbot', 'Palabras clave', 'Horario de atención', 'Mensajes especiales', 'Transferencia a agente'] },
        ia: { title: 'Inteligencia Artificial', desc: 'Configura asistentes IA con tu propia base de conocimiento.', count: 8, items: ['Crear un asistente IA', 'Base de conocimiento', 'Tono y personalidad', 'Límites y respuestas no resueltas'] },
        automatizaciones: { title: 'Automatizaciones', desc: 'Conecta GIJAC con herramientas externas mediante webhooks y n8n.', count: 7, items: ['Conectar n8n', 'Webhooks entrantes', 'Disparadores y acciones', 'Registro de ejecuciones'] },
        plantillas: { title: 'Plantillas', desc: 'Crea, envía a aprobación y administra tus plantillas de WhatsApp.', count: 10, items: ['Crear una plantilla', 'Categorías y aprobación', 'Variables dinámicas', 'Botones y CTA', 'Estados de rechazo'] },
        metricas: { title: 'Métricas', desc: 'Interpreta el rendimiento de campañas, chatbots y agentes.', count: 8, items: ['Panel de métricas', 'Entregados vs leídos', 'Tasa de respuesta', 'Exportar reportes'] },
        admin: { title: 'Administración', desc: 'Gestiona usuarios, facturación, seguridad y preferencias de la cuenta.', count: 9, items: ['Usuarios y equipos', 'Facturación y planes', 'Seguridad y sesiones', 'Registro de actividad'] }
    };
    function paintDoc(key) {
        var d = DOCS[key]; if (!d) return;
        var html = '<span class="eyebrow"><i class="fa-solid fa-book"></i> Documentación</span>' +
            '<h3 class="fw-bold mb-2">' + d.title + '</h3>' +
            '<p class="text-secondary">' + d.desc + '</p>' +
            '<div class="d-flex align-items-center gap-2 mb-4"><span class="chip">' + d.count + ' artículos</span>' +
            '<span class="chip dark">Actualizado hoy</span></div>';
        d.items.forEach(function (a) {
            html += '<a href="#documentacion" class="doc-article"><span><i class="fa-regular fa-file-lines me-2 lead-ico"></i>' +
                a + '</span><i class="fa-solid fa-arrow-right-long text-secondary"></i></a>';
        });
        $('#docPanel').html(html);
    }
    $('.doc-cat > button').on('click', function () {
        var $cat = $(this).parent();
        $('.doc-cat').removeClass('active');
        $cat.addClass('active').toggleClass('open');
        $('.doc-cat').not($cat).removeClass('open').find('.doc-sub').slideUp(180);
        $cat.find('.doc-sub').slideToggle(200);
        paintDoc($cat.data('key'));
    });
    paintDoc('campanas');
    $('.doc-cat[data-key="campanas"]').addClass('active open').find('.doc-sub').show();

    /* ---------- Modal de video ---------- */
    $(document).on('click', '.video-card', function () {
        var $c = $(this);
        $('#videoTitle').text($c.data('title'));
        $('#videoDesc').text($c.data('desc'));
        $('#videoCat').text($c.data('cat'));
        $('#videoDur').text($c.data('dur'));
        new bootstrap.Modal(document.getElementById('videoModal')).show();
    });

    /* ---------- Descargas / CTA demo ---------- */
    $(document).on('click', '[data-toast]', function (e) {
        e.preventDefault();
        toast($(this).data('toast'));
    });

    function toast(msg) {
        var $t = $('<div class="gj-toast"><i class="fa-solid fa-circle-check me-2" style="color:#2C8F99"></i>' +
            $('<i>').text(msg).html() + '</div>').appendTo('#toasts');
        setTimeout(function () { $t.fadeOut(250, function () { $(this).remove(); }); }, 3200);
    }
    window.gjToast = toast;

    /* ---------- Tooltips Bootstrap ---------- */
    $('[data-bs-toggle="tooltip"]').each(function () { new bootstrap.Tooltip(this); });

    /* ---------- Cerrar menú móvil al navegar ---------- */
    $('.navbar-collapse .nav-link').on('click', function () {
        var el = document.getElementById('gjMenu');
        if (el && el.classList.contains('show')) bootstrap.Collapse.getInstance(el).hide();
    });
});
