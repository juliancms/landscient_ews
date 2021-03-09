<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Raingauge;

class RaingaugesController extends Controller
{
    public function index()
    {
        $raingauges = Raingauge::all();
        return view('raingauges/index', [
            'raingauges' => $raingauges
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('raingauges.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $raingauge = Raingauge::create([
            'name' => $request->input('name')
        ]);
        return redirect('/raingauges');
    }
}
