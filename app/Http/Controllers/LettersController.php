<?php

namespace App\Http\Controllers;

use App\Certificate;
use App\Prescription;
use App\Referral;
use App\Sicknote;
use Illuminate\Http\Request;

class LettersController extends Controller
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
            $prescriptions = Prescription::where('creator_id', auth()->id())->count();
            $referrals = Referral::where('creator_id', auth()->id())->count();
            $sicknotes = Sicknote::where('creator_id', auth()->id())->count();
            $certificates = Certificate::where('creator_id', auth()->id())->count();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $prescriptions = Prescription::where('company_id', $company->id)->count();
                    $referrals = Referral::where('company_id', $company->id)->count();
                    $sicknotes = Sicknote::where('company_id', $company->id)->count();
                    $certificates = Certificate::where('company_id', $company->id)->count();
                }
            } else {
                $prescriptions = 0;
                $referrals = 0;
                $sicknotes = 0;
                $certificates = 0;
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $prescriptions = Prescription::where('company_id', $company->id)->count();
                $referrals = Referral::where('company_id', $company->id)->count();
                $sicknotes = Sicknote::where('company_id', $company->id)->count();
                $certificates = Certificate::where('company_id', $company->id)->count();
            }
        } else {
            $prescriptions = Prescription::all()->count();
            $referrals = Referral::all()->count();
            $sicknotes = Sicknote::all()->count();
            $certificates = Certificate::all()->count();
        }

        return view('letters.index', compact('prescriptions', 'referrals', 'sicknotes', 'certificates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
