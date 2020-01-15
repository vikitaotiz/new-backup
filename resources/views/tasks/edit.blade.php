@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                </div>

                <div class="col-md-4">
                    <strong>Edit Task</strong>
                </div>
                <div class="col-md-4">
                     <a href="{{route('tasks.index')}}" class="btn btn-sm btn-info btn-block"><i class="fa fa-list"></i> Task List</a>
                </div>
            </div>
        </div>

        <div class="panel-body">

            <form action="{{route('tasks.update', $task->id)}}" method="post">

                {{csrf_field()}} {{method_field('PUT')}}

                <div class="row">
                    <div class="col-md-6">
                          <div class="form-group">
                              <label>Name</label>
                              <input type="text" name="name" value="{{$task->name}}" class="form-control" placeholder="Enter Name...">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                              <label>Deadline</label>
                              <input type="date" name="deadline" id="deadline" value="{{date('Y-m-d', strtotime($task->deadline))}}" class="form-control">
                        </div>
                    </div>
                </div><hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Assign Patient</label>
                            <select name="user_id" id="user_id" class="form-control">
                                @foreach ($patients as $patient)
                                <option value="{{$patient->id}}"
                                    @if ($patient->id == $task->user_id)
                                        selected
                                    @endif>
                                        DOB : {{$patient->date_of_birth}} -
                                        {{$patient->firstname}}
                                        {{$patient->lastname}}.
                                        NHS : {{$patient->nhs_number}}
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
                                <option value="{{$user->id}}" @if ($user->id == $task->doctor_id) selected @endif>{{$user->firstname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div><hr>

                 <div class="row" style="padding: 2%;">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Enter Description...">{{$task->description}}</textarea>
                    </div>
                </div>
                <hr>

                <div class="row" style="padding: 2%;">
                    <div class="form-group">
                        <input type="submit" class="btn btn-sm btn-success btn-block" value="Update Task">
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
