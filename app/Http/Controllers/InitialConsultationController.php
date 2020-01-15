<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Consultations\CreateInitialConsultation;
use App\Http\Requests\Consultations\UpdateInitialConsultation;
use App\InitialConsultation;
use App\User;
use App\CompanyDetail;

class InitialConsultationController extends Controller
{
    public function __construct()
    {
        $this->middleware('companyCount')->only(['create', 'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 5) {
            $consultations = auth()->user()->initialconsultations;
        } elseif (auth()->user()->role_id == 3) {
            $consultations = InitialConsultation::where('creator_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $consultations = InitialConsultation::where('company_id', $company->id)->get();
                }
            } else {
                $consultations = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $consultations = InitialConsultation::where('company_id', $company->id)->get();
            }
        } else {
            $consultations = User::with('initialconsultations')->get()->map->initialconsultations->collapse();
        }

        return view('initialnotes.index', compact('consultations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $users = User::where(['role_id' => 5, 'user_id' => auth()->id()])->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $users = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $users = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $users = User::where('role_id', 5)->get();
        }

        return view('initialnotes.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateInitialConsultation $request)
    {
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


        return redirect('initialnotes')->with('success', 'Note created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $consultation = InitialConsultation::find($id);

        return view('initialnotes.show', compact('consultation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $consultation = InitialConsultation::find($id);

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if (auth()->user()->role_id == 4 || auth()->user()->role_id == 3) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $users = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $users = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $users = User::where('role_id', 5)->get();
        }

        return view('initialnotes.edit', compact('consultation', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateInitialConsultation $request, $id)
    {
        $company = $this->activeCompany();

        $initialnote = InitialConsultation::findOrfail($id);

        $initialnote->complain = $request->complain;
        $initialnote->history_presenting_complaint = $request->history_presenting_complaint;
        $initialnote->past_medical_history = $request->past_medical_history;
        $initialnote->drug_history = $request->drug_history;
        $initialnote->drug_allergies = $request->drug_allergies;
        $initialnote->family_history = $request->family_history;
        $initialnote->social_history = $request->social_history;
        $initialnote->examination = $request->examination;
        $initialnote->diagnosis = $request->diagnosis;
        $initialnote->treatment = $request->treatment;

        $initialnote->user_id = $request->user_id;
        $initialnote->company_id = $company->id;
        $initialnote->creator_id = auth()->user()->id;

        $initialnote->save();

        return redirect()->back()->with('success', 'Note updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $consultation = InitialConsultation::find($id);

        $consultation->delete();

        return redirect('initialnotes')->with('success', 'Note deleted successfully.');
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
