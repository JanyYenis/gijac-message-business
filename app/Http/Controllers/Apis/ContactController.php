<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contacts = Contacto::where('estado', Contacto::ACTIVO)
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'data' => $contacts->map(fn($contact) => [
                'id' => $contact->id,
                'nombre' => $contact->nombre,
                'apellido' => $contact->apellido,
                'email' => $contact->email,
                'codigo_telefono' => $contact->codigo_telefono,
                'telefono' => $contact->telefono,
                'category' => $contact->categoria,
                'website' => $contact->website,
                'description' => $contact->descripcion,
                'info' => $contact->info,
            ])
        ]);
    }

    public function show($id)
    {
        $contact = Contacto::where('id', $id)
            ->where('estado', Contacto::ACTIVO)
            ->firstOrFail();

        return response()->json([
            'data' => [
                'id' => $contact->id,
                'nombre' => $contact->nombre,
                'apellido' => $contact->apellido,
                'codigo_telefono' => $contact->codigo_telefono,
                'telefono' => $contact->telefono,
            ]
        ]);
    }
}
