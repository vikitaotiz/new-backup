<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charge;

class ChargesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if (auth()->user()->role_id == 3) {
            $charges = Charge::latest()->where('user_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $charges = Charge::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $charges = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $charges = Charge::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        }  else {
            $charges = Charge::all();
        }

        return view('charges.index', compact('charges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('charges.create');
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
            'amount' => 'required'
        ]);

        $charge = new Charge;

        $charge->name = $request->name;
        $charge->amount = $request->amount;
        $charge->description = $request->description;
        $charge->user_id = auth()->user()->id;

        $charge->save();

        return redirect('charges')->with('success', 'Charge created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $charge = Charge::findOrfail($id);

        return view('charges.show', compact('charge'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $charge = Charge::findOrfail($id);

        return view('charges.edit', compact('charge'));
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
            'amount' => 'required'
        ]);

        $charge = Charge::findOrfail($id);

        $charge->name = $request->name;
        $charge->amount = $request->amount;
        $charge->description = $request->description;
        $charge->user_id = auth()->user()->id;

        $charge->save();

        return redirect('charges')->with('success', 'Charge updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $charge = Charge::findOrfail($id);

        $charge->delete();

        return redirect('charges')->with('success', 'Charge deleted successfully.');
    }
}
