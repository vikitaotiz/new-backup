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
                    <strong>Create New Invoice for {{$user->firstname}} {{$user->lastname}}</strong>
                </div>
                <div class="col-md-4">
                    {{-- <a href="{{route('invoices.create')}}" class="btn btn-sm btn-primary">Create New invoice</a> --}}
                </div>
            </div>
        </div>
    </div>
    @include('inc.tabMenu', ['tabMenuPosition'=>9, 'patient_id'=>$user->id])
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    @if($user->doctor && count($user->doctor->companies))
                        @foreach($user->doctor->companies as $item)
                            @if($item->logo)
                                <img src="{{asset('/storage/'.$item->logo)}}" alt="Company Logo" width="50" height="30">
                            @else
                                <i class="fa fa-globe"></i>
                            @endif
                            {{$item->name}}.
                        @endforeach
                    @else
                        <i class="fa fa-globe"></i> {{config('app.name')}}.
                    @endif
                    <small class="pull-right">Date: {{date('D, d, M, Y, H:i:s')}}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-12 invoice-col">

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
            {{-- <div class="col-xs-12 table-responsive"> --}}

            <form action="{{route('patient_store_invoice.store')}}" method="POST"
                  style="padding-left: 1%; padding-right: 1%;">

                {{csrf_field()}}

                <input type="hidden" name="user_id" value="{{$user->id}}">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Patient Name</label>
                            <select name="user_id" id="user_id" class="form-control" disabled>
                                <option value="{{$user->id}}">
                                    {{$user->firstname}} {{$user->firstname}} -
                                    DOB : {{$user->date_of_birth}} -
                                    NHS : {{$user->nhs_number}}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Product / Service</label>
                            <select name="product_service" id="product_service" class="form-control">
                                @foreach ($services as $service)
                                    <option value="{{$service->id}}">{{$service->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Code / Serial</label>
                            <input type="text" class="form-control" value="{{old('code_serial')}}" name="code_serial"
                                   placeholder="Enter Code or Serial...">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Invoice To (Insurance : Optional)</label>
                            <input type="text" class="form-control" value="{{old('insurance_name')}}" name="insurance_name" placeholder="Enter Insurance Name...">
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Quantity / Hours</label>
                            <input type="number" name="quantity" class="form-control" value="{{old('quantity')}}"
                                   placeholder="Enter Quantity">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Charges Per Hour / Item</label>
                            <select name="charge_id" class="form-control">
                                @foreach ($charges as $charge)
                                    <option value="{{$charge->id}}">{{$charge->name}} : {{$charge->amount}}</option>
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
                                        <option value="{{$tax->id}}">{{$tax->name}} : {{$tax->rate}}</option>
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
                                    <option value="{{$currency->id}}">{{$currency->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row" style="padding-left: 1%; padding-right: 1%;">
                    <div class="form-group" style="padding-left:1%; padding-right:1%;">
                        <label>Description</label>
                        <textarea name="description" id="description" class="form-control"
                                  placeholder="Enter Description...">{{old('description')}}</textarea>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" style="padding-left:1%; padding-right:1%;">
                            <label>Invoice Due Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control"
                                   value="{{old('due_date')}}" placeholder="Enter Invoice Due Date">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Staff Name</label>
                            <select name="doctor_id" id="doctor_id" class="form-control">
                                @foreach ($doctors as $doctor)
                                    <option value="{{$doctor->id}}">{{$doctor->firstname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row" style="padding-left: 1%; padding-right: 1%;">
                    <input type="submit" value="Create Invoice" class="btn btn-sm btn-success btn-block">
                </div>

            </form>


        </div>
        <!-- /.col -->
        </div>


    </section>
@endsection
