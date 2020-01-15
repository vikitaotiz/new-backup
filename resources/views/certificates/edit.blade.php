@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Edit Certificate Letter</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('certificates.create')}}" class="btn btn-sm btn-primary">Create New certificate</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('certificates.update', $certificate->id)}}" method="post">
                                {{csrf_field()}} {{method_field('PUT')}}
                                
                                <div class="row" style="padding-left: 1%; padding-right: 1%;">
                                   <div class="form-group">
                                    <label>From (Assign Company)</label>
                                    <select name="company_id" id="company_id" class="form-control">
                                        <option></option> 
                                        @foreach ($companies as $company)
                                            <option value="{{$company->id}}"
                                                @if ($company->id == $certificate->company_id)
                                                    selected
                                                @endif>{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                   </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Certificate Name</label>
                                            <input type="text" name="name" value="{{$certificate->name}}" class="form-control" placeholder="Enter certificate Name...">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Related Patient</label>
                                            <select name="user_id" id="patient_id" class="form-control">
                                                @foreach ($users as $user)
                                                    <option value="{{$user->id}}"
                                                        @if ($user->id == $certificate->user_id)
                                                            selected
                                                        @endif>
                                                        {{$user->firstname}} {{$user->lastname}} - 
                                                        NHS Number : {{$user->nhs_number}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div><hr>

                               <div class="row" style="padding: 1%;">
                                    <div class="form-group">
                                        <label>Certificate Body</label>
                                        <textarea name="body" class="form-control" id="description" placeholder="Enter Certificate Body...">
                                            {{$certificate->body}}
                                        </textarea>
                                    </div>
                               </div>

                                <div class="form-group">
                                    <input type="submit" value="Update certificate" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>
                </div>
            </div>
    
@endsection
