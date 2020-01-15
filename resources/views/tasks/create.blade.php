@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-4">
                                <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                            </div>
                            <div class="col-md-4">
                                <strong>Create New Task</strong>
                            </div>
                            <div class="col-md-4">
                                {{-- <a href="{{route('clients.edit')}}" class="btn btn-sm btn-primary">Edit Client</a> --}}
                            </div>
                        </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('tasks.store')}}" method="post">

                                {{csrf_field()}}
                        
                                <div class="row">
                                    <div class="col-md-6">
                                         <div class="form-group">
                                              <label>Name</label>
                                              <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="Enter Name...">
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                              <label>Deadline</label>
                                              <input type="date" name="deadline" id="deadline" value="{{old('deadline')}}" class="form-control" placeholder="Enter Task's Deadline...">
                                        </div>
                                    </div>
                                </div><hr>
            
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Assign Patient. <a href="{{route('patients.create')}}">Create New</a></label>
                                            <select name="user_id" id="user_id" class="form-control">
                                                @foreach ($patients as $patient)
                                                    <option value="{{$patient->id}}">
                                                            {{$patient->firstname}} {{$patient->lastname}} - 
                                                           
                                                            {{-- DOB : {{$user->date_of_birth->format('D jS, M, Y')}} -  --}}
                                                            
                                                            {{-- NHS : {{$user->nhs_number}} --}}
                                                    </option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Assign Staff</label>
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
                                        <textarea name="description" class="form-control" id="description" placeholder="Enter Description...">{{old('description')}}</textarea>
                                    </div>
                                </div>
                                <hr>
                               
                                <div class="row" style="padding: 2%;">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-sm btn-success btn-block" value="Submit Task">
                                    </div>
                                </div>
                            </form>
                </div>
            </div>
    
@endsection
