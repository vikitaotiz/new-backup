<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Invoices\CreateInvoices;

use App\Invoice;
use App\User;
use App\CompanyDetail;
use App\Service;
use App\Charge;
use App\Currency;
use App\Tax;

class PatientInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['chargesCount', 'currenciesCount',
                           'servicesCount', 'companyCount'])
             ->only(['create', 'store']);
    }

    public function create($id)
    {
        $user = User::find($id);

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $doctors = User::where(['id' => auth()->id()])->get();
            $charges = Charge::latest()->where('user_id', auth()->id())->get();
            $services = Service::where('user_id', auth()->id())
                ->get();
            $currencies = Currency::latest()->where('user_id', auth()->id())->get();
            $taxes = Tax::latest()->where('user_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $doctors = User::where('role_id', 3)->whereIn('id', $company->users->pluck('id'))->get();
                    $services = Service::whereIn('user_id', $company->users->pluck('id'))
                        ->get();
                    $charges = Charge::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                    $currencies = Currency::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                    $taxes = Tax::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $doctors = collect();
                $services = collect();
                $charges = collect();
                $currencies = collect();
                $taxes = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $doctors = User::where('role_id', 3)->whereIn('id', $company->users->pluck('id'))->get();
                $services = Service::whereIn('user_id', $company->users->pluck('id'))
                    ->get();
                $charges = Charge::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                $currencies = Currency::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                $taxes = Tax::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $doctors = User::where('role_id', 3)->get();
            $services = Service::all();
            $charges = Charge::all();
            $currencies = Currency::all();
            $taxes = Tax::all();
        }

        return view('invoices.patientinvoice',
        compact('user', 'services', 'charges', 'currencies', 'doctors', 'taxes'));
    }

    public function store(CreateInvoices $request)
    {
        $user_id = $request->user_id;

        $company = $this->activeCompany();

        $invoice = new Invoice;

        $invoice->product_service = $request->product_service;
        $invoice->description = $request->description;
        $invoice->quantity = $request->quantity;
        $invoice->charge_id = $request->charge_id;
        $invoice->tax_id = $request->tax_id;
        $invoice->currency_id = $request->currency_id;
        $invoice->due_date = $request->due_date;
        $invoice->company_id = $company->id;
        $invoice->insurance_name = $request->insurance_name;

        $invoice->user_id = $request->user_id;
        $invoice->doctor_id = $request->doctor_id;
        $invoice->creator_id = auth()->user()->id;

        $invoice->save();

        return redirect()->route('patients.show', [$user_id, '#invoices'])->with('success', 'Invoice added successfully');
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
