<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tax;

class TaxController extends Controller
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

        if(auth()->user()->role_id == 3) {
            $taxes = Tax::latest()->where('user_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $taxes = Tax::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $taxes = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $taxes = Tax::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $taxes = Tax::all();
        }

        return view('taxes.index', compact('taxes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('taxes.create');
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
            'rate' => 'required'
        ]);

        $tax = new Tax;

        $tax->name = $request->name;
        $tax->rate = $request->rate;
        $tax->user_id = auth()->user()->id;

        $tax->save();

        return redirect('taxes')->with('success', 'Tax created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tax = Tax::findOrfail($id);

        return view('taxes.show', compact('tax'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tax = Tax::findOrfail($id);

        return view('taxes.edit', compact('tax'));

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
            'rate' => 'required'
        ]);

        $tax = Tax::findOrfail($id);

        $tax->name = $request->name;
        $tax->rate = $request->rate;
        $tax->user_id = auth()->user()->id;

        $tax->save();

        return redirect('taxes')->with('success', 'Tax updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tax = Tax::findOrfail($id);

        $tax->delete();

        return redirect('taxes')->with('success', 'Tax deleted successfully');

    }
}
