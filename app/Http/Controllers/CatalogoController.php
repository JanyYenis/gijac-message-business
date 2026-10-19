<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Jobs\SincronizarCatalogo;
use App\Models\Usuario;
use App\Models\WhatsappAccount;
use App\Services\Meta\CatalogoService;
use App\Services\Meta\CommerceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatalogoController extends Controller
{
    private function catalogo(): CatalogoService
    {
        return new CatalogoService((string) $this->token, $this->catalogoId(), (string) $this->cod_config);
    }

    /**
     * Catálogo de la empresa: el guardado en su conexión o, si no hay, el que Meta ya tiene
     * conectado a su cuenta de WhatsApp. NO se crea solo: lo crea el cliente cuando quiere (ver crear()).
     */
    private function catalogoId(): ?string
    {
        $cuenta = WhatsappAccount::find($this->cod_config);
        if (!$cuenta) {
            return null;
        }
        if ($cuenta->catalogo_id) {
            return $cuenta->catalogo_id;
        }

        $id = CatalogoService::buscarConectado((string) $this->token, (string) $this->waba_id);
        if ($id) {
            $cuenta->forceFill(['catalogo_id' => $id])->save();
        }

        return $id;
    }

    public function index()
    {
        // $this->validarPermiso(Usuario::PERMISO_CATALOGO_LISTADO);
        return view('catalogo.index');
    }

    public function listado(Request $request)
    {
        // $this->validarPermiso(Usuario::PERMISO_CATALOGO_LISTADO);

        $pagina = (int) ($request->input('pagina') ?: 1);
        $cantidad = (int) $request->input('cantidad_pagina', 12);

        // Sin catálogo todavía: se ofrece crearlo (decisión del cliente), no se crea automáticamente.
        if (!$this->catalogoId()) {
            return [
                "estado"     => "success",
                "html"       => view("catalogo.sin-catalogo")->render(),
                "kpis"       => null,
                "categorias" => null,
                "total"      => 0,
            ];
        }

        $servicio = $this->catalogo();

        // Nunca sincronizado o "Actualizar": se espera a Meta. Si solo está viejo, se muestra lo local y se actualiza aparte.
        $vigencia = $servicio->estadoVigencia();
        if ($request->boolean('refrescar') || $vigencia === 'nunca') {
            try {
                $servicio->sincronizar();
            } catch (\ErrorException $e) {
                // Código 100: el id guardado no es un catálogo (o ya no existe). Se olvida y se ofrece crear/conectar uno.
                if ($e->getCode() !== 100) {
                    throw $e;
                }
                WhatsappAccount::where('id', $this->cod_config)->update(['catalogo_id' => null, 'catalogo_sync_at' => null]);

                return ["estado" => "success", "html" => view("catalogo.sin-catalogo")->render(), "kpis" => null, "categorias" => null, "total" => 0];
            }
        } elseif ($vigencia === 'vieja' && Cache::add("catalogo:sync:{$this->cod_config}", 1, 300)) {
            SincronizarCatalogo::dispatch($this->cod_config);
        }

        $resultado = $servicio->consultar([
            'busqueda'       => $request->input('busqueda'),
            'disponibilidad' => $request->input('disponibilidad'),
            'categoria'      => $request->input('categoria'),
            'orden'          => $request->input('orden', 'name_asc'),
        ], $pagina, $cantidad);

        $info['productos'] = $resultado['productos'];
        $info['ultimaPagina'] = $resultado['productos']->lastPage();
        $info['paginaActual'] = $pagina;
        // $info['puede_crear'] = can(Usuario::PERMISO_CATALOGO_CREAR);
        // $info['puede_editar'] = can(Usuario::PERMISO_CATALOGO_EDITAR);
        // $info['puede_eliminar'] = can(Usuario::PERMISO_CATALOGO_ELIMINAR);

        return [
            "estado"     => "success",
            "html"       => view("catalogo.card", $info)->render(),
            "kpis"       => $resultado['kpis'],
            "categorias" => $resultado['categorias'],
            "total"      => $resultado['productos']->total(),
        ];
    }

    /** El cliente decide crear su catálogo: se crea en Meta, se conecta a su WhatsApp y se deja listo. */
    public function crear(Request $request)
    {
        $datos = $request->validate(['nombre' => 'nullable|string|max:100']);

        $cuenta = WhatsappAccount::findOrFail($this->cod_config);
        if (empty($cuenta->business_id)) {
            throw new ErrorException(__("Tu cuenta no tiene un portafolio de negocio de Meta asociado; completa la conexión en Configuración."));
        }

        $nombre = trim($datos['nombre'] ?? '') ?: ('Catálogo ' . ($cuenta->display_name ?: 'GIJAC'));
        $id = CatalogoService::provisionar((string) $this->token, $cuenta->business_id, (string) $this->waba_id, $nombre);

        $cuenta->forceFill(['catalogo_id' => $id, 'catalogo_sync_at' => null])->save();

        // Que el catálogo se vea en el chat de WhatsApp (si falla, el cliente igual puede usar el catálogo)
        try {
            app(CommerceService::class)->setCommerceSettings((string) $this->token, (string) $this->phone_number_id, true, true);
        } catch (\Throwable $e) {
            report($e);
        }

        return ["estado" => "success", "mensaje" => __("Catálogo creado y conectado a tu WhatsApp.")];
    }

    public function obtener(string $id)
    {
        // $this->validarPermiso(Usuario::PERMISO_CATALOGO_EDITAR);

        $producto = $this->catalogo()->obtener($id);
        if (!$producto) {
            throw new ErrorException(__("El producto no existe en el catálogo."));
        }

        return ["estado" => "success", "producto" => $producto];
    }

    public function store(Request $request)
    {
        $id = $request->input('id');
        // $this->validarPermiso($id ? Usuario::PERMISO_CATALOGO_EDITAR : Usuario::PERMISO_CATALOGO_CREAR);

        $datos = $request->validate([
            'nombre'         => 'required|string|max:150',
            'sku'            => ($id ? 'nullable' : 'required') . '|string|max:50',
            'descripcion'    => 'nullable|string|max:5000',
            'categoria'      => 'nullable|string|max:100',
            'marca'          => 'nullable|string|max:100',
            'url'            => 'nullable|url',
            'precio'         => 'required|numeric|min:0',
            'precio_oferta'  => 'nullable|numeric|min:0',
            'moneda'         => 'required|in:USD,EUR,COP,MXN,BRL',
            'disponibilidad' => 'required|in:in stock,out of stock,preorder,not available',
            'cantidad'       => 'nullable|integer|min:0',
            'condicion'      => 'required|in:new,refurbished,used',
            'imagen_url'     => 'required|url',
        ]);

        if ($id && $id != 'null' && $id != 'undefined') {
            $this->catalogo()->actualizar($id, $datos);
            $mensaje = __("Producto actualizado en el catálogo.");
        } else {
            $this->catalogo()->crear($datos);
            $mensaje = __("Producto creado correctamente.");
        }

        return ["estado" => "success", "mensaje" => $mensaje];
    }

    public function eliminar(string $id)
    {
        // $this->validarPermiso(Usuario::PERMISO_CATALOGO_ELIMINAR);

        $this->catalogo()->eliminar($id);

        return ["estado" => "success", "mensaje" => __("Producto eliminado del catálogo.")];
    }

    private function validarPermiso(string $permiso): void
    {
        if (!can($permiso)) {
            throw new ErrorException(__("No tienes permisos para acceder a esta sección."));
        }
    }
}
