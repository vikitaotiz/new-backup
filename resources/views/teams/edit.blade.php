@extends('adminlte::page')

@section('title', 'HospitalNote')

@section('content_header')
    <div class="row">
        <div class="col-md-6">
            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
        </div>
        <div class="col-md-6">
            <strong>Create Content (Team)</strong>
        </div>
    </div>
@stop

@section('content')
    <div class="panel panel-primary">
        <div class="panel-body">
            <form action="{{route('teams.update', $team->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="{{$team->name}}" class="form-control" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Team's Role</label>
                            <input type="text" name="role" value="{{$team->role}}" class="form-control" placeholder="Enter Team's Role">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Image (Team)</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="col-md-6">
                        @if ($team->image)
                            <img src="{{asset('storage/'.$team->image)}}" alt="{{$team->name}}" width="80%" height="350">
                        @else
                            <h5>Image not provided</h5>
                        @endif                        
                    </div>
                </div>
                <br>
                <div class="p-2">
                    <label>Bio</label>
                    <textarea name="bio" class="form-control" id="team_area" placeholder="Enter Team' Bio">
                        {!! $team->bio !!}
                    </textarea>
                </div>
                <br>
                <div class="p-2">
                    <input type="submit" value="Submit Content" class="btn btn-sm btn-primary btn-block">
                </div>
            </form>
        </div>
        
    </div>
@stop