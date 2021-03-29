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
             
        return redirect('/rainfalldatas');
    }

    /**
     * Simulates Rainfall events from a Demo Database.
     *
     * @return \Illuminate\Http\Response
     */
    public function simulate($id)
    {
        $rainfalldatas = Rainfalldata::where('demodb_id', $id)->get()->toArray();
        //Loop Rainfall Data      
        $id_raining = 0;
        $rain_events = array();
        foreach($rainfalldatas as $key=>$data)
        {
            
            $current_id = $data['id'];
            //For the first 10 data there will be no rain event
            if($key < 10) {
                continue;
            }
            //If it is not raining, the last 10 data are evaluated to determine if there is a rain event
            if($id_raining == 0) {
                /**
                 * If there is no rain event, continue to the next data in the rainfalldatas loop,
                 * if there is a rain event, save the id of the data that marks the beginning of the rain, in a temporary array ($rain_events)
                 */
                if($this->rain_start($rainfalldatas, $key) == false) {
                    continue;
                } else {
                    $id_raining = $current_id - 10;
                    $rain_events[$id_raining]['id_start'] = $id_raining;
                    continue;
                }
            }
            // If it is raining evaluates when the rain finish
            if($this->rain_end($rainfalldatas, $key, $id_raining) == false) {
                continue;
            } else {
                $rain_events[$id_raining]['id_end'] =  $current_id;
                $id_raining = 0;
                continue;
            }
        }

        print_r($rain_events);
        exit;

        return view('rainfalldatas/import', [
            'raingauges' => $raingauges
        ]);
    }

    /**
     * Evaluates the last 10 data to determine if there is a rain event
     *
     */
    private function rain_start($rainfalldatas, $key)
    {
        $first_key = $key - 10;
        $current_key = $key;
        // If the first data of the last 10 data is equal to 0, it does not start a rain event
        if($rainfalldatas[$current_key]['P1'] == 0) {
            return false;
        }
        // If the last data of the last 10 data is equal to 0, it does not start a rain event
        if($rainfalldatas[$first_key]['P1'] == 0) {
            return false;
        }
        //Slice the last 10 data from the $rainfalldatas array
        $last10 = array_slice($rainfalldatas, $first_key, $current_key);
        $accum10 = 0;
        foreach ($last10 as $row) {
            $accum10 = $accum10 + $row['P1'];
        }
        if($accum10 >= 0.666667) {
            return true;
        }
        return false;
    }

    /**
     * Evaluates the last 10 data to determine if there is a rain event
     *
     */
    private function rain_end($rainfalldatas, $key, $id_raining)
    {
        $first_key = array_search($id_raining, $rainfalldatas);
        $current_key = $key;
        $key_slice = ($current_key - $first_key) + 1;
        $new_array = array_slice($rainfalldatas, $first_key, $key_slice);

        if(count($new_array) <= 360) {
            return false;
        }

        $current_first_key = $key - 360;       
        $current_array = array_slice($rainfalldatas, $current_first_key, $key_slice);
        $accum = array_sum(array_column($current_array, 'P1'));
        
        if(($accum / 6 >= 4)){           
            return false;
        } else {            
            return true;
        }
        
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
