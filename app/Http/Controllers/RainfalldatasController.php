<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RainfalldatasController extends Controller
{
    public function index()
    {
        return view('rainfalldatas/index');
    }
}
