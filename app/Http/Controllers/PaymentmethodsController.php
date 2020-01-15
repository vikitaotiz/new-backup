<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Paymentmethods\CreatePaymentmethods;
use App\Http\Requests\Paymentmethods\UpdatePaymentmethods;

use App\Paymentmethod;

class PaymentmethodsController extends Controller
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
            $paymentmethods = Paymentmethod::latest()->where('user_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $paymentmethods = Paymentmethod::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $paymentmethods = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $paymentmethods = Paymentmethod::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $paymentmethods = Paymentmethod::all();
        }

        return view('paymentmethods.index', compact('paymentmethods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('paymentmethods.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePaymentmethods $request)
    {
        Paymentmethod::create([
            'name' => request('name'),
            'details' => request('details'),
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('paymentmethods.index')
                         ->with('success', 'Payment method created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paymentmethod = Paymentmethod::findOrfail($id);

        return view('paymentmethods.show', compact('paymentmethod'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $paymentmethod = Paymentmethod::findOrfail($id);

        return view('paymentmethods.edit', compact('paymentmethod'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentmethods $request, $id)
    {
        $paymentmethod = Paymentmethod::findOrfail($id);

        $paymentmethod = Paymentmethod::update([
            'name' => request('name'),
            'details' => request('details'),
            'user_id' => auth()->user()
        ]);

        return redirect()->route('paymentmethods.show', $paymentmethod->id)
                         ->with('success', 'Payment method updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paymentmethod = Paymentmethod::findOrfail($id);

        $paymentmethod->delete();

        return redirect()->route('paymentmethods.index')
                         ->with('success', 'Payment method deleted successfully.');
    }
}
