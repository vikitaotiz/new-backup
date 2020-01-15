<?php

namespace App\Http\Controllers;

use App\EmbedUrl;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\CompanyDetails\CreateCompanydetails;
use App\Http\Requests\CompanyDetails\UpdateCompanydetails;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\CompanyDetail;

class CompanyDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateCompanydetails $request)
    {
        $company_logo = $request->logo->store('public/company_logo');
        $company_logo = str_replace("public/", "", $company_logo);

        CompanyDetail::create([
            'name' => request('name'),
            'uuid' => Str::uuid()->toString(),
            'address' => request('address'),
            'email' => request('email'),
            'phone' => request('phone'),
            'industry' => request('industry'),
            'logo' => $company_logo,
            'status' => request('status'),
            'more_info' => request('more_info'),
            'user_id' => auth()->user()->id

        ]);

        return redirect()->route('settings.index')
                         ->with('success', 'Company Details Created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
       $company = CompanyDetail::findOrfail($id);

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if (auth()->user()->role_id == 6) {
            if (auth()->user()->company) {
                $users = User::whereIn('id', auth()->user()->company->users->pluck('id'))
                    ->where('role_id', 3)
                    ->where('availability', 1)
                    ->get();
            } else {
                $users = collect();
            }
        } else {
            $users = User::where('role_id', 3)
                ->get();
        }

        $urls = EmbedUrl::latest()->where('company_detail_id', $company->id)->get();

       return view('settings.show', compact('company', 'users', 'urls'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $companydetail = CompanyDetail::findOrfail($id);

        return view('settings.edit', compact('companydetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCompanydetails $request, $id)
    {
        $companydetail = CompanyDetail::findOrfail($id);

        if($request->hasFile('logo')) {
            $company_logo = $request->logo->store('public/company_logo');
            $company_logo = str_replace("public/", "", $company_logo);
            if($companydetail->logo){
                Storage::delete("public/" . $companydetail->logo);
            }
            $companydetail->logo = $company_logo;
            $companydetail->save();
        }

            $companydetail->name = $request->name;
            $companydetail->address = $request->address;
            $companydetail->email = $request->email;
            $companydetail->phone = $request->phone;
            $companydetail->industry = $request->industry;
            $companydetail->status = $request->status;
            $companydetail->more_info = $request->more_info;
            $companydetail->user_id = auth()->user()->id;


            // foreach ($companies as $company) {
            //     if ($company->status == 1) {

            //         dd($company->status);

            //     } else {
            //         // dd('There is no active company.');
            //
            //     }
            // }

            $companydetail->save();

        return redirect()->route('settings.index')
                         ->with('success', 'Company Details Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $companydetail = CompanyDetail::findOrfail($id);

        $companydetail->delete();

        return redirect()->route('settings.index')
                         ->with('success', 'Company Deleted successfully.');

    }

}
