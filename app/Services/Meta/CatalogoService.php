<?php

namespace App\Services\Meta;

use ErrorException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CatalogoService
{
    const CAMPOS = 'id,retailer_id,name,description,price,sale_price,currency,availability,image_url,url,brand,condition,category,review_status,review_rejection_reasons';
    const CACHE_SEGUNDOS = 60;
    const MAX_PAGINAS_API = 20;

    private string $url;
    private ?string $catalogoId;
    private ?string $token;

    public function __construct(string $token)
    {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));
        $cfg = config('whatsapp');
        $this->url = $cfg['url'].'/'.$version;
        $this->catalogoId = $cfg['id'];
        $this->token = $token;
    }

    /* ---------- Cliente HTTP ---------- */

    private function cliente(): PendingRequest
    {
        if (!$this->catalogoId || !$this->token) {
            throw new ErrorException(__("El catálogo de WhatsApp no está configurado."));
        }
        return Http::baseUrl($this->url)->acceptJson()->timeout(20)->withToken($this->token);
    }

    private function verificar(Response $r, string $contexto): Response
    {
        if ($r->successful()) {
            return $r;
        }

        $err = $r->json('error') ?? [];
        Log::error("[Meta Graph API] $contexto", [
            'http'  => $r->status(),
            'error' => $err,
        ]);

        $codigo = $err['code'] ?? null;
        $mensaje = match ($codigo) {
            190 => __("Token inválido o expirado (código 190)."),
            100 => __("Parámetro o ID de catálogo inválido (código 100).") . ' ' . ($err['message'] ?? ''),
            200 => __("Permisos insuficientes: el token necesita catalog_management."),
            default => $err['message'] ?? __("No se pudo contactar la Graph API."),
        };

        throw new ErrorException("$contexto: $mensaje");
    }

    private function limpiarCache(): void
    {
        Cache::forget("catalogo_wa_{$this->catalogoId}");
    }

    /* ---------- Normalización ---------- */

    public static function normalizarPrecio($raw): ?float
    {
        if ($raw === null || $raw === '') return null;
        if (is_numeric($raw)) return (float) $raw;

        $limpio = preg_replace('/[^\d.,-]/', '', (string) $raw);
        $limpio = preg_replace('/\.(?=\d{3}\b)/', '', $limpio);
        $limpio = str_replace(',', '.', $limpio);

        return is_numeric($limpio) ? (float) $limpio : null;
    }

    private function normalizarProducto(array $p): array
    {
        $precio = self::normalizarPrecio($p['price'] ?? null);
        $oferta = self::normalizarPrecio($p['sale_price'] ?? null);

        $p['precio'] = $precio;
        $p['precio_oferta'] = ($oferta && $precio && $oferta < $precio) ? $oferta : null;
        $p['agotado'] = str_starts_with($p['availability'] ?? '', 'out');
        $p['moneda'] = $p['currency'] ?? config('whatsapp.moneda');

        return $p;
    }

    /* ---------- Lectura ---------- */

    /** Trae TODOS los productos siguiendo la paginación por cursor de Meta. */
    public function listarTodos(bool $forzar = false): Collection
    {
        $key = "catalogo_wa_{$this->catalogoId}";
        if ($forzar) Cache::forget($key);

        return Cache::remember($key, self::CACHE_SEGUNDOS, function () {
            $productos = collect();
            $after = null;
            $paginas = 0;

            do {
                $r = $this->verificar(
                    $this->cliente()->get("{$this->catalogoId}/products", array_filter([
                        'fields' => self::CAMPOS,
                        'limit'  => 100,
                        'after'  => $after,
                    ])),
                    __('Listar productos')
                );

                $productos = $productos->merge($r->json('data', []));
                $after = $r->json('paging.next') ? $r->json('paging.cursors.after') : null;
            } while ($after && ++$paginas < self::MAX_PAGINAS_API);

            return $productos->map(fn($p) => $this->normalizarProducto($p))->values();
        });
    }

    public function obtener(string $id): ?array
    {
        return $this->listarTodos()->firstWhere('id', $id);
    }

    /**
     * Filtra, ordena y pagina en servidor.
     * @return array{productos: LengthAwarePaginator, kpis: array, categorias: Collection}
     */
    public function consultar(array $filtros, int $pagina, int $cantidad): array
    {
        $todos = $this->listarTodos(!empty($filtros['refrescar']));

        $busqueda = mb_strtolower(trim($filtros['busqueda'] ?? ''));
        $filtrados = $todos->filter(function ($p) use ($filtros, $busqueda) {
            if ($busqueda !== '') {
                $texto = mb_strtolower(($p['name'] ?? '') . ' ' . ($p['description'] ?? '') . ' ' . ($p['brand'] ?? '') . ' ' . ($p['retailer_id'] ?? ''));
                if (!str_contains($texto, $busqueda)) return false;
            }
            if (!empty($filtros['disponibilidad']) && !str_starts_with($p['availability'] ?? '', $filtros['disponibilidad'])) {
                return false;
            }
            if (!empty($filtros['categoria']) && ($p['category'] ?? '') !== $filtros['categoria']) {
                return false;
            }
            return true;
        });

        $filtrados = match ($filtros['orden'] ?? 'name_asc') {
            'name_desc'  => $filtrados->sortByDesc(fn($p) => mb_strtolower($p['name'] ?? '')),
            'price_asc'  => $filtrados->sortBy(fn($p) => $p['precio'] ?? 0),
            'price_desc' => $filtrados->sortByDesc(fn($p) => $p['precio'] ?? 0),
            default      => $filtrados->sortBy(fn($p) => mb_strtolower($p['name'] ?? '')),
        };

        $paginador = new LengthAwarePaginator(
            $filtrados->forPage($pagina, $cantidad)->values(),
            $filtrados->count(),
            $cantidad,
            $pagina
        );

        $precios = $todos->pluck('precio')->filter();

        return [
            'productos'  => $paginador,
            'categorias' => $todos->pluck('category')->filter()->unique()->sort()->values(),
            'kpis'       => [
                'total'     => $todos->count(),
                'activos'   => $todos->filter(fn($p) => str_starts_with($p['availability'] ?? '', 'in'))->count(),
                'ofertas'   => $todos->filter(fn($p) => $p['precio_oferta'])->count(),
                'promedio'  => $precios->count() ? round($precios->avg(), 2) : 0,
            ],
        ];
    }

    /* ---------- Escritura ---------- */
    private function armarPayload(array $d, bool $esNuevo): array
    {
        $payload = [
            'name'         => $d['nombre'],
            'description'  => $d['descripcion'] ?? '',
            'price'        => (int) round($d['precio'] * 100),   // Meta espera centavos
            'currency'     => $d['moneda'],
            'availability' => $d['disponibilidad'],
            'condition'    => $d['condicion'],
            'image_url'    => $d['imagen_url'],
        ];

        if (!empty($d['precio_oferta']) && $d['precio_oferta'] > 0) {
            $payload['sale_price'] = (int) round($d['precio_oferta'] * 100);
        }
        foreach (['marca' => 'brand', 'url' => 'url', 'categoria' => 'category'] as $campo => $meta) {
            if (!empty($d[$campo])) $payload[$meta] = $d[$campo];
        }
        if (isset($d['cantidad']) && $d['cantidad'] !== '') {
            $payload['inventory'] = (int) $d['cantidad'];
        }
        if ($esNuevo) {
            $payload['retailer_id'] = $d['sku'];
        }

        return $payload;
    }

    public function crear(array $datos): array
    {
        $r = $this->verificar(
            $this->cliente()->asForm()->post("{$this->catalogoId}/products", $this->armarPayload($datos, true)),
            __('Crear producto')
        );
        $this->limpiarCache();
        return $r->json();
    }

    public function actualizar(string $productoId, array $datos): array
    {
        $r = $this->verificar(
            $this->cliente()->asForm()->post($productoId, $this->armarPayload($datos, false)),
            __('Actualizar producto')
        );
        $this->limpiarCache();
        return $r->json();
    }

    public function eliminar(string $productoId): void
    {
        $this->verificar($this->cliente()->delete($productoId), __('Eliminar producto'));
        $this->limpiarCache();
    }
}
