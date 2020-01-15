@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                     <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>
                                {{$payment->invoice_id}} : 
                                {{App\Invoice::find($payment->invoice_id)->product_service}}
                            </strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('payments.edit', $payment->id)}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-pencil"></i>  Edit payment</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Invoice Amount : </strong> #
                        </li>
                        <li class="list-group-item">
                            <strong>Amount Paid : </strong> {{$payment->amount}} 
                        </li>
                        <li class="list-group-item">
                            <strong>Balance : </strong> #
                        </li>
                    </ul>
                </div>
         </div>
    
@endsection
