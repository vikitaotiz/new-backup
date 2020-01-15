<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;

class CurrencyController extends Controller
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
            $currencies = Currency::latest()->where('user_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $currencies = Currency::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $currencies = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $currencies = Currency::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $currencies = Currency::all();
        }

        return view('currencies.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('currencies.create');
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
            'symbol' => 'required'
        ]);

        $currency = new Currency;

        $currency->name = $request->name;
        $currency->symbol = $request->symbol;
        $currency->user_id = auth()->user()->id;

        $currency->save();

        return redirect('currencies')->with('success', 'Currency created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $currency = Currency::findOrfail($id);

        return view('currencies.show', compact('currency'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $currency = Currency::findOrfail($id);

        return view('currencies.edit', compact('currency'));
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
            'symbol' => 'required'
        ]);

        $currency = Currency::findOrfail($id);

        $currency->name = $request->name;
        $currency->symbol = $request->symbol;
        $currency->user_id = auth()->user()->id;

        $currency->save();

        return redirect('currencies')->with('success', 'Currency updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currency = Currency::findOrfail($id);

        $currency->delete();

        return redirect('currencies')->with('success', 'Currency deleted successfully');
    }
}
