<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Consultations\CreateInitialConsultation;

use App\InitialConsultation;
use App\User;
use App\CompanyDetail;

class PatientInitialnoteController extends Controller
{
    public function create($id)
    {
        $user = User::find($id);

        return view('initialnotes.patientinitialnote', compact('user'));
    }

    public function store(CreateInitialConsultation $request)
    {
        $user_id = $request->user_id;

        $company = $this->activeCompany();

        InitialConsultation::create([
            'complain' => $request->complain,
            'history_presenting_complaint' => $request->history_presenting_complaint,
            'past_medical_history' => $request->past_medical_history,
            'drug_history' => $request->drug_history,
            'drug_allergies' => $request->drug_allergies,
            'family_history' => $request->family_history,
            'social_history' => $request->social_history,
            'examination' => $request->examination,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,

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
