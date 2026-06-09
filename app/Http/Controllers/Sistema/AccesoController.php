<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccesoController extends Controller
{
    public function index()
    {
        return view('sistema.politicas.index');
    }

    public function store()
    {
        
    }
}
