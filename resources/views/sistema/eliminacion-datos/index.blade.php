@extends('layouts.principal')

@section('css')
@endsection

@section('content')
    <div class="container main-container" style=" margin-top: 100px; margin-bottom: 50px;">
        <div class="data-deletion-card">
            <div class="page-header mb-5">
                <h1 style="color: var(--primary-color);">
                    <i class="fas fa-shield-alt"></i>
                    Eliminación de Datos
                </h1>
            </div>

            <div class="mb-5">
                <div class="card" style="border-left: 5px solid var(--primary-color);">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Información sobre la Eliminación de Datos
                        </h5>
                        <p class="card-text mb-3">
                            Los usuarios pueden solicitar que se eliminen sus datos personales recopilados por nuestra aplicación conectada con Meta (Facebook, Instagram, WhatsApp). Respetamos tu privacidad y cumplimos con todas las regulaciones de protección de datos.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <div class="card" style="border-left: 5px solid var(--primary-color);">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-clipboard-list text-success me-2"></i>
                            Instrucciones para Solicitar la Eliminación
                        </h5>
                        <div class="row">
                            <div class="col-md-8">
                                <ol class="list-group list-group-numbered">
                                    <li class="list-group-item d-flex align-items-start border-0 bg-transparent">
                                        <div class="ms-2 me-auto">
                                            <strong>Envía un correo electrónico</strong> a la dirección oficial
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-start border-0 bg-transparent">
                                        <div class="ms-2 me-auto">
                                            <strong>Asunto:</strong> "Solicitud de eliminación de datos"
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-start border-0 bg-transparent">
                                        <div class="ms-2 me-auto">
                                            <strong>Incluye tu identificador</strong> de usuario de Meta en el mensaje
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="mt-3">
                                    <a href="mailto:info@gijac.co?subject=Solicitud de eliminación de datos" class="btn btn-primary btn-lg me-3">
                                        <i class="fas fa-envelope"></i>
                                        Enviar Solicitud
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="instructions-card mb-5">
                <h4 class="mb-4">
                    <i class="fas fa-clipboard-list text-success me-2"></i>
                    Instrucciones para Solicitar la Eliminación
                </h4>

                <div class="row">
                    <div class="col-md-8">
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item d-flex align-items-start border-0 bg-transparent">
                                <div class="ms-2 me-auto">
                                    <strong>Envía un correo electrónico</strong> a la dirección oficial
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start border-0 bg-transparent">
                                <div class="ms-2 me-auto">
                                    <strong>Asunto:</strong> "Solicitud de eliminación de datos"
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start border-0 bg-transparent">
                                <div class="ms-2 me-auto">
                                    <strong>Incluye tu identificador</strong> de usuario de Meta en el mensaje
                                </div>
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="mt-3">
                            <a href="mailto:info@gijac.co?subject=Solicitud de eliminación de datos" class="btn btn-primary btn-lg me-3">
                                <i class="fas fa-envelope"></i>
                                Enviar Solicitud
                            </a>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="alert alert-warning mb-5">
                <div class="d-flex align-items-center">
                    <i class="fas fa-clock text-warning me-3" style="font-size: 2rem;"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Tiempo de Procesamiento</h5>
                        <p class="mb-0">
                            <strong>Tu solicitud será procesada en un máximo de 48 horas.</strong>
                            Recibirás una confirmación por correo electrónico una vez que tus datos hayan sido eliminados completamente de nuestros sistemas.
                        </p>
                    </div>
                </div>
            </div>

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
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Tiempo de Respuesta:</strong> Nos comprometemos a responder a todas las consultas relacionadas con la privacidad dentro de 30 días hábiles.
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
