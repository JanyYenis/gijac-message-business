@extends('layouts.principal')

@section('css')
@endsection

@section('content')
    <!-- Privacy Policy Content -->
    <section class="section mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <!-- Introducción -->
                    <div class="mb-5">
                        <h2 class="h3 mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-shield-alt me-2"></i>Introducción
                        </h2>
                        <p class="mb-3">
                            En GIJAC MESSAGE BUSINESS, nos comprometemos a proteger y respetar su privacidad. Esta política de privacidad explica cómo recopilamos, utilizamos, almacenamos y protegemos su información personal cuando utiliza nuestros servicios.
                        </p>
                        <p>
                            Al utilizar nuestra plataforma, usted acepta las prácticas descritas en esta política de privacidad. Le recomendamos que lea cuidadosamente este documento antes de utilizar nuestros servicios.
                        </p>
                    </div>

                    <!-- Datos que recopilamos -->
                    <div class="mb-5">
                        <h2 class="h3 mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-database me-2"></i>Datos que Recopilamos
                        </h2>
                        <h4 class="h5 mb-3">Información Personal</h4>
                        <ul class="mb-3">
                            <li>Nombre completo y datos de contacto</li>
                            <li>Dirección de correo electrónico</li>
                            <li>Número de teléfono</li>
                            <li>Información de la empresa y cargo</li>
                            <li>Datos de facturación y pago</li>
                        </ul>

                        <h4 class="h5 mb-3">Información Técnica</h4>
                        <ul class="mb-3">
                            <li>Dirección IP y datos de ubicación</li>
                            <li>Información del navegador y dispositivo</li>
                            <li>Cookies y tecnologías similares</li>
                            <li>Registros de actividad en la plataforma</li>
                            <li>Métricas de uso y rendimiento</li>
                        </ul>

                        <h4 class="h5 mb-3">Datos de WhatsApp Business</h4>
                        <ul>
                            <li>Mensajes enviados y recibidos a través de la API</li>
                            <li>Contactos y listas de destinatarios</li>
                            <li>Plantillas de mensajes y configuraciones</li>
                            <li>Estadísticas de entrega y engagement</li>
                        </ul>
                    </div>

                    <!-- Uso de datos -->
                    <div class="mb-5">
                        <h2 class="h3 mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-cogs me-2"></i>Uso de los Datos
                        </h2>
                        <p class="mb-3">Utilizamos su información personal para los siguientes propósitos:</p>

                        <h4 class="h5 mb-3">Prestación de Servicios</h4>
                        <ul class="mb-3">
                            <li>Proporcionar acceso a la plataforma WhatsApp Business</li>
                            <li>Procesar y entregar mensajes a través de la API</li>
                            <li>Generar análisis y reportes de rendimiento</li>
                            <li>Brindar soporte técnico y atención al cliente</li>
                        </ul>

                        <h4 class="h5 mb-3">Mejora de Servicios</h4>
                        <ul class="mb-3">
                            <li>Analizar patrones de uso para mejorar la plataforma</li>
                            <li>Desarrollar nuevas funcionalidades</li>
                            <li>Optimizar la experiencia del usuario</li>
                            <li>Prevenir fraude y garantizar la seguridad</li>
                        </ul>

                        <h4 class="h5 mb-3">Comunicación</h4>
                        <ul>
                            <li>Enviar notificaciones importantes del servicio</li>
                            <li>Informar sobre actualizaciones y nuevas funciones</li>
                            <li>Responder a consultas y solicitudes de soporte</li>
                            <li>Enviar comunicaciones de marketing (con su consentimiento)</li>
                        </ul>
                    </div>

                    <!-- Protección de la información -->
                    <div class="mb-5">
                        <h2 class="h3 mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-lock me-2"></i>Protección de la Información
                        </h2>
                        <p class="mb-3">
                            Implementamos medidas técnicas y organizativas apropiadas para proteger su información personal contra el acceso no autorizado, alteración, divulgación o destrucción.
                        </p>

                        <h4 class="h5 mb-3">Medidas de Seguridad Técnicas</h4>
                        <ul class="mb-3">
                            <li>Encriptación SSL/TLS para todas las comunicaciones</li>
                            <li>Encriptación de datos en reposo</li>
                            <li>Autenticación de dos factores (2FA)</li>
                            <li>Monitoreo continuo de seguridad</li>
                            <li>Copias de seguridad regulares y seguras</li>
                        </ul>

                        <h4 class="h5 mb-3">Medidas Organizativas</h4>
                        <ul class="mb-3">
                            <li>Acceso limitado a datos personales solo al personal autorizado</li>
                            <li>Formación regular en privacidad y seguridad</li>
                            <li>Auditorías periódicas de seguridad</li>
                            <li>Políticas estrictas de gestión de datos</li>
                        </ul>

                        <h4 class="h5 mb-3">Cumplimiento Normativo</h4>
                        <ul>
                            {{-- <li>Cumplimiento con el Reglamento General de Protección de Datos (GDPR)</li> --}}
                            <li>Adhesión a las políticas de WhatsApp Business API</li>
                            {{-- <li>Certificaciones de seguridad ISO 27001</li> --}}
                            {{-- <li>Auditorías de terceros independientes</li> --}}
                        </ul>
                    </div>

                    <!-- Derechos del usuario -->
                    <div class="mb-5">
                        <h2 class="h3 mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-user-shield me-2"></i>Derechos del Usuario
                        </h2>
                        <p class="mb-3">Usted tiene los siguientes derechos con respecto a su información personal:</p>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-eye text-primary me-2"></i>Acceso
                                        </h5>
                                        <p class="card-text">Solicitar una copia de los datos personales que tenemos sobre usted.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-edit text-primary me-2"></i>Rectificación
                                        </h5>
                                        <p class="card-text">Solicitar la corrección de información personal inexacta o incompleta.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-trash text-primary me-2"></i>Eliminación
                                        </h5>
                                        <p class="card-text">Solicitar la eliminación de sus datos personales en ciertas circunstancias.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-ban text-primary me-2"></i>Limitación
                                        </h5>
                                        <p class="card-text">Solicitar la restricción del procesamiento de su información personal.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-download text-primary me-2"></i>Portabilidad
                                        </h5>
                                        <p class="card-text">Recibir sus datos personales en un formato estructurado y legible.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-times-circle text-primary me-2"></i>Oposición
                                        </h5>
                                        <p class="card-text">Oponerse al procesamiento de sus datos para fines de marketing directo.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>¿Cómo ejercer sus derechos?</strong> Para ejercer cualquiera de estos derechos, puede contactarnos a través de nuestro formulario de contacto o enviando un correo a <a href="mailto:privacidad@gijac.co">privacidad@gijac.co</a>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="mb-5">
                        <h2 class="h3 mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-envelope me-2"></i>Contacto
                        </h2>
                        <p class="mb-3">
                            Si tiene preguntas, comentarios o inquietudes sobre esta política de privacidad o sobre cómo manejamos su información personal, no dude en contactarnos:
                        </p>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Datos de Contacto</h5>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-envelope me-2 text-primary"></i>
                                                <strong>Email:</strong> privacidad@gijac.co
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-phone me-2 text-primary"></i>
                                                <strong>Teléfono:</strong> +57 (317) 178-9584
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                                <strong>Dirección:</strong> Corregimiento de Navarro, callejón El Recuerdo
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6 d-none">
                                        <h5>Delegado de Protección de Datos</h5>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-user me-2 text-primary"></i>
                                                <strong>DPO:</strong> María González
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-envelope me-2 text-primary"></i>
                                                <strong>Email:</strong> dpo@gijac.co
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Tiempo de Respuesta:</strong> Nos comprometemos a responder a todas las consultas relacionadas con la privacidad dentro de 30 días hábiles.
                        </div>
                    </div>

                    <!-- Cambios en la política -->
                    <div class="mb-5">
                        <div class="card" style="border-left: 5px solid var(--primary-color);">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-sync-alt me-2"></i>Cambios en esta Política
                                </h5>
                                <p class="card-text mb-3">
                                    Podemos actualizar esta política de privacidad de vez en cuando para reflejar cambios en nuestras prácticas de información o en la legislación aplicable.
                                </p>
                                <ul class="mb-0">
                                    <li>Le notificaremos sobre cambios significativos por correo electrónico</li>
                                    {{-- <li>La fecha de la última actualización aparece en la parte superior de esta página</li> --}}
                                    <li>Le recomendamos revisar periódicamente esta política</li>
                                    <li>El uso continuado de nuestros servicios constituye la aceptación de los cambios</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection
