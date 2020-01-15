<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Referral;
use App\User;
use App\CompanyDetail;

class ReferralController extends Controller
{
    public function __construct()
    {
        $this->middleware(['companyCount'])
             ->only(['create', 'store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 5) {
            $referrals = auth()->user()->referrals;
        } elseif(auth()->user()->role_id == 3) {
            $referrals = Referral::where('creator_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $referrals = Referral::whereIn('creator_id', $company->users->pluck('id'))->get();
                }
            } else {
                $referrals = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $referrals = Referral::whereIn('creator_id', $company->users->pluck('id'))->get();
            }
        } else {
            $referrals = User::with('referrals')->get()->map->referrals->collapse();
        }

        return view('referrals.index', compact('referrals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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

        $companies = CompanyDetail::all();

        return view('referrals.create', compact('users', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'body' => 'required',
            'user_id' => 'required',
        ]);

        if (auth()->user()->company) {
            $company = auth()->user()->company;
        } else {
            $company = $this->activeCompany();
        }

        $referral = new Referral;

        $referral->name = $request->name;
        $referral->body = $request->body;
        $referral->user_id = $request->user_id;
        $referral->company_id = $company->id;
        $referral->creator_id = auth()->user()->id;

        $referral->save();

        return redirect('referrals')->with('success', 'Referral Letter created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $referral = Referral::findOrfail($id);

        return view('referrals.show', compact('referral'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $referral = Referral::findOrfail($id);

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

        return view('referrals.edit', compact('referral', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'body' => 'required',
            'user_id' => 'required',
        ]);

        if (auth()->user()->company) {
            $company = auth()->user()->company;
        } else {
            $company = $this->activeCompany();
        }

        $referral = Referral::findOrfail($id);

        $referral->name = $request->name;
        $referral->body = $request->body;
        $referral->user_id = $request->user_id;
        $referral->company_id = $company->id;
        $referral->creator_id = auth()->user()->id;

        $referral->save();

        return redirect()->route('referrals.show', $referral->id)->with('success', 'Referral Letter updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $referral = Referral::findOrfail($id);

        $referral->delete();

        return redirect('referrals');
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
