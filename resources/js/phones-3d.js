/* ============================================================
   GIJAC MESSAGE BUSINESS — 3D Phones (Three.js / WebGL)
   Two floating phones with live dashboard & chat screens,
   floating 3D icons, realistic shadows and mouse parallax.
   ============================================================ */
(function () {
    "use strict";

    var container = document.getElementById("phones-3d");
    if (!container || typeof THREE === "undefined") return;

    var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    // ---- Colors ----
    var TEAL = "#1E6F78", TEAL_MID = "#287F88", TEAL_LIGHT = "#2C8F99";
    var TEAL_DARK = "#0C3940", MINT = "#5fd6c9", WHITE = "#ffffff";

    // ============================================================
    //  SCENE / CAMERA / RENDERER
    // ============================================================
    var scene = new THREE.Scene();

    var camera = new THREE.PerspectiveCamera(35, 1, 0.1, 100);
    camera.position.set(0, 0, 8.4);

    var renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    if (renderer.outputEncoding !== undefined) renderer.outputEncoding = THREE.sRGBEncoding;
    container.appendChild(renderer.domElement);
    container.classList.add("ready");

    // ============================================================
    //  LIGHTS
    // ============================================================
    scene.add(new THREE.AmbientLight(0xbfe4e2, 0.55));

    var hemi = new THREE.HemisphereLight(0xffffff, 0x0b2e34, 0.5);
    scene.add(hemi);

    var keyLight = new THREE.DirectionalLight(0xffffff, 1.15);
    keyLight.position.set(-4, 6, 6);
    keyLight.castShadow = true;
    keyLight.shadow.mapSize.width = 1024;
    keyLight.shadow.mapSize.height = 1024;
    keyLight.shadow.camera.near = 1;
    keyLight.shadow.camera.far = 30;
    keyLight.shadow.camera.left = -8;
    keyLight.shadow.camera.right = 8;
    keyLight.shadow.camera.top = 8;
    keyLight.shadow.camera.bottom = -8;
    keyLight.shadow.radius = 6;
    keyLight.shadow.bias = -0.0005;
    scene.add(keyLight);

    // teal rim light for that premium glow
    var rim = new THREE.PointLight(0x2c8f99, 1.4, 30);
    rim.position.set(5, -2, 4);
    scene.add(rim);

    var fill = new THREE.PointLight(0x5fd6c9, 0.7, 30);
    fill.position.set(-5, -3, 2);
    scene.add(fill);

    // ============================================================
    //  SHADOW CATCHER (behind the phones)
    // ============================================================
    var shadowPlane = new THREE.Mesh(
        new THREE.PlaneGeometry(30, 30),
        new THREE.ShadowMaterial({ opacity: 0.32 })
    );
    shadowPlane.position.z = -2.2;
    shadowPlane.receiveShadow = true;
    scene.add(shadowPlane);

    // ============================================================
    //  HELPERS
    // ============================================================
    function roundedRectShape(w, h, r) {
        var s = new THREE.Shape();
        var x = -w / 2, y = -h / 2;
        s.moveTo(x + r, y);
        s.lineTo(x + w - r, y);
        s.quadraticCurveTo(x + w, y, x + w, y + r);
        s.lineTo(x + w, y + h - r);
        s.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
        s.lineTo(x + r, y + h);
        s.quadraticCurveTo(x, y + h, x, y + h - r);
        s.lineTo(x, y + r);
        s.quadraticCurveTo(x, y, x + r, y);
        return s;
    }

    // canvas 2D rounded rect
    function rr(ctx, x, y, w, h, r) {
        if (w < 2 * r) r = w / 2;
        if (h < 2 * r) r = h / 2;
        ctx.beginPath();
        ctx.moveTo(x + r, y);
        ctx.arcTo(x + w, y, x + w, y + h, r);
        ctx.arcTo(x + w, y + h, x, y + h, r);
        ctx.arcTo(x, y + h, x, y, r);
        ctx.arcTo(x, y, x + w, y, r);
        ctx.closePath();
    }

    // ============================================================
    //  PHONE BUILDER
    // ============================================================
    var PW = 2.05, PH = 4.25, PR = 0.34, PDEPTH = 0.26;

    function buildPhone(screenCanvas) {
        var group = new THREE.Group();

        // body (extruded rounded rect with beveled edges)
        var shape = roundedRectShape(PW, PH, PR);
        var geo = new THREE.ExtrudeGeometry(shape, {
            depth: PDEPTH, bevelEnabled: true, bevelThickness: 0.08,
            bevelSize: 0.08, bevelSegments: 6, curveSegments: 24
        });
        geo.center();
        var bodyMat = new THREE.MeshStandardMaterial({
            color: 0x0a2429, metalness: 0.85, roughness: 0.28
        });
        var body = new THREE.Mesh(geo, bodyMat);
        body.castShadow = true;
        body.receiveShadow = true;
        group.add(body);

        // metallic side frame (slightly larger, thin ring look via second material tone)
        var frameGeo = new THREE.ExtrudeGeometry(roundedRectShape(PW + 0.06, PH + 0.06, PR + 0.03), {
            depth: PDEPTH * 0.7, bevelEnabled: true, bevelThickness: 0.05,
            bevelSize: 0.05, bevelSegments: 4, curveSegments: 20
        });
        frameGeo.center();
        var frameMat = new THREE.MeshStandardMaterial({ color: 0x1E6F78, metalness: 1.0, roughness: 0.35 });
        var frame = new THREE.Mesh(frameGeo, frameMat);
        frame.position.z = -0.02;
        frame.castShadow = true;
        group.add(frame);

        // screen (live canvas texture)
        var tex = new THREE.CanvasTexture(screenCanvas);
        tex.anisotropy = renderer.capabilities.getMaxAnisotropy();
        if (tex.encoding !== undefined) tex.encoding = THREE.sRGBEncoding;
        var screenMat = new THREE.MeshBasicMaterial({ map: tex });
        var screen = new THREE.Mesh(new THREE.PlaneGeometry(PW - 0.22, PH - 0.22), screenMat);
        screen.position.z = PDEPTH / 2 + 0.11;
        group.add(screen);

        // subtle glossy reflection overlay on the glass
        var glossMat = new THREE.MeshBasicMaterial({
            color: 0xffffff, transparent: true, opacity: 0.06,
            blending: THREE.AdditiveBlending, depthWrite: false
        });
        var gloss = new THREE.Mesh(new THREE.PlaneGeometry(PW - 0.22, PH - 0.22), glossMat);
        gloss.position.z = PDEPTH / 2 + 0.12;
        group.add(gloss);

        // speaker notch (pill)
        var notchGeo = new THREE.ExtrudeGeometry(roundedRectShape(0.42, 0.11, 0.055), {
            depth: 0.04, bevelEnabled: false, curveSegments: 8
        });
        notchGeo.center();
        var notch = new THREE.Mesh(
            notchGeo,
            new THREE.MeshStandardMaterial({ color: 0x05171a, metalness: 0.6, roughness: 0.5 })
        );
        notch.position.set(0, PH / 2 - 0.3, PDEPTH / 2 + 0.12);
        group.add(notch);

        group.userData.texture = tex;
        return group;
    }

    // ============================================================
    //  SCREEN 1 — DASHBOARD (animated)
    // ============================================================
    var dashCanvas = document.createElement("canvas");
    dashCanvas.width = 520; dashCanvas.height = 1080;
    var dctx = dashCanvas.getContext("2d");

    var dashState = { msgs: 0, convs: 0, ctr: 0, bars: [0.4, 0.7, 0.5, 0.9, 0.6, 0.8, 0.75], phase: 0 };
    var dashTargets = { msgs: 12584, convs: 8452, ctr: 24.8 };

    function drawDashboard() {
        var w = dashCanvas.width, h = dashCanvas.height, ctx = dctx;
        // bg
        var g = ctx.createLinearGradient(0, 0, 0, h);
        g.addColorStop(0, "#0e3d44"); g.addColorStop(1, "#0a2b31");
        ctx.fillStyle = g; ctx.fillRect(0, 0, w, h);

        var pad = 38;
        // header
        ctx.fillStyle = "#ffffff";
        ctx.font = "700 44px 'Plus Jakarta Sans', sans-serif";
        ctx.fillText("¡Hola, Empresa!", pad, 96);
        ctx.fillStyle = "#8fc4bf";
        ctx.font = "400 26px Inter, sans-serif";
        ctx.fillText("Resumen de tu actividad", pad, 138);

        // avatar
        ctx.beginPath(); ctx.arc(w - pad - 34, 88, 34, 0, Math.PI * 2);
        ctx.fillStyle = "#2C8F99"; ctx.fill();
        ctx.fillStyle = "#fff"; ctx.font = "700 30px 'Plus Jakarta Sans'";
        ctx.textAlign = "center"; ctx.fillText("E", w - pad - 34, 99); ctx.textAlign = "left";

        // metric cards
        var cards = [
            { label: "Mensajes", value: Math.floor(dashState.msgs).toLocaleString("es-ES"), delta: "+18.2%" },
            { label: "Conversac.", value: Math.floor(dashState.convs).toLocaleString("es-ES"), delta: "+12.4%" },
            { label: "CTR", value: dashState.ctr.toFixed(1) + "%", delta: "+6.7%" }
        ];
        var cy = 186, ch = 150, gap = 16, cw = (w - pad * 2 - gap * 2) / 3;
        cards.forEach(function (c, i) {
            var cx = pad + i * (cw + gap);
            ctx.fillStyle = "rgba(255,255,255,0.07)";
            rr(ctx, cx, cy, cw, ch, 20); ctx.fill();
            ctx.strokeStyle = "rgba(255,255,255,0.12)"; ctx.lineWidth = 1.5; ctx.stroke();
            ctx.fillStyle = "#8fc4bf"; ctx.font = "500 21px Inter";
            ctx.fillText(c.label, cx + 18, cy + 38);
            ctx.fillStyle = "#fff"; ctx.font = "800 34px 'Plus Jakarta Sans'";
            ctx.fillText(c.value, cx + 18, cy + 82);
            ctx.fillStyle = "#4ade80"; ctx.font = "600 20px Inter";
            ctx.fillText("▲ " + c.delta, cx + 18, cy + 118);
        });

        // line/area chart card
        var chy = cy + ch + 26, chh = 300;
        ctx.fillStyle = "rgba(255,255,255,0.06)";
        rr(ctx, pad, chy, w - pad * 2, chh, 22); ctx.fill();
        ctx.strokeStyle = "rgba(255,255,255,0.1)"; ctx.lineWidth = 1.5; ctx.stroke();
        ctx.fillStyle = "#cdeeeb"; ctx.font = "700 24px 'Plus Jakarta Sans'";
        ctx.fillText("Rendimiento de campañas", pad + 22, chy + 44);

        // animated line
        var innerX = pad + 30, innerW = w - pad * 2 - 60;
        var baseY = chy + chh - 46, amp = 130;
        var pts = 7;
        ctx.beginPath();
        var coords = [];
        for (var i = 0; i < pts; i++) {
            var px = innerX + (innerW / (pts - 1)) * i;
            var wobble = Math.sin(dashState.phase + i * 0.9) * 0.12;
            var py = baseY - (0.25 + (i / (pts - 1)) * 0.55 + wobble) * amp;
            coords.push([px, py]);
        }
        // area fill
        var lg = ctx.createLinearGradient(0, chy, 0, baseY);
        lg.addColorStop(0, "rgba(94,214,201,0.5)"); lg.addColorStop(1, "rgba(94,214,201,0)");
        ctx.beginPath(); ctx.moveTo(coords[0][0], baseY);
        coords.forEach(function (c) { ctx.lineTo(c[0], c[1]); });
        ctx.lineTo(coords[coords.length - 1][0], baseY); ctx.closePath();
        ctx.fillStyle = lg; ctx.fill();
        // line
        ctx.beginPath(); ctx.moveTo(coords[0][0], coords[0][1]);
        coords.forEach(function (c) { ctx.lineTo(c[0], c[1]); });
        ctx.strokeStyle = "#5fd6c9"; ctx.lineWidth = 4; ctx.lineJoin = "round"; ctx.stroke();
        coords.forEach(function (c) {
            ctx.beginPath(); ctx.arc(c[0], c[1], 6, 0, Math.PI * 2);
            ctx.fillStyle = "#eafaf9"; ctx.fill();
        });

        // bottom: bars + donut
        var by = chy + chh + 26, bh = 260;
        // bars card
        var bcw = (w - pad * 2 - 16) * 0.52;
        ctx.fillStyle = "rgba(255,255,255,0.06)";
        rr(ctx, pad, by, bcw, bh, 22); ctx.fill();
        ctx.fillStyle = "#cdeeeb"; ctx.font = "700 22px 'Plus Jakarta Sans'";
        ctx.fillText("Mensajes / día", pad + 20, by + 40);
        var bx = pad + 22, bw = 30, bgap = ((bcw - 44) - dashState.bars.length * bw) / (dashState.bars.length - 1);
        dashState.bars.forEach(function (v, i) {
            var val = v * (0.7 + 0.3 * Math.abs(Math.sin(dashState.phase * 0.8 + i)));
            var barH = val * (bh - 90);
            var xx = bx + i * (bw + bgap), yy = by + bh - 26 - barH;
            var bg2 = ctx.createLinearGradient(0, yy, 0, yy + barH);
            bg2.addColorStop(0, "#5fd6c9"); bg2.addColorStop(1, "#1E6F78");
            ctx.fillStyle = bg2; rr(ctx, xx, yy, bw, barH, 8); ctx.fill();
        });

        // donut card
        var dcx = pad + bcw + 16, dcw = w - pad - dcx;
        ctx.fillStyle = "rgba(255,255,255,0.06)";
        rr(ctx, dcx, by, dcw, bh, 22); ctx.fill();
        ctx.fillStyle = "#cdeeeb"; ctx.font = "700 22px 'Plus Jakarta Sans'";
        ctx.fillText("Canales", dcx + 20, by + 40);
        var ccx = dcx + dcw / 2, ccy = by + bh / 2 + 20, cr = 62;
        var segs = [
            { v: 0.5, c: "#25D366" }, { v: 0.3, c: "#2C8F99" }, { v: 0.2, c: "#5fd6c9" }
        ];
        var start = -Math.PI / 2 + dashState.phase * 0.15;
        segs.forEach(function (s) {
            var end = start + s.v * Math.PI * 2;
            ctx.beginPath(); ctx.moveTo(ccx, ccy);
            ctx.arc(ccx, ccy, cr, start, end); ctx.closePath();
            ctx.fillStyle = s.c; ctx.fill();
            start = end;
        });
        // donut hole
        ctx.beginPath(); ctx.arc(ccx, ccy, cr * 0.58, 0, Math.PI * 2);
        ctx.fillStyle = "#0c333a"; ctx.fill();

        // status bar time
        ctx.fillStyle = "rgba(255,255,255,0.85)"; ctx.font = "600 22px Inter";
        ctx.fillText("9:41", pad, 44);
    }

    // ============================================================
    //  SCREEN 2 — LIVE CHAT (animated)
    // ============================================================
    var chatCanvas = document.createElement("canvas");
    chatCanvas.width = 520; chatCanvas.height = 1080;
    var cctx = chatCanvas.getContext("2d");

    var chatScript = [
        { side: "in", text: "Hola! Necesito ayuda con mi pedido" },
        { side: "out", text: "¡Claro! ¿Me compartes tu N° de orden?" },
        { side: "in", text: "Es la #48213" },
        { side: "out", text: "Tu pedido va en camino 🚚" },
        { side: "in", text: "Perfecto, muchas gracias!" },
        { side: "out", text: "Con gusto. ¿Algo más? 😊" }
    ];
    var chatMsgs = [];
    var chatIndex = 0;
    var chatTyping = false;

    function chatTick() {
        if (chatTyping) return;
        chatTyping = true;
        setTimeout(function () {
            chatMsgs.push(chatScript[chatIndex % chatScript.length]);
            if (chatMsgs.length > 6) chatMsgs.shift();
            chatIndex++;
            chatTyping = false;
        }, 1200);
    }
    var chatTimer = null;

    function wrapText(ctx, text, maxW) {
        var words = text.split(" "), lines = [], line = "";
        for (var i = 0; i < words.length; i++) {
            var test = line ? line + " " + words[i] : words[i];
            if (ctx.measureText(test).width > maxW && line) { lines.push(line); line = words[i]; }
            else line = test;
        }
        if (line) lines.push(line);
        return lines;
    }

    function drawChat() {
        var w = chatCanvas.width, h = chatCanvas.height, ctx = cctx;
        // wallpaper
        ctx.fillStyle = "#0b262b"; ctx.fillRect(0, 0, w, h);
        ctx.fillStyle = "rgba(44,143,153,0.05)";
        for (var gx = 0; gx < w; gx += 40) for (var gy = 120; gy < h; gy += 40) {
            ctx.beginPath(); ctx.arc(gx, gy, 2, 0, Math.PI * 2); ctx.fill();
        }

        // header
        var hg = ctx.createLinearGradient(0, 0, w, 0);
        hg.addColorStop(0, "#128C7E"); hg.addColorStop(1, "#1E6F78");
        ctx.fillStyle = hg; ctx.fillRect(0, 0, w, 150);
        ctx.beginPath(); ctx.arc(74, 92, 34, 0, Math.PI * 2);
        ctx.fillStyle = "#eafaf9"; ctx.fill();
        ctx.fillStyle = "#128C7E"; ctx.font = "700 30px 'Plus Jakarta Sans'";
        ctx.textAlign = "center"; ctx.fillText("C", 74, 103); ctx.textAlign = "left";
        ctx.fillStyle = "#fff"; ctx.font = "700 30px 'Plus Jakarta Sans'";
        ctx.fillText("Cliente GIJAC", 126, 88);
        ctx.fillStyle = "#b9f5cf"; ctx.font = "400 22px Inter";
        ctx.fillText("en línea", 152, 122);
        ctx.beginPath(); ctx.arc(134, 115, 7, 0, Math.PI * 2); ctx.fillStyle = "#4ade80"; ctx.fill();
        ctx.fillStyle = "rgba(255,255,255,0.9)"; ctx.font = "600 22px Inter";
        ctx.fillText("9:41", 20, 40);

        // messages
        var y = 210, pad = 30, maxBubble = w - 150;
        ctx.font = "400 25px Inter";
        for (var m = 0; m < chatMsgs.length; m++) {
            var msg = chatMsgs[m];
            var lines = wrapText(ctx, msg.text, maxBubble - 40);
            var bw = 0;
            lines.forEach(function (l) { bw = Math.max(bw, ctx.measureText(l).width); });
            bw += 40;
            var bh = lines.length * 34 + 34;
            var out = msg.side === "out";
            var bx = out ? w - pad - bw : pad;
            ctx.fillStyle = out ? "#128C7E" : "#173a40";
            rr(ctx, bx, y, bw, bh, 18); ctx.fill();
            ctx.fillStyle = out ? "#eafaf9" : "#dcefec";
            lines.forEach(function (l, li) { ctx.fillText(l, bx + 20, y + 34 + li * 34); });
            // time
            ctx.fillStyle = out ? "rgba(255,255,255,0.6)" : "rgba(180,220,216,0.5)";
            ctx.font = "400 17px Inter";
            ctx.fillText("9:4" + (m % 9), bx + bw - 52, y + bh - 12);
            ctx.font = "400 25px Inter";
            y += bh + 20;
        }

        // typing indicator
        if (chatTyping) {
            var tw = 110, th = 56, tx = pad, ty = y;
            ctx.fillStyle = "#173a40"; rr(ctx, tx, ty, tw, th, 18); ctx.fill();
            var t = Date.now() / 200;
            for (var d = 0; d < 3; d++) {
                var off = Math.sin(t + d * 0.6) * 5;
                ctx.beginPath(); ctx.arc(tx + 30 + d * 26, ty + th / 2 + off, 7, 0, Math.PI * 2);
                ctx.fillStyle = "#6ce0d4"; ctx.fill();
            }
        }

        // input bar
        ctx.fillStyle = "#0e3138"; ctx.fillRect(0, h - 96, w, 96);
        ctx.fillStyle = "#173a40"; rr(ctx, 24, h - 78, w - 120, 58, 29); ctx.fill();
        ctx.fillStyle = "#6c9a97"; ctx.font = "400 24px Inter";
        ctx.fillText("Escribe un mensaje…", 48, h - 42);
        ctx.beginPath(); ctx.arc(w - 54, h - 49, 30, 0, Math.PI * 2);
        ctx.fillStyle = "#25D366"; ctx.fill();
        // send triangle
        ctx.fillStyle = "#0e3138"; ctx.beginPath();
        ctx.moveTo(w - 66, h - 62); ctx.lineTo(w - 40, h - 49); ctx.lineTo(w - 66, h - 36); ctx.closePath(); ctx.fill();

        // unread badge (pulsing) top-right
        var pulse = 1 + Math.sin(Date.now() / 300) * 0.12;
        ctx.beginPath(); ctx.arc(w - 40, 60, 20 * pulse, 0, Math.PI * 2);
        ctx.fillStyle = "#ff3b30"; ctx.fill();
        ctx.fillStyle = "#fff"; ctx.font = "700 24px 'Plus Jakarta Sans'";
        ctx.textAlign = "center"; ctx.fillText("3", w - 40, 68); ctx.textAlign = "left";
    }

    // ============================================================
    //  BUILD PHONES
    // ============================================================
    drawDashboard();
    drawChat();

    var phone1 = buildPhone(dashCanvas); // dashboard
    var phone2 = buildPhone(chatCanvas); // chat

    var stage = new THREE.Group();
    scene.add(stage);

    phone1.position.set(-1.15, 0.25, -0.45);
    phone1.rotation.set(0.05, 0.34, 0.04);
    phone1.userData.floatPhase = 0;
    phone1.userData.floatSpeed = 0.7;
    phone1.userData.baseY = phone1.position.y;
    phone1.userData.baseRotY = phone1.rotation.y;

    phone2.position.set(1.2, -0.35, 0.55);
    phone2.rotation.set(-0.04, -0.26, -0.05);
    phone2.userData.floatPhase = Math.PI;
    phone2.userData.floatSpeed = 0.85;
    phone2.userData.baseY = phone2.position.y;
    phone2.userData.baseRotY = phone2.rotation.y;

    stage.add(phone1, phone2);

    // ============================================================
    //  FLOATING 3D ICONS (sprites with drawn glyphs)
    // ============================================================
    function makeIconTexture(drawGlyph, bg) {
        var c = document.createElement("canvas");
        c.width = c.height = 160;
        var g = c.getContext("2d");
        // glass rounded square
        rr(g, 12, 12, 136, 136, 34);
        var grad = g.createLinearGradient(0, 0, 160, 160);
        grad.addColorStop(0, bg[0]); grad.addColorStop(1, bg[1]);
        g.fillStyle = grad; g.fill();
        g.strokeStyle = "rgba(255,255,255,0.35)"; g.lineWidth = 3; g.stroke();
        g.strokeStyle = "#fff"; g.fillStyle = "#fff"; g.lineWidth = 8;
        g.lineCap = "round"; g.lineJoin = "round";
        drawGlyph(g);
        var t = new THREE.CanvasTexture(c);
        if (t.encoding !== undefined) t.encoding = THREE.sRGBEncoding;
        return t;
    }

    var glyphs = {
        whatsapp: function (g) {
            g.beginPath(); g.arc(80, 80, 34, 0, Math.PI * 2); g.stroke();
            g.lineWidth = 9;
            g.beginPath(); g.moveTo(66, 66); g.quadraticCurveTo(64, 92, 92, 96); g.stroke();
        },
        chart: function (g) {
            g.lineWidth = 12;
            g.beginPath(); g.moveTo(56, 104); g.lineTo(56, 84); g.stroke();
            g.beginPath(); g.moveTo(80, 104); g.lineTo(80, 66); g.stroke();
            g.beginPath(); g.moveTo(104, 104); g.lineTo(104, 76); g.stroke();
        },
        campaign: function (g) {
            g.lineWidth = 8;
            g.beginPath(); g.moveTo(56, 70); g.lineTo(56, 90); g.lineTo(88, 104);
            g.lineTo(88, 56); g.lineTo(56, 70); g.stroke();
            g.beginPath(); g.moveTo(88, 72); g.lineTo(104, 66); g.moveTo(88, 88); g.lineTo(104, 94); g.stroke();
        },
        bell: function (g) {
            g.lineWidth = 8;
            g.beginPath(); g.moveTo(60, 96); g.quadraticCurveTo(60, 62, 80, 62);
            g.quadraticCurveTo(100, 62, 100, 96); g.lineTo(60, 96); g.stroke();
            g.beginPath(); g.arc(80, 104, 6, 0, Math.PI * 2); g.stroke();
        },
        shield: function (g) {
            g.lineWidth = 8;
            g.beginPath(); g.moveTo(80, 54); g.lineTo(104, 64); g.lineTo(104, 88);
            g.quadraticCurveTo(104, 104, 80, 112); g.quadraticCurveTo(56, 104, 56, 88);
            g.lineTo(56, 64); g.closePath(); g.stroke();
            g.lineWidth = 7; g.beginPath(); g.moveTo(70, 82); g.lineTo(78, 92); g.lineTo(92, 72); g.stroke();
        },
        ai: function (g) {
            g.lineWidth = 8;
            rr(g, 58, 62, 44, 40, 12); g.stroke();
            g.beginPath(); g.moveTo(80, 62); g.lineTo(80, 50); g.stroke();
            g.beginPath(); g.arc(80, 46, 5, 0, Math.PI * 2); g.stroke();
            g.fillStyle = "#fff";
            g.beginPath(); g.arc(70, 82, 5, 0, Math.PI * 2); g.fill();
            g.beginPath(); g.arc(90, 82, 5, 0, Math.PI * 2); g.fill();
        }
    };

    var iconDefs = [
        { glyph: "whatsapp", bg: ["#25D366", "#128C7E"], pos: [-1.95, 1.75, 1.6], scale: 0.62 },
        { glyph: "chart", bg: ["#2C8F99", "#145962"], pos: [2.0, 1.95, 1.3], scale: 0.56 },
        { glyph: "campaign", bg: ["#1E6F78", "#0C3940"], pos: [1.95, -1.25, 1.5], scale: 0.5 },
        { glyph: "bell", bg: ["#5fd6c9", "#1E6F78"], pos: [-1.85, -0.95, 1.7], scale: 0.52 },
        { glyph: "shield", bg: ["#287F88", "#0C3940"], pos: [-1.5, -2.05, 1.9], scale: 0.48 },
        { glyph: "ai", bg: ["#2C8F99", "#5fd6c9"], pos: [0.15, 2.35, 1.8], scale: 0.5 }
    ];

    var icons = [];
    iconDefs.forEach(function (def, i) {
        var mat = new THREE.SpriteMaterial({
            map: makeIconTexture(glyphs[def.glyph], def.bg),
            transparent: true
        });
        var sp = new THREE.Sprite(mat);
        sp.position.set(def.pos[0], def.pos[1], def.pos[2]);
        sp.scale.set(def.scale, def.scale, def.scale);
        sp.userData.baseY = def.pos[1];
        sp.userData.baseX = def.pos[0];
        sp.userData.phase = i * 1.3;
        sp.userData.speed = 0.6 + i * 0.12;
        sp.userData.amp = 0.14 + (i % 3) * 0.05;
        stage.add(sp);
        icons.push(sp);
    });

    // ============================================================
    //  MOUSE PARALLAX
    // ============================================================
    var targetRX = 0, targetRY = 0, curRX = 0, curRY = 0;
    function onPointer(e) {
        var rect = container.getBoundingClientRect();
        var cx = (e.clientX - rect.left) / rect.width - 0.5;
        var cy = (e.clientY - rect.top) / rect.height - 0.5;
        targetRY = cx * 0.5;
        targetRX = cy * 0.35;
    }
    window.addEventListener("pointermove", onPointer, { passive: true });
    container.addEventListener("pointerleave", function () { targetRX = 0; targetRY = 0; });

    // ============================================================
    //  RESIZE
    // ============================================================
    function resize() {
        var w = container.clientWidth, h = container.clientHeight;
        if (!w || !h) return;
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
    }
    resize();
    window.addEventListener("resize", resize);

    // ============================================================
    //  ANIMATION LOOP
    // ============================================================
    var clock = new THREE.Clock();
    var texAccum = 0;
    var running = true;

    // pause rendering when section off-screen (perf)
    if ("IntersectionObserver" in window) {
        var vis = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                running = en.isIntersecting;
                if (running && !chatTimer) chatTimer = setInterval(chatTick, 2600);
            });
        }, { threshold: 0.05 });
        vis.observe(container);
    } else {
        chatTimer = setInterval(chatTick, 2600);
    }

    function animate() {
        requestAnimationFrame(animate);
        if (!running) return;

        var t = clock.getElapsedTime();
        var dt = clock.getDelta ? 0 : 0; // keep simple

        // smooth parallax
        curRX += (targetRX - curRX) * 0.06;
        curRY += (targetRY - curRY) * 0.06;
        stage.rotation.x = curRX;
        stage.rotation.y = curRY;

        // phones float + gentle rotation
        [phone1, phone2].forEach(function (p) {
            var u = p.userData;
            p.position.y = u.baseY + Math.sin(t * u.floatSpeed + u.floatPhase) * 0.16;
            p.rotation.y = u.baseRotY + Math.sin(t * 0.5 + u.floatPhase) * 0.06;
            p.rotation.x = 0.05 + Math.sin(t * 0.4 + u.floatPhase) * 0.03;
        });

        // icons float independently
        icons.forEach(function (sp) {
            var u = sp.userData;
            sp.position.y = u.baseY + Math.sin(t * u.speed + u.phase) * u.amp * 2.2;
            sp.position.x = u.baseX + Math.cos(t * u.speed * 0.6 + u.phase) * u.amp;
            sp.material.rotation = Math.sin(t * 0.4 + u.phase) * 0.15;
        });

        // update live screen textures ~20fps
        texAccum += 1;
        if (texAccum % 3 === 0 && !reduceMotion) {
            dashState.phase += 0.05;
            dashState.msgs += (dashTargets.msgs - dashState.msgs) * 0.04;
            dashState.convs += (dashTargets.convs - dashState.convs) * 0.04;
            dashState.ctr += (dashTargets.ctr - dashState.ctr) * 0.04;
            drawDashboard();
            phone1.userData.texture.needsUpdate = true;
            drawChat();
            phone2.userData.texture.needsUpdate = true;
        }

        renderer.render(scene, camera);
    }
    animate();

})();
