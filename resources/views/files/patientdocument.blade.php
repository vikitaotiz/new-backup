@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
                <div class="col-md-4">
                    <strong>Upload File for {{$user->firstname}} {{$user->lastname}}</strong>
                </div>
                <div class="col-md-4">
                    {{-- <a href="{{route('roles.create')}}" class="btn btn-sm btn-primary">Create New Role</a> --}}
                </div>
            </div>
        </div>

        <div class="panel-body">
            @include('inc.tabMenu', ['tabMenuPosition'=>4, 'patient_id'=>$user->id])
            <h3 class="jumbotron text-center">Upload Patient's Document</h3>
            <form method="post" action="{{route('patient_store_document.store')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" value="{{$user->id}}">
                <div class="form-group">
                    <label>Select Patient</label>
                    <select name="user_id" id="user_id" class="form-control" disabled>
                        <option value="{{$user->id}}">{{$user->firstname}} {{$user->lastname}}</option>
                    </select>
                </div>
                <input type="text" name="name" class="form-control" placeholder="Enter file name...">
                <br>
                <input type="file" name="filename" class="form-control"
                       accept="image/jpeg,image/jpg,image/gif,image/png,application/pdf,image/x-eps">

                <br>
                <div class="form-group">
                    <input type="submit" class="btn btn-sm btn-success btn-block" value="Submit File">
                </div>
            </form>
        </div>
    </div>

@endsection
