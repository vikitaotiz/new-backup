@extends('adminlte::page')

@section('title', 'HospitalNote')

@section('content_header')
    <div class="row">
        <div class="col-md-3">
            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
        </div>
        <div class="col-md-3">
            <strong>
                {{$offer->title}}
            </strong>
        </div>
        <div class="col-md-3">
            <a href="{{route('offers.edit', $offer->id)}}" class="btn btn-sm btn-success btn-block">
                Edit Content
            </a>
        </div>
        <div class="col-md-3">
            <form action="{{route('offers.destroy', $offer->id)}}" method="POST">
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
                <p>{{$offer->title}}</p>
            </div>
            <hr>
            <div class="p-2">
                @if ($offer->image)
                    <img src="{{asset('storage/'.$offer->image)}}" alt="{{$offer->title}}" width="100%" height="400">
                @else
                    <h5>Image not provided</h5>
                @endif
            </div>
            <hr>
            <div class="p-2">
                <p>
                    {!! $offer->content !!}
                </p>
            </div>
        </div>
    </div>
@stop