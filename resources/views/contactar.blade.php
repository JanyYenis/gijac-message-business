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
            border: 1px solid rgba(20, 89, 98, .2) !important;
        }') }</style>
@endsection

@section('content')
    <!-- Contact Section -->
    <section class="section mt-5">
        <div class="container">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-7 mb-5">
                    <div class="card">
                        <div class="card-header" style="background-color: var(--primary-color); color: white;">
                            <h3 class="mb-0">
                                <i class="fas fa-envelope me-2"></i>{{ __('Envíanos un Mensaje') }}</h3>
                        </div>
                        <div class="card-body">
                            <form id="contactForm">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="firstName" class="form-label required">
                                            <i class="fas fa-user me-2"></i>{{ __('Nombre') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ __('Ingrese su nombre') }}" id="firstName" name="nombre" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="lastName" class="form-label required">
                                            <i class="fas fa-user me-2"></i>{{ __('Apellidos') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ __('Ingrese sus apellidos') }}" id="lastName" name="apellido" required>
                                    </div>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label required">
                                            <i class="fas fa-envelope me-2"></i>{{ __('Correo Electrónico') }}</label>
                                        <input type="email" class="form-control" placeholder="{{ __('Ingrese su correo electrónico') }}" id="email" name="email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">
                                            <i class="fas fa-phone me-2"></i>{{ __('Teléfono') }}</label>
                                        <input type="tel" class="form-control" placeholder="{{ __('Ingrese su teléfono') }}" id="phone" name="phone">
                                    </div>
                                </div>

                                <div class="mb-3 mt-3">
                                    <label for="company" class="form-label">
                                        <i class="fas fa-building me-2"></i>{{ __('Empresa') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ __('Ingrese el nombre de su empresa') }}" id="company" name="company">
                                </div>

                                <div class="mb-3">
                                    <label for="subject" class="form-label required">
                                        <i class="fas fa-tag me-2"></i>{{ __('Asunto') }}</label>
                                    <select class="form-select" id="subject" name="asunto" required>
                                        <option value="">{{ __('Selecciona un tema') }}</option>
                                        <option value="{{ __('pricing') }}">{{ __('Consulta sobre Precios') }}</option>
                                        <option value="{{ __('demo') }}">{{ __('Solicitud de Demo') }}</option>
                                        <option value="{{ __('technical') }}">{{ __('Soporte Técnico') }}</option>
                                        <option value="{{ __('integration') }}">{{ __('Integración/API') }}</option>
                                        <option value="{{ __('sales') }}">{{ __('Ventas - Plan Enterprise') }}</option>
                                        <option value="{{ __('billing') }}">{{ __('Facturación') }}</option>
                                        <option value="{{ __('other') }}">{{ __('Otro') }}</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label required">
                                        <i class="fas fa-comment-dots me-2"></i>{{ __('Mensaje') }}</label>
                                    <textarea class="form-control" id="message" name="mensaje" rows="5" required
                                        placeholder="{{ __('Cuéntanos sobre tu proyecto y cómo podemos ayudarte...') }}"></textarea>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="privacy" name="privacidad"
                                            required>
                                        <label class="form-check-label required" for="privacy">{{ __('Acepto las') }}<a href="{{ route('politicas-privacidad') }}" target="_blank">{{ __('Políticas de Privacidad') }}</a>{{ __('y el tratamiento de mis datos') }}</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                                        <label class="form-check-label" for="newsletter">{{ __('Quiero recibir noticias y actualizaciones sobre la plataforma') }}</label>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>{{ __('Enviar Mensaje') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-5">
                    <!-- Contact Cards -->
                    <div class="row g-4 mb-4">
                        <!-- Phone -->
                        <div class="col-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="feature-icon mx-auto mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <h5>{{ __('Teléfono') }}</h5>
                                    <p class="mb-2">{{ __('Llámanos directamente') }}</p>
                                    <a href="tel:+573171789584" class="btn btn-outline-primary">
                                        <i class="fas fa-phone me-2"></i>+57 (317) 178-9584
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="feature-icon mx-auto mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <h5>{{ __('Email') }}</h5>
                                    <p class="mb-2">{{ __('Escríbenos tu consulta') }}</p>
                                    <a href="mailto:soporte@gijac.co" class="btn btn-outline-primary">
                                        <i class="fas fa-envelope me-2"></i>soporte@gijac.co
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- WhatsApp -->
                        <div class="col-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="feature-icon mx-auto mb-3"
                                        style="width: 60px; height: 60px; background-color: #25D366;">
                                        <i class="fab fa-whatsapp"></i>
                                    </div>
                                    <h5>{{ __('WhatsApp') }}</h5>
                                    <p class="mb-2">{{ __('Chat directo con nosotros') }}</p>
                                    <a href="https://wa.me/573171789584" target="_blank" class="btn btn-success">
                                        <i class="fab fa-whatsapp me-2"></i>{{ __('Abrir Chat') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Office Info -->
                    <div class="card" style="height: auto !important;">
                        <div class="card-header" style="background-color: var(--primary-color); color: white;">
                            <h5 class="mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ __('Nuestra Oficina') }}</h5>
                        </div>
                        <div class="card-body">
                            <address class="mb-3">
                                <strong>{{ __('GIJAC MESSAGE BUSINESS') }}</strong><br>
                                {{-- Corregimiento de Navarro, callejón El Recuerdo.<br> --}}
                                Santiago de Cali<br>{{ __('Colombia') }}</address>

                            <h6>{{ __('Horarios de Atención:') }}</h6>
                            <ul class="list-unstyled">
                                <li>
                                    <i class="far fa-clock me-2"></i>
                                    <strong>{{ __('Lunes a Viernes:') }}</strong>{{ __('9:00 AM - 5:00 PM (EST)') }}</li>
                                <li>
                                    <i class="fas fa-times me-2"></i>
                                    <strong>{{ __('Sábados y Domingos:') }}</strong>{{ __('Cerrado') }}</li>
                            </ul>

                            <div class="alert alert-info">
                                <i class="fas fa-headset me-2"></i>
                                <strong>{{ __('Soporte 24/7:') }}</strong>{{ __('Usuarios Enterprise tienen acceso a soporte técnico las 24
                                horas.') }</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="section" style="background-color: #f8f9fa;">
        <div class="container">
            <h2 class="section-title">{{ __('Nuestra Ubicación') }}</h2>

            <!-- Google Maps Embed -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <!-- Placeholder for Google Maps - In real implementation, you would use actual Google Maps embed -->
                            <div
                                style="width: 100%; height: 400px; background: linear-gradient(135deg, #1E6A75 0%, #155a63 100%); display: flex; align-items: center; justify-content: center; color: white; border-radius: 0.375rem;">
                                    <iframe
                                        src="https://www.google.com/maps?q=cali&output=embed"
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"
                                        aria-label="{{ __('Mapa de nuestra ubicación') }}" width="90%" height="90%">
                                    </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ mix('/js/contactarnos/principal.js') }}"></script>
@endsection
