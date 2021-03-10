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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $raingauge = Raingauge::where('id', $id)->first();
        return view('raingauges.edit')->with('raingauge', $raingauge);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $raingauge = Raingauge::where('id', $id)
        ->update([
            'name' => $request->input('name')
        ]);

        return redirect('/raingauges');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Raingauge $raingauge)
    {
        $raingauge->delete();
        
        return redirect('/raingauges');
    }
}
