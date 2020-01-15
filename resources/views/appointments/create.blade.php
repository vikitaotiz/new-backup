@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                     <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Create New Appointment</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('clients.edit')}}" class="btn btn-sm btn-primary">Edit Client</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('appointments.store')}}" method="post">

                                {{csrf_field()}}
            
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Service</label>
                                            <select name="service_id" id="service_id" class="form-control">
                                                @foreach ($services as $service)
                                                    <option value="{{$service->id}}">{{$service->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                              <label>Appointment Date</label>
                                              <input type="text" name="appointment_date" id="appointment_date" value="{{old('appointment_date')}}" class="form-control" placeholder="Select Appointment Date">
                                        </div>
                                    </div>
                                
                                </div><hr>

                                <div class="row" style="padding: 2%;">
                                    <label>Choose Appointment Color</label>
                                    <input type="color" name="color" id="color" class="form-control">
                                </div><hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            @if(auth()->user()->role_id == 5)
                                                <label>Patient Name.</label>
                                                <select name="user_id" id="patient_id" class="form-control" disabled>
                                                    <option value="{{auth()->user()->id}}">
                                                            {{auth()->user()->firstname}} {{auth()->user()->lastname}}
                                                            - DOB : {{auth()->user()->date_of_birth}}
                                                            @if(auth()->user()->nhs_number)
                                                            - NHS : {{auth()->user()->nhs_number}}
                                                            @endif
                                                    </option>
                                                </select> 
                                            @else
                                                <label>Patient Name. 
                                                    <a href="#" data-toggle="modal" data-target="#addNewPatient1">Create New</a>
                                                </label>
                                                <select name="user_id" id="patient_id" class="form-control">
                                                    @foreach ($patients as $patient)
                                                        <option value="{{$patient->id}}">{{$patient->firstname}} {{$patient->lastname}} 
                                                        DOB - {{$patient->date_of_birth}}
                                                        @if($patient->nhs_number)
                                                        NHS - {{$patient->nhs_number}}
                                                        @endif
                                                            
                                                        </option> 
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Assign Staff (Available Only)</label>
                                            <select name="doctor_id" id="doctor_id" class="form-control">
                                                @foreach ($users as $user)
                                            <option value="{{$user->id}}">{{$user->firstname}}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div><hr>
                                 <div class="row" style="padding: 2%;">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" id="description" class="form-control" placeholder="Enter Description...">{{old('description')}}</textarea>
                                    </div>
                                </div>
                                <hr>
                               
                                <div class="row" style="padding: 2%;">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-sm btn-success btn-block" value="Submit Appointment">
                                    </div>
                                </div>
                            </form>
                </div>

{{-- Create Patient on the appointment --}}

            <div class="modal fade" id="addNewPatient1" role="dialog">
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
            </div>

            
    
@endsection
