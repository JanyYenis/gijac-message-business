<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        return view('empresas.index');
    }

    public function store(Request $request)
    {
        $datos = $request->all();
        $datos['cod_usuario'] = auth()->user()->uuid;

        $path = null;
        if (isset($data['imagen'])) {
            $path = $data['imagen']->store('clinicas', 'public');
            $datos['foto'] = url(Storage::url($path));
        }

        $empresa = Empresa::updateOrCreate([
            'nit' => $datos['nit']
        ], $datos);

        if (!$empresa) {
            throw new ErrorException('Ha ocurrido un error al interntar crear el negocio.');
        }

        $empresa->refresh();

        // Verifica el ID
        if (empty($empresa->id)) {
            throw new ErrorException("El empresa no tiene un ID asignado.");
        }

        auth()->user()->update(['cod_empresa' => $empresa->id]);

        return [
            'estado' => 'success',
            'mensaje' => 'Se registro correctamente la empresa.',
        ];
    }
}
