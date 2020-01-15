@extends('adminlte::page')

@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
                <div class="col-md-4">
                    <strong>Edit Invoice</strong>
                </div>
                <div class="col-md-4">
                    {{-- <a href="{{route('invoices.create')}}" class="btn btn-sm btn-primary">Create New invoice</a> --}}
                </div>
            </div>
        </div>
    </div>
    @include('inc.tabMenu', ['tabMenuPosition'=>9, 'patient_id'=>$invoice->user_id])
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> {{config('app.name')}}.
                    <small class="pull-right">Date: {{date('D, d, M, Y, H:i:s')}}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">

            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">

            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">

                <hr>

                <form action="{{route('invoices.update', $invoice->id)}}" method="post">

                    {{csrf_field()}} {{method_field('PATCH')}}

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Patient Name</label>
                                <select name="user_id" id="patient_id" class="form-control">
                                    <option value="">No Patient Selected</option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}"
                                                @if($user->id == $invoice->user_id)
                                                selected
                                                @endif>{{$user->firstname}} {{$user->lastname}} -
                                            NHS No.: {{$user->nhs_number}} -
                                        {{-- Date Of Birth: {{$doctor->date_of_birth->format('D jS, M, Y')}} </option> --}}
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Product / Service</label>
                                <select name="product_service" id="product_service" class="form-control">
                                    @foreach ($services as $service)
                                        <option value="{{$service->id}}"
                                                @if($service->id == $invoice->product_service)
                                                selected
                                                @endif>{{$service->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Code / Serial</label>
                                <input type="text" class="form-control" value="{{$invoice->code_serial}}"
                                       name="code_serial" placeholder="Enter Code or Serial...">
                            </div>
                        </div>

                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Invoice To :</label>
                            <textarea name="insurance_name" id="insurance_name" class="form-control"
                                      placeholder="Enter Insurance Name">
                        {{$invoice->insurance_name}}
                    </textarea>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="form-group" style="padding-left:1%; padding-right:1%;">
                            <label>Description</label>
                            <textarea name="description" id="description" class="form-control"
                                      placeholder="Enter Description...">{{$invoice->description}}</textarea>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Quantity / Hours</label>
                                <input type="number" name="quantity" class="form-control" value="{{$invoice->quantity}}"
                                       placeholder="Enter Quantity">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Charges Per Hour / Item</label>
                                <select name="charge_id" class="form-control">
                                    @foreach ($charges as $charge)
                                        <option value="{{$charge->id}}"
                                                @if($charge->id == $invoice->charge_id)
                                                selected
                                                @endif>{{$charge->name}} : {{$charge->amount}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tax</label>
                                @if (App\Tax::all()->count() == 0)
                                    <p>No Tax Available. <a href="{{route('taxes.create')}}">Click To Create Tax</a></p>
                                @else
                                    <select name="tax_id" class="form-control">
                                        @foreach ($taxes as $tax)
                                            <option value="">No Tax Selected</option>
                                            <option value="{{$tax->id}}"
                                                    @if($tax->id == $invoice->tax_id)
                                                    selected
                                                    @endif>
                                                {{$tax->name}} : {{$tax->rate}}</option>
                                        @endforeach
                                    </select>

                                @endif

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Currency</label>
                                <select name="currency_id" class="form-control">
                                    @foreach ($currencies as $currency)
                                        <option value="{{$currency->id}}"
                                                @if($currency->id == $invoice->currency_id)
                                                selected
                                                @endif>{{$currency->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" style="padding-left:1%; padding-right:1%;">
                                <label>Invoice Due Date</label>
                                <input type="text" name="due_date" id="due_date" class="form-control"
                                       value="{{$invoice->due_date}}" placeholder="Enter Invoice Due Date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Staff Name</label>
                                <select name="doctor_id" id="user_id" class="form-control">
                                    @foreach ($doctors as $doctor)
                                        <option value="{{$doctor->id}}"
                                                @if($doctor->id == $invoice->doctor_id)
                                                selected
                                                @endif>{{$doctor->firstname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="form-group" style="padding-left:1%; padding-right:1%;">
                            <input type="submit" value="Update Invoice" class="btn btn-sm btn-success btn-block">
                        </div>
                    </div>
                </form>

                <hr>

            </div>
            <!-- /.col -->
        </div>

    </section>
@endsection