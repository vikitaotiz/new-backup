@extends('adminlte::page')

@section('title', 'HospitalNote')

@section('content_header')
    <div class="row">
        <div class="col-md-3">
            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
        </div>
        <div class="col-md-3">
            <strong>
                {{$team->name}}
            </strong>
        </div>
        <div class="col-md-3">
            <a href="{{route('teams.edit', $team->id)}}" class="btn btn-sm btn-success btn-block">
                Edit Content
            </a>
        </div>
        <div class="col-md-3">
            <form action="{{route('teams.destroy', $team->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete Slider Content" class="btn btn-sm btn-danger btn-block"
                onclick="return confirm('Are you sure?')">
            </form>
        </div>
    </div>
@stop

@section('content')
    <div class="panel panel-primary">
        <div class="panel-body">
            <div class="p-2">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item">Name : {{$team->name}}</li>
                            <li class="list-group-item">Role : {{$team->role}}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        @if ($team->image)
                            <img src="{{asset('storage/'.$team->image)}}" alt="{{$team->name}}" width="100%" height="400">
                        @else
                            <h5>Image not provided</h5>
                        @endif
                    </div>
                </div>
                
            </div>

            <hr>
            <div class="p-2">
                <p>
                    {!! $team->bio !!}
                </p>
            </div>
        </div>
    </div>
@stop