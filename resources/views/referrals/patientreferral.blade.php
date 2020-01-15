@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Create Referral Letter {{$user->firstname}} {{$user->lastname}}</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('referrals.create')}}" class="btn btn-sm btn-primary">Create New referral</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('patient_store_referral.store')}}" method="post">

                                {{csrf_field()}}
                                 
                                 <input type="hidden" name="user_id" value="{{$user->id}}">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Referral Name</label>
                                            <input type="text" name="name" class="form-control" placeholder="Enter referral Name...">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Patient Name</label>
                                            <select name="user_id" id="user_id" class="form-control" disabled>
                                                    <option value="{{$user->id}}">
                                                        {{$user->firstname}} {{$user->lastname}} - 
                                                        NHS Number : {{$user->nhs_number}}
                                                    </option>
                                            </select>
                                        </div>
                                    </div>
                                </div><hr>

                               <div class="row" style="padding: 1%;">
                                    <div class="form-group">
                                        <label>Referral Body</label>
                                        <textarea name="body" class="form-control" id="description" placeholder="Enter Referral Body..."></textarea>
                                    </div>
                               </div>
                               <hr>
                                <div class="form-group">
                                    <input type="submit" value="Submit Referral Letter" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>
                </div>
            </div>
    
@endsection
