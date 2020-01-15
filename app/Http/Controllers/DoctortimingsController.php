<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Doctortimings\CreateTiming;
use App\Http\Requests\Doctortimings\UpdateTiming;
use App\Doctortiming;
use Carbon\Carbon;
use App\User;

class DoctortimingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timings = Doctortiming::all();

        return view('doctortimings.index', compact('timings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('role_id', '!=', 5)
                ->where('role_id', '!=', 1)
                ->where('role_id', '!=', 2)
                ->get();

        return view('doctortimings.create', compact('users'));        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTiming $request)
    {
        Doctortiming::create([
            'user_id' => $request->user_id,
            'from' => Carbon::createFromFormat('Y-m-d', $request->from),
            'to' => Carbon::createFromFormat('Y-m-d', $request->to),
            'status' => $request->status,
            'creator_id' => auth()->user()->id
        ]);

        return redirect()->route('doctortimings.index')->with('Doctor Timing Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $timing = Doctortiming::findOrfail($id);

        return view('doctortimings.show', compact('timing'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $timing = Doctortiming::findOrfail($id);

        $users = User::where('role_id', '!=', 5)
                ->where('role_id', '!=', 1)
                ->where('role_id', '!=', 2)
                ->get();

        return view('doctortimings.edit', compact('timing', 'users'));
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
        $timing = Doctortiming::findOrfail($id);

        $timing->user_id = $request->user_id;
        $timing->from = Carbon::createFromFormat('Y-m-d', $request->from);
        $timing->to = Carbon::createFromFormat('Y-m-d', $request->to);
        $timing->status = $request->status;
        $timing->creator_id = auth()->user()->id;
        
        $timing->save();

        return redirect()->route('doctortimings.show', $timing->id)->with('success', 'Doctor Timing Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $timing = Doctortiming::findOrfail($id);

        $timing->delete();

        return redirect()->route('doctortimings.index')->with('Doctor Timing Deleted Successfully.');   
    }

    public function change_timing_status(Request $request, $id)
    {
        $user = User::find($id);

        $user->availability = $request->availability;

        $user->save();

        return redirect()->back()->with('success', 'User Availability Changed Successfully.');   

    }
}
