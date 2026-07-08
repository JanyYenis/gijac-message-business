<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GIJAC - Conexión de Dispositivo</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #075E54 0%, #128C7E 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            max-width: 380px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #25D366, #128C7E);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 36px;
        }
        h1 {
            color: #075E54;
            font-size: 22px;
            margin-bottom: 12px;
        }
        p {
            color: #667781;
            font-size: 15px;
            line-height: 1.5;
            margin-bottom: 24px;
        }
        .status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #E8F5E9;
            color: #2E7D32;
            padding: 12px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 24px;
        }
        .status .dot {
            width: 10px;
            height: 10px;
            background: #4CAF50;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }
        .btn {
            display: block;
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:active {
            transform: scale(0.98);
        }
        .btn-primary {
            background: linear-gradient(135deg, #25D366, #128C7E);
            color: white;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            margin-bottom: 12px;
        }
        .btn-secondary {
            background: #F5F5F5;
            color: #075E54;
        }
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: #999;
            font-size: 13px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #E0E0E0;
        }
        .instructions {
            text-align: left;
            background: #F8F9FA;
            border-radius: 12px;
            padding: 16px;
            margin-top: 20px;
        }
        .instructions h3 {
            color: #333;
            font-size: 14px;
            margin-bottom: 12px;
        }
        .instructions ol {
            padding-left: 20px;
            color: #555;
            font-size: 13px;
            line-height: 1.8;
        }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">📱</div>
        <h1>Vincular Dispositivo</h1>
        <p>Se ha detectado un intento de conexión desde otro dispositivo.</p>

        <div class="status" id="status-waiting">
            <span class="dot"></span>
            Esperando conexión de la app...
        </div>

        <div class="status hidden" id="status-success" style="background: #E8F5E9;">
            ✅ Dispositivo conectado exitosamente
        </div>

        <a href="https://play.google.com/store/apps/details?id=com.example.gmb" class="btn btn-primary" id="btn-play">
            📲 Abrir App (Android)
        </a>

        <a href="https://apps.apple.com/app/gijac/id1234567890" class="btn btn-secondary hidden" id="btn-ios">
            📲 Abrir App (iOS)
        </a>

        <div class="divider">o</div>

        <div class="instructions">
            <h3>📋 Instrucciones:</h3>
            <ol>
                <li>Abre la app <strong>GIJAC</strong> en tu celular</li>
                <li>Ve a <strong>Perfil → Escanear QR</strong></li>
                <li>Apunta la cámara al código QR</li>
                <li>¡Listo! Tu dispositivo quedará vinculado</li>
            </ol>
        </div>
    </div>

    <!-- Datos ocultos para la app (Deep Link fallback) -->
    <script>
        // Detectar si es iOS para mostrar el botón correcto
        if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
            document.getElementById('btn-play').classList.add('hidden');
            document.getElementById('btn-ios').classList.remove('hidden');
        }

        // Intentar abrir la app via Deep Link
        const token = '{{ $token }}';
        const server = '{{ $server }}';
        const deepLink = `gijac://auth?token=${encodeURIComponent(token)}&server=${encodeURIComponent(server)}`;

        // Intentar abrir la app automáticamente
        function tryOpenApp() {
            const startTime = Date.now();

            // Crear iframe invisible para intentar abrir el deep link
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = deepLink;
            document.body.appendChild(iframe);

            // Si después de 2 segundos seguimos en la página, la app no se abrió
            setTimeout(() => {
                if (Date.now() - startTime < 3000) {
                    // La app no se abrió, mostrar instrucciones
                    console.log('App no detectada, mostrando instrucciones');
                }
            }, 2500);
        }

        // Intentar abrir al cargar la página
        window.addEventListener('load', tryOpenApp);

        // También intentar cuando el usuario haga click en los botones
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                window.location.href = deepLink;

                // Fallback a la tienda después de un delay
                setTimeout(() => {
                    window.location.href = btn.href;
                }, 2500);
            });
        });
    </script>
</body>
</html>
