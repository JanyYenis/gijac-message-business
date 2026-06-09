<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoliticaPrivacidadController extends Controller
{
    public function index()
    {
        return view('sistema.politicas.index');
    }
    
    public function condiciones()
    {
        return view('sistema.condiciones.index');
    }
}
