<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideotutorialesController extends Controller
{
    public function index(Request $request)
    {
        return view('videotutoriales.index');
    }
}