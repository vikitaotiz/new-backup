<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Certificate;
use App\User;
use App\CompanyDetail;

class CertificateController extends Controller
{

    public function __construct()
    {
        $this->middleware(['companyCount', 'activeCompany'])->only(['create', 'store']);
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
            $certificates = auth()->user()->certificates;
        } elseif (auth()->user()->role_id == 3) {
            $certificates = Certificate::where('creator_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $certificates = Certificate::where('company_id', $company->id)->get();
                }
            } else {
                $certificates = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            $certificates = Certificate::where('company_id', auth()->user()->company->id)->get();
        } else {
            $certificates = User::with('certificates')->get()->map->certificates->collapse();
        }

        return view('certificates.index', compact('certificates'));
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

        return view('certificates.create', compact('users'));
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

        $certificate = new Certificate;

        $certificate->name = $request->name;
        $certificate->body = $request->body;
        $certificate->company_id = $company->id;
        $certificate->user_id = $request->user_id;
        $certificate->creator_id = auth()->user()->id;

        $certificate->save();

        return redirect('certificates')->with('success', 'Certificate created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $certificate = Certificate::findOrfail($id);

        return view('certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        $certificate = Certificate::findOrfail($id);

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

        return view('certificates.edit', compact('certificate', 'users', 'companies'));
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

        $certificate = Certificate::findOrfail($id);

        if (auth()->user()->company) {
            $company = auth()->user()->company;
        } else {
            $company = $this->activeCompany();
        }

        $certificate->name = $request->name;
        $certificate->body = $request->body;
        $certificate->company_id = $company->id;
        $certificate->user_id = $request->user_id;
        $certificate->creator_id = auth()->user()->id;

        $certificate->save();

        return redirect()->route('certificates.show', $certificate->id)->with('success', 'Certificate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $certificate = Certificate::findOrfail($id);

        $certificate->delete();

        return redirect('certificates');
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
