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
                            {{-- <a href="{{route('clients.edit')}}" class="btn btn-sm btn-primary">Edit Client</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body" style="padding:5%;">
  
                        <form action="{{route('contacts.update', $contact->id)}}" method="post" enctype="multipart/form-data">

                                {{csrf_field()}} {{method_field('PATCH')}}
            
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Relative</label>
                                            <input type="text" name="relative_name" value="{{$contact->relative_name}}" class="form-control" placeholder="Enter Name...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                              <label>Phone</label>
                                              <input type="text" name="phone" value="{{$contact->phone}}" class="form-control" placeholder="Enter Phone Number...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                            <div class="form-group">
                                                  <label>Email</label>
                                                  <input type="email" name="email" value="{{$contact->email}}" class="form-control" placeholder="Enter Email...">
                                            </div>
                                        </div>
                                    <div class="col-md-3">
                                         <div class="form-group">
                                            <label>Related Patient</label>
                                            <select name="user_id" id="user_id" class="form-control">
                                                @foreach ($users as $user)
                                                    <option value="{{$user->id}}" 
                                                        @if ($user->id == $contact->user_id)
                                                            selected
                                                        @endif>
                                                        {{$user->firstname}} {{$user->lastname}}</option>
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
                                                <option value="other" @if($contact->relationship_type) selected @endif>Other Relationship</option>
                                                
                                                <option value="spouse" @if($contact->relationship_type) selected @endif>Spouse</option>
                                                <option value="sibling" @if($contact->relationship_type) selected @endif>Sibling</option>
                                                <option value="daughter" @if($contact->relationship_type) selected @endif>Daughter</option>
                                                <option value="son" @if($contact->relationship_type) selected @endif>Son</option>
                                                <option value="father" @if($contact->relationship_type) selected @endif>Father</option>
                                                 
                                                <option value="mother" @if($contact->relationship_type) selected @endif>Mother</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <input type="text" class="form-control"  id="date_of_birth" value="{{$contact->date_of_birth}}" name="date_of_birth">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                           <label>NHS Number</label>
                                           <input type="text" name="nhs_number" value="{{$contact->nhs_number}}" class="form-control" placeholder="Enter NHS Number...">
                                        </div>
                                    </div>
                                </div><hr>
            
                                <div class="row">
                                        <div class="form-group">
                                           <label>More Information</label>
                                           <textarea name="more_info" id="more_info" class="form-control" placeholder="Enter More Infomation, e.g Medical Histroy...">{{$contact->more_info}}</textarea>
                                        </div>
                                </div><hr>

                                <div class="row">
                                        <div class="form-group">
                                           <label>Medical History</label>
                                           <textarea name="medical_history" id="medical_history" class="form-control" placeholder="Enter More Infomation, e.g Medical Histroy...">{{$contact->medical_history}}</textarea>
                                        </div>
                                </div><hr>
            
                                <div class="row">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-sm btn-block btn-success" value="Update Contact">
                                    </div>
                                </div>
                            </form>

                </div>
            </div>
    
@endsection
