@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                   <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Create New Contact</strong>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('contacts.store')}}" method="post" enctype="multipart/form-data">

                                {{csrf_field()}}
            
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Relative</label>
                                            <input type="text" name="relative_name" value="{{old('relative_name')}}" class="form-control" placeholder="Enter Relative's Name...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                              <label>Phone</label>
                                              <input type="text" name="phone" value="{{old('phone')}}" class="form-control" placeholder="Enter Phone Number...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                            <div class="form-group">
                                                  <label>Email</label>
                                                  <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="Enter Email...">
                                            </div>
                                        </div>
                                    <div class="col-md-3">
                                         <div class="form-group">
                                                <label>Patient Name. 
                                                        <a href="#" data-toggle="modal" data-target="#addNewPatient">Create New</a>
                                                    </label>
                                            <select name="user_id" id="patient_id" class="form-control">
                                                @foreach ($users as $user)
                                                    <option value="{{$user->id}}"
                                                        @if(@isset($user->id))
                                                            selected
                                                        @endif
                                                        >{{$user->firstname}} {{$user->lastname}} : Date Of Birth- {{$user->date_of_birth}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div><hr>
            
                                <div class="row">
                                     <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Profile Photo</label>
                                            <input type="file" class="form-control" name="profile_photo">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Relationship Type</label>
                                            <select name="relationship_type" id="relationship_type" class="form-control">
                                                <option value="other">Other Relationship</option>
                                                <option value="spouse">Spouse</option>
                                                <option value="sibling">Sibling</option>
                                                <option value="daughter">Daughter</option>
                                                <option value="son">Son</option>
                                                <option value="father">Father</option>
                                                <option value="mother">Mother</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <input type="date" class="form-control" id="date_of_birth" value="{{old('date_of_birth')}}" name="date_of_birth" placeholder="Enter Date of Birth">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                           <label>NHS Number</label>
                                           <input type="text" name="nhs_number" value="{{old('nhs_number')}}" class="form-control" placeholder="Enter NHS Number...">
                                        </div>
                                    </div>
                                </div><hr>
            
                                <div class="row" style="padding: 2%;">
                                        <div class="form-group">
                                           <label>More Information</label>
                                           <textarea name="more_info" id="more_info" class="form-control" placeholder="Enter More Infomation, e.g Medical Histroy...">{{old('more_info')}}</textarea>
                                        </div>
                                </div><hr>

                                <div class="row" style="padding: 2%;">
                                        <div class="form-group">
                                           <label>Medical History</label>
                                           <textarea name="medical_history" id="medical_history" class="form-control" placeholder="Enter More Infomation, e.g Medical Histroy...">{{old('medical_history')}}</textarea>
                                        </div>
                                </div><hr>
            
                                <div class="row" style="padding: 2%;">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-sm btn-block btn-success" value="Submit Contact">
                                    </div>
                                </div>
                            </form>

                </div>
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
    
@endsection
