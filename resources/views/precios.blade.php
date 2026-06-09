@extends('layouts.principal')

@section('content')
    <section class="hero">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h1 class="display-6 fw-bold mb-3">Planes simples y transparentes</h1>
                    <p class="lead mb-0">Escala cuando lo necesites, sin sorpresas.</p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <img src="https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?q=80&w=1600&auto=format&fit=crop"
                        alt="Personas trabajando y planificando" class="img-fluid rounded shadow-sm">
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                @foreach ($planes as $index => $plan)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 card-hover {{ $index == 1 ? 'pricing-popular pricing-card featured' : '' }}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold">{{$plan?->nombre}}</h5>
                                <p class="text-muted">Para equipos que recién comienzan.</p>
                                <h2 class="fw-bold mb-3">${{formatoMiles($plan?->valor ?? 0)}}<span class="fs-6 text-muted">/{{ $plan?->infoTipo?->nombre }}</span></h2>
                                <ul class="list-unstyled mb-4">
                                    <li>• {{ $plan?->max_contactos ? formatoMiles($plan?->max_contactos) : 'Ilimitado' }} Contactos Activos</li>
                                    @foreach ($plan->serviciosHabilitados as $item)
                                        <li>• {{$item?->nombre}}</li>
                                    @endforeach
                                </ul>
                                <a href="{{ route('facturas.pago', ['plan' => $plan->id]) }}" class="btn {{ $index == 1 ? 'btn-primary' : 'btn-outline-primary' }} mt-auto">Obtener</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card mt-5" style="border-left: 5px solid var(--primary-color);">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-info-circle fa-2x me-3" style="color: var(--primary-color);"></i>
                        <h4 class="mb-0">Información Importante</h4>
                    </div>
                    <p class="mb-3">
                        <strong>El precio de los envíos de WhatsApp corre por cuenta del cliente.</strong>
                    </p>
                    <p class="mb-3">
                        Los costos mostrados corresponden únicamente al uso de la plataforma. Los envíos de mensajes se
                        facturan directamente por WhatsApp Business API según sus tarifas oficiales.
                    </p>
                    <p class="mb-3">
                        Para mas informacion lo invitamos a consultar los precios que utiliza
                        <a href="https://developers.facebook.com/docs/whatsapp/pricing/?translation"
                            target="_blank" class="link-primary">WhatsApp Business API</a>.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
