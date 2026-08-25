<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    public function index(Request $request)
    {
        return view('calendario.index');
    }
}
