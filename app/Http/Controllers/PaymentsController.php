<?php

namespace App\Http\Controllers;

use App\Paymentmethod;
use Illuminate\Http\Request;
use App\Http\Requests\Payments\CreatePayments;
use App\Http\Requests\Payments\UpdatePayments;

use App\Payment;
use App\Invoice;
use App\User;

class PaymentsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['paymentmethodsCount', 'invoicesCount'])
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

        if (auth()->user()->role_id == 3) {
            $payments = Payment::with('invoice', 'user')->where('user_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $payments = Payment::with('invoice', 'user')->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $payments = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $payments = Payment::with('invoice', 'user')->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $payments = Payment::with('invoice', 'user')->get();
        }

        return view('payments.index', compact('payments'));
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

        if(auth()->user()->role_id == 5) {
            $invoices = auth()->user()->invoices;
        } elseif (auth()->user()->role_id == 3) {
            $invoices = auth()->user()->doctorInvoices;
            $paymentmethods = Paymentmethod::latest()->where('user_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $invoices = Invoice::latest()->whereIn('doctor_id', $company->user->pluck('id'))->get();
                    $paymentmethods = Paymentmethod::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $invoices = collect();
                $paymentmethods = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $invoices = Invoice::latest()->whereIn('doctor_id', $company->user->pluck('id'))->get();
                $paymentmethods = Paymentmethod::latest()->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $invoices = User::with('invoices')->get()->map->invoices->collapse();
            $paymentmethods = Paymentmethod::all();
        }

        return view('payments.create', compact('invoices', 'paymentmethods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePayments $request)
    {
        $payment = new Payment;

        $payment->amount = $request->amount;
        $payment->payment_method = $request->payment_method;
        $payment->invoice_id = $request->invoice_id;
        $payment->user_id = auth()->user()->id;

        $payment->save();

        return redirect('payments')->with('success', 'Payment made successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::findOrfail($id);

        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = Payment::findOrfail($id);

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 5) {
            $invoices = auth()->user()->invoices;
        } elseif (auth()->user()->role_id == 3) {
            $invoices = auth()->user()->doctorInvoices;
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

        return view('payments.edit', compact('payment', 'invoices', 'paymentmethods'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePayments $request, $id)
    {
        $payment = Payment::findOrfail($id);

        $payment->amount = $request->amount;
        $payment->payment_method = $request->payment_method;
        $payment->invoice_id = $request->invoice_id;
        $payment->user_id = auth()->user()->id;

        $payment->save();

        return redirect('payments')->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::findOrfail($id);

        $payment->delete();

        return redirect('payments')->with('success', 'Payment deleted successfully.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allPayments()
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

            $total = Payment::select('payments.id')
                ->leftjoin('users AS B','B.id','=','payments.user_id')
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('payments.user_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('payments.user_id', auth()->user()->id);
                            });
                        }
                    }
                    if (auth()->user()->role_id == 5) {
                        $query->where(function ($q) use ($input) {
                            $q->where('payments.user_id', auth()->user()->id);
                        });
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('payments.id', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('payments.created_at', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('payments.invoice_id', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('payments.amount', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), 'like', '%' . $input['keyword'] . '%');
                    });
                })->count();

            $sql = Payment::with('invoice')->select('payments.*',
                \DB::raw("CONCAT(B.firstname, ' ', B.lastname) as doctor_name"))
                ->leftjoin('invoices','invoices.id','=','payments.invoice_id')
                ->leftjoin('users AS B','B.id','=','payments.user_id')
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('payments.user_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('payments.user_id', auth()->user()->id);
                            });
                        }
                    }
                    if (auth()->user()->role_id == 5) {
                        $query->where(function ($q) use ($input) {
                            $q->where('payments.user_id', auth()->user()->id);
                        });
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('payments.id', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('payments.created_at', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('payments.invoice_id', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('payments.amount', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), 'like', '%' . $input['keyword'] . '%');
                    });
                });

            if ($sort_by == 'doctor') {
                $sql->orderBy(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), $order_by);
            } elseif ($sort_by == 'product_service') {
                $sql->orderBy(\DB::raw('invoices.product_service'), $order_by);
            } else {
                $sql->orderBy($sort_by, $order_by);
            }

            $payments = $sql->skip($skip)->take($limit)->get();

            return response()->json(['status' => 200, 'data' => ['payments' => $payments, 'counts' => $total]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'errors' => $e->getMessage()], 200);
        }
    }
}
