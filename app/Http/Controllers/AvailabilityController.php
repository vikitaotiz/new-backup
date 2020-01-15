<?php

namespace App\Http\Controllers;

use App\Availability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate form data
        $request->validate([
            'from' => 'required|date_format:Y-m-d|after:yesterday',
            'type' => 'required|boolean',
            'user_id' => 'required|integer',
        ]);

        if ($request->type) {
            // Validate form data
            $request->validate([
                'from_time' => 'required|date_format:H:i',
                'to_time' => 'required|date_format:H:i|after:from_time',
            ]);
        } else {
            // Validate form data
            $request->validate([
                'to' => 'required|date_format:Y-m-d|after_or_equal:from',
            ]);
        }

        $availability = new Availability();

        if ($request->type) {
            $availability->from_time = date('H:i', strtotime($request->from_time));
            $availability->to_time = date('H:i', strtotime($request->to_time));
        } else {
            $availability->to = $request->to;
        }

        $availability->from = $request->from;
        $availability->type = $request->type;
        $availability->user_id = $request->user_id;
        $availability->creator_id = auth()->id();
        $availability->save();

        return redirect()->back()->with('success', 'Availability Created Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $availability = Availability::findOrfail($id);

        return response()->json($availability);
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
        // Validate form data
        $request->validate([
            'from' => 'required|date_format:Y-m-d|after:yesterday',
            'type' => 'required|boolean',
            'user_id' => 'required|integer',
        ]);

        if ($request->type) {
            // Validate form data
            $request->validate([
                'from_time' => 'required|date_format:H:i',
                'to_time' => 'required|date_format:H:i|after:from_time',
            ]);
        } else {
            // Validate form data
            $request->validate([
                'to' => 'required|date_format:Y-m-d|after_or_equal:from',
            ]);
        }

        $availability = Availability::find($id);
        $availability->from = $request->from;

        if ($request->type) {
            $availability->from_time = $request->from_time;
            $availability->to_time = $request->to_time;
        } else {
            $availability->to = $request->to;
        }

        $availability->type = $request->type;
        $availability->save();

        return redirect()->back()->with('success', 'Availability Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Availability::destroy($id);

        return redirect()->back()->with('success', 'Availability Deleted Successfully');
    }
}
