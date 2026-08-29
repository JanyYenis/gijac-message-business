/* GIJAC · Partículas sutiles del hero (Canvas 2D vanilla) */
(function () {
    var canvas = document.getElementById('heroCanvas');
    if (!canvas) return;
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduced) return;

    var ctx = canvas.getContext('2d');
    var particles = [];
    var mouse = { x: -999, y: -999 };
    var raf = null;

    function size() {
        var r = canvas.parentElement.getBoundingClientRect();
        var dpr = Math.min(window.devicePixelRatio || 1, 2);
        canvas.width = r.width * dpr;
        canvas.height = r.height * dpr;
        canvas.style.width = r.width + 'px';
        canvas.style.height = r.height + 'px';
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        build(r.width, r.height);
    }

    function build(w, h) {
        var count = w < 768 ? 26 : Math.min(70, Math.floor(w / 20));
        particles = [];
        for (var i = 0; i < count; i++) {
            particles.push({
                x: Math.random() * w,
                y: Math.random() * h,
                r: Math.random() * 1.8 + 0.6,
                vx: (Math.random() - 0.5) * 0.22,
                vy: (Math.random() - 0.5) * 0.22,
                o: Math.random() * 0.35 + 0.12
            });
        }
    }

    function frame() {
        var w = canvas.clientWidth, h = canvas.clientHeight;
        ctx.clearRect(0, 0, w, h);

        for (var i = 0; i < particles.length; i++) {
            var p = particles[i];
            p.x += p.vx; p.y += p.vy;
            if (p.x < 0) p.x = w; if (p.x > w) p.x = 0;
            if (p.y < 0) p.y = h; if (p.y > h) p.y = 0;

            var dx = p.x - mouse.x, dy = p.y - mouse.y;
            var d = Math.sqrt(dx * dx + dy * dy);
            if (d < 110) { p.x += dx / d * 0.5; p.y += dy / d * 0.5; }

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(191,242,247,' + p.o + ')';
            ctx.fill();

            for (var j = i + 1; j < particles.length; j++) {
                var q = particles[j];
                var ddx = p.x - q.x, ddy = p.y - q.y;
                var dd = ddx * ddx + ddy * ddy;
                if (dd < 15000) {
                    ctx.beginPath();
                    ctx.moveTo(p.x, p.y); ctx.lineTo(q.x, q.y);
                    ctx.strokeStyle = 'rgba(127,227,236,' + (0.10 * (1 - dd / 15000)) + ')';
                    ctx.lineWidth = 1;
                    ctx.stroke();
                }
            }
        }
        raf = requestAnimationFrame(frame);
    }

    canvas.parentElement.addEventListener('mousemove', function (e) {
        var r = canvas.getBoundingClientRect();
        mouse.x = e.clientX - r.left; mouse.y = e.clientY - r.top;
    });
    canvas.parentElement.addEventListener('mouseleave', function () { mouse.x = mouse.y = -999; });
    window.addEventListener('resize', size);

    // Pausar cuando el hero sale del viewport (performance)
    if ('IntersectionObserver' in window) {
        new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { if (!raf) raf = requestAnimationFrame(frame); }
                else { cancelAnimationFrame(raf); raf = null; }
            });
        }, { threshold: 0.01 }).observe(canvas);
    }

    size();
    raf = requestAnimationFrame(frame);
})();
