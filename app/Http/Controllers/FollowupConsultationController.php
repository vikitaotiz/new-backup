<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FollowupConsultation;
use App\User;
use App\CompanyDetail;

class FollowupConsultationController extends Controller
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
            $consultations = auth()->user()->followupconsultations;
        } elseif (auth()->user()->role_id == 3) {
            $consultations = FollowupConsultation::where('creator_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $consultations = FollowupConsultation::where('company_id', $company->id)->get();
                }
            } else {
                $consultations = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $consultations = FollowupConsultation::where('company_id', $company->id)->get();
            }
        } else {
            $consultations = User::with('followupconsultations')->get()->map->followupconsultations->collapse();
        }

        return view('followupnotes.index', compact('consultations'));
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

        return view('followupnotes.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
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


        return redirect('followupnotes')->with('success', 'Note created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $consultation = FollowupConsultation::find($id);

        return view('followupnotes.show', compact('consultation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $consultation = FollowupConsultation::find($id);

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

        return view('followupnotes.edit', compact('consultation', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'patient_progress' => 'required',
            'assessment' => '',
            'plan' => '',
            'user_id' => 'required',
        ]);

        $company = $this->activeCompany();

        $consultation = FollowupConsultation::findOrfail($id);

        $consultation->patient_progress = $request->patient_progress;
        $consultation->assessment = $request->assessment;
        $consultation->plan = $request->plan;

        $consultation->user_id = $request->user_id;
        $consultation->company_id = $company->id;
        $consultation->creator_id = auth()->user()->id;

        $consultation->save();


        return redirect()->route('followupnotes.show', $consultation->id)->with('success', 'Note updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $consultation = FollowupConsultation::find($id);

        $consultation->delete();

        return redirect('followupnotes')->with('success', 'Note deleted successfully.');
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
