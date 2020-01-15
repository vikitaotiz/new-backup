@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                     <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Edit Patient</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('clients.edit')}}" class="btn btn-sm btn-primary">Edit Client</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                        <form action="{{route('patients.update', $patient->id)}}" method="post" enctype="multipart/form-data">

                                {{csrf_field()}} {{method_field('PATCH')}}

                                <div class="form-group">
                                    <label for="username">User name</label>
                                    <input type="text" name="username" class="form-control" value="{{ $patient->username }}" placeholder="User name" required>
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                    <span class="help-block">
                                        <strong>Patient will need this username along with password during login.</strong>
                                    </span>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <select name="title" id="title" class="form-control">

                                                <option value="mr" {{$patient->title == 'mr' ? 'selected' : ''}}>Mr.</option>
                                                <option value="mrs" {{$patient->title == 'mrs' ? 'selected' : ''}}>Mrs.</option>
                                                <option value="miss" {{$patient->title == 'miss' ? 'selected' : ''}}>Miss.</option>
                                                <option value="master" {{$patient->title == 'master' ? 'selected' : ''}}>Master.</option>
                                                <option value="eng" {{$patient->title == 'eng' ? 'selected' : ''}}>Eng.</option>
                                                <option value="dr" {{$patient->title == 'dr' ? 'selected' : ''}}>Dr.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                              <label>First Name</label>
                                              <input type="text" name="firstname" value="{{$patient->firstname}}" class="form-control" placeholder="Enter First Name...">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                         <div class="form-group">
                                              <label>Last Name</label>
                                              <input type="text" name="lastname" value="{{$patient->lastname}}" class="form-control" placeholder="Enter Last Name...">
                                        </div>
                                    </div>
                                </div><hr>

                                <div class="row">
                                     <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Profile Photo</label>
                                            <input type="file" name="profile_photo" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select name="gender" id="gender" class="form-control">
                                                <option value="male" @if($patient->gender == 'male') selected @endif>Male</option>
                                                <option value="female" @if($patient->gender == 'female') selected @endif>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <input type="text" class="form-control"  id="date_of_birth" value="{{$patient->date_of_birth}}" name="date_of_birth">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="{{$patient->email}}" placeholder="Enter Email">
                                        </div>
                                    </div>
                                </div><hr>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                           <label>NHS Number</label>
                                           <input type="text" name="nhs_number" value="{{$patient->nhs_number}}" class="form-control" placeholder="Enter NHS Number...">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="text" name="phone" class="form-control" value="{{$patient->phone}}" placeholder="Phone Number...">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Emergency Contact</label>
                                            <input type="text" name="emergency_contact" class="form-control" value="{{$patient->emergency_contact}}" placeholder="Enter Emergency Number...">
                                        </div>
                                    </div>
                                </div><hr>

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="address" class="form-control" value="{{$patient->address}}" placeholder="Enter Address">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Occupation</label>
                                            <input type="text" name="occupation" class="form-control" value="{{$patient->occupation}}" placeholder="Enter Occupation...
                                            ">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Height</label>
                                            <input type="text" name="height" class="form-control"  value="{{$patient->height}}" placeholder="Enter Height">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Weight</label>
                                            <input type="text" name="weight" class="form-control"value="{{$patient->weight}}" placeholder="Enter Weight">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Blood Type</label>
                                            <input type="text" name="blood_type" class="form-control" value="{{$patient->blood_type}}" placeholder="Enter Blood Type...
                                            ">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Referral Source</label>
                                            <input type="text" name="referral_source" class="form-control" value="{{$patient->referral_source}}" placeholder="Enter Referral Source">
                                        </div>
                                    </div>
                                </div><hr>

                                <div class="row" >
                                   <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Assign Staff</label>
                                        <select name="user_id" id="doctor_id" class="form-control">
                                            @foreach ($users as $user)
                                                <option value="{{$user->id}}" @if($user->id == $patient->user_id) selected @endif>
                                                    {{$user->firstname}} {{$user->lastname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                   </div>

                                    <div class="col-md-4">
                                        <label>Communication Preference</label>
                                        <select name="communication_preference" id="communication_preference" class="form-control">
                                            <option selected disabled>Select Communication Preference</option>
                                            <option value="0" @if($patient->communication_preference === 0) selected @endif>Email</option>
                                            <option value="1" @if($patient->communication_preference == 1) selected @endif>SMS</option>
                                            <option value="2" @if($patient->communication_preference == 2) selected @endif>SMS & Email</option>
                                        </select>
                                    </div>

                                   {{--@if(auth()->user()->role_id == 1)
                                       <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Assign Role</label>
                                            <select name="role_id" id="role_id" class="form-control">
                                                @foreach ($roles as $role)
                                                    <option value="{{$role->id}}" @if($role->id == $patient->role_id) selected @endif>
                                                        {{$role->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                       </div>
                                   @elseif(auth()->user()->role_id == 2)
                                   <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Assign Role</label>
                                            <select name="role_id" id="role_id" class="form-control">
                                                @foreach ($roles as $role)
                                                    <option value="{{$role->id}}" @if($role->id == $patient->role_id) selected @endif>
                                                        {{$role->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                       </div>
                                   @endif--}}
                                   <div class="col-md-4">

                                        <label>Password * (Can be retained or changed.)</label>
                                        <input type="password" name="password" class="form-control" placeholder="Enter User Password...">

                                    </div>
                                </div><hr>

                                <div class="row" style="padding: 1%;">
                                    <label>General Practioner (GP) Deatils</label>
                                    <div class="form-group">
                                        <textarea name="gp_details" id="gp_details" class="form-control" placeholder="Enter GP Details...">
                                            {{$patient->gp_details}}
                                        </textarea>
                                    </div>
                                </div><hr>

                                <div class="row" style="padding: 1%;">
                                    <label>Medication Allergies</label>
                                    <div class="form-group">
                                        <textarea name="medication_allergies" id="medication_allergies" class="form-control" placeholder="Enter Any Medication Allergies">
                                            {{$patient->medication_allergies}}
                                        </textarea>
                                    </div>
                                </div>
                                <hr>
                                <div class="row" style="padding: 1%;">
                                    <label>Current Medication</label>
                                    <div class="form-group">
                                        <textarea name="current_medication" class="form-control" id="current_medication" placeholder="Enter Any Current Medication">
                                            {{$patient->current_medication}}
                                        </textarea>
                                    </div>
                                </div>

                                <hr>
                                <div class="row" style="padding: 1%;">
                                    <div class="form-group">
                                        <label>Patient's More Information</label>
                                        <textarea name="more_info" class="form-control" id="more_info" placeholder="Enter More Information...">
                                            {{$patient->more_info}}
                                        </textarea>
                                    </div>
                                </div>
                            <hr>
                            <div class="row" style="padding: 1%;">
                                <div class="form-group">
                                    <label>Alert Message</label>
                                    <textarea name="patient_note" class="form-control" id="patient_note" placeholder="Patient's Note...">{{$patient->patient_note}}</textarea>
                                </div>
                            </div>
                            <hr>
                            <div class="row" style="padding: 1%;">
                                <h4>Privacy policy</h4>
                                <div class="form-group">
                                    <label>Does the client consent to your privacy policy?</label><br>
                                    <label class="radio-inline">
                                        <input type="radio" name="privacy_policy" value="0" @if($patient->privacy_policy === 0) checked @endif>No response
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="privacy_policy" value="1" @if($patient->privacy_policy == 1) checked @endif>Accepted
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="privacy_policy" value="2" @if($patient->privacy_policy == 2) checked @endif>Rejected
                                    </label>
                                </div>
                            </div>
                                <div class="row" style="padding: 1%;">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-sm btn-success btn-block" value="Update Patient">
                                    </div>
                                </div>
                            </form>

                </div>
            </div>
@endsection
