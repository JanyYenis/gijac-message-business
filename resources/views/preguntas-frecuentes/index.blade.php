@extends('layouts.principal')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/gmb.css') }}">
     <style>{{ __('.hero-section {
            min-height: auto !important;
        }
        .site-header .nav-link {
            color: #123f45 !important;
            margin: auto !important;
        }
        .site-header .nav-link.active {
            color: #123f45 !important;
        }
        .site-header .brand-name {
            color: var(--teal-dark) !important;
        }

        .site-header .btn-ghost {
            color: var(--teal-dark) !important;
            border: 1px solid rgba(20, 89, 98, .2) !important;
        }
        .btn {
            border: 1px solid rgb(255 255 255 / 20%) !important;
        }') }</style>
@endsection

@section('content')
    <!-- FAQ Section -->
    <section class="section mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <!-- General Questions -->
                    <div class="mb-5">
                        <h2 class="section-title text-start">
                            <i class="fas fa-info-circle me-2"></i>{{ __('Preguntas Generales') }}</h2>

                        <div class="accordion" id="generalAccordion">
                            <!-- Question 1 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="general1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#generalAnswer1">{{ __('¿Cómo funciona la plataforma?') }}</button>
                                </h3>
                                <div id="generalAnswer1" class="accordion-collapse collapse show"
                                    data-bs-parent="#generalAccordion">
                                    <div class="accordion-body">
                                        <p>{{ __('Nuestra plataforma se conecta directamente a la API oficial de WhatsApp Business,
                                            permitiéndote:') }</p>
                                        <ul class="mb-3">
                                            <li><strong>{{ __('Enviar mensajes masivos:') }}</strong>{{ __('Crea campañas personalizadas para
                                                miles de contactos') }</li>
                                            <li><strong>{{ __('Automatizar respuestas:') }}</strong>{{ __('Configura respuestas automáticas
                                                para consultas frecuentes') }</li>
                                            <li><strong>{{ __('Gestionar conversaciones:') }}</strong>{{ __('Centraliza todos los chats en una sola interfaz') }}</li>
                                            <li><strong>{{ __('Analizar resultados:') }}</strong>{{ __('Obtén métricas detalladas de entrega y
                                                engagement') }</li>
                                        </ul>
                                        <p>{{ __('El proceso es simple: conectas tu cuenta de WhatsApp Business, importas tus
                                            contactos, creas tus campañas y la plataforma se encarga del resto.') }</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 2 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="general2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#generalAnswer2">{{ __('¿Es seguro usar la plataforma?') }}</button>
                                </h3>
                                <div id="generalAnswer2" class="accordion-collapse collapse"
                                    data-bs-parent="#generalAccordion">
                                    <div class="accordion-body">
                                        <p><strong>{{ __('Absolutamente seguro.') }}</strong>{{ __('Implementamos las mejores prácticas de
                                            seguridad:') }</p>
                                        <ul class="mb-3">
                                            <li>{{ __('Encriptación SSL/TLS en todas las comunicaciones') }}</li>
                                            <li>{{ __('Autenticación de dos factores (2FA)') }}</li>
                                            <li>{{ __('Cumplimiento total con las políticas de WhatsApp') }}</li>
                                            {{-- <li>{{ __('Certificaciones ISO 27001') }}</li> --}}
                                            {{-- <li>{{ __('Auditorías regulares de seguridad') }}</li> --}}
                                        </ul>
                                        <p>{{ __('Además, utilizamos la API oficial de WhatsApp Business, por lo que tu cuenta
                                            siempre cumple con los términos y condiciones de WhatsApp.') }</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 3 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="general3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#generalAnswer3">{{ __('¿Qué necesito para empezar?') }}</button>
                                </h3>
                                <div id="generalAnswer3" class="accordion-collapse collapse"
                                    data-bs-parent="#generalAccordion">
                                    <div class="accordion-body">
                                        <p>{{ __('Para comenzar a usar nuestra plataforma necesitas:') }}</p>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <div class="card h-100">
                                                    <div class="card-body text-center">
                                                        <i class="fab fa-whatsapp fa-3x text-success mb-3"></i>
                                                        <h6>{{ __('Cuenta WhatsApp Business') }}</h6>
                                                        <p class="small mb-0">{{ __('Una cuenta verificada de WhatsApp Business') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card h-100">
                                                    <div class="card-body text-center">
                                                        <i class="fas fa-phone fa-3x text-primary mb-3"></i>
                                                        <h6>{{ __('Número Telefónico') }}</h6>
                                                        <p class="small mb-0">{{ __('Un número dedicado para WhatsApp Business') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p>{{ __('Nosotros te ayudamos con todo el proceso de configuración y conexión a la API de
                                            WhatsApp Business.') }</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Campaigns Questions -->
                    <div class="mb-5">
                        <h2 class="section-title text-start">
                            <i class="fas fa-bullhorn me-2"></i>{{ __('Campañas y Envíos') }}</h2>

                        <div class="accordion" id="campaignsAccordion">
                            <!-- Question 4 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="campaigns1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#campaignsAnswer1">{{ __('¿Puedo enviar campañas por etiquetas?') }}</button>
                                </h3>
                                <div id="campaignsAnswer1" class="accordion-collapse collapse"
                                    data-bs-parent="#campaignsAccordion">
                                    <div class="accordion-body">
                                        <p><strong>{{ __('¡Por supuesto!') }}</strong>{{ __('Nuestro sistema de etiquetas te permite segmentar
                                            tu audiencia de forma muy precisa:') }</p>
                                        <ul class="mb-3">
                                            <li><strong>{{ __('Etiquetas automáticas:') }}</strong>{{ __('Basadas en comportamiento,
                                                ubicación, fecha de registro') }</li>
                                            <li><strong>{{ __('Etiquetas manuales:') }}</strong>{{ __('Que puedes asignar según tus criterios
                                                comerciales') }</li>
                                            <li><strong>{{ __('Combinación de etiquetas:') }}</strong>{{ __('Crea segmentos súper específicos
                                                combinando múltiples etiquetas') }</li>
                                            <li><strong>{{ __('Etiquetas dinámicas:') }}</strong>{{ __('Que se actualizan automáticamente
                                                según nuevas interacciones') }</li>
                                        </ul>
                                        <p>{{ __('Por ejemplo: puedes enviar una campaña solo a "Clientes VIP + Madrid + Compra
                                            reciente" para máxima personalización.') }</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 5 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="campaigns2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#campaignsAnswer2">{{ __('¿Puedo programar campañas?') }}</button>
                                </h3>
                                <div id="campaignsAnswer2" class="accordion-collapse collapse"
                                    data-bs-parent="#campaignsAccordion">
                                    <div class="accordion-body">
                                        <p><strong>{{ __('Sí, completamente.') }}</strong>{{ __('Nuestra funcionalidad de programación
                                            incluye:') }</p>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                                                    <h6>{{ __('Fecha Específica') }}</h6>
                                                    <p class="small">{{ __('Programa para día y hora exactos') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <i class="fas fa-redo fa-2x text-primary mb-2"></i>
                                                    <h6>{{ __('Campañas Recurrentes') }}</h6>
                                                    <p class="small">{{ __('Diarias, semanales o mensuales') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                                                    <h6>{{ __('Zona Horaria') }}</h6>
                                                    <p class="small">{{ __('Respeta la zona horaria de cada contacto') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <p>{{ __('Además, puedes programar secuencias de mensajes automáticas basadas en triggers
                                            específicos.') }</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 6 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="campaigns3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#campaignsAnswer3">{{ __('¿Qué tipos de mensajes puedo enviar?') }}</button>
                                </h3>
                                <div id="campaignsAnswer3" class="accordion-collapse collapse"
                                    data-bs-parent="#campaignsAccordion">
                                    <div class="accordion-body">
                                        <p>{{ __('Puedes enviar todos los tipos de mensajes soportados por WhatsApp Business API:') }}</p>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <ul>
                                                    <li><strong>{{ __('Texto simple:') }}</strong>{{ __('Mensajes de texto personalizados') }}</li>
                                                    <li><strong>{{ __('Texto con formato:') }}</strong>{{ __('Negrita, cursiva, etc.') }}</li>
                                                    <li><strong>{{ __('Imágenes:') }}</strong>{{ __('JPG, PNG con texto descriptivo') }}</li>
                                                    <li><strong>{{ __('Documentos:') }}</strong>{{ __('PDF, DOCX, XLSX') }}</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <ul>
                                                    <li><strong>{{ __('Videos:') }}</strong>{{ __('MP4 hasta 16MB') }}</li>
                                                    <li><strong>{{ __('Audio:') }}</strong>{{ __('Mensajes de voz') }}</li>
                                                    <li><strong>{{ __('Ubicaciones:') }}</strong>{{ __('Compartir coordenadas') }}</li>
                                                    <li><strong>{{ __('Botones interactivos:') }}</strong>{{ __('Call-to-action') }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="alert alert-info">
                                            <i class="fas fa-lightbulb me-2"></i>
                                            <strong>{{ __('Tip:') }}</strong>{{ __('Los mensajes con plantillas pre-aprobadas por WhatsApp
                                            tienen mayor tasa de entrega.') }</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Questions -->
                    <div class="mb-5">
                        <h2 class="section-title text-start">
                            <i class="fas fa-dollar-sign me-2"></i>{{ __('Precios y Facturación') }}</h2>

                        <div class="accordion" id="pricingAccordion">
                            <!-- Question 7 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="pricing1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#pricingAnswer1">{{ __('¿Los precios incluyen el costo de WhatsApp Business?') }}</button>
                                </h3>
                                <div id="pricingAnswer1" class="accordion-collapse collapse"
                                    data-bs-parent="#pricingAccordion">
                                    <div class="accordion-body">
                                        <div class="alert alert-warning mb-3">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>{{ __('No, los precios de la plataforma NO incluyen los costos de WhatsApp
                                                Business API.') }</strong>
                                        </div>
                                        <p>{{ __('Nuestros precios cubren únicamente el uso de la plataforma. Los envíos se
                                            facturan por separado según las tarifas oficiales de') }<a href="https://developers.facebook.com/docs/whatsapp/pricing/?translation"
                                                target="_blank" class="link-primary">{{ __('WhatsApp') }}</a>.
                                        </p>
                                        {{-- <table class="table table-striped mb-3">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Tipo de Conversación') }}</th>
                                                    <th>{{ __('Costo Aproximado') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ __('Iniciada por el negocio') }}</td>
                                                    <td>{{ __('$0.005 - $0.019 por conversación') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Iniciada por el cliente') }}</td>
                                                    <td>{{ __('$0.001 - $0.005 por conversación') }}</td>
                                                </tr>
                                            </tbody>
                                        </table> --}}
                                        <p><small><em>{{ __('Los precios varían según el país de destino. Una conversación puede
                                                    incluir múltiples mensajes en un período de 24 horas.') }</em></small></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 8 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="pricing2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#pricingAnswer2">{{ __('¿Hay costos ocultos o adicionales?') }}</button>
                                </h3>
                                <div id="pricingAnswer2" class="accordion-collapse collapse"
                                    data-bs-parent="#pricingAccordion">
                                    <div class="accordion-body">
                                        <p><strong>{{ __('No hay costos ocultos.') }}</strong>{{ __('Nuestro modelo de precios es 100%
                                            transparente:') }</p>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <div class="card border-success">
                                                    <div class="card-header bg-success text-white">
                                                        <h6 class="mb-0"><i class="fas fa-check me-2"></i>{{ __('Incluido en tu plan') }}</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="list-unstyled mb-0">
                                                            <li>{{ __('✓ Uso de la plataforma') }}</li>
                                                            <li>{{ __('✓ Soporte técnico') }}</li>
                                                            <li>{{ __('✓ Actualizaciones') }}</li>
                                                            <li>{{ __('✓ Almacenamiento de datos') }}</li>
                                                            <li>{{ __('✓ Analytics y reportes') }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card border-info">
                                                    <div class="card-header bg-info text-white">
                                                        <h6 class="mb-0"><i class="fas fa-info me-2"></i>{{ __('Costos adicionales') }}</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="list-unstyled mb-0">
                                                            {{-- <li>{{ __('• Envíos de WhatsApp (según uso)') }}</li> --}}
                                                            <li>{{ __('• Servicios de consultoría (opcional)') }}</li>
                                                            <li>{{ __('• Integraciones personalizadas (opcional)') }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <p>{{ __('Te proporcionamos estimaciones detalladas de costos antes de cada campaña para
                                            que siempre sepas cuánto vas a invertir.') }</p> --}}
                                    </div>
                                </div>
                            </div>

                            <!-- Question 9 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="pricing3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#pricingAnswer3">{{ __('¿Puedo cambiar de plan en cualquier momento?') }}</button>
                                </h3>
                                <div id="pricingAnswer3" class="accordion-collapse collapse"
                                    data-bs-parent="#pricingAccordion">
                                    <div class="accordion-body">
                                        <p><strong>{{ __('¡Absolutamente!') }}</strong>{{ __('Puedes cambiar tu plan cuando necesites:') }}</p>
                                        <ul class="mb-3">
                                            <li><strong>{{ __('Upgrade:') }}</strong>{{ __('Inmediato, pagas la diferencia prorrateada') }}</li>
                                            <li><strong>{{ __('Downgrade:') }}</strong>{{ __('Al final del período de facturación actual') }}</li>
                                            <li><strong>{{ __('Sin penalizaciones:') }}</strong>{{ __('No cobramos fees por cambios de plan') }}</li>
                                            <li><strong>{{ __('Flexibilidad total:') }}</strong>{{ __('Adapta el plan al crecimiento de tu negocio') }}</li>
                                        </ul>
                                        <div class="alert alert-success">
                                            <i class="fas fa-gift me-2"></i>
                                            <strong>{{ __('Bonus:') }}</strong>{{ __('Si necesitas más recursos temporalmente (como en Black
                                            Friday), podemos activar un boost temporal sin cambiar tu plan base.') }</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Questions -->
                    <div class="mb-5">
                        <h2 class="section-title text-start">
                            <i class="fas fa-cogs me-2"></i>{{ __('Aspectos Técnicos') }}</h2>

                        <div class="accordion" id="technicalAccordion">
                            <!-- Question 10 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="technical1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#technicalAnswer1">{{ __('¿Tienen API disponible para integraciones?') }}</button>
                                </h3>
                                <div id="technicalAnswer1" class="accordion-collapse collapse"
                                    data-bs-parent="#technicalAccordion">
                                    <div class="accordion-body">
                                        <p><strong>{{ __('Sí, tenemos una API RESTful completa') }}</strong>{{ __('disponible en los planes
                                            Professional y Enterprise:') }</p>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <h6>{{ __('Funciones API Disponibles:') }}</h6>
                                                <ul>
                                                    <li>{{ __('Envío de mensajes individuales') }}</li>
                                                    <li>{{ __('Datos de las campañas') }}</li>
                                                    {{-- <li>{{ __('Creación de campañas') }}</li> --}}
                                                    {{-- <li>{{ __('Gestión de contactos') }}</li> --}}
                                                    <li>{{ __('Obtención de métricas') }}</li>
                                                    {{-- <li>{{ __('Configuración de webhooks') }}</li> --}}
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>{{ __('Recursos para Desarrolladores:') }}</h6>
                                                <ul>
                                                    <li>{{ __('Documentación completa') }}</li>
                                                    {{-- <li>{{ __('SDK para JavaScript/PHP') }}</li> --}}
                                                    <li>{{ __('Postman Collection') }}</li>
                                                    {{-- <li>{{ __('Sandbox para pruebas') }}</li> --}}
                                                    <li>{{ __('Soporte técnico dedicado') }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="alert alert-info">
                                            <i class="fas fa-code me-2"></i>
                                            <strong>{{ __('Para Desarrolladores:') }}</strong>{{ __('Nuestra API sigue estándares REST, usa
                                            autenticación JWT y soporta rate limiting para uso eficiente.') }</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 11 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="technical2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#technicalAnswer2">{{ __('¿Qué límites de envío tiene WhatsApp?') }}</button>
                                </h3>
                                <div id="technicalAnswer2" class="accordion-collapse collapse"
                                    data-bs-parent="#technicalAccordion">
                                    <div class="accordion-body">
                                        <p>{{ __('WhatsApp Business API tiene límites que van aumentando según tu reputación y
                                            volumen histórico:') }</p>
                                        <table class="table table-striped mb-3">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Nivel de Cuenta') }}</th>
                                                    <th>{{ __('Límite Diario') }}</th>
                                                    <th>{{ __('Cómo Alcanzar') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ __('Tier 1 (Nueva)') }}</td>
                                                    <td>{{ __('250 conversaciones únicas') }}</td>
                                                    <td>{{ __('Cuenta nueva recién verificada') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Tier 2') }}</td>
                                                    <td>{{ __('1,000 conversaciones únicas') }}</td>
                                                    <td>{{ __('Tras 7 días consecutivos cerca del límite') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Tier 3') }}</td>
                                                    <td>{{ __('10,000 conversaciones únicas') }}</td>
                                                    <td>{{ __('Continuando con buen comportamiento') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Tier 4+') }}</td>
                                                    <td>{{ __('100,000+ conversaciones únicas') }}</td>
                                                    <td>{{ __('Cuentas de alto volumen establecidas') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-lightbulb me-2"></i>
                                            <strong>{{ __('Importante:') }}</strong>{{ __('Una conversación puede incluir múltiples mensajes.
                                            Los límites se resetean cada 24 horas.') }</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="section" style="background-color: var(--primary-color); color: white;">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2>{{ __('¿Tienes más preguntas?') }}</h2>
                    <p class="lead mb-4">{{ __('Nuestro equipo de soporte está disponible para resolver cualquier duda que tengas.') }}</p>
                    <div class="row justify-content-center g-3">
                        <div class="col-md-4">
                            <a href="{{ route('contactarnos') }}" class="btn btn-light btn-lg w-100">
                                <i class="fas fa-envelope me-2"></i>{{ __('Enviar Consulta') }}</a>
                        </div>
                        <div class="col-md-4">
                            <a href="tel:+573171789584" class="btn btn-outline-light btn-lg w-100">
                                <i class="fas fa-phone me-2"></i>{{ __('Llamar Ahora') }}</a>
                        </div>
                        <div class="col-md-4">
                            <a href="https://wa.me/573171789584" class="btn btn-outline-light btn-lg w-100">
                                <i class="fab fa-whatsapp me-2"></i>{{ __('WhatsApp') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection
