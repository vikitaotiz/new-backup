@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                     <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Create Appointment for {{$patient->firstname}} {{$patient->lastname}} </strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('clients.edit')}}" class="btn btn-sm btn-primary">Edit Client</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('patient_store_appointment.store')}}" method="post">

                                {{csrf_field()}}

                                <input type="hidden" name="user_id" value="{{$patient->id}}">
                                
                                <div class="row" style="padding: 1%;">
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
                                            <label>Assign Patient</label>
                                            <select name="user_id" id="user_id" class="form-control" disabled>
                                                <option value="{{$patient->id}}">{{$patient->firstname}} {{$patient->lastname}} - {{$patient->nhs_number}}</option> 
                                            </select>
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
            </div>
    
@endsection
