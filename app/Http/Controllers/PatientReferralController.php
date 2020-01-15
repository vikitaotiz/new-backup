<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Referral;
use App\User;
use App\CompanyDetail;

class PatientReferralController extends Controller
{
    public function create($id)
    {
        $user = User::find($id);

        return view('referrals.patientreferral', compact('user'));
    }

    public function store(Request $request)
    {
        $user_id = $request->user_id;

        $this->validate($request, [
            'name' => 'required',
            'body' => 'required',
            'user_id' => 'required',
        ]);

        $company = $this->activeCompany();

        $referral = new Referral;

        $referral->name = $request->name;
        $referral->body = $request->body;
        $referral->user_id = $request->user_id;
        $referral->company_id = $company->id;
        $referral->creator_id = auth()->user()->id;

        $referral->save();

        return redirect()->route('patients.show', $user_id)->with('success', 'Referral Letter created successfully.');
    }

    public function activeCompany()
    {
        $companies = CompanyDetail::all();

        foreach ($companies as $company) {
            if ($company->status == 1) {
               return $company->first();
            }
        }
    }
}
