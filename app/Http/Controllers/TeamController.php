<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Team;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::all();

        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'role' => 'required',
        ]);

        $team = Team::create([
            'name' => $request->name,
            'role' => $request->role,
            'bio' => $request->bio,
            'user_id' => auth()->user()->id
        ]);

        if($request->hasFile('image')) {

            $this->validate($request, [
                'image' => 'file|image|mimes:jpeg,jpg,png,gif|max:10000'
            ]);

            $image = $request->image->store('team_images', 'public');

            $team->image = $image;

            $team->save();
        }

        toastr()->success('Service Content has been created successfully!');

        return redirect()->route('teams.show', compact('team'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $team = Team::findOrFail($id);

        return view('teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $team = Team::findOrFail($id);

        return view('teams.edit', compact('team'));
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
        $this->validate($request, [
            'name' => 'required',
            'role' => 'required',
        ]);

        $team = Team::whereId($id)->update([
            'name' => $request->name,
            'role' => $request->role,
            'bio' => $request->bio,
            'user_id' => auth()->user()->id
        ]);

        if($request->has('image')) {

            $this->validate($request, [
                'image' => 'file|image|mimes:jpeg,jpg,png,gif|max:10000'
            ]);

            $team = Team::findOrFail($id);

            if($team->image){
                Storage::delete('public/'.$team->image);
            }

            $image = $request->image->store('team_images', 'public');

            $team->image = $image;

            $team->save();
        }

        toastr()->success('Service Content has been edited successfully!');

        return redirect()->route('teams.show', compact('team'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $team = Team::findOrFail($id);

        if($team->image){
            Storage::delete('public/'.$team->image);
        }
        
        $team->delete();

        toastr()->success('Service Content has been deleted successfully!');

        return redirect()->route('teams.index');

    }
}
