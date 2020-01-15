<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Sicknote;
use App\CompanyDetail;

class PatientSicknoteController extends Controller
{
    public function create($id)
    {
        $user = User::find($id);

        return view('sicknotes.patientsicknote', compact('user'));
    }

    public function store(Request $request)
    {
        $user_id = $request->user_id;

        $this->validate($request, [
            'name' => 'required',
            'body' => 'required',
            'user_id' => 'required',
        ]);

        $note = new Sicknote;

        $company = $this->activeCompany();

        $note->name = $request->name;
        $note->body = $request->body;
        $note->user_id = $request->user_id;
        $note->company_id = $company->id;
        $note->creator_id = auth()->user()->id;

        $note->save();

        return redirect()->route('patients.show', $user_id)->with('success', 'Sicknote created successfully.');
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
