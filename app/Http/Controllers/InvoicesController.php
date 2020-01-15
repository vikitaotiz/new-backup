<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Invoices\CreateInvoices;
use App\Http\Requests\Invoices\UpdateInvoices;

use App\Invoice;
use App\User;
use App\Charge;
use App\Tax;
use App\CompanyDetail;
use App\Currency;
use App\Service;
use App\Paymentmethod;

class InvoicesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['chargesCount', 'currenciesCount',
                           'servicesCount', 'companyCount'])
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
            $invoices = auth()->user()->invoices;
        } elseif (auth()->user()->role_id == 3) {
            $invoices = auth()->user()->doctorInvoices;
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $invoices = Invoice::latest()->whereIn('doctor_id', $company->users->pluck('id'))->get();
                }
            } else {
                $invoices = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $invoices = Invoice::latest()->whereIn('doctor_id', $company->users->pluck('id'))->get();
            }
        } else {
            $invoices = User::with('invoices')->get()->map->invoices->collapse();
        }

        return view('invoices.index', compact('invoices'));
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
            $doctors = User::where(['id' => auth()->id()])->get();
            $charges = Charge::latest()->where('user_id', auth()->id())->get();
            $services = Service::where('user_id', auth()->id())
                ->get();
            $currencies = Currency::latest()->where('user_id', auth()->id())->get();
            $taxes = Tax::latest()->where('user_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $doctors = User::where('role_id', '!=', 5)
                        ->where('role_id', '!=', 1)
                        ->where('role_id', '!=', 2)
                        ->where('role_id', '!=', 6)
                        ->whereIn('id', $company->users->pluck('id'))
                        ->get();
                    $users = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
                    $services = Service::whereIn('user_id', $company->users->pluck('id'))
                        ->get();
                    $charges = Charge::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                    $currencies = Currency::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                    $taxes = Tax::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $users = collect();
                $doctors = collect();
                $charges = collect();
                $taxes = collect();
                $services = collect();
                $currencies = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $doctors = User::where('role_id', '!=', 5)
                    ->where('role_id', '!=', 1)
                    ->where('role_id', '!=', 2)
                    ->where('role_id', '!=', 6)
                    ->whereIn('id', $company->users->pluck('id'))
                    ->get();
                $users = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
                $services = Service::whereIn('user_id', $company->users->pluck('id'))
                    ->get();
                $charges = Charge::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                $currencies = Currency::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                $taxes = Tax::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $users = User::latest()->where('role_id', 5)->get();
            $doctors = User::where('role_id', '!=', 5)
                ->where('role_id', '!=', 1)
                ->where('role_id', '!=', 2)
                ->where('role_id', '!=', 6)
                ->get();
            $charges = Charge::all();
            $taxes = Tax::all();
            $services = Service::all();
            $currencies = Currency::all();
        }

        return view('invoices.create',
               compact('users', 'doctors', 'charges',
               'taxes', 'currencies', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateInvoices $request)
    {
        $invoice = new Invoice;

        $company = $this->activeCompany();

        $invoice->product_service = $request->product_service;
        $invoice->description = $request->description;
        // $invoice->code_serial = $request->code_serial;
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

        return redirect('invoices')->with('success', 'Invoice added successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if (auth()->user()->role_id == 3) {
            $invoices = auth()->user()->invoices;
            $paymentmethods = Paymentmethod::latest()->where('user_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $invoices = Invoice::latest()->whereIn('doctor_id', $company->users->pluck('id'))->get();
                    $paymentmethods = Paymentmethod::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $invoices = collect();
                $paymentmethods = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $invoices = Invoice::latest()->whereIn('doctor_id', $company->users->pluck('id'))->get();
                $paymentmethods = Paymentmethod::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $invoices = User::with('invoices')->get()->map->invoices->collapse();
            $paymentmethods = Paymentmethod::all();
        }

        $invoice = Invoice::findOrfail($id);

        return view('invoices.show', compact('invoice', 'invoices', 'paymentmethods'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::findOrfail($id);

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $users = User::where(['role_id' => 5, 'user_id' => auth()->id()])->get();
            $doctors = User::where(['id' => auth()->id()])->get();
            $charges = Charge::latest()->where('user_id', auth()->id())->get();
            $services = Service::where('user_id', auth()->id())
                ->get();
            $currencies = Currency::latest()->where('user_id', auth()->id())->get();
            $taxes = Tax::latest()->where('user_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $doctors = User::where('role_id', '!=', 5)
                        ->where('role_id', '!=', 1)
                        ->where('role_id', '!=', 2)
                        ->where('role_id', '!=', 6)
                        ->whereIn('id', $company->users->pluck('id'))
                        ->get();
                    $users = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
                    $services = Service::whereIn('user_id', $company->users->pluck('id'))
                        ->get();
                    $charges = Charge::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                    $currencies = Currency::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                    $taxes = Tax::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $users = collect();
                $doctors = collect();
                $charges = collect();
                $taxes = collect();
                $services = collect();
                $currencies = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $doctors = User::where('role_id', '!=', 5)
                    ->where('role_id', '!=', 1)
                    ->where('role_id', '!=', 2)
                    ->where('role_id', '!=', 6)
                    ->whereIn('id', $company->users->pluck('id'))
                    ->get();
                $users = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
                $services = Service::whereIn('user_id', $company->users->pluck('id'))
                    ->get();
                $charges = Charge::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                $currencies = Currency::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                $taxes = Tax::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $users = User::latest()->where('role_id', 5)->get();
            $doctors = User::where('role_id', '!=', 5)
                ->where('role_id', '!=', 1)
                ->where('role_id', '!=', 2)
                ->where('role_id', '!=', 6)
                ->get();
            $charges = Charge::all();
            $taxes = Tax::all();
            $services = Service::all();
            $currencies = Currency::all();
        }

        return view('invoices.edit',
        compact('invoice', 'users', 'doctors', 'services', 'charges', 'taxes', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvoices $request, $id)
    {
        $invoice = Invoice::findOrfail($id);

        $company = $this->activeCompany();

        $invoice->product_service = $request->product_service;
        $invoice->description = $request->description;
        // $invoice->code_serial = $request->code_serial;
        $invoice->quantity = $request->quantity;
        $invoice->charge_id = $request->charge_id;
        $invoice->tax_id = $request->tax_id;
        $invoice->currency_id = $request->currency_id;
        $invoice->due_date = $request->due_date;
        $invoice->insurance_name = $request->insurance_name;
        $invoice->company_id = $company->id;

        $invoice->user_id = $request->user_id;
        $invoice->doctor_id = $request->doctor_id;
        $invoice->creator_id = auth()->user()->id;

        $invoice->save();

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrfail($id);

        $invoice->delete();

        return redirect('invoices')->with('success', 'Invoice removed successfully');

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allInvoices()
    {
        try {
            if(auth()->user() != null && isset(auth()->user()->company->company)){
                auth()->user()->company = auth()->user()->company->company;
            }

            $input = \request()->all();
            $input['keyword'] = isset($input['keyword']) ? $input['keyword'] : '';
            $pageNo = isset($input['pageNo']) && $input['pageNo'] > 0 ? $input['pageNo'] : 1;
            $limit = isset($input['perPage']) ? $input['perPage'] : 10;
            $skip = $limit * ($pageNo - 1);
            $sort_by = isset($input['sort_by']) ? $input['sort_by'] : 'id';
            $order_by = isset($input['order_by']) ? $input['order_by'] : 'desc';

            if (auth()->user()->role_id == 6) {
                $input['doctors'] = auth()->user()->company->users->pluck('id');
            } elseif (auth()->user()->role_id == 4) {
                if (count(auth()->user()->companies)) {
                    foreach (auth()->user()->companies as $company) {
                        $input['doctors'] = $company->users->pluck('id');
                    }
                } else {
                    $input['doctors'] = [];
                }
            }

            $total = Invoice::select('invoices.id')
                ->leftjoin('services','services.id','=','invoices.product_service')
                ->leftjoin('users AS A','A.id','=','invoices.user_id')
                ->leftjoin('users AS B','B.id','=','invoices.doctor_id')
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('invoices.doctor_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('invoices.doctor_id', auth()->user()->id);
                            });
                        }
                    }
                    if (auth()->user()->role_id == 5) {
                        $query->where(function ($q) use ($input) {
                            $q->where('invoices.user_id', auth()->user()->id);
                        });
                    }
                    $query->where(function ($q) use ($input) {
                        $q->Where('invoices.id', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('invoices.created_at', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('services.name', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(A.firstname, ' ', A.lastname)"), 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), 'like', '%' . $input['keyword'] . '%');
                    });
                })->count();

            $sql = Invoice::with('charge', 'currency')->select('invoices.*', 'services.name as service_name',
                \DB::raw("CONCAT(A.firstname, ' ', A.lastname) as patient_name"),
                \DB::raw("CONCAT(B.firstname, ' ', B.lastname) as doctor_name"))
                ->leftjoin('services','services.id','=','invoices.product_service')
                ->leftjoin('users AS A','A.id','=','invoices.user_id')
                ->leftjoin('users AS B','B.id','=','invoices.doctor_id')
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('invoices.doctor_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('invoices.doctor_id', auth()->user()->id);
                            });
                        }
                    }
                    if (auth()->user()->role_id == 5) {
                        $query->where(function ($q) use ($input) {
                            $q->where('invoices.user_id', auth()->user()->id);
                        });
                    }
                    $query->where(function ($q) use ($input) {
                        $q->Where('invoices.id', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('invoices.created_at', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('services.name', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(A.firstname, ' ', A.lastname)"), 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), 'like', '%' . $input['keyword'] . '%');
                    });
                });

            if ($sort_by == 'patient') {
                $sql->orderBy(\DB::raw("CONCAT(A.firstname, ' ', A.lastname)"), $order_by);
            } elseif ($sort_by == 'doctor') {
                $sql->orderBy(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), $order_by);
            } elseif ($sort_by == 'service') {
                $sql->orderBy(\DB::raw('services.name'), $order_by);
            } else {
                $sql->orderBy($sort_by, $order_by);
            }

            $invoices = $sql->skip($skip)->take($limit)->get();

            return response()->json(['status' => 200, 'data' => ['invoices' => $invoices, 'counts' => $total]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'errors' => $e->getMessage()], 200);
        }
    }
}
