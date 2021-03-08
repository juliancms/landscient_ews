<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RaingaugesController extends Controller
{
    public function index()
    {
        return view('raingauges/index');
    }
}
