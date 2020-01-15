@extends('adminlte::page')

@section('css')
    <style>
        .invFixed{
            table-layout: fixed; width: 100%;
        }
    </style>
@endsection
@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
                <div class="col-md-4">
                    {{-- <strong>Edit Invoice</strong> --}}
                </div>
                <div class="col-md-4">
                    <a href="{{route('invoices.edit', $invoice->id)}}" class="btn btn-sm btn-primary btn-block">
                        <i class="fa fa-pencil"></i> Edit Invoice</a>
                </div>
            </div>
        </div>
    </div>
    @include('inc.tabMenu', ['tabMenuPosition'=>9, 'patient_id'=>$invoice->user_id])
    <section class="invoice" id="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    @if($invoice->doctor && count($invoice->doctor->companies))
                        @foreach($invoice->doctor->companies as $item)
                            @if($item->logo)
                                <img src="{{asset('/storage/'.$item->logo)}}" alt="Company Logo" width="50"
                                     height="30">
                            @else
                                <i class="fa fa-building"></i>
                            @endif
                            {{$item->name}}.
                        @endforeach
                    @else
                        @if($invoice->company->logo)
                            <img src="{{asset('/storage/'.$invoice->company->logo)}}" alt="Company Logo" width="50"
                                 height="30">
                        @else
                            <i class="fa fa-building"></i>
                        @endif
                        {{$invoice->company->name}}.
                    @endif
                    <small class="pull-right">Date: {{date('d/m/Y')}}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    @if($invoice->doctor && count($invoice->doctor->companies))
                        @foreach($invoice->doctor->companies as $item)
                            <strong>{{$item->name}}</strong><br>
                            Address: {{$item->address}}<br>
                            Phone: {{$item->phone}}<br>
                            Email: {{$item->email}}
                        @endforeach
                    @else
                        <strong>{{$invoice->company->name}}</strong><br>
                        Address: {{$invoice->company->address}}<br>
                        Phone: {{$invoice->company->phone}}<br>
                        Email: {{$invoice->company->email}}
                    @endif
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong>
                        @if($invoice->user)
                            {{$invoice->user->firstname}}
                            {{$invoice->user->lastname}}
                        @else
                            {{$invoice->insurance_name}}
                        @endif
                    </strong><br>
                    @if($invoice->user)
                        Address: {{$invoice->user->address}}<br>
                        Phone: {{$invoice->user->phone}}<br>
                        Email: {{$invoice->user->email}}
                        <hr> {{$invoice->insurance_name ?? ''}}
                    @else
                        {{$invoice->insurance_name}}
                    @endif

                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>Invoice </b>#00HN - {{$invoice->id}}<br>
                <br>
                <b>Payment Due:</b>
                @if($invoice->due_date)
                    {{\Carbon\Carbon::parse($invoice->due_date)->format('D jS, M,Y')}}
                @else
                    <p>Not Set</p>
                @endif
                <br>

            </div>
            <!-- /.col -->
        </div>
        <br><br>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-bordered invFixed">
                    <thead class="bg-success">
                    <tr>
                        <th>Product / Service</th>
                        <th style="width: 25%;">Description</th>
                        <th>Code / Serial</th>
                        <th>Quantity</th>
                        <th>Amount Per Item ({{$invoice->currency ? $invoice->currency->symbol : '$'}})</th>
                        <th>Total ({{$invoice->currency ? $invoice->currency->symbol : '$'}})</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$invoice->service ? $invoice->service->name : 'no service'}}</td>

                        <td>{!! $invoice->description !!}</td>
                        <td>{{$invoice->code_serial}}</td>
                        <td>{{$invoice->quantity}}</td>
                        <td>
                            {{$invoice->charge ? $invoice->charge->amount : '0'}}
                        </td>
                        <td>
                            {{($invoice->quantity) * ($invoice->charge ? $invoice->charge->amount : 0)}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <br><br>
        <!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-6">
                <p class="lead">Payment Methods:</p>

                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                    @if(count($paymentmethods) == 0)
                        <span>No Payment Method Available.</span><br>
                        <span><a href="{{route('paymentmethods.create')}}">Click To Create.</a></span>
                    @else
                        @foreach ($paymentmethods as $method)
                            <span>{{$method->name}}</span><br>
                        @endforeach
                    @endif
                </p>
            </div>
            <!-- /.col -->
            <div class="col-xs-6">
                <p class="lead">Amount Due</p>

                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>Subtotal ({{App\Currency::find($invoice->currency_id)->symbol}})</th>
                            <td>
                                {{($invoice->quantity) * (App\Charge::find($invoice->charge_id)->amount)}}
                            </td>
                        </tr>
                        <tr>
                            <th>Tax</th>
                            <td>
                                @if($invoice->tax_id)
                                    {{App\Tax::find($invoice->tax_id)->name}} {{App\Tax::find($invoice->tax_id)->rate}}%
                            </td>
                            @else
                                No Tax
                            @endif
                        </tr>
                        <tr>
                            <th>Total ({{App\Currency::find($invoice->currency_id)->symbol}})</th>
                            <td>

                                @if($invoice->tax_id)
                                    {{
                                      (((App\Tax::find($invoice->tax_id)->rate)/100) *
                                      (($invoice->quantity) *
                                      (App\Charge::find($invoice->charge_id)->amount))) +
                                      (($invoice->quantity) * (App\Charge::find($invoice->charge_id)->amount))
                                    }}
                                @else
                                    {{
                                      (($invoice->quantity) *
                                      (App\Charge::find($invoice->charge_id)->amount))
                                     }}
                                @endif


                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>

    <!-- this row will not appear when printing -->
    <div class="row no-print" style="padding: 2%;">
        <div class="col-xs-12">
            <button onclick="printContent('invoice')" type="button" target="_blank" class="btn btn-default"><i
                        class="fa fa-print"></i> Print
            </button>

            @if(App\Paymentmethod::all()->count() > 0)
                <button data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-success pull-right">
                    <i class="fa fa-usd"></i> Make Payment
                </button>
            @endif

        </div>
    </div>


    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Make Payment</h4>
                </div>
                <div class="modal-body">

                    <form action="{{route('payments.store')}}" method="post">
                        {{csrf_field()}}

                        <div class="form-group">
                            <label>Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-control">
                                @foreach ($paymentmethods as $paymentmethod)
                                    <option value="{{$paymentmethod->id}}">{{$paymentmethod->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Invoice to be paid</label>
                            <select name="invoice_id" id="invoice_id" class="form-control">
                                @foreach ($invoices as $invoice)
                                    <option value="{{$invoice->id}}">
                                        Invoice No.: #00HN{{$invoice->id}} -
                                        Invoice Name : {{$invoice->product_service}} -
                                        Amount :
                                        @if($invoice->tax_id)
                                            {{
                                              (((App\Tax::find($invoice->tax_id)->rate)/100) *
                                              (($invoice->quantity) *
                                              (App\Charge::find($invoice->charge_id)->amount))) +
                                              (($invoice->quantity) * (App\Charge::find($invoice->charge_id)->amount))
                                            }}
                                        @else
                                            {{
                                              (($invoice->quantity) *
                                              (App\Charge::find($invoice->charge_id)->amount))
                                            }}
                                        @endif

                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Amount to be paid</label>
                            <input type="number" name="amount" class="form-control" value="{{old('amount')}}"
                                   placeholder="Enter Amount to be Paid...">
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default pull-left btn-sm btn-block"
                                            data-dismiss="modal">Close
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" value="Submit payment"
                                           class="btn btn-sm btn-block btn-success">
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


@endsection
