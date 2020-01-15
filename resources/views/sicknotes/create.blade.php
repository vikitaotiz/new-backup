@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Create New Sicknote Letter</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('sicknotes.create')}}" class="btn btn-sm btn-primary">Create New sicknote</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('sicknotes.store')}}" method="post">
                                {{csrf_field()}}

                                {{-- <div class="row" style="padding-left: 1%; padding-right: 1%;">
                                    <label>Assign Company</label>
                                    <select name="company_id" id="company_id">
                                        <option></option>
                                        @foreach ($companies as $company)
                                            <option value="{{$company->id}}">{{$company->name}}</option>                                            
                                        @endforeach
                                    </select>
                                </div><br> --}}
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Sicknote Name</label>
                                            <input type="text" name="name" class="form-control" placeholder="Enter sicknote Name...">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Related Patient. <a href="{{route('patients.create')}}">Create New</a></label>
                                            <select name="user_id" id="patient_id" class="form-control">
                                                @foreach ($users as $user)
                                                    <option value="{{$user->id}}">
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
                                        <label>Sicknote Body</label>
                                        <textarea name="body" class="form-control" id="description" placeholder="Enter Sicknote Body..."></textarea>
                                    </div>
                               </div>
                               <hr>
                                <div class="form-group">
                                    <input type="submit" value="Submit sicknote" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>
                </div>
            </div>
    
@endsection
