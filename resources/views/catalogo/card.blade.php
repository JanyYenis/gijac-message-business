<div class="row g-4">
    @forelse ($productos as $p)
        <div class="col-12 col-sm-6 col-lg-4 col-xxl-3">
            <article class="p-card" data-id="{{ $p['id'] }}">
                <div class="p-thumb">
                    @if (!empty($p['image_url']))
                        <img src="{{ $p['image_url'] }}" alt="{{ $p['name'] }}" loading="lazy"
                            onerror="this.replaceWith(Object.assign(document.createElement('i'),{className:'bi bi-image ph'}))">
                    @else
                        <i class="bi bi-image ph"></i>
                    @endif
                    <div class="p-badges">
                        <span class="pill {{ $p['agotado'] ? 'pill-out' : 'pill-stock' }}">
                            <i class="bi {{ $p['agotado'] ? 'bi-x-circle-fill' : 'bi-check-circle-fill' }}"></i>
                            {{ $p['agotado'] ? __('Agotado') : __('En stock') }}
                        </span>
                        @if ($p['precio_oferta'])
                            <span class="pill pill-sale">
                                <i class="bi bi-tag-fill"></i>
                                {{ __('Oferta') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-body">
                    <h3 class="p-name">{{ $p['name'] }}</h3>
                    <p class="p-desc">{{ $p['description'] ?? __('Sin descripción') }}</p>
                    <div class="p-price">
                        {{ number_format($p['precio_oferta'] ?? ($p['precio'] ?? 0), 2) }} {{ $p['moneda'] }}
                        @if ($p['precio_oferta'])
                            <s>{{ number_format($p['precio'], 2) }}</s>
                        @endif
                    </div>
                    <div class="p-meta">
                        @if (!empty($p['brand']))
                            <span>
                                <i class="bi bi-award me-1"></i>
                                {{ $p['brand'] }}
                            </span>
                        @endif
                        @if (!empty($p['condition']))
                            <span>
                                <i class="bi bi-box-seam me-1"></i>
                                {{ $p['condition'] }}
                            </span>
                        @endif
                        @if (!empty($p['review_status']))
                            <span>
                                <i class="bi bi-box-seam me-1"></i>
                                {{ $p['review_status'] }}
                            </span>
                        @endif
                        <span>
                            <i class="bi bi-upc-scan me-1"></i>
                            {{ $p['retailer_id'] ?? $p['id'] }}
                        </span>
                    </div>
                    <div class="p-foot">
                        {{-- @if ($puede_editar) --}}
                            <button class="btn btn-soft js-edit">
                                <i class="bi bi-pencil me-1"></i>
                                {{ __('Editar') }}
                            </button>
                        {{-- @endif
                        @if ($puede_eliminar) --}}
                            <button class="btn btn-soft text-danger js-delete">
                                <i class="bi bi-trash3 me-1"></i>
                                {{ __('Eliminar') }}
                            </button>
                        {{-- @endif --}}
                    </div>
                </div>
            </article>
        </div>
    @empty
        <div class="state-box">
            <div class="ic ic-teal">
                <i class="bi bi-inbox"></i>
            </div>
            <h5 class="fw-bold">{{ __('No se encontraron productos') }}</h5>
            <p class="text-muted mb-0">{{ __('Ajusta los filtros o crea un producto nuevo para empezar.') }}</p>
        </div>
    @endforelse
</div>

@if ($ultimaPagina > 1)
    @component('catalogo.paginado')
        @slot('cantidadDatos', $productos)
        @slot('ultimaPagina', $ultimaPagina)
        @slot('paginaActual', $paginaActual)
    @endcomponent
@endif
