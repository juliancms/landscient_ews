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
        return view('rainfalldatas/index');
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
    public function store()
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

}
