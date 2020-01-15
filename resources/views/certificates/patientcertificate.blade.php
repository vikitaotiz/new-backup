@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Create Certificate Letter for {{$user->firstname}} {{$user->lastname}}</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('certificates.create')}}" class="btn btn-sm btn-primary">Create New certificate</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('patient_store_certificate.store')}}" method="post">

                                {{csrf_field()}}

                                <input type="hidden" name="user_id" value="{{$user->id}}">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Certificate Name</label>
                                            <input type="text" name="name" class="form-control" placeholder="Enter certificate Name...">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Related Patient</label>
                                            <select name="user_id" id="patient_id" class="form-control" disabled>
                                                <option value="{{$user->id}}"><strong>{{$user->nhs_number}} : </strong> {{$user->firstname}} {{$user->lastname}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div><hr>

                               <div class="row" style="padding: 1%;">
                                    <div class="form-group">
                                        <label>Certificate Body</label>
                                        <textarea name="body" class="form-control" id="description" placeholder="Enter Certificate Body..."></textarea>
                                    </div>
                               </div>

                                <div class="form-group">
                                    <input type="submit" value="Submit certificate" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>
                </div>
            </div>
    
@endsection
