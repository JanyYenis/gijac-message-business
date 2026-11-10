<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoliticaController extends Controller
{
    public function politicasPrivacidad()
    {
        return view('sistema.politicas.index');
    }

    public function politicaSoporteTecnico()
    {
        return view('sistema.politicas.soporte');
    }

    public function condiciones()
    {
        return view('sistema.condiciones.index');
    }
}
