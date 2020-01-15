<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Prescriptions\CreatePrescriptions;

use App\Prescription;
use App\User;
use App\CompanyDetail;
use App\Medication;

class PatientPrescriptionController extends Controller
{
    public function create($id)
    {
        $user = User::find($id);

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if (auth()->user()->role_id == 3) {
            $medications = Medication::latest()->where('user_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $medications = Medication::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $medications = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $medications = Medication::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $medications = Medication::all();
        }

        return view('prescriptions.patientprescription', compact('user', 'medications'));
    }

    public function store(CreatePrescriptions $request)
    {
        $user_id = $request->user_id;

        $company = $this->activeCompany();

        $prescription = Prescription::create([
            'drug_allergies' => request('drug_allergies'),
            'comments' => request('comments'),
            'user_id' => request('user_id'),
            'company_id' => $company->id,
            'creator_id' => auth()->user()->id,
        ]);


        if($request->hasFile('signature')) {

            $signature = $request->signature->store('public/prescription_signatures');
            $signature = str_replace("public/", "", $signature);

            $prescription->signature = $signature;

            $prescription->save();
        }

        return redirect()->route('prescriptions.show', $prescription->id)
                         ->with('success', 'Prescription created successfully');
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
