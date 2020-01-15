<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\CompanyDetail;
use App\Certificate;

class PatientCertificateController extends Controller
{
    public function create($id)
    {
        $user = User::find($id);

        return view('certificates.patientcertificate', compact('user'));
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

        $certificate = new Certificate;

        $certificate->name = $request->name;
        $certificate->body = $request->body;
        $certificate->company_id = $company->id;
        $certificate->user_id = $request->user_id;
        $certificate->creator_id = auth()->user()->id;

        $certificate->save();

        return redirect()->route('patients.show', $user_id)->with('success', 'Certificate created successfully.');
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
