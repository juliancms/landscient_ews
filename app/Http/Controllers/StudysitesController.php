<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Studysite;

class StudysitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studysites = Studysite::all();
        return view('studysites/index', [
            'studysites' => $studysites
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('studysites.create');
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
            'alpha' => 'required|numeric',
            'beta' => 'required|numeric',
            'duration_initial' => 'required|integer',
            'duration_final' => 'required|integer',
        ]);

        $studysite = Studysite::create([
            'name' => $request->input('name'),
            'alpha' => $request->input('alpha'),
            'beta' => $request->input('beta'),
            'duration_initial' => $request->input('duration_initial'),
            'duration_final' => $request->input('duration_final')
        ]);
        return redirect('/studysites');
    }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $studysite = Studysite::where('id', $id)->first();
        return view('studysites.edit')->with('studysite', $studysite);
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
            'alpha' => 'required|numeric',
            'beta' => 'required|numeric',
            'duration_initial' => 'required|integer',
            'duration_final' => 'required|integer',
        ]);

        $studysite = Studysite::where('id', $id)
        ->update([
            'name' => $request->input('name'),
            'alpha' => $request->input('alpha'),
            'beta' => $request->input('beta'),
            'duration_initial' => $request->input('duration_initial'),
            'duration_final' => $request->input('duration_final')
        ]);

        return redirect('/studysites');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Studysite $studysite)
    {
        $studysite->delete();
        
        return redirect('/studysites');
    }
}
