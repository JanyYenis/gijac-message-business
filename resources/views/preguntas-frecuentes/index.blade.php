@extends('layouts.principal')

@section('css')
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
                            <i class="fas fa-info-circle me-2"></i>Preguntas Generales
                        </h2>

                        <div class="accordion" id="generalAccordion">
                            <!-- Question 1 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="general1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#generalAnswer1">
                                        ¿Cómo funciona la plataforma?
                                    </button>
                                </h3>
                                <div id="generalAnswer1" class="accordion-collapse collapse show"
                                    data-bs-parent="#generalAccordion">
                                    <div class="accordion-body">
                                        <p>Nuestra plataforma se conecta directamente a la API oficial de WhatsApp Business,
                                            permitiéndote:</p>
                                        <ul class="mb-3">
                                            <li><strong>Enviar mensajes masivos:</strong> Crea campañas personalizadas para
                                                miles de contactos</li>
                                            <li><strong>Automatizar respuestas:</strong> Configura respuestas automáticas
                                                para consultas frecuentes</li>
                                            <li><strong>Gestionar conversaciones:</strong> Centraliza todos los chats en una
                                                sola interfaz</li>
                                            <li><strong>Analizar resultados:</strong> Obtén métricas detalladas de entrega y
                                                engagement</li>
                                        </ul>
                                        <p>El proceso es simple: conectas tu cuenta de WhatsApp Business, importas tus
                                            contactos, creas tus campañas y la plataforma se encarga del resto.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 2 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="general2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#generalAnswer2">
                                        ¿Es seguro usar la plataforma?
                                    </button>
                                </h3>
                                <div id="generalAnswer2" class="accordion-collapse collapse"
                                    data-bs-parent="#generalAccordion">
                                    <div class="accordion-body">
                                        <p><strong>Absolutamente seguro.</strong> Implementamos las mejores prácticas de
                                            seguridad:</p>
                                        <ul class="mb-3">
                                            <li>Encriptación SSL/TLS en todas las comunicaciones</li>
                                            <li>Autenticación de dos factores (2FA)</li>
                                            <li>Cumplimiento total con las políticas de WhatsApp</li>
                                            {{-- <li>Certificaciones ISO 27001</li> --}}
                                            {{-- <li>Auditorías regulares de seguridad</li> --}}
                                        </ul>
                                        <p>Además, utilizamos la API oficial de WhatsApp Business, por lo que tu cuenta
                                            siempre cumple con los términos y condiciones de WhatsApp.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 3 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="general3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#generalAnswer3">
                                        ¿Qué necesito para empezar?
                                    </button>
                                </h3>
                                <div id="generalAnswer3" class="accordion-collapse collapse"
                                    data-bs-parent="#generalAccordion">
                                    <div class="accordion-body">
                                        <p>Para comenzar a usar nuestra plataforma necesitas:</p>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <div class="card h-100">
                                                    <div class="card-body text-center">
                                                        <i class="fab fa-whatsapp fa-3x text-success mb-3"></i>
                                                        <h6>Cuenta WhatsApp Business</h6>
                                                        <p class="small mb-0">Una cuenta verificada de WhatsApp Business</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card h-100">
                                                    <div class="card-body text-center">
                                                        <i class="fas fa-phone fa-3x text-primary mb-3"></i>
                                                        <h6>Número Telefónico</h6>
                                                        <p class="small mb-0">Un número dedicado para WhatsApp Business</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p>Nosotros te ayudamos con todo el proceso de configuración y conexión a la API de
                                            WhatsApp Business.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Campaigns Questions -->
                    <div class="mb-5">
                        <h2 class="section-title text-start">
                            <i class="fas fa-bullhorn me-2"></i>Campañas y Envíos
                        </h2>

                        <div class="accordion" id="campaignsAccordion">
                            <!-- Question 4 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="campaigns1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#campaignsAnswer1">
                                        ¿Puedo enviar campañas por etiquetas?
                                    </button>
                                </h3>
                                <div id="campaignsAnswer1" class="accordion-collapse collapse"
                                    data-bs-parent="#campaignsAccordion">
                                    <div class="accordion-body">
                                        <p><strong>¡Por supuesto!</strong> Nuestro sistema de etiquetas te permite segmentar
                                            tu audiencia de forma muy precisa:</p>
                                        <ul class="mb-3">
                                            <li><strong>Etiquetas automáticas:</strong> Basadas en comportamiento,
                                                ubicación, fecha de registro</li>
                                            <li><strong>Etiquetas manuales:</strong> Que puedes asignar según tus criterios
                                                comerciales</li>
                                            <li><strong>Combinación de etiquetas:</strong> Crea segmentos súper específicos
                                                combinando múltiples etiquetas</li>
                                            <li><strong>Etiquetas dinámicas:</strong> Que se actualizan automáticamente
                                                según nuevas interacciones</li>
                                        </ul>
                                        <p>Por ejemplo: puedes enviar una campaña solo a "Clientes VIP + Madrid + Compra
                                            reciente" para máxima personalización.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 5 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="campaigns2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#campaignsAnswer2">
                                        ¿Puedo programar campañas?
                                    </button>
                                </h3>
                                <div id="campaignsAnswer2" class="accordion-collapse collapse"
                                    data-bs-parent="#campaignsAccordion">
                                    <div class="accordion-body">
                                        <p><strong>Sí, completamente.</strong> Nuestra funcionalidad de programación
                                            incluye:</p>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                                                    <h6>Fecha Específica</h6>
                                                    <p class="small">Programa para día y hora exactos</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <i class="fas fa-redo fa-2x text-primary mb-2"></i>
                                                    <h6>Campañas Recurrentes</h6>
                                                    <p class="small">Diarias, semanales o mensuales</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                                                    <h6>Zona Horaria</h6>
                                                    <p class="small">Respeta la zona horaria de cada contacto</p>
                                                </div>
                                            </div>
                                        </div>
                                        <p>Además, puedes programar secuencias de mensajes automáticas basadas en triggers
                                            específicos.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 6 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="campaigns3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#campaignsAnswer3">
                                        ¿Qué tipos de mensajes puedo enviar?
                                    </button>
                                </h3>
                                <div id="campaignsAnswer3" class="accordion-collapse collapse"
                                    data-bs-parent="#campaignsAccordion">
                                    <div class="accordion-body">
                                        <p>Puedes enviar todos los tipos de mensajes soportados por WhatsApp Business API:
                                        </p>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <ul>
                                                    <li><strong>Texto simple:</strong> Mensajes de texto personalizados</li>
                                                    <li><strong>Texto con formato:</strong> Negrita, cursiva, etc.</li>
                                                    <li><strong>Imágenes:</strong> JPG, PNG con texto descriptivo</li>
                                                    <li><strong>Documentos:</strong> PDF, DOCX, XLSX</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <ul>
                                                    <li><strong>Videos:</strong> MP4 hasta 16MB</li>
                                                    <li><strong>Audio:</strong> Mensajes de voz</li>
                                                    <li><strong>Ubicaciones:</strong> Compartir coordenadas</li>
                                                    <li><strong>Botones interactivos:</strong> Call-to-action</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="alert alert-info">
                                            <i class="fas fa-lightbulb me-2"></i>
                                            <strong>Tip:</strong> Los mensajes con plantillas pre-aprobadas por WhatsApp
                                            tienen mayor tasa de entrega.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Questions -->
                    <div class="mb-5">
                        <h2 class="section-title text-start">
                            <i class="fas fa-dollar-sign me-2"></i>Precios y Facturación
                        </h2>

                        <div class="accordion" id="pricingAccordion">
                            <!-- Question 7 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="pricing1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#pricingAnswer1">
                                        ¿Los precios incluyen el costo de WhatsApp Business?
                                    </button>
                                </h3>
                                <div id="pricingAnswer1" class="accordion-collapse collapse"
                                    data-bs-parent="#pricingAccordion">
                                    <div class="accordion-body">
                                        <div class="alert alert-warning mb-3">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>No, los precios de la plataforma NO incluyen los costos de WhatsApp
                                                Business API.</strong>
                                        </div>
                                        <p>Nuestros precios cubren únicamente el uso de la plataforma. Los envíos se
                                            facturan por separado según las tarifas oficiales de
                                            <a href="https://developers.facebook.com/docs/whatsapp/pricing/?translation"
                                                target="_blank" class="link-primary">WhatsApp
                                            </a>.
                                        </p>
                                        {{-- <table class="table table-striped mb-3">
                                            <thead>
                                                <tr>
                                                    <th>Tipo de Conversación</th>
                                                    <th>Costo Aproximado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Iniciada por el negocio</td>
                                                    <td>$0.005 - $0.019 por conversación</td>
                                                </tr>
                                                <tr>
                                                    <td>Iniciada por el cliente</td>
                                                    <td>$0.001 - $0.005 por conversación</td>
                                                </tr>
                                            </tbody>
                                        </table> --}}
                                        <p><small><em>Los precios varían según el país de destino. Una conversación puede
                                                    incluir múltiples mensajes en un período de 24 horas.</em></small></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 8 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="pricing2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#pricingAnswer2">
                                        ¿Hay costos ocultos o adicionales?
                                    </button>
                                </h3>
                                <div id="pricingAnswer2" class="accordion-collapse collapse"
                                    data-bs-parent="#pricingAccordion">
                                    <div class="accordion-body">
                                        <p><strong>No hay costos ocultos.</strong> Nuestro modelo de precios es 100%
                                            transparente:</p>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <div class="card border-success">
                                                    <div class="card-header bg-success text-white">
                                                        <h6 class="mb-0"><i class="fas fa-check me-2"></i>Incluido en tu
                                                            plan</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="list-unstyled mb-0">
                                                            <li>✓ Uso de la plataforma</li>
                                                            <li>✓ Soporte técnico</li>
                                                            <li>✓ Actualizaciones</li>
                                                            <li>✓ Almacenamiento de datos</li>
                                                            <li>✓ Analytics y reportes</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card border-info">
                                                    <div class="card-header bg-info text-white">
                                                        <h6 class="mb-0"><i class="fas fa-info me-2"></i>Costos
                                                            adicionales</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="list-unstyled mb-0">
                                                            {{-- <li>• Envíos de WhatsApp (según uso)</li> --}}
                                                            <li>• Servicios de consultoría (opcional)</li>
                                                            <li>• Integraciones personalizadas (opcional)</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <p>Te proporcionamos estimaciones detalladas de costos antes de cada campaña para
                                            que siempre sepas cuánto vas a invertir.</p> --}}
                                    </div>
                                </div>
                            </div>

                            <!-- Question 9 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="pricing3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#pricingAnswer3">
                                        ¿Puedo cambiar de plan en cualquier momento?
                                    </button>
                                </h3>
                                <div id="pricingAnswer3" class="accordion-collapse collapse"
                                    data-bs-parent="#pricingAccordion">
                                    <div class="accordion-body">
                                        <p><strong>¡Absolutamente!</strong> Puedes cambiar tu plan cuando necesites:</p>
                                        <ul class="mb-3">
                                            <li><strong>Upgrade:</strong> Inmediato, pagas la diferencia prorrateada</li>
                                            <li><strong>Downgrade:</strong> Al final del período de facturación actual</li>
                                            <li><strong>Sin penalizaciones:</strong> No cobramos fees por cambios de plan
                                            </li>
                                            <li><strong>Flexibilidad total:</strong> Adapta el plan al crecimiento de tu
                                                negocio</li>
                                        </ul>
                                        <div class="alert alert-success">
                                            <i class="fas fa-gift me-2"></i>
                                            <strong>Bonus:</strong> Si necesitas más recursos temporalmente (como en Black
                                            Friday), podemos activar un boost temporal sin cambiar tu plan base.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Questions -->
                    <div class="mb-5">
                        <h2 class="section-title text-start">
                            <i class="fas fa-cogs me-2"></i>Aspectos Técnicos
                        </h2>

                        <div class="accordion" id="technicalAccordion">
                            <!-- Question 10 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="technical1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#technicalAnswer1">
                                        ¿Tienen API disponible para integraciones?
                                    </button>
                                </h3>
                                <div id="technicalAnswer1" class="accordion-collapse collapse"
                                    data-bs-parent="#technicalAccordion">
                                    <div class="accordion-body">
                                        <p><strong>Sí, tenemos una API RESTful completa</strong> disponible en los planes
                                            Professional y Enterprise:</p>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <h6>Funciones API Disponibles:</h6>
                                                <ul>
                                                    <li>Envío de mensajes individuales</li>
                                                    <li>Datos de las campañas</li>
                                                    {{-- <li>Creación de campañas</li> --}}
                                                    {{-- <li>Gestión de contactos</li> --}}
                                                    <li>Obtención de métricas</li>
                                                    {{-- <li>Configuración de webhooks</li> --}}
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Recursos para Desarrolladores:</h6>
                                                <ul>
                                                    <li>Documentación completa</li>
                                                    {{-- <li>SDK para JavaScript/PHP</li> --}}
                                                    <li>Postman Collection</li>
                                                    {{-- <li>Sandbox para pruebas</li> --}}
                                                    <li>Soporte técnico dedicado</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="alert alert-info">
                                            <i class="fas fa-code me-2"></i>
                                            <strong>Para Desarrolladores:</strong> Nuestra API sigue estándares REST, usa
                                            autenticación JWT y soporta rate limiting para uso eficiente.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 11 -->
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="technical2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#technicalAnswer2">
                                        ¿Qué límites de envío tiene WhatsApp?
                                    </button>
                                </h3>
                                <div id="technicalAnswer2" class="accordion-collapse collapse"
                                    data-bs-parent="#technicalAccordion">
                                    <div class="accordion-body">
                                        <p>WhatsApp Business API tiene límites que van aumentando según tu reputación y
                                            volumen histórico:</p>
                                        <table class="table table-striped mb-3">
                                            <thead>
                                                <tr>
                                                    <th>Nivel de Cuenta</th>
                                                    <th>Límite Diario</th>
                                                    <th>Cómo Alcanzar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Tier 1 (Nueva)</td>
                                                    <td>250 conversaciones únicas</td>
                                                    <td>Cuenta nueva recién verificada</td>
                                                </tr>
                                                <tr>
                                                    <td>Tier 2</td>
                                                    <td>1,000 conversaciones únicas</td>
                                                    <td>Tras 7 días consecutivos cerca del límite</td>
                                                </tr>
                                                <tr>
                                                    <td>Tier 3</td>
                                                    <td>10,000 conversaciones únicas</td>
                                                    <td>Continuando con buen comportamiento</td>
                                                </tr>
                                                <tr>
                                                    <td>Tier 4+</td>
                                                    <td>100,000+ conversaciones únicas</td>
                                                    <td>Cuentas de alto volumen establecidas</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-lightbulb me-2"></i>
                                            <strong>Importante:</strong> Una conversación puede incluir múltiples mensajes.
                                            Los límites se resetean cada 24 horas.
                                        </div>
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
                    <h2>¿Tienes más preguntas?</h2>
                    <p class="lead mb-4">Nuestro equipo de soporte está disponible para resolver cualquier duda que tengas.
                    </p>
                    <div class="row justify-content-center g-3">
                        <div class="col-md-4">
                            <a href="{{ route('contactarnos') }}" class="btn btn-light btn-lg w-100">
                                <i class="fas fa-envelope me-2"></i>Enviar Consulta
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="tel:+573171789584" class="btn btn-outline-light btn-lg w-100">
                                <i class="fas fa-phone me-2"></i>Llamar Ahora
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="https://wa.me/573171789584" class="btn btn-outline-light btn-lg w-100">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection
