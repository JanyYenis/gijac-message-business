@extends('layouts.principal')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/gmb.css') }}">
    <style>{{ __(':root {
            --whatsapp-green: #25D366;
            --whatsapp-dark: #128C7E;
            --text-primary: #1a1a1a;
            --text-secondary: #6b7280;
        }

        body {
            font-family: \'Inter\', sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
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

        .last-updated {
            background-color: #e3f2fd;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid #2196f3;
        }
        .hero-section {
            min-height: auto !important;
        }
        .site-header .nav-link {
            color: #fff !important;
            margin: auto !important;
        }
        .site-header .nav-link.active {
            color: #fff !important;
        }') }</style>
@endsection

@section('content')
    <!-- Header -->
    <div class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-3">{{ __('Términos y Condiciones de Uso') }}</h1>
                    <p class="lead">{{ __('GIJAC MESSAGE BUSINESS') }}</p>
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
                        <strong>{{ __('Fecha de última actualización:') }}</strong>{{ __('2 de septiembre de 2025') }}</div>

                    <!-- 1. Introducción -->
                    <h2 class="section-title">{{ __('1. Introducción') }}</h2>
                    <div class="subsection">
                        <p>{{ __('Bienvenido a GIJAC MESSAGE BUSINESS, una plataforma de Software como Servicio (SaaS) que proporciona herramientas de mensajería masiva, chatbots y automatización para WhatsApp Business.') }}</p>

                        <p>{{ __('Al acceder, registrarse o utilizar cualquier funcionalidad de nuestra plataforma, usted acepta estar legalmente vinculado por estos Términos y Condiciones de Uso. Si no está de acuerdo con alguna parte de estos términos, no debe utilizar nuestros servicios.') }}</p>

                        <p>{{ __('Estos términos constituyen un acuerdo legal entre usted (el "Usuario" o "Cliente") y GIJAC MESSAGE BUSINESS (en adelante "GIJAC", "nosotros", "nuestro" o "la Plataforma").') }}</p>
                    </div>

                    <!-- 2. Uso Permitido y Prohibiciones -->
                    <h2 class="section-title">{{ __('2. Uso Permitido y Prohibiciones') }}</h2>
                    <div class="subsection">
                        <h5>{{ __('2.1 Uso Permitido') }}</h5>
                        <p>{{ __('Usted se compromete a utilizar nuestros servicios únicamente:') }}</p>
                        <ul>
                            <li style="margin-bottom: 0.5rem;">{{ __('De conformidad con todas las leyes y regulaciones aplicables') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('En cumplimiento de las políticas oficiales de WhatsApp Business API') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Para fines comerciales legítimos y éticos') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Respetando los derechos de privacidad y protección de datos de terceros') }}</li>
                        </ul>

                        <h5>{{ __('2.2 Prohibiciones') }}</h5>
                        <p>{{ __('Está estrictamente prohibido utilizar nuestros servicios para:') }}</p>
                        <ul>
                            <li style="margin-bottom: 0.5rem;">
                                <strong>{{ __('Spam:') }}</strong>{{ __('Envío masivo de mensajes no solicitados o no autorizados') }}</li>
                            <li style="margin-bottom: 0.5rem;">
                                <strong>{{ __('Fraude:') }}</strong>{{ __('Actividades fraudulentas, engañosas o que induzcan a error') }}</li>
                            <li style="margin-bottom: 0.5rem;">
                                <strong>{{ __('Uso indebido de datos:') }}</strong>{{ __('Recopilación, procesamiento o uso no autorizado de datos personales sensibles') }}</li>
                            <li style="margin-bottom: 0.5rem;">
                                <strong>{{ __('Actividades ilegales:') }}</strong>{{ __('Cualquier actividad que viole leyes locales, nacionales o internacionales') }}</li>
                            <li style="margin-bottom: 0.5rem;">
                                <strong>{{ __('Contenido inapropiado:') }}</strong>{{ __('Difusión de contenido ofensivo, discriminatorio, violento o que incite al odio') }}</li>
                            <li style="margin-bottom: 0.5rem;">
                                <strong>{{ __('Violación de derechos:') }}</strong>{{ __('Infracción de derechos de autor, marcas registradas o propiedad intelectual') }}</li>
                        </ul>
                    </div>

                    <!-- 3. Suscripciones, Pagos y Cancelaciones -->
                    <h2 class="section-title">{{ __('3. Suscripciones, Pagos y Cancelaciones') }}</h2>
                    <div class="subsection">
                        <h5>{{ __('3.1 Modelo de Suscripción') }}</h5>
                        <p>{{ __('GIJAC MESSAGE BUSINESS opera bajo un modelo de Software como Servicio (SaaS) con suscripciones recurrentes. Los planes disponibles y sus características se detallan en nuestro sitio web oficial.') }}</p>

                        <h5>{{ __('3.2 Condiciones de Pago') }}</h5>
                        <ul>
                            <li style="margin-bottom: 0.5rem;">{{ __('Los pagos se procesan de forma automática según el ciclo de facturación seleccionado (mensual o anual)') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Todos los precios se muestran en la moneda local aplicable e incluyen impuestos cuando corresponda') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('El acceso a los servicios se mantiene mientras la suscripción esté activa y al día') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Los cargos no pagados pueden resultar en la suspensión temporal o permanente del servicio') }}</li>
                        </ul>

                        <h5>{{ __('3.3 Reembolsos') }}</h5>
                        <p>{{ __('Ofrecemos reembolsos únicamente en las siguientes circunstancias:') }}</p>
                        <ul>
                            <li style="margin-bottom: 0.5rem;">{{ __('Fallas técnicas prolongadas atribuibles exclusivamente a nuestra plataforma') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Cancelación dentro de los primeros 7 días de la primera suscripción (período de prueba)') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Cargos duplicados por errores del sistema de facturación') }}</li>
                        </ul>

                        <h5>{{ __('3.4 Cancelación') }}</h5>
                        <p>{{ __('Puede cancelar su suscripción en cualquier momento desde su panel de control. La cancelación será efectiva al final del período de facturación actual, manteniendo acceso hasta esa fecha.') }}</p>
                    </div>

                    <!-- 4. Disponibilidad del Servicio -->
                    <h2 class="section-title">{{ __('4. Disponibilidad del Servicio') }}</h2>
                    <div class="subsection">
                        <div class="highlight-box">
                            <p><strong>{{ __('Dependencia de Terceros:') }}</strong>{{ __('Nuestros servicios dependen de la API oficial de WhatsApp Business y otros proveedores de servicios externos. Por esta razón, no podemos garantizar una disponibilidad del 100%.') }}</p>
                        </div>

                        <p>{{ __('Nos esforzamos por mantener la máxima disponibilidad posible, pero pueden ocurrir interrupciones debido a:') }}</p>
                        <ul>
                            <li style="margin-bottom: 0.5rem;">{{ __('Mantenimiento programado de la API de WhatsApp') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Actualizaciones de seguridad y mejoras del sistema') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Circunstancias imprevistas o fuerza mayor') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Problemas técnicos de proveedores externos') }}</li>
                        </ul>

                        <p>{{ __('En caso de interrupciones prolongadas, notificaremos a nuestros usuarios y trabajaremos para restaurar el servicio lo antes posible.') }}</p>
                    </div>

                    <!-- 5. Datos Personales y Privacidad -->
                    <h2 class="section-title">{{ __('5. Datos Personales y Privacidad') }}</h2>
                    <div class="subsection">
                        <h5>{{ __('5.1 Roles de Tratamiento de Datos') }}</h5>
                        <p>{{ __('En el marco de la protección de datos personales:') }}</p>
                        <ul>
                            <li style="margin-bottom: 0.5rem;">
                                <strong>{{ __('GIJAC MESSAGE BUSINESS') }}</strong>{{ __('actúa como') }}<strong>{{ __('encargado del tratamiento') }}</strong>{{ __('de los datos que procesa a través de nuestra plataforma') }}</li>
                            <li style="margin-bottom: 0.5rem;">
                                <strong>{{ __('El Cliente') }}</strong>{{ __('(usted) es el') }}<strong>{{ __('responsable del tratamiento') }}</strong>{{ __('frente a sus usuarios finales y debe cumplir con todas las obligaciones legales correspondientes') }}</li>
                        </ul>

                        <h5>{{ __('5.2 Política de Privacidad') }}</h5>
                        <p>{{ __('El uso de nuestros servicios implica la aceptación de nuestra Política de Privacidad, donde se detallan las prácticas de recopilación, uso y protección de datos.') }}</p>

                        <h5>{{ __('5.3 Cláusula Especial de Acceso para Soporte') }}</h5>
                        <div class="highlight-box">
                            <p><strong>{{ __('Acceso para Diagnóstico:') }}</strong>{{ __('Para brindar un mejor soporte técnico y siempre con autorización previa del cliente, el equipo de GIJAC MESSAGE BUSINESS podrá acceder a la información de su perfil únicamente con fines de diagnóstico y resolución de problemas técnicos, sin realizar modificaciones no autorizadas ni afectar el funcionamiento normal de su cuenta.') }}</p>
                        </div>

                        <h5>{{ __('5.4 Responsabilidades del Cliente') }}</h5>
                        <p>{{ __('Como responsable del tratamiento, usted debe:') }}</p>
                        <ul>
                            <li style="margin-bottom: 0.5rem;">{{ __('Obtener los consentimientos necesarios de sus usuarios finales') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Cumplir con las leyes de protección de datos aplicables') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Informar a sus usuarios sobre el tratamiento de sus datos') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Implementar medidas de seguridad adecuadas') }}</li>
                        </ul>
                    </div>

                    <!-- 6. Uso de Chatbots, Campañas y Automatizaciones -->
                    <h2 class="section-title">{{ __('6. Uso de Chatbots, Campañas y Automatizaciones') }}</h2>
                    <div class="subsection">
                        <h5>{{ __('6.1 Naturaleza de los Chatbots') }}</h5>
                        <p>{{ __('Los chatbots y sistemas de automatización proporcionados por nuestra plataforma son herramientas de asistencia que') }}<strong>{{ __('no sustituyen la supervisión humana') }}</strong>{{ __('. Se recomienda encarecidamente mantener supervisión y revisión regular de las interacciones automatizadas.') }}</p>

                        <h5>{{ __('6.2 Responsabilidad del Contenido') }}</h5>
                        <p>{{ __('El usuario es completamente responsable de:') }}</p>
                        <ul>
                            <li style="margin-bottom: 0.5rem;">{{ __('Todo el contenido enviado a través de chatbots y campañas automatizadas') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('La configuración y programación de respuestas automáticas') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('El cumplimiento de las políticas de WhatsApp Business en mensajes automatizados') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('La veracidad y legalidad de la información difundida') }}</li>
                        </ul>

                        <h5>{{ __('6.3 Mejores Prácticas') }}</h5>
                        <p>{{ __('Recomendamos:') }}</p>
                        <ul>
                            <li style="margin-bottom: 0.5rem;">{{ __('Revisar periódicamente las conversaciones automatizadas') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Proporcionar opciones claras para contactar con un humano') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Mantener actualizadas las respuestas y flujos de conversación') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Respetar los horarios y frecuencias apropiadas de mensajería') }}</li>
                        </ul>
                    </div>

                    <!-- 7. Limitación de Responsabilidad -->
                    <h2 class="section-title">{{ __('7. Limitación de Responsabilidad') }}</h2>
                    <div class="subsection">
                        <h5>{{ __('7.1 Servicio "Tal Cual"') }}</h5>
                        <p>{{ __('Nuestros servicios se proporcionan "tal cual" y "según disponibilidad", sin garantías expresas o implícitas de ningún tipo, incluyendo pero no limitándose a garantías de comerciabilidad, idoneidad para un propósito particular o no infracción.') }}</p>

                        <h5>{{ __('7.2 Limitación de Daños') }}</h5>
                        <p>{{ __('En ningún caso GIJAC MESSAGE BUSINESS será responsable por:') }}</p>
                        <ul>
                            <li style="margin-bottom: 0.5rem;">{{ __('Daños indirectos, incidentales, especiales o consecuenciales') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Pérdida de beneficios, datos, uso, buena voluntad u otras pérdidas intangibles') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Interrupciones del servicio causadas por terceros o circunstancias fuera de nuestro control') }}</li>
                        </ul>

                        <h5>{{ __('7.3 Responsabilidad Máxima') }}</h5>
                        <div class="highlight-box">
                            <p><strong>{{ __('Límite de Responsabilidad:') }}</strong>{{ __('Nuestra responsabilidad máxima total hacia usted por cualquier reclamación relacionada con estos términos o el uso de nuestros servicios se limita al monto total pagado por usted a GIJAC MESSAGE BUSINESS en los últimos 12 meses.') }}</p>
                        </div>
                    </div>

                    <!-- 8. Terminación del Servicio -->
                    <h2 class="section-title">{{ __('8. Terminación del Servicio') }}</h2>
                    <div class="subsection">
                        <h5>{{ __('8.1 Terminación por Parte de GIJAC') }}</h5>
                        <p>{{ __('Nos reservamos el derecho de suspender o terminar su acceso a nuestros servicios, con o sin previo aviso, en los siguientes casos:') }}</p>
                        <ul>
                            <li style="margin-bottom: 0.5rem;">{{ __('Violación de estos Términos y Condiciones') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Incumplimiento de las políticas de WhatsApp Business API') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Actividades fraudulentas o ilegales') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Falta de pago de las suscripciones') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Uso que comprometa la seguridad o estabilidad de la plataforma') }}</li>
                        </ul>

                        <h5>{{ __('8.2 Terminación por Parte del Usuario') }}</h5>
                        <p>{{ __('Puede cancelar su cuenta y terminar estos términos en cualquier momento:') }}</p>
                        <ul>
                            <li style="margin-bottom: 0.5rem;">{{ __('Accediendo a la configuración de su cuenta') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Contactando a nuestro equipo de soporte') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('La terminación será efectiva al final del período de facturación actual') }}</li>
                        </ul>

                        <h5>{{ __('8.3 Efectos de la Terminación') }}</h5>
                        <p>{{ __('Al terminar el servicio:') }}</p>
                        <ul>
                            <li style="margin-bottom: 0.5rem;">{{ __('Se suspenderá inmediatamente el acceso a la plataforma') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Los datos podrán ser eliminados según nuestra política de retención') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Las obligaciones de pago pendientes permanecerán vigentes') }}</li>
                        </ul>
                    </div>

                    <!-- 9. Cambios en Precios y Términos -->
                    <h2 class="section-title">{{ __('9. Cambios en Precios y Términos') }}</h2>
                    <div class="subsection">
                        <h5>{{ __('9.1 Modificaciones de Precios') }}</h5>
                        <p>{{ __('Nos reservamos el derecho de modificar nuestros precios con un aviso previo mínimo de 30 días. Los cambios de precio no afectarán el período de facturación actual ya pagado.') }}</p>

                        <h5>{{ __('9.2 Modificaciones de Términos') }}</h5>
                        <p>{{ __('Podemos actualizar estos Términos y Condiciones ocasionalmente. Las modificaciones significativas serán notificadas con al menos 15 días de anticipación a través de:') }}</p>
                        <ul>
                            <li style="margin-bottom: 0.5rem;">{{ __('Notificación por correo electrónico') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Aviso en la plataforma') }}</li>
                            <li style="margin-bottom: 0.5rem;">{{ __('Actualización en nuestro sitio web') }}</li>
                        </ul>

                        <h5>{{ __('9.3 Aceptación de Cambios') }}</h5>
                        <p>{{ __('El uso continuado de nuestros servicios después de la entrada en vigor de las modificaciones constituye su aceptación de los nuevos términos.') }}</p>
                    </div>

                    <!-- 10. Legislación Aplicable y Resolución de Disputas -->
                    <h2 class="section-title">{{ __('10. Legislación Aplicable y Resolución de Disputas') }}</h2>
                    <div class="subsection">
                        <h5>{{ __('10.1 Ley Aplicable') }}</h5>
                        <p>{{ __('Estos Términos y Condiciones se rigen por las leyes de la República de Colombia, sin perjuicio de sus principios de conflicto de leyes.') }}</p>

                        <h5>{{ __('10.2 Jurisdicción') }}</h5>
                        <p>{{ __('Cualquier disputa, controversia o reclamación relacionada con estos términos será sometida a la jurisdicción exclusiva de los tribunales competentes de Colombia.') }}</p>

                        <h5>{{ __('10.3 Resolución Amistosa') }}</h5>
                        <p>{{ __('Antes de iniciar cualquier procedimiento legal, las partes se comprometen a intentar resolver las disputas de manera amistosa a través de negociación directa durante un período de 30 días.') }}</p>
                    </div>

                    <!-- 11. Divisibilidad y Renuncia -->
                    <h2 class="section-title">{{ __('11. Divisibilidad y Renuncia') }}</h2>
                    <div class="subsection">
                        <h5>{{ __('11.1 Divisibilidad') }}</h5>
                        <p>{{ __('Si cualquier disposición de estos términos es declarada inválida, ilegal o inaplicable por un tribunal competente, las disposiciones restantes permanecerán en pleno vigor y efecto.') }}</p>

                        <h5>{{ __('11.2 Renuncia') }}</h5>
                        <p>{{ __('La falta de ejercicio o el retraso en el ejercicio de cualquier derecho, poder o privilegio bajo estos términos no constituirá una renuncia al mismo.') }}</p>

                        <h5>{{ __('11.3 Acuerdo Completo') }}</h5>
                        <p>{{ __('Estos Términos y Condiciones, junto con nuestra Política de Privacidad, constituyen el acuerdo completo entre las partes con respecto al uso de nuestros servicios.') }}</p>
                    </div>

                    <!-- 12. Contacto -->
                    <div class="contact-info">
                        <h3 class="mb-3">{{ __('12. Información de Contacto') }}</h3>
                        <p class="mb-2">{{ __('Para cualquier consulta, soporte técnico o asuntos relacionados con estos Términos y Condiciones, puede contactarnos a través de:') }}</p>
                        <p class="mb-0">
                            <strong>{{ __('Correo electrónico oficial:') }}</strong>
                            <a href="mailto:info@gijac.co" class="text-white text-decoration-underline">info@gijac.co</a>
                        </p>
                        <p class="mb-0 mt-2">
                            <strong>{{ __('Plataforma:') }}</strong>{{ __('GIJAC MESSAGE BUSINESS') }}<br>
                            <strong>{{ __('Sitio web:') }}</strong> <a href="#" class="text-white text-decoration-underline">{{ __('www.gijac.com') }}</a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
