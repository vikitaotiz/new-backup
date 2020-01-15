@extends('adminlte::page')

@section('content')
<div class="panel panel-success">
        <div class="panel-heading">
              <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
                <div class="col-md-4">
                    <strong>Create New Invoice</strong>
                </div>
                <div class="col-md-4">
                    {{-- <a href="{{route('invoices.create')}}" class="btn btn-sm btn-primary">Create New invoice</a> --}}
                </div>
            </div>
        </div>
</div>

<section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              <i class="fa fa-globe"></i> HospitalNote.
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
                          
             <form action="{{route('invoices.store')}}" method="POST"  style="padding-left: 1%; padding-right: 1%;">

              {{csrf_field()}}

              <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          <label>Patient Name. 
                              <a href="#" data-toggle="modal" data-target="#addNewPatient">Create New</a>
                          </label>
                        <select name="user_id" id="user_id" class="form-control">
                              <option value="">No Patient Selected</option>
                            @foreach ($users as $user)
                              <option value="{{$user->id}}">
                                    {{$user->firstname}} {{$user->firstname}} - 
                                    DOB : {{$user->date_of_birth}} - 
                                    NHS : {{$user->nhs_number}}
                              </option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  
                  <div class="col-md-4">
                    <div class="form-group">
                        <label>Product / Service</label>
                        <select name="product_service" id="product_service" class="form-control">
                          @foreach ($services as $service)
                            <option value="{{$service->id}}">{{$service->name}}</option>
                          @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
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
                          <input type="number" name="quantity" class="form-control" value="{{old('quantity')}}" placeholder="Enter Quantity">
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
                      <textarea name="description" id="description" class="form-control" placeholder="Enter Description...">{{old('description')}}</textarea>
                  </div>
              </div><hr>

              <div class="row">
                <div class="col-md-6">
                    <div class="form-group" style="padding-left:1%; padding-right:1%;">
                        <label>Invoice Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control" value="{{old('due_date')}}" placeholder="Enter Invoice Due Date">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Staff Name</label>
                        <select name="doctor_id" id="doctor_id" class="form-control">
                          @foreach ($doctors as $doctor)
                            <option value="{{$doctor->id}}">{{$doctor->firstname}} {{$doctor->lastname}}</option>
                          @endforeach
                        </select>
                    </div>
                  </div>                
              </div>
              <hr>
              <div class="row"  style="padding-left: 1%; padding-right: 1%;">
                <input type="submit" value="Create Invoice" class="btn btn-sm btn-success btn-block">
        </div>

    </form>


             
          </div>
          <!-- /.col -->
        </div>


        
<div class="modal fade" id="addNewPatient" role="dialog">
                <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Patient</h4>
                    </div>
                    <div class="modal-body">

                       <form action="{{route('appointment_user.store')}}" method="post">

                                {{csrf_field()}}

                                <div class="row">
                                       
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                  <label>First Name *</label>
                                                  <input type="text" name="firstname" value="{{old('firstname')}}" class="form-control" placeholder="Enter First Name...">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                             <div class="form-group">
                                                  <label>Last Name *</label>
                                                  <input type="text" name="lastname" value="{{old('lastname')}}" class="form-control" placeholder="Enter Last Name...">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Gender *</label>
                                                <select name="gender" id="gender" class="form-control">
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div><hr>
                
                                    <div class="row">
                
                                        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Date of Birth *</label>
                                                <input type="text" class="form-control" id="date_of_birth" value="{{old('date_of_birth')}}" name="date_of_birth" placeholder="Select Date of Birth">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Phone *</label>
                                                <input type="text" name="phone" class="form-control" value="{{old('phone')}}" placeholder="Phone Number...">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email *</label>
                                                <input type="email" name="email" class="form-control" value="{{old('email')}}" placeholder="Enter Email">
                                            </div>
                                        </div>
                
                                        
                                    </div><hr>
                
                                    <div class="row">
                                            <div class="col-md-12">
                                                <label>Password *</label>
                                                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                                                    <input type="password" name="password" class="form-control"
                                                           placeholder="{{ trans('adminlte::adminlte.password') }}">
                                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                                    @if ($errors->has('password'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
            
                                        </div><hr>
                                        <input type="hidden" name="role_id" value="5">
                                <div class="row" style="padding: 2%;">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="submit" class="btn btn-sm btn-success btn-block" value="Submit Appointment">
                                    </div>
                                </div>
                            </form>

                    </div>
                    
                </div>

                </div>
            </div>


      </section>
@endsection