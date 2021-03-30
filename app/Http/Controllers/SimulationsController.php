<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rainfalldata;
use App\Models\Raingauge;
use App\Models\Demodb;
use App\Models\Rainfallevent;
use App\Models\Advisorylevel;
use App\Models\Simulation;

class SimulationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $simulations = Simulation::all();

        return view('simulations/index', [
            'simulations' => $simulations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $demodbs = Demodb::all();

        return view('simulations/create', [
            'demodbs' => $demodbs
        ]);
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
            'demodb' => 'required|integer',
        ]);
        $id = $request->input('demodb');
        $rainfalldatas = Rainfalldata::where('demodb_id', $id)->get();
        //Loop Rainfall Data      
        $id_raining = 0;
        $key_raining = 0;
        $rain_events = array();
        $duration_rain = 0;
        $accum_rain = 0;
        $alpha = $rainfalldatas[0]->raingauges->studysites->alpha;
        $beta = $rainfalldatas[0]->raingauges->studysites->beta;
        $raingauge_id = $rainfalldatas[0]->raingauges->id;
        foreach($rainfalldatas as $key=>$data)
        {
            
            $current_id = $data->id;
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
                    $key_raining = $key - 10;
                    for ($x = 0; $x <= 10; $x++) {
                        $key_loop = ($key - 10) + $x;                        
                        $accum_rain = $accum_rain + $rainfalldatas[$key_loop]->P1;
                        $duration_rain++;
                        $intensity_critic = (($duration_rain / 60) ** $beta) * $alpha;
                        $intensity = $accum_rain / $duration_rain;
                        $intensity_ratio = $intensity / $intensity_critic;
                        
                        $rainfalldatas[$key_loop]->intensityratio = $intensity_ratio;
                        $rainfalldatas[$key_loop]->rainfallevent_duration = $duration_rain;
                        $rainfalldatas[$key_loop]->save();

                        //Get Advisory Level
                        $arr_rain = $rainfalldatas->sortBy([
                            ['intensityratio', 'desc'],
                            ['rainfallevent_duration', 'desc'],
                        ])->first();
                        $rainfalldatas[$key_loop]->advisorylevel = $arr_rain->intensityratio;
                        $rainfalldatas[$key_loop]->advisorylevel_duration = $arr_rain->rainfallevent_duration;
                        $rainfalldatas[$key_loop]->save();

                        $rain_events[$id_raining]['advisory_levels'][$key_loop]['rainfallevent_id'] = $id_raining + $x;
                        $rain_events[$id_raining]['advisory_levels'][$key_loop]['intensityratio'] = $intensity_ratio;
                        $rain_events[$id_raining]['advisory_levels'][$key_loop]['duration'] = $duration_rain;
                    }
                    continue;
                }
            }
            // If it is raining evaluates when the rain finish
            $accum_rain = $accum_rain + $rainfalldatas[$key]->P1;
            $duration_rain++;
            $intensity_critic = (($duration_rain / 60) ** $beta) * $alpha;
            $intensity = $accum_rain / $duration_rain;
            $intensity_ratio = $intensity / $intensity_critic;
            
            $data->rainfallevent_duration = $duration_rain;
            $data->intensityratio = $intensity_ratio;
            $data->save();

            //Get Advisory Level
            $arr_rain = $rainfalldatas->sortBy([
                ['intensityratio', 'desc'],
                ['rainfallevent_duration', 'desc'],
            ])->first();

            $data->advisorylevel = $arr_rain->intensityratio;
            $data->advisorylevel_duration = $arr_rain->rainfallevent_duration;
            $data->save();

            $rain_events[$id_raining]['advisory_levels'][$key]['rainfallevent_id'] = $current_id;
            $rain_events[$id_raining]['advisory_levels'][$key]['intensityratio'] = $intensity_ratio;
            $rain_events[$id_raining]['advisory_levels'][$key]['duration'] = $duration_rain;
            if($this->rain_end($rainfalldatas, $key, $id_raining, $accum_rain, $key_raining) == false) {              
                continue;
            } else {
                $rain_events[$id_raining]['id_end'] =  $current_id;
                $rain_events[$id_raining]['accum'] =  $accum_rain;
                $rain_events[$id_raining]['rainduration'] =  $duration_rain;
                $rain_events[$id_raining]['intensity'] =  $intensity;
                $id_raining = 0;
                $accum_rain = 0;
                $duration_rain = 0;
                continue;
            }
        }
        if(count($rain_events) > 0){
            $simulation = Simulation::create([
                'raingauge_id' => $id,
                'demodb_id' => $raingauge_id,
            ]);
            foreach($rain_events as $key=>$row) {
                $rainfallevent = Rainfallevent::create([
                    'simulation_id' => $simulation->id,
                    'rainfalldata_id_start' => $row['id_start'],
                    'rainfalldata_id_end' => $row['id_end'],
                    'accum' => $row['accum'],
                    'rainduration' => $row['rainduration'],
                    'rainintensity' => $row['intensity']
                ]);
                $dataSet = [];
                foreach($row['advisory_levels'] as $row2){
                    $dataSet[] = [
                        'rainfallevent_id' => $rainfallevent->id,
                        'intensityratio' => $row2['intensityratio'],
                        'duration' => $row2['duration'],
                    ];               
                }
                Advisorylevel::insert($dataSet);
            }            
        }

        return redirect('/simulations');
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
        if($rainfalldatas[$current_key]->P1 == 0) {
            return false;
        }
        // If the last data of the last 10 data is equal to 0, it does not start a rain event
        if($rainfalldatas[$first_key]->P1 == 0) {
            return false;
        }
        //Slice the last 10 data from the $rainfalldatas array
        $last10 = $rainfalldatas->slice($first_key, $current_key);
        $accum10 = $last10->sum('P1');
        if($accum10 >= 0.666667) {
            return true;
        }
        return false;
    }

    /**
     * Evaluates the last 10 data to determine if there is a rain event
     *
     */
    private function rain_end($rainfalldatas, $key, $id_raining, $accum, $key_raining)
    {
        $current_key = $key;
        $key_slice = ($current_key - $key_raining) + 1;
        $new_array = $rainfalldatas->slice($key_raining, $key_slice);
        
        if(count($new_array) <= 360) {
            return false;
        }

        $current_first_key = $key - 360; 
        $current_array = $rainfalldatas->slice($current_first_key, $key_slice);
        $accum_current = $current_array->sum('P1');

        if(($accum_current / 6 >= 4)){           
            return false;
        } else {            
            return true;
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $simulation = Simulation::where('id', $id)->first();
        $rainfalldatas = Rainfalldata::where('demodb_id', $simulation->demodb_id)->get();
        return view('simulations/show', [
            'simulation' => $simulation,
            'rainfalldatas' => $rainfalldatas,
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Simulation $simulation)
    {
        $simulation->delete();
        
        return redirect('/simulations');
    }
}
