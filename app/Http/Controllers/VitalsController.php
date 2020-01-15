<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Vitals\CreateVitals;
use App\Http\Requests\Vitals\UpdateVitals;

use App\Vital;
use App\User;
use App\CompanyDetail;

class VitalsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['companyCount', 'activeCompany'])->only(['create', 'store']);
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

        if(auth()->user()->role_id == 3) {
            $vitals = Vital::where('creator_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $vitals = Vital::where('company_id', $company->id)->get();
                }
            } else {
                $vitals = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $vitals = Vital::where('company_id', $company->id)->get();
            }
        } else {
            $vitals = User::with('vitals')->get()->map->vitals->collapse();
        }

        return view('vitals.index', compact('vitals'));
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

        return view('vitals.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateVitals $request)
    {
        $company = $this->activeCompany();

        Vital::create([
            'user_id' => $request->user_id,
            'company_id' => $company->id,
            'temperature' => $request->temperature,
            'pulse_rate' => $request->pulse_rate,
            'systolic_bp' => $request->systolic_bp,
            'diastolic_bp' => $request->diastolic_bp,
            'respiratory_rate' => $request->respiratory_rate,
            'oxygen_saturation' => $request->oxygen_saturation,
            'o2_administered' => $request->o2_administered,
            'pain' => $request->pain,
            'head_circumference' => $request->head_circumference,
            'height' => $request->height,
            'weight' => $request->weight,
            'creator_id' => auth()->user()->id
        ]);

        return redirect('vitals')->with('success', 'Note created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $vital = Vital::findOrfail($id);

        return view('vitals.show', compact('vital'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $vital = Vital::findOrfail($id);

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

        return view('vitals.edit', compact('vital', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVitals $request, $id)
    {
        $company = $this->activeCompany();

        $vital = Vital::findOrfail($id);

        $vital->user_id = $request->user_id;
        $vital->company_id = $company->id;
        $vital->temperature = $request->temperature;
        $vital->pulse_rate = $request->pulse_rate;
        $vital->systolic_bp = $request->systolic_bp;
        $vital->diastolic_bp = $request->diastolic_bp;
        $vital->respiratory_rate = $request->respiratory_rate;
        $vital->oxygen_saturation = $request->oxygen_saturation;
        $vital->o2_administered = $request->o2_administered;
        $vital->pain = $request->pain;
        $vital->head_circumference = $request->head_circumference;
        $vital->height = $request->height;
        $vital->weight = $request->weight;
        $vital->creator_id = auth()->user()->id;

        $vital->save();

        return redirect()->route('vitals.show', $vital->id)->with('success', 'Note updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vital = Vital::findOrfail($id);

        $vital->delete();

        return redirect('vitals');
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
