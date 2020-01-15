<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Vitals\CreateVitals;

use App\Vital;
use App\User;
use App\CompanyDetail;

class PatientVitalController extends Controller
{
    public function create($id)
    {
        $user = User::find($id);

        return view('vitals.patientvital', compact('user'));
    }

    public function store(CreateVitals $request)
    {
        $user_id = $request->user_id;

        $company = $this->activeCompany();

        Vital::create([
            'user_id' => $request->user_id,
            'company_id' => $company->id,
            'temperature' => $request->temperature,
            'pulse_rate' => $request->pulse_rate,
            'systolic_bp' => $request->systolic_bp,
            'diastolic_bp' => $request->diastolic_bp,
            'BP' => $request->systolic_bp . '-' . $request->diastolic_bp,
            'respiratory_rate' => $request->respiratory_rate,
            'oxygen_saturation' => $request->oxygen_saturation,
            'o2_administered' => $request->o2_administered,
            'pain' => $request->pain,
            'head_circumference' => $request->head_circumference,
            'height' => $request->height,
            'weight' => $request->weight,
            'creator_id' => auth()->user()->id
        ]);

        return redirect()->route('patients.show', [$user_id, '#notes'])->with('success', 'Note created successfully');
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
