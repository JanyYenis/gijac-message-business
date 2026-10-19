<?php

namespace App\Services\Meta;

use App\Models\CatalogoProducto;
use ErrorException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Catálogo de WhatsApp: el listado se lee de la copia local (tabla catalogo_productos)
 * y solo se consulta a Meta al sincronizar o al escribir (crear / editar / eliminar).
 */
class CatalogoService
{
    const CAMPOS = 'id,retailer_id,name,description,price,sale_price,currency,availability,image_url,url,brand,condition,category,review_status,review_rejection_reasons';
    const MAX_PAGINAS_API = 50;
    /** Minutos tras los cuales, al abrir el catálogo, se actualiza en segundo plano */
    const MINUTOS_VIGENCIA = 15;

    private string $url;

    public function __construct(
        private string $token,
        private ?string $catalogoId,
        private string $codConfig
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));
        $this->url = config('whatsapp.url') . '/' . $version;
    }

    /* ---------- Cliente HTTP ---------- */

    private function cliente(): PendingRequest
    {
        if (!$this->catalogoId || !$this->token) {
            throw new ErrorException(__("Primero crea tu catálogo de WhatsApp."));
        }
        return Http::baseUrl($this->url)->acceptJson()->timeout(20)->withToken($this->token);
    }

    private function verificar(Response $r, string $contexto): Response
    {
        if ($r->successful()) {
            return $r;
        }

        $err = $r->json('error') ?? [];
        Log::error("[Meta Graph API] $contexto", ['http' => $r->status(), 'error' => $err]);

        $mensaje = match ($err['code'] ?? null) {
            190 => __("Token inválido o expirado (código 190)."),
            100 => __("Parámetro o ID de catálogo inválido (código 100).") . ' ' . ($err['message'] ?? ''),
            200 => __("Permisos insuficientes: el token necesita catalog_management."),
            default => $err['message'] ?? __("No se pudo contactar la Graph API."),
        };

        throw new ErrorException("$contexto: $mensaje", (int) ($err['code'] ?? 0));
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

    /** Producto de la Graph API -> columnas de catalogo_productos */
    private function aFila(array $p): array
    {
        $precio = self::normalizarPrecio($p['price'] ?? null);
        $oferta = self::normalizarPrecio($p['sale_price'] ?? null);

        return [
            'producto_id' => (string) $p['id'],
            'retailer_id' => $p['retailer_id'] ?? null,
            'name' => isset($p['name']) ? mb_substr($p['name'], 0, 250) : null,
            'description' => $p['description'] ?? null,
            'precio' => $precio,
            'precio_oferta' => ($oferta && $precio && $oferta < $precio) ? $oferta : null,
            'moneda' => $p['currency'] ?? config('whatsapp.moneda'),
            'availability' => $p['availability'] ?? null,
            'image_url' => $p['image_url'] ?? null,
            'url' => $p['url'] ?? null,
            'brand' => isset($p['brand']) ? mb_substr($p['brand'], 0, 150) : null,
            'condition' => $p['condition'] ?? null,
            'category' => isset($p['category']) ? mb_substr($p['category'], 0, 250) : null,
            'review_status' => $p['review_status'] ?? null,
        ];
    }


    /* ---------- Catálogo de la empresa (lo crea el cliente cuando quiere) ---------- */

    private static function http(string $token): PendingRequest
    {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        return Http::baseUrl(config('whatsapp.url') . '/' . $version)->acceptJson()->timeout(20)->withToken($token);
    }

    /** Catálogo que la cuenta de WhatsApp (WABA) ya tiene conectado en Meta, si lo hay. */
    public static function buscarConectado(string $token, string $wabaId): ?string
    {
        $r = self::http($token)->get("{$wabaId}/product_catalogs");

        return $r->successful() ? ($r->json('data.0.id') ?: null) : null;
    }

    /**
     * Crea un catálogo nuevo en el portafolio de negocio y lo conecta a la cuenta de WhatsApp.
     * Si la cuenta ya tenía uno conectado, lo reutiliza (no crea duplicados).
     *
     * @return string id del catálogo
     */
    public static function provisionar(string $token, string $businessId, string $wabaId, string $nombre): string
    {
        if ($existente = self::buscarConectado($token, $wabaId)) {
            return $existente;
        }

        $crear = self::http($token)->asForm()->post("{$businessId}/owned_product_catalogs", [
            'name' => $nombre,
            'vertical' => 'commerce',
        ]);
        if (!$crear->successful() || !$crear->json('id')) {
            Log::error('[Meta Graph API] Crear catálogo', ['http' => $crear->status(), 'error' => $crear->json('error')]);
            throw new ErrorException(__('No se pudo crear el catálogo') . ': ' . ($crear->json('error.message') ?? __('Meta no respondió.')));
        }
        $catalogoId = (string) $crear->json('id');

        $conectar = self::http($token)->asForm()->post("{$wabaId}/product_catalogs", ['catalog_id' => $catalogoId]);
        if (!$conectar->successful()) {
            Log::error('[Meta Graph API] Conectar catálogo a WABA', ['http' => $conectar->status(), 'error' => $conectar->json('error')]);
            throw new ErrorException(__('El catálogo se creó pero no se pudo conectar a tu cuenta de WhatsApp') . ': ' . ($conectar->json('error.message') ?? ''));
        }

        return $catalogoId;
    }

    /* ---------- Sincronización ---------- */

    private function base()
    {
        return CatalogoProducto::where('cod_config', $this->codConfig);
    }

    private function marcarSincronizado(): void
    {
        DB::table('whatsapp_accounts')->where('id', $this->codConfig)->update(['catalogo_sync_at' => now()]);
    }

    /**
     * Descarga TODOS los productos (paginación por cursor) y deja la copia local igual a Meta.
     * Si Meta falla a mitad no se toca nada, así una falla temporal no vacía el catálogo.
     *
     * @return int cantidad de productos recibidos
     */
    public function sincronizar(): int
    {
        $productos = [];
        $after = null;
        $paginas = 0;

        do {
            $r = $this->verificar(
                $this->cliente()->get("{$this->catalogoId}/products", array_filter([
                    'fields' => self::CAMPOS,
                    'limit' => 100,
                    'after' => $after,
                ])),
                __('Listar productos')
            );

            foreach ($r->json('data', []) as $p) {
                $productos[] = $p;
            }
            $after = $r->json('paging.next') ? $r->json('paging.cursors.after') : null;
        } while ($after && ++$paginas < self::MAX_PAGINAS_API);

        if ($after) {
            throw new ErrorException(__('El catálogo tiene más productos de los que se pueden sincronizar de una vez.'));
        }

        DB::transaction(function () use ($productos) {
            $ids = [];
            foreach ($productos as $p) {
                if (empty($p['id'])) continue;
                $ids[] = (string) $p['id'];
                $fila = $this->aFila($p);
                CatalogoProducto::updateOrCreate(
                    ['cod_config' => $this->codConfig, 'producto_id' => $fila['producto_id']],
                    $fila
                );
            }

            // Los que ya no existen en Meta se quitan de la copia local
            $this->base()->when($ids, fn ($q) => $q->whereNotIn('producto_id', $ids))->delete();
        });

        $this->marcarSincronizado();

        return count($productos);
    }

    /** ¿Hace falta sincronizar ya (nunca se hizo) o conviene hacerlo en segundo plano (datos viejos)? */
    public function estadoVigencia(): string
    {
        $ultima = DB::table('whatsapp_accounts')->where('id', $this->codConfig)->value('catalogo_sync_at');
        if (!$ultima) return 'nunca';

        return \Illuminate\Support\Carbon::parse($ultima)->diffInMinutes(now(), true) >= self::MINUTOS_VIGENCIA ? 'vieja' : 'vigente';
    }

    /* ---------- Lectura (copia local) ---------- */

    public function obtener(string $id): ?array
    {
        return $this->base()->where('producto_id', $id)->first()?->aArreglo();
    }

    /**
     * Filtra, ordena y pagina en la base de datos.
     * @return array{productos: LengthAwarePaginator, kpis: array, categorias: \Illuminate\Support\Collection}
     */
    public function consultar(array $filtros, int $pagina, int $cantidad): array
    {
        $q = $this->base();

        $busqueda = trim($filtros['busqueda'] ?? '');
        if ($busqueda !== '') {
            $like = '%' . addcslashes($busqueda, '%_\\') . '%';
            $q->where(function ($w) use ($like) {
                $w->where('name', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('brand', 'like', $like)
                    ->orWhere('retailer_id', 'like', $like);
            });
        }
        if (!empty($filtros['disponibilidad'])) {
            $q->where('availability', 'like', addcslashes($filtros['disponibilidad'], '%_\\') . '%');
        }
        if (!empty($filtros['categoria'])) {
            $q->where('category', $filtros['categoria']);
        }

        match ($filtros['orden'] ?? 'name_asc') {
            'name_desc' => $q->orderBy('name', 'desc'),
            'price_asc' => $q->orderByRaw('COALESCE(precio, 0) asc')->orderBy('name'),
            'price_desc' => $q->orderByRaw('COALESCE(precio, 0) desc')->orderBy('name'),
            default => $q->orderBy('name', 'asc'),
        };

        $paginador = $q->paginate($cantidad, ['*'], 'page', $pagina);
        $paginador->setCollection($paginador->getCollection()->map(fn ($m) => $m->aArreglo()));

        $k = $this->base()->selectRaw(
            "COUNT(*) as total,
             SUM(CASE WHEN availability LIKE 'in%' THEN 1 ELSE 0 END) as activos,
             SUM(CASE WHEN precio_oferta IS NOT NULL THEN 1 ELSE 0 END) as ofertas,
             AVG(NULLIF(precio, 0)) as promedio"
        )->first();

        return [
            'productos' => $paginador,
            'categorias' => $this->base()->whereNotNull('category')->where('category', '!=', '')
                ->distinct()->orderBy('category')->pluck('category'),
            'kpis' => [
                'total' => (int) $k->total,
                'activos' => (int) $k->activos,
                'ofertas' => (int) $k->ofertas,
                'promedio' => $k->promedio ? round((float) $k->promedio, 2) : 0,
            ],
        ];
    }

    /* ---------- Escritura (Meta primero, luego la copia local) ---------- */

    private function armarPayload(array $d, bool $esNuevo): array
    {
        $payload = [
            'name' => $d['nombre'],
            'description' => $d['descripcion'] ?? '',
            'price' => (int) round($d['precio'] * 100),   // Meta espera centavos
            'currency' => $d['moneda'],
            'availability' => $d['disponibilidad'],
            'condition' => $d['condicion'],
            'image_url' => $d['imagen_url'],
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

    /** Vuelve a leer el producto en Meta y actualiza su fila local. Si falla, el listado se corrige en la próxima sincronización. */
    private function refrescarProducto(string $productoId): void
    {
        try {
            $r = $this->verificar(
                $this->cliente()->get($productoId, ['fields' => self::CAMPOS]),
                __('Leer producto')
            );
            $p = $r->json();
            if (!empty($p['id'])) {
                $fila = $this->aFila($p);
                CatalogoProducto::updateOrCreate(
                    ['cod_config' => $this->codConfig, 'producto_id' => $fila['producto_id']],
                    $fila
                );
            }
        } catch (\Throwable $e) {
            Log::warning('No se pudo refrescar el producto del catálogo', ['producto' => $productoId, 'error' => $e->getMessage()]);
        }
    }

    public function crear(array $datos): array
    {
        $r = $this->verificar(
            $this->cliente()->asForm()->post("{$this->catalogoId}/products", $this->armarPayload($datos, true)),
            __('Crear producto')
        );
        if ($id = $r->json('id')) {
            $this->refrescarProducto((string) $id);
        }
        return $r->json();
    }

    public function actualizar(string $productoId, array $datos): array
    {
        $r = $this->verificar(
            $this->cliente()->asForm()->post($productoId, $this->armarPayload($datos, false)),
            __('Actualizar producto')
        );
        $this->refrescarProducto($productoId);
        return $r->json();
    }

    public function eliminar(string $productoId): void
    {
        $this->verificar($this->cliente()->delete($productoId), __('Eliminar producto'));
        $this->base()->where('producto_id', $productoId)->delete();
    }
}
