@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success">Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Create New Patient (Required Fields *)</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('clients.edit')}}" class="btn btn-sm btn-primary">Edit Client</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                  <form action="{{route('patients.store')}}" method="post" enctype="multipart/form-data">

                    {{csrf_field()}}

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Title</label>
                                <select name="title" id="title" class="form-control">
                                    <option value="mr">Mr.</option>
                                    <option value="mrs">Mrs.</option>
                                    <option value="miss">Miss.</option>
                                    <option value="master">Master.</option>
                                    <option value="eng">Eng.</option>
                                    <option value="dr">Dr.</option>
                                </select>
                            </div>
                        </div>
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
                    </div><hr>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date of Birth *</label>
                                <input type="text" class="form-control" id="date_of_birth" value="{{old('date_of_birth')}}" name="date_of_birth" placeholder="Select Date of Birth">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{old('phone')}}" placeholder="Phone Number...">
                            </div>
                        </div>
                    </div><hr>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{old('email')}}" placeholder="Enter Email">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                               <label>NHS Number</label>
                               <input type="text" name="nhs_number" value="{{old('nhs_number')}}" class="form-control" placeholder="Enter NHS Number...">
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Emergency Contact</label>
                                <input type="text" name="emergency_contact" class="form-control" value="{{old('emergency_contact')}}" placeholder="Enter Emergency Number...">
                            </div>
                        </div>
                    </div><hr>



                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control" value="{{old('address')}}" placeholder="Enter Address">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label>Assign Staff</label>
                            <select name="user_id" id="user_id" class="form-control">
                                <option selected disabled>Select Staff</option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->firstname}} {{$user->lastname}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Communication Preference</label>
                            <select name="communication_preference" id="communication_preference" class="form-control">
                                <option selected disabled>Select Communication Preference</option>
                                <option value="0">Email</option>
                                <option value="1">SMS</option>
                                <option value="2">SMS & Email</option>
                            </select>
                        </div>
                    </div>
                      <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Occupation</label>
                                <input type="text" name="occupation" class="form-control" value="{{old('occupation')}}" placeholder="Enter Occupation...
                                ">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Height</label>
                                <input type="text" name="height" class="form-control" value="{{old('height')}}" placeholder="Enter Height">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Weight</label>
                                <input type="text" name="weight" class="form-control" value="{{old('weight')}}" placeholder="Enter Weight">
                            </div>
                        </div>
                    </div><hr>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Blood Type</label>
                                <input type="text" name="blood_type" class="form-control" value="{{old('blood_type')}}" placeholder="Enter Blood Type...
                                ">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Referral Source</label>
                                <input type="text" name="referral_source" class="form-control" value="{{old('referral_source')}}" placeholder="Enter Referral Source">
                            </div>
                        </div>

                        <div class="col-md-4">
                                <div class="form-group">
                                    <label>Profile Photo</label>
                                    <input type="file" name="profile_photo" class="form-control">
                                </div>
                            </div>
                    </div><hr>

                    <div class="row" style="padding: 1%;">
                        <div class="col-md-6">
                            <label>General Practioner (GP) Deatils</label>
                            <div class="form-group">
                                <textarea name="gp_details" id="gp_details" class="form-control" placeholder="Enter GP Details...">{{old('gp_details')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Medication Allergies</label>
                            <div class="form-group">
                                <textarea name="medication_allergies" id="medication_allergies" class="form-control" placeholder="Enter Any Medication Allergies">{{old('medication_allergies')}}</textarea>
                            </div>
                        </div>

                    </div><hr>

                    <div class="row" style="padding: 1%;">
                        <div class="col-md-6">
                             <label>Current Medication</label>
                            <div class="form-group">
                                <textarea name="current_medication" id="current_medication" class="form-control" placeholder="Enter Any Current Medication">{{old('current_medication')}}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Patient's More Information</label>
                                <textarea name="more_info" class="form-control" id="more_info" placeholder="Enter More Information...">{{old('more_info')}}</textarea>
                            </div>
                        </div>
                    </div>
                      <hr>

                      <div class="row" style="padding: 1%;">
                          <div class="form-group">
                              <label>Alert Message</label>
                              <textarea name="patient_note" class="form-control" id="patient_note" placeholder="Patient's Note...">{{old('patient_note')}}</textarea>
                          </div>
                      </div>
                      <hr>

                      <div class="row" style="padding: 1%;">
                          <h4>Privacy policy</h4>
                          <div class="form-group">
                              <label>Does the client consent to your privacy policy?</label><br>
                              <label class="radio-inline">
                                  <input type="radio" name="privacy_policy" checked value="0">No response
                              </label>
                              <label class="radio-inline">
                                  <input type="radio" name="privacy_policy" value="1">Accepted
                              </label>
                              <label class="radio-inline">
                                  <input type="radio" name="privacy_policy" value="2">Rejected
                              </label>
                          </div>
                      </div>

                    <div class="row" style="padding: 1%;">
                        <div class="form-group">
                            <input type="submit" class="btn btn-sm btn-success btn-block" value="Submit Patient">
                        </div>
                    </div>
                </form>

                </div>
            </div>

@endsection
