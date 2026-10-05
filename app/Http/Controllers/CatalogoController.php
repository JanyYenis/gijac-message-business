<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\Usuario;
use App\Services\Meta\CatalogoService;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    private function catalogo(): CatalogoService
    {
        return new CatalogoService($this->token, $this->version);
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

        $resultado = $this->catalogo()->consultar([
            'busqueda'       => $request->input('busqueda'),
            'disponibilidad' => $request->input('disponibilidad'),
            'categoria'      => $request->input('categoria'),
            'orden'          => $request->input('orden', 'name_asc'),
            'refrescar'      => $request->boolean('refrescar'),
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
