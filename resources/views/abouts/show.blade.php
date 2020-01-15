@extends('adminlte::page')

@section('title', 'HospitalNote')

@section('content_header')
    <div class="row">
        <div class="col-md-3">
            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
        </div>
        <div class="col-md-3">
            <strong>
                {{$about->title}}
            </strong>
        </div>
        <div class="col-md-3">
            <a href="{{route('abouts.edit', $about->id)}}" class="btn btn-sm btn-success btn-block">
                Edit Content
            </a>
        </div>
        <div class="col-md-3">
            <form action="{{route('abouts.destroy', $about->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete Slider Content" class="btn btn-sm btn-danger btn-block"
                onclick="return configrm('Are you sure?')">
            </form>
        </div>
    </div>
@stop

@section('content')
    <div class="panel panel-primary">
        <div class="panel-body">
            <div class="p-2">
                <p>{{$about->title}}</p>
            </div>
            <hr>
            
            <hr>
            <div class="p-2">
                <div class="row">
                    <div class="col-md-6">
                        @if ($about->image)
                            <img src="{{asset('storage/'.$about->image)}}" alt="{{$about->title}}" width="100%" height="350">
                        @else
                            <h5>Image not provided</h5>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <p class="text-center">
                            {!! $about->content !!}
                        </p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@stop