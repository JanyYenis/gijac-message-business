/* ============================================================
   GIJAC MESSAGE BUSINESS — Interactions (jQuery + Vanilla)
   ============================================================ */

$(function () {

    /* ---------- Header scroll effect ---------- */
    const $header = $('#main-header');
    const $backTop = $('#back-to-top');
    function onScroll() {
        const y = window.scrollY;
        $header.toggleClass('scrolled', y > 30);
        $backTop.toggleClass('show', y > 500);
    }
    $(window).on('scroll', onScroll);
    onScroll();

    /* ---------- Smooth anchor scroll + active link ---------- */
    $('a[href^="#"]').on('click', function (e) {
        const target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            const offset = target.offset().top - 70;
            $('html, body').animate({ scrollTop: offset }, 700);
            // close mobile menu
            const $collapse = $('#navContent');
            if ($collapse.hasClass('show')) {
                $collapse.collapse('hide');
            }
        }
    });

    $backTop.on('click', function () {
        $('html, body').animate({ scrollTop: 0 }, 700);
    });

    /* ---------- Modules data ---------- */
    const modules = [
        { icon: 'bi-speedometer2', title: 'Dashboard', desc: 'Panel de control completo con métricas en tiempo real, estadísticas de campañas y análisis detallado de rendimiento.' },
        { icon: 'bi-chat-dots', title: 'Chat', desc: 'Sistema de chat centralizado para gestionar todas las conversaciones con tus clientes desde una sola interfaz.' },
        { icon: 'bi-megaphone', title: 'Campañas', desc: 'Crea y programa campañas masivas personalizadas con segmentación avanzada y programación automática.' },
        { icon: 'bi-file-earmark-text', title: 'Plantillas', desc: 'Biblioteca de plantillas pre-diseñadas y personalizables para diferentes tipos de mensajes y campañas.' },
        { icon: 'bi-tags', title: 'Etiquetas', desc: 'Sistema de etiquetado inteligente para organizar contactos y segmentar audiencias de forma eficiente.' },
        { icon: 'bi-gear-wide-connected', title: 'Configuración', desc: 'Panel de configuración avanzado para personalizar la plataforma según las necesidades de tu negocio.' },
        { icon: 'bi-person-vcard', title: 'Contactos', desc: 'Gestión completa de contactos con importación masiva, sincronización y organización automática.' },
        { icon: 'bi-ticket-detailed', title: 'Tickets', desc: 'Sistema de tickets integrado para gestionar solicitudes de soporte y seguimiento de consultas.' },
        { icon: 'bi-robot', title: 'Respuestas Automáticas', desc: 'Chatbot inteligente con respuestas automáticas personalizables para atención 24/7.' }
    ];

    const $grid = $('#modules-grid');
    modules.forEach(function (m) {
        $grid.append(
            '<div class="col-md-6 col-lg-4 reveal" data-reveal="up">' +
            '<div class="module-card tilt-3d" data-tilt>' +
            '<div class="module-icon"><i class="bi ' + m.icon + '"></i></div>' +
            '<h4>' + m.title + '</h4>' +
            '<p>' + m.desc + '</p>' +
            '</div>' +
            '</div>'
        );
    });

    /* ---------- Scroll reveal (IntersectionObserver) ---------- */
    const revealEls = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry, i) {
            if (entry.isIntersecting) {
                const el = entry.target;
                // stagger siblings a touch
                const delay = (Array.prototype.indexOf.call(el.parentNode.children, el) % 3) * 90;
                setTimeout(function () { el.classList.add('visible'); }, delay);
                io.unobserve(el);
            }
        });
    }, { threshold: 0.15 });
    revealEls.forEach(function (el) { io.observe(el); });

    /* ---------- Hero words sequential reveal ---------- */
    $('.reveal-word').each(function (i) {
        const $w = $(this);
        setTimeout(function () {
            $w.css({ opacity: 1, transform: 'translateY(0)', transition: 'all .6s cubic-bezier(.2,.8,.2,1)' });
        }, 200 + i * 90);
    });

    /* ---------- Counter animation ---------- */
    function animateCount($el) {
        const target = parseFloat($el.data('count'));
        const decimals = parseInt($el.data('decimals') || 0, 10);
        const suffix = $el.data('suffix') || '';
        const duration = 1800;
        const start = performance.now();
        function frame(now) {
            const p = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - p, 3);
            let val = target * eased;
            let display = decimals ? val.toFixed(decimals) : Math.floor(val).toLocaleString('es-ES');
            $el.text(display + suffix);
            if (p < 1) requestAnimationFrame(frame);
            else $el.text((decimals ? target.toFixed(decimals) : target.toLocaleString('es-ES')) + suffix);
        }
        requestAnimationFrame(frame);
    }
    const counterIO = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            const $el = $(entry.target);
            if (entry.isIntersecting && !$el.data('done') && $el.data('count') !== undefined) {
                $el.data('done', true);
                animateCount($el);
                counterIO.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    $('.stat-number[data-count]').each(function () { counterIO.observe(this); });

    /* ---------- 3D Tilt on hover ---------- */
    const supportsHover = window.matchMedia('(hover: hover)').matches;
    if (supportsHover) {
        $(document).on('mousemove', '[data-tilt]', function (e) {
            const el = this;
            const r = el.getBoundingClientRect();
            const x = (e.clientX - r.left) / r.width - 0.5;
            const y = (e.clientY - r.top) / r.height - 0.5;
            const max = 8;
            el.style.transform = 'perspective(900px) rotateY(' + (x * max) + 'deg) rotateX(' + (-y * max) + 'deg) scale(1.02)';
        });
        $(document).on('mouseleave', '[data-tilt]', function () {
            this.style.transform = 'perspective(900px) rotateY(0) rotateX(0) scale(1)';
        });
    }

    /* ---------- Magnetic buttons ---------- */
    if (supportsHover) {
        $(document).on('mousemove', '.magnetic', function (e) {
            const r = this.getBoundingClientRect();
            const x = e.clientX - r.left - r.width / 2;
            const y = e.clientY - r.top - r.height / 2;
            this.style.transform = 'translate(' + x * 0.25 + 'px,' + y * 0.35 + 'px)';
        });
        $(document).on('mouseleave', '.magnetic', function () {
            this.style.transform = 'translate(0,0)';
        });
    }

    /* ---------- IA chat simulation ---------- */
    const chatScript = [
        { side: 'in', text: 'Hola 👋 Quiero información sobre sus planes de campañas.' },
        { side: 'out', text: '¡Hola! Con gusto. Tenemos campañas masivas con segmentación e IA. ¿Cuántos contactos manejas?' },
        { side: 'in', text: 'Alrededor de 8.000 clientes.' },
        { side: 'out', text: 'Perfecto ✅ Con nuestro plan Business puedes enviar mensajes ilimitados y automatizar respuestas 24/7.' }
    ];
    const $chatBody = $('#chat-body');
    const $typing = $('#chat-typing');
    let chatStarted = false;

    function playChat(i) {
        if (i >= chatScript.length) {
            setTimeout(function () { $chatBody.empty(); playChat(0); }, 3500);
            return;
        }
        $typing.css('display', 'flex');
        setTimeout(function () {
            $typing.hide();
            const msg = chatScript[i];
            $chatBody.append('<div class="chat-bubble ' + msg.side + '">' + msg.text + '</div>');
            $chatBody.scrollTop($chatBody[0].scrollHeight);
            setTimeout(function () { playChat(i + 1); }, 1400);
        }, 1100);
    }

    const chatIO = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting && !chatStarted) {
                chatStarted = true;
                playChat(0);
            }
        });
    }, { threshold: 0.4 });
    if ($chatBody.length) chatIO.observe($chatBody[0]);

    /* ---------- Particle background (neural network) ---------- */
    const canvas = document.getElementById('particle-canvas');
    const ctx = canvas.getContext('2d');
    let W, H, particles;

    function resize() {
        W = canvas.width = window.innerWidth;
        H = canvas.height = window.innerHeight;
        const count = Math.min(70, Math.floor(W / 22));
        particles = [];
        for (let i = 0; i < count; i++) {
            particles.push({
                x: Math.random() * W,
                y: Math.random() * H,
                vx: (Math.random() - 0.5) * 0.4,
                vy: (Math.random() - 0.5) * 0.4,
                r: Math.random() * 1.8 + 0.8
            });
        }
    }

    function draw() {
        ctx.clearRect(0, 0, W, H);
        for (let i = 0; i < particles.length; i++) {
            const p = particles[i];
            p.x += p.vx; p.y += p.vy;
            if (p.x < 0 || p.x > W) p.vx *= -1;
            if (p.y < 0 || p.y > H) p.vy *= -1;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(44,143,153,0.5)';
            ctx.fill();
            for (let j = i + 1; j < particles.length; j++) {
                const q = particles[j];
                const dx = p.x - q.x, dy = p.y - q.y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 130) {
                    ctx.beginPath();
                    ctx.moveTo(p.x, p.y);
                    ctx.lineTo(q.x, q.y);
                    ctx.strokeStyle = 'rgba(44,143,153,' + (0.12 * (1 - dist / 130)) + ')';
                    ctx.lineWidth = 1;
                    ctx.stroke();
                }
            }
        }
        requestAnimationFrame(draw);
    }

    resize();
    draw();
    let resizeT;
    $(window).on('resize', function () {
        clearTimeout(resizeT);
        resizeT = setTimeout(resize, 200);
    });

});
