@php
    $disabled = '';
    if ($permisos) {
        $disabled = 'disabled';
    }
@endphp
@extends('layouts.index')

@section('css')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: var(--gray-800);
            line-height: 1.6;
            min-height: 100vh;
        }

        .container-wrapper {
            width: 100%;
        }

        .config-card {
            background: var(--whatsapp-received);
            border-radius: 20px;
            box-shadow: var(--card-shadow-hover);
            overflow: hidden;
            border: 1px solid var(--gray-200);
            transition: all 0.3s ease;
        }

        .config-card:hover {
            box-shadow: 0 1.5rem 4rem rgba(0, 0, 0, 0.2);
        }

        .card-header-custom {
            background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark));
            color: var(--whatsapp-received);
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-green), var(--whatsapp-green), var(--primary-green));
        }

        .card-header-custom::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .card-title-custom {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .card-title-custom i {
            font-size: 2.5rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .card-subtitle-custom {
            font-size: 1.1rem;
            opacity: 0.95;
            font-weight: 400;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        .card-body-custom {
            padding: 3rem 2.5rem;
        }

        .description-box {
            background: linear-gradient(135deg, rgba(37, 211, 102, 0.05), rgba(34, 197, 94, 0.05));
            border-left: 4px solid var(--whatsapp-green);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .description-box p {
            margin: 0;
            color: var(--gray-700);
            font-size: 1rem;
            line-height: 1.7;
        }

        .description-box strong {
            color: var(--whatsapp-dark);
            font-weight: 600;
        }

        .form-label-custom {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label-custom i {
            color: var(--whatsapp-green);
            font-size: 1.2rem;
        }

        .textarea-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control-custom {
            width: 100%;
            min-height: 300px;
            padding: 1.5rem;
            border: 2px solid var(--gray-300);
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            line-height: 1.7;
            resize: vertical;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control-custom:focus {
            outline: none;
            border-color: var(--whatsapp-green);
            box-shadow: 0 0 0 0.25rem rgba(37, 211, 102, 0.25);
            background: var(--whatsapp-received);
        }

        .form-control-custom::placeholder {
            color: var(--gray-600);
            opacity: 0.7;
        }

        .char-counter {
            position: absolute;
            bottom: -1.5rem;
            right: 0;
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        .char-counter.warning {
            color: var(--warning-color);
        }

        .char-counter.danger {
            color: var(--danger-color);
        }

        .btn-save-custom {
            width: 100%;
            background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark));
            border: none;
            color: var(--whatsapp-received);
            border-radius: 12px;
            padding: 1.25rem 2rem;
            font-size: 1.2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 2.5rem;
            box-shadow: var(--card-shadow-hover);
        }

        .btn-save-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-save-custom:hover::before {
            left: 100%;
        }

        .btn-save-custom:hover {
            background: linear-gradient(135deg, var(--whatsapp-dark), var(--accent-color));
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-save-custom:active {
            transform: translateY(0);
        }

        .btn-save-custom:disabled {
            background: var(--gray-300);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-save-custom i {
            font-size: 1.3rem;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--whatsapp-received);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .alert-custom {
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            border: none;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-custom i {
            font-size: 1.5rem;
        }

        .alert-success-custom {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-danger-custom {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        .alert-warning-custom {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
            color: #92400e;
            border-left: 4px solid var(--warning-color);
        }

        .alert-info-custom {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
            color: #1e40af;
            border-left: 4px solid var(--info-color);
        }

        .example-prompts {
            background: var(--gray-100);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .example-prompts h6 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .example-prompts h6 i {
            color: var(--info-color);
        }

        .example-item {
            background: var(--whatsapp-received);
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            color: var(--gray-700);
            line-height: 1.6;
        }

        .example-item[disabled] {
            pointer-events: none;
            opacity: 0.6; /* Opcional: para indicar visualmente que está deshabilitado */
            cursor: not-allowed; /* Opcional: cambia el cursor */
        }

        .example-item:hover {
            border-color: var(--whatsapp-green);
            background: rgba(37, 211, 102, 0.05);
            transform: translateX(5px);
        }

        .example-item:last-child {
            margin-bottom: 0;
        }

        .shake {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem 0.5rem;
            }

            .card-header-custom {
                padding: 2rem 1.5rem;
            }

            .card-title-custom {
                font-size: 1.5rem;
                flex-direction: column;
                gap: 0.5rem;
            }

            .card-title-custom i {
                font-size: 2rem;
            }

            .card-subtitle-custom {
                font-size: 1rem;
            }

            .card-body-custom {
                padding: 2rem 1.5rem;
            }

            .form-control-custom {
                min-height: 250px;
                padding: 1rem;
            }

            .btn-save-custom {
                padding: 1rem 1.5rem;
                font-size: 1.1rem;
            }

            .example-prompts {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .card-title-custom {
                font-size: 1.3rem;
            }

            .card-subtitle-custom {
                font-size: 0.95rem;
            }

            .card-body-custom {
                padding: 1.5rem 1rem;
            }

            .description-box {
                padding: 1rem;
            }

            .form-control-custom {
                min-height: 200px;
                font-size: 0.95rem;
            }

            .btn-save-custom {
                font-size: 1rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        @media (prefers-contrast: high) {
            .config-card {
                border: 2px solid var(--gray-800);
            }

            .form-control-custom {
                border-width: 2px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-wrapper">
        <div class="config-card">
            <!-- Card Header -->
            <div class="card-header-custom">
                <h1 class="card-title-custom text-white">
                    <i class="fas fa-brain"></i>
                    Configura tu Prompt de Clasificación
                </h1>
                <p class="card-subtitle-custom">
                    Define cómo la inteligencia artificial interpretará y clasificará los mensajes
                </p>
            </div>

            <!-- Card Body -->
            <div class="card-body-custom">
                <!-- Alert Container -->
                <div id="alertContainer"></div>

                <!-- Description Box -->
                <div class="description-box">
                    <p>
                        <strong>Instrucciones:</strong> Escribe un prompt detallado que explique a la IA cómo debe clasificar los mensajes.
                        Sé específico sobre las categorías, criterios y el formato de respuesta esperado.
                        Un buen prompt mejorará significativamente la precisión de la clasificación.
                    </p>
                </div>

                <!-- Form -->
                <form id="promptForm" novalidate>
                    <div class="mb-4">
                        <label for="promptTextarea" class="form-label-custom">
                            <i class="fas fa-edit"></i>
                            Prompt de Clasificación
                        </label>
                        <div class="textarea-wrapper">
                            <textarea
                                class="form-control-custom"
                                id="promptTextarea"
                                name="prompt_usuario"
                                placeholder="Ejemplo: Eres un asistente de clasificación de mensajes. Tu tarea es analizar cada mensaje y clasificarlo en una de las siguientes categorías: 'consulta', 'queja', 'sugerencia' o 'otro'. Responde únicamente con el nombre de la categoría en minúsculas..."
                                required
                                maxlength="5000"
                                {{ $disabled }}
                            >Clasificación de Sentimientos: Analiza el sentimiento del mensaje y clasifícalo como 'positivo', 'negativo' o 'neutral'. Considera el tono, las palabras utilizadas y el contexto general.</textarea>
                            <small class="char-counter" id="charCounter">0 / 5000 caracteres</small>
                        </div>
                    </div>

                    @if (!$permisos)
                        <button type="submit" class="btn-save-custom" id="saveBtn">
                            <i class="fas fa-save"></i>
                            Guardar Prompt
                        </button>
                    @endif
                </form>

                <!-- Example Prompts -->
                <div class="example-prompts">
                    <h6>
                        <i class="fas fa-lightbulb"></i>
                        Ejemplos de Prompts
                    </h6>
                    <div class="example-item" {{ $disabled }} data-example="1">
                        <strong>Clasificación de Sentimientos:</strong> Analiza el sentimiento del mensaje y clasifícalo como 'positivo', 'negativo' o 'neutral'. Considera el tono, las palabras utilizadas y el contexto general.
                    </div>
                    <div class="example-item" {{ $disabled }} data-example="2">
                        <strong>Clasificación por Urgencia:</strong> Evalúa la urgencia del mensaje y clasifícalo como 'urgente', 'normal' o 'baja prioridad'. Los mensajes urgentes contienen palabras como 'inmediato', 'urgente', 'ahora', etc.
                    </div>
                    <div class="example-item" {{ $disabled }} data-example="3">
                        <strong>Clasificación por Departamento:</strong> Determina a qué departamento debe dirigirse el mensaje: 'ventas', 'soporte técnico', 'facturación' o 'recursos humanos'. Basa tu decisión en el contenido y las palabras clave del mensaje.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
@endsection

@section('scripts')
    <script src="{{ mix('js/clasificacion-ia/principal.js') }}"></script>
@endsection
