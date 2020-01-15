@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Create New payment</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('payments.create')}}" class="btn btn-sm btn-primary">Create New payment</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
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
                                        Amount : {{
                                            (((App\Tax::find($invoice->tax_id)->rate)/100) *
                                            (($invoice->quantity) *
                                            (App\Charge::find($invoice->charge_id)->amount))) +
                                            (($invoice->quantity) * (App\Charge::find($invoice->charge_id)->amount))
                                          }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Amount to be apid</label>
                            <input type="number" name="amount" class="form-control" value="{{old('amount')}}" placeholder="Enter Amount to be Paid...">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Submit payment" class="btn btn-sm btn-block btn-success">
                        </div>
                    </form>
                </div>
            </div>

@endsection
