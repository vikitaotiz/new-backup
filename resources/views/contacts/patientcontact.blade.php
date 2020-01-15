@extends('adminlte::page')

@section('content')
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-4">
                <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
            </div>
            <div class="col-md-4">
                <strong>Create Contact for {{$user->firstname}} {{$user->lastname}}</strong>
            </div>
            <div class="col-md-4">
            </div>
        </div>
    </div>
    <div class="panel-body">
        @include('inc.tabMenu', ['tabMenuPosition'=>2, 'patient_id'=>$user->id])
        <form action="{{route('patient_store_contact.store')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" name="user_id" value="{{$user->id}}">
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
                        <label>Related Patient</label>
                        <select name="user_id" id="user_id" class="form-control" disabled>
                            <option value="{{$user->id}}">{{$user->firstname}} {{$user->lastname}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <hr>
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
            </div>
            <hr>
            <div class="row" style="padding: 2%;">
                    <div class="form-group">
                        <label>More Information</label>
                        <textarea name="more_info" id="more_info" class="form-control" placeholder="Enter More Infomation, e.g Medical Histroy...">{{old('more_info')}}</textarea>
                    </div>
            </div>
            <hr>
            <div class="row" style="padding: 2%;">
                    <div class="form-group">
                        <label>Medical History</label>
                        <textarea name="medical_history" id="medical_history" class="form-control" placeholder="Enter More Infomation, e.g Medical Histroy...">{{old('medical_history')}}</textarea>
                    </div>
            </div>
            <hr>
            <div class="row" style="padding: 2%;">
                <div class="form-group">
                    <input type="submit" class="btn btn-sm btn-block btn-success" value="Submit Contact">
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
