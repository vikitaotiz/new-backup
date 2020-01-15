<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\FollowupConsultation;
use App\User;
use App\CompanyDetail;

class PatientFollowupnoteController extends Controller
{
    public function create($id)
    {
        $user = User::find($id);

        return view('followupnotes.patientfollowupnote', compact('user'));
    }

    public function store(Request $request)
    {
        $user_id = $request->user_id;

        $this->validate($request, [
            'patient_progress' => 'required',
            'assessment' => '',
            'plan' => '',
            'user_id' => 'required',
        ]);

        $company = $this->activeCompany();

        FollowupConsultation::create([
            'patient_progress' => $request->patient_progress,
            'assessment' => $request->assessment,
            'plan' => $request->plan,
            'user_id' => $request->user_id,
            'company_id' => $company->id,
            'creator_id' => auth()->user()->id
        ]);

        return redirect()->route('patients.show', [$user_id, '#notes'])->with('success', 'Note created successfully.');
    }

    public function activeCompany()
    {
        $companies = CompanyDetail::all();

        foreach ($companies as $company) {
            if ($company->status == 1) {
               return $company->where('status', 1)->first();
            }
        }
    }
}
