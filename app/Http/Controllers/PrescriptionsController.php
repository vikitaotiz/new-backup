<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Prescriptions\CreatePrescriptions;
use App\Http\Requests\Prescriptions\UpdatePrescriptions;

use App\Prescription;
use App\Medication;
use App\Drug;
use App\User;
use App\CompanyDetail;
use Illuminate\Support\Facades\Storage;
use Validator;
use File;

class PrescriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['medicationsCount', 'companyCount'])->only('create', 'store');
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
            $prescriptions = auth()->user()->prescriptions;
        } elseif (auth()->user()->role_id == 3) {
            $prescriptions = Prescription::where('creator_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $prescriptions = Prescription::where('company_id', $company->id)->get();
                }
            } else {
                $prescriptions = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $prescriptions = Prescription::where('company_id', $company->id)->get();
            }
        } else {
            $prescriptions = User::with('prescriptions')->get()->map->prescriptions->collapse();
        }

        return view('prescriptions.index', compact('prescriptions'));
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

        $medications = Medication::all();

        return view('prescriptions.create', compact('users', 'medications'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePrescriptions $request)
    {
        if (auth()->user()->company) {
            $company = auth()->user()->company;
        } else {
            $company = $this->activeCompany();
        }

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $prescription = Prescription::findOrfail($id);

        // $signature = Storage::url($prescription->signature);

        $medications = Medication::all();

        $drugs = $prescription->drugs;

        return view('prescriptions.show', compact('prescription', 'medications', 'drugs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $prescription = Prescription::findOrfail($id);

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

        $medications = Medication::all();

        return view('prescriptions.edit', compact('prescription', 'users', 'medications'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePrescriptions $request, $id)
    {
        $prescription = Prescription::findOrfail($id);

        if($request->hasFile('signature')) {
            $signature = $request->signature->store('public/prescription_signatures');
            $signature = str_replace("public/", "", $signature);

            Storage::delete($prescription->signature);

            $prescription->signature = $signature;
        }

        if (auth()->user()->company) {
            $company = auth()->user()->company;
        } else {
            $company = $this->activeCompany();
        }

        $prescription->drug_allergies = $request->drug_allergies;
        $prescription->comments = $request->comments;

        $prescription->user_id = $request->user_id;
        $prescription->company_id = $company->id;

        $prescription->save();

        return redirect()->route('prescriptions.show', $prescription->id)->with('success', 'Prescription updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $prescription = Prescription::findOrfail($id);

        $prescription->delete();

        return redirect()->back()->with('success', 'Prescription deleted successfully');
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

    public function prescriptionsEdit(Request $request)
    {
        $pres = Prescription::find($request->id);

        if($request->has('file')){
            $validator = Validator::make($request->all(),
            ['file' => 'image',],
            ['file.image' => 'The file must be an image (jpeg, png, bmp, gif, or svg)']);

            if ($validator->fails())
            return array(
                'fail' => true,
                'errors' => $validator->errors()
            );

            $extension = $request->file('file')->getClientOriginalExtension();
            $dir = 'uploads/';
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $request->file('file')->move($dir, $filename);

            if($pres->signature){
                File::delete($pres->signature);
            }
            $pres->signature = $filename;
        }
        if($request->allergy){
            $pres->drug_allergies = $request->allergy;
        }
        if($request->commentdata){
            $pres->comments = $request->commentdata;
        }
        $pres->save();

        return $pres;
    }
}
