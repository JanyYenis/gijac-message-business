<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Pais;
use App\Models\Ciudad;
use Illuminate\Http\Request;

class FormOptionsController extends Controller
{
    public function index()
    {
        $identificationTypesData = Usuario::darTipoDocumento();
        $identificationTypes = [];
        foreach ($identificationTypesData as $item) {
            $identificationTypes[] = [
                'id' => (string) $item->codigo,
                'name' => $item->nombre,
            ];
        }

        $gendersData = Usuario::darTipoGenero();
        foreach ($gendersData as $item) {
            $genders[] = [
                'id' => (string) $item->codigo,
                'name' => $item->nombre,
            ];
        }

        $countries = Pais::where('estado', Pais::ACTIVO)
            ->whereNotNull('nombre')
            ->get()
            ->map(function($country) {
                return [
                    'id' => (string) $country->id,
                    'name' => $country->nombre,
                ];
            });

        $cities = Ciudad::where('estado', Ciudad::ACTIVO)
            ->whereNotNull('nombre')
            ->whereNotNull('id_pais')
            ->get()
            ->map(function($city) {
                return [
                    'id' => (string) $city->id,
                    'name' => $city->nombre,
                    'pais_id' => (string) $city->id_pais, // Campo para filtrar por país
                ];
            });

        return response()->json([
            'data' => [
                'identification_types' => $identificationTypes,
                'genders' => $genders,
                'countries' => $countries,
                'cities' => $cities,
            ]
        ]);
    }
}
