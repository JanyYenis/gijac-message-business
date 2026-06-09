@extends('layouts.principal')

@section('css')
    <style>
        :root {
            --whatsapp-green: #25D366;
            --whatsapp-dark: #128C7E;
            --text-primary: #1a1a1a;
            --text-secondary: #6b7280;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
        }

        .hero-section {
            min-height: 100px !important;
        }

        .content-section {
            padding: 3rem 0;
        }

        .section-title {
            color: var(--whatsapp-dark);
            font-weight: 600;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--whatsapp-green);
            padding-left: 1rem;
        }

        .subsection {
            margin-bottom: 1.5rem;
        }

        .highlight-box {
            background-color: #f8f9fa;
            border-left: 4px solid var(--whatsapp-green);
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 0 8px 8px 0;
        }

        .contact-info {
            background: linear-gradient(135deg, var(--whatsapp-green) 0%, var(--whatsapp-dark) 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-top: 2rem;
        }

        ul li {
            margin-bottom: 0.5rem;
        }

        .last-updated {
            background-color: #e3f2fd;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid #2196f3;
        }
    </style>
@endsection

@section('content')
    <!-- Header -->
    <div class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-3">Términos y Condiciones de Uso</h1>
                    <p class="lead">GIJAC MESSAGE BUSINESS</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">

                    <div class="last-updated">
                        <strong>Fecha de última actualización:</strong> 2 de septiembre de 2025
                    </div>

                    <!-- 1. Introducción -->
                    <h2 class="section-title">1. Introducción</h2>
                    <div class="subsection">
                        <p>Bienvenido a GIJAC MESSAGE BUSINESS, una plataforma de Software como Servicio (SaaS) que proporciona herramientas de mensajería masiva, chatbots y automatización para WhatsApp Business.</p>

                        <p>Al acceder, registrarse o utilizar cualquier funcionalidad de nuestra plataforma, usted acepta estar legalmente vinculado por estos Términos y Condiciones de Uso. Si no está de acuerdo con alguna parte de estos términos, no debe utilizar nuestros servicios.</p>

                        <p>Estos términos constituyen un acuerdo legal entre usted (el "Usuario" o "Cliente") y GIJAC MESSAGE BUSINESS (en adelante "GIJAC", "nosotros", "nuestro" o "la Plataforma").</p>
                    </div>

                    <!-- 2. Uso Permitido y Prohibiciones -->
                    <h2 class="section-title">2. Uso Permitido y Prohibiciones</h2>
                    <div class="subsection">
                        <h5>2.1 Uso Permitido</h5>
                        <p>Usted se compromete a utilizar nuestros servicios únicamente:</p>
                        <ul>
                            <li>De conformidad con todas las leyes y regulaciones aplicables</li>
                            <li>En cumplimiento de las políticas oficiales de WhatsApp Business API</li>
                            <li>Para fines comerciales legítimos y éticos</li>
                            <li>Respetando los derechos de privacidad y protección de datos de terceros</li>
                        </ul>

                        <h5>2.2 Prohibiciones</h5>
                        <p>Está estrictamente prohibido utilizar nuestros servicios para:</p>
                        <ul>
                            <li><strong>Spam:</strong> Envío masivo de mensajes no solicitados o no autorizados</li>
                            <li><strong>Fraude:</strong> Actividades fraudulentas, engañosas o que induzcan a error</li>
                            <li><strong>Uso indebido de datos:</strong> Recopilación, procesamiento o uso no autorizado de datos personales sensibles</li>
                            <li><strong>Actividades ilegales:</strong> Cualquier actividad que viole leyes locales, nacionales o internacionales</li>
                            <li><strong>Contenido inapropiado:</strong> Difusión de contenido ofensivo, discriminatorio, violento o que incite al odio</li>
                            <li><strong>Violación de derechos:</strong> Infracción de derechos de autor, marcas registradas o propiedad intelectual</li>
                        </ul>
                    </div>

                    <!-- 3. Suscripciones, Pagos y Cancelaciones -->
                    <h2 class="section-title">3. Suscripciones, Pagos y Cancelaciones</h2>
                    <div class="subsection">
                        <h5>3.1 Modelo de Suscripción</h5>
                        <p>GIJAC MESSAGE BUSINESS opera bajo un modelo de Software como Servicio (SaaS) con suscripciones recurrentes. Los planes disponibles y sus características se detallan en nuestro sitio web oficial.</p>

                        <h5>3.2 Condiciones de Pago</h5>
                        <ul>
                            <li>Los pagos se procesan de forma automática según el ciclo de facturación seleccionado (mensual o anual)</li>
                            <li>Todos los precios se muestran en la moneda local aplicable e incluyen impuestos cuando corresponda</li>
                            <li>El acceso a los servicios se mantiene mientras la suscripción esté activa y al día</li>
                            <li>Los cargos no pagados pueden resultar en la suspensión temporal o permanente del servicio</li>
                        </ul>

                        <h5>3.3 Reembolsos</h5>
                        <p>Ofrecemos reembolsos únicamente en las siguientes circunstancias:</p>
                        <ul>
                            <li>Fallas técnicas prolongadas atribuibles exclusivamente a nuestra plataforma</li>
                            <li>Cancelación dentro de los primeros 7 días de la primera suscripción (período de prueba)</li>
                            <li>Cargos duplicados por errores del sistema de facturación</li>
                        </ul>

                        <h5>3.4 Cancelación</h5>
                        <p>Puede cancelar su suscripción en cualquier momento desde su panel de control. La cancelación será efectiva al final del período de facturación actual, manteniendo acceso hasta esa fecha.</p>
                    </div>

                    <!-- 4. Disponibilidad del Servicio -->
                    <h2 class="section-title">4. Disponibilidad del Servicio</h2>
                    <div class="subsection">
                        <div class="highlight-box">
                            <p><strong>Dependencia de Terceros:</strong> Nuestros servicios dependen de la API oficial de WhatsApp Business y otros proveedores de servicios externos. Por esta razón, no podemos garantizar una disponibilidad del 100%.</p>
                        </div>

                        <p>Nos esforzamos por mantener la máxima disponibilidad posible, pero pueden ocurrir interrupciones debido a:</p>
                        <ul>
                            <li>Mantenimiento programado de la API de WhatsApp</li>
                            <li>Actualizaciones de seguridad y mejoras del sistema</li>
                            <li>Circunstancias imprevistas o fuerza mayor</li>
                            <li>Problemas técnicos de proveedores externos</li>
                        </ul>

                        <p>En caso de interrupciones prolongadas, notificaremos a nuestros usuarios y trabajaremos para restaurar el servicio lo antes posible.</p>
                    </div>

                    <!-- 5. Datos Personales y Privacidad -->
                    <h2 class="section-title">5. Datos Personales y Privacidad</h2>
                    <div class="subsection">
                        <h5>5.1 Roles de Tratamiento de Datos</h5>
                        <p>En el marco de la protección de datos personales:</p>
                        <ul>
                            <li><strong>GIJAC MESSAGE BUSINESS</strong> actúa como <strong>encargado del tratamiento</strong> de los datos que procesa a través de nuestra plataforma</li>
                            <li><strong>El Cliente</strong> (usted) es el <strong>responsable del tratamiento</strong> frente a sus usuarios finales y debe cumplir con todas las obligaciones legales correspondientes</li>
                        </ul>

                        <h5>5.2 Política de Privacidad</h5>
                        <p>El uso de nuestros servicios implica la aceptación de nuestra Política de Privacidad, donde se detallan las prácticas de recopilación, uso y protección de datos.</p>

                        <h5>5.3 Cláusula Especial de Acceso para Soporte</h5>
                        <div class="highlight-box">
                            <p><strong>Acceso para Diagnóstico:</strong> Para brindar un mejor soporte técnico y siempre con autorización previa del cliente, el equipo de GIJAC MESSAGE BUSINESS podrá acceder a la información de su perfil únicamente con fines de diagnóstico y resolución de problemas técnicos, sin realizar modificaciones no autorizadas ni afectar el funcionamiento normal de su cuenta.</p>
                        </div>

                        <h5>5.4 Responsabilidades del Cliente</h5>
                        <p>Como responsable del tratamiento, usted debe:</p>
                        <ul>
                            <li>Obtener los consentimientos necesarios de sus usuarios finales</li>
                            <li>Cumplir con las leyes de protección de datos aplicables</li>
                            <li>Informar a sus usuarios sobre el tratamiento de sus datos</li>
                            <li>Implementar medidas de seguridad adecuadas</li>
                        </ul>
                    </div>

                    <!-- 6. Uso de Chatbots, Campañas y Automatizaciones -->
                    <h2 class="section-title">6. Uso de Chatbots, Campañas y Automatizaciones</h2>
                    <div class="subsection">
                        <h5>6.1 Naturaleza de los Chatbots</h5>
                        <p>Los chatbots y sistemas de automatización proporcionados por nuestra plataforma son herramientas de asistencia que <strong>no sustituyen la supervisión humana</strong>. Se recomienda encarecidamente mantener supervisión y revisión regular de las interacciones automatizadas.</p>

                        <h5>6.2 Responsabilidad del Contenido</h5>
                        <p>El usuario es completamente responsable de:</p>
                        <ul>
                            <li>Todo el contenido enviado a través de chatbots y campañas automatizadas</li>
                            <li>La configuración y programación de respuestas automáticas</li>
                            <li>El cumplimiento de las políticas de WhatsApp Business en mensajes automatizados</li>
                            <li>La veracidad y legalidad de la información difundida</li>
                        </ul>

                        <h5>6.3 Mejores Prácticas</h5>
                        <p>Recomendamos:</p>
                        <ul>
                            <li>Revisar periódicamente las conversaciones automatizadas</li>
                            <li>Proporcionar opciones claras para contactar con un humano</li>
                            <li>Mantener actualizadas las respuestas y flujos de conversación</li>
                            <li>Respetar los horarios y frecuencias apropiadas de mensajería</li>
                        </ul>
                    </div>

                    <!-- 7. Limitación de Responsabilidad -->
                    <h2 class="section-title">7. Limitación de Responsabilidad</h2>
                    <div class="subsection">
                        <h5>7.1 Servicio "Tal Cual"</h5>
                        <p>Nuestros servicios se proporcionan "tal cual" y "según disponibilidad", sin garantías expresas o implícitas de ningún tipo, incluyendo pero no limitándose a garantías de comerciabilidad, idoneidad para un propósito particular o no infracción.</p>

                        <h5>7.2 Limitación de Daños</h5>
                        <p>En ningún caso GIJAC MESSAGE BUSINESS será responsable por:</p>
                        <ul>
                            <li>Daños indirectos, incidentales, especiales o consecuenciales</li>
                            <li>Pérdida de beneficios, datos, uso, buena voluntad u otras pérdidas intangibles</li>
                            <li>Interrupciones del servicio causadas por terceros o circunstancias fuera de nuestro control</li>
                        </ul>

                        <h5>7.3 Responsabilidad Máxima</h5>
                        <div class="highlight-box">
                            <p><strong>Límite de Responsabilidad:</strong> Nuestra responsabilidad máxima total hacia usted por cualquier reclamación relacionada con estos términos o el uso de nuestros servicios se limita al monto total pagado por usted a GIJAC MESSAGE BUSINESS en los últimos 12 meses.</p>
                        </div>
                    </div>

                    <!-- 8. Terminación del Servicio -->
                    <h2 class="section-title">8. Terminación del Servicio</h2>
                    <div class="subsection">
                        <h5>8.1 Terminación por Parte de GIJAC</h5>
                        <p>Nos reservamos el derecho de suspender o terminar su acceso a nuestros servicios, con o sin previo aviso, en los siguientes casos:</p>
                        <ul>
                            <li>Violación de estos Términos y Condiciones</li>
                            <li>Incumplimiento de las políticas de WhatsApp Business API</li>
                            <li>Actividades fraudulentas o ilegales</li>
                            <li>Falta de pago de las suscripciones</li>
                            <li>Uso que comprometa la seguridad o estabilidad de la plataforma</li>
                        </ul>

                        <h5>8.2 Terminación por Parte del Usuario</h5>
                        <p>Puede cancelar su cuenta y terminar estos términos en cualquier momento:</p>
                        <ul>
                            <li>Accediendo a la configuración de su cuenta</li>
                            <li>Contactando a nuestro equipo de soporte</li>
                            <li>La terminación será efectiva al final del período de facturación actual</li>
                        </ul>

                        <h5>8.3 Efectos de la Terminación</h5>
                        <p>Al terminar el servicio:</p>
                        <ul>
                            <li>Se suspenderá inmediatamente el acceso a la plataforma</li>
                            <li>Los datos podrán ser eliminados según nuestra política de retención</li>
                            <li>Las obligaciones de pago pendientes permanecerán vigentes</li>
                        </ul>
                    </div>

                    <!-- 9. Cambios en Precios y Términos -->
                    <h2 class="section-title">9. Cambios en Precios y Términos</h2>
                    <div class="subsection">
                        <h5>9.1 Modificaciones de Precios</h5>
                        <p>Nos reservamos el derecho de modificar nuestros precios con un aviso previo mínimo de 30 días. Los cambios de precio no afectarán el período de facturación actual ya pagado.</p>

                        <h5>9.2 Modificaciones de Términos</h5>
                        <p>Podemos actualizar estos Términos y Condiciones ocasionalmente. Las modificaciones significativas serán notificadas con al menos 15 días de anticipación a través de:</p>
                        <ul>
                            <li>Notificación por correo electrónico</li>
                            <li>Aviso en la plataforma</li>
                            <li>Actualización en nuestro sitio web</li>
                        </ul>

                        <h5>9.3 Aceptación de Cambios</h5>
                        <p>El uso continuado de nuestros servicios después de la entrada en vigor de las modificaciones constituye su aceptación de los nuevos términos.</p>
                    </div>

                    <!-- 10. Legislación Aplicable y Resolución de Disputas -->
                    <h2 class="section-title">10. Legislación Aplicable y Resolución de Disputas</h2>
                    <div class="subsection">
                        <h5>10.1 Ley Aplicable</h5>
                        <p>Estos Términos y Condiciones se rigen por las leyes de la República de Colombia, sin perjuicio de sus principios de conflicto de leyes.</p>

                        <h5>10.2 Jurisdicción</h5>
                        <p>Cualquier disputa, controversia o reclamación relacionada con estos términos será sometida a la jurisdicción exclusiva de los tribunales competentes de Colombia.</p>

                        <h5>10.3 Resolución Amistosa</h5>
                        <p>Antes de iniciar cualquier procedimiento legal, las partes se comprometen a intentar resolver las disputas de manera amistosa a través de negociación directa durante un período de 30 días.</p>
                    </div>

                    <!-- 11. Divisibilidad y Renuncia -->
                    <h2 class="section-title">11. Divisibilidad y Renuncia</h2>
                    <div class="subsection">
                        <h5>11.1 Divisibilidad</h5>
                        <p>Si cualquier disposición de estos términos es declarada inválida, ilegal o inaplicable por un tribunal competente, las disposiciones restantes permanecerán en pleno vigor y efecto.</p>

                        <h5>11.2 Renuncia</h5>
                        <p>La falta de ejercicio o el retraso en el ejercicio de cualquier derecho, poder o privilegio bajo estos términos no constituirá una renuncia al mismo.</p>

                        <h5>11.3 Acuerdo Completo</h5>
                        <p>Estos Términos y Condiciones, junto con nuestra Política de Privacidad, constituyen el acuerdo completo entre las partes con respecto al uso de nuestros servicios.</p>
                    </div>

                    <!-- 12. Contacto -->
                    <div class="contact-info">
                        <h3 class="mb-3">12. Información de Contacto</h3>
                        <p class="mb-2">Para cualquier consulta, soporte técnico o asuntos relacionados con estos Términos y Condiciones, puede contactarnos a través de:</p>
                        <p class="mb-0">
                            <strong>Correo electrónico oficial:</strong>
                            <a href="mailto:info@gijac.co" class="text-white text-decoration-underline">info@gijac.co</a>
                        </p>
                        <p class="mb-0 mt-2">
                            <strong>Plataforma:</strong> GIJAC MESSAGE BUSINESS<br>
                            <strong>Sitio web:</strong> <a href="#" class="text-white text-decoration-underline">www.gijac.com</a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
