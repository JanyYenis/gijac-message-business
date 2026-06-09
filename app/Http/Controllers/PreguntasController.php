<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreguntasController extends Controller
{
    public function index(Request $request)
    {
        return view('preguntas-frecuentes.index');
    }
}