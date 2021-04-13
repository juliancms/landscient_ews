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
     * Simulate the rain data and store the corresponding advisory levels for
     * each of the rows
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*Gets de id of the database and creates a variable $rainfallevent
        * that stores the rainfall data
        */
        $request->validate([
            'demodb' => 'required|integer',
        ]);
        $id = $request->input('demodb');
        $rainfalldatas = Rainfalldata::where('demodb_id', $id)->get();


        //Loop Rainfall Data      
        $id_raining = 0;
        $key_raining_start = 0;
        $rain_events = array();
        $duration_rain = 0;
        $accum_rain = 0;
        $alpha = $rainfalldatas[0]->raingauges->studysites->alpha;
        $beta = $rainfalldatas[0]->raingauges->studysites->beta;
        $raingauge_id = $rainfalldatas[0]->raingauges->id;
        foreach($rainfalldatas as $current_key=>$data)
        {
            
            $current_id = $data->id;
            //For the first 10 data there will be no rain event
            if($current_key < 10) {
                continue;
            }
            /*
            * If it is not raining evaluates whether the rain is going to
            * start or continues the cycle to the next data
            */
            if($id_raining == 0) {
                /**
                 * If there is no rain event, continue to the next data in
                 * the rainfalldatas loop,
                 * if there is a rain event, save the id of the data that
                 * marks the beginning of the rain in a variable called $id_raining
                 * and in a temporary array of the rain events called $rain_events
                 */
                if($this->rain_start($rainfalldatas, $current_key) == false) {
                    continue;
                } else {
                    $id_raining = $current_id - 10;
                    $rain_events[$id_raining]['id_start'] = $id_raining;
                    $key_raining_start = $current_key - 10;
                    /*
                    * The beginning of the rain is detected 10 data after the rain,
                    * for this reason a loop of the 10 previous data is made to save
                    * the rain calculations for each data sent by the rain gauge
                    * updating the database of the demo data.
                    */
                    for ($x = 0; $x <= 10; $x++) {
                        $key_loop = ($current_key - 10) + $x;
                        $key_slice = $key_loop - $key_raining_start + 1;
                        $accum_rain = $accum_rain + $rainfalldatas[$key_loop]->P1;                   
                        $duration_rain++;                        
                        
                        $array_send_advisory_level = $rainfalldatas->slice($key_raining_start, $key_slice);
                        $array_advisory_level = $this->advisory_level($array_send_advisory_level, $beta, $alpha);

                        $rainfalldatas[$key_loop]->advisorylevel = $array_advisory_level['intensity_ratio'];
                        $rainfalldatas[$key_loop]->advisorylevel_duration = $array_advisory_level['duration'];
                        $rainfalldatas[$key_loop]->rainfallevent_duration = $duration_rain;
                        $rainfalldatas[$key_loop]->save();
                    }
                    continue;
                }
            }
            // Calculates the rainfall data and stores it in the demo database.
            $accum_rain = $accum_rain + $rainfalldatas[$current_key]->P1;
            $duration_rain++;

            $key_slice = $current_key - $key_raining_start + 1;
            $array_send_advisory_level = $rainfalldatas->slice($key_raining_start, $key_slice);
            $array_advisory_level = $this->advisory_level($array_send_advisory_level, $beta, $alpha);

            $data->advisorylevel = $array_advisory_level['intensity_ratio'];
            $data->advisorylevel_duration = $array_advisory_level['duration'];
            
            $data->rainfallevent_duration = $duration_rain;
            $data->save();

            /*
            * After entering the corresponding rain data, determine if the rain
            * has ended. In case it has not finished, the comparison cycle continues
            * for the next data, if the rain has finished, save the final data
            * of the rain in the $rainevents array and continue the cycle to the next data.
            */
            if($this->rain_end($rainfalldatas, $current_key, $id_raining, $accum_rain, $key_raining_start) == false) {              
                continue;
            } else {
                $intensity = $accum_rain / $duration_rain;
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

        /*
        * After comparing each of the data in the demo database, the simulation
        * and the rain events are saved in the database.
        */
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
            }            
        }

        return redirect('/simulations');
    }

    /**
     * Evaluates the last 10 data to determine if there is a rain event
     *
     */
    private function rain_start($rainfalldatas, $current_key)
    {       
        $first_key = $current_key - 10;
        
        // If the first data of the last 10 data is equal to 0, it does not start a rain event
        if($rainfalldatas[$current_key]->P1 == 0) {
            return false;
        }
        // If the last data of the last 10 data is equal to 0, it does not start a rain event
        if($rainfalldatas[$first_key]->P1 == 0) {
            return false;
        }
        //Slice the last 10 data from the $rainfalldatas array
        $key_slice = $current_key - $first_key + 1;
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
    private function rain_end($rainfalldatas, $current_key, $id_raining, $accum, $key_raining_start)
    {
        // Creates a new temporary array with the data from the current rain event
        $key_slice = $current_key - $key_raining_start + 1;
        $new_array = $rainfalldatas->slice($key_raining_start, $key_slice);
        
        // If there is no more than 360 rain data, the rain is not over
        if(count($new_array) <= 360) {
            return false;
        }

        /* If there are more than 360 rain data and the accumulated rainfall
        * corresponds to the algorithm that determines the end of the rain,
        * the rain event ends 
        */
        $current_first_key = $current_key - 360; 
        $current_array = $rainfalldatas->slice($current_first_key, $key_slice);
        $accum_current = $current_array->sum('P1');

        if(($accum_current / 6 >= 4)){           
            return false;
        } else {            
            return true;
        }
        
    }

    /**
     * Evaluates an array to determine the current advisory level in a given position
     *
     */
    private function advisory_level($array, $beta, $alpha)
    {
        /*
        * For the list of data sent in an array of the current position, compare
        * each of the possibilities backwards to forwards and save the intensity
        * ration and its duration in a temporary array
        */

        $advisory_levels = array();
        $last_key = $array->keys()->last();
        $accum_rain = 0;
        $duration_rain = 0;
        $i = 0;
        foreach($array as $row){          
            $accum_rain = $accum_rain + $array[$last_key]->P1;
            $duration_rain++;
            $intensity_critic = (($duration_rain / 60) ** $beta) * $alpha;
            $intensity = $accum_rain / ($duration_rain / 60);
            $intensity_ratio = $intensity / $intensity_critic;
            $advisory_levels[$i]['intensity_ratio'] = $intensity_ratio;
            $advisory_levels[$i]['duration'] = $duration_rain;
            $last_key--;
            $i++;
        }

        /*
        * Reorganize the temporary array of all the intensity ratio possibilities
        * to determine which is the highest and send the highest intensity ratio
        * as advisory level
        */
        array_multisort(
            array_column($advisory_levels, 'intensity_ratio'),  SORT_DESC,
            array_column($advisory_levels, 'duration'), SORT_ASC,
            $advisory_levels
        );

        return $advisory_levels[array_key_first($advisory_levels)];
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
        $duration_initial = $rainfalldatas[0]->raingauges->studysites->duration_initial * 60;
        $duration_final = $rainfalldatas[0]->raingauges->studysites->duration_final * 60;
        return view('simulations/show', [
            'simulation' => $simulation,
            'rainfalldatas' => $rainfalldatas,
            'duration_initial' => $duration_initial,
            'duration_final' => $duration_final,
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
