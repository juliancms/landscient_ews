<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\RainfalldataImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Rainfalldata;
use App\Models\Raingauge;
use App\Models\Demodb;

class RainfalldatasController extends Controller
{
    public function index()
    {
        $demodbs = Demodb::all();
        return view('rainfalldatas/index', [
            'demodbs' => $demodbs
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $demodb = Demodb::where('id', $id)->first();
        return view('rainfalldatas.edit')->with('demodb', $demodb);
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

        $demodb = Demodb::where('id', $id)
        ->update([
            'name' => $request->input('name')
        ]);

        return redirect('/rainfalldatas');
    }


     /**
     * Show the form for importing Rainfall data.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        $raingauges = Raingauge::all();

        return view('rainfalldatas/import', [
            'raingauges' => $raingauges
        ]);
    }

    /**
     * Store data imported.
     *
     * @return \Illuminate\Support\Collection
     */
    public function saveimport()
    {
        request()->validate([
            'name' => 'required|max:255',
        ]);

        $demodb = Demodb::create([
            'name' => request()->input('name')
        ]);
        Excel::import(new RainfalldataImport(request()->input('raingauge_id'), $demodb->id),request()->file('file'));
             
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $demodb = Demodb::find($id);
        $demodb->delete();
        
        return redirect('/rainfalldatas');
    }

}
