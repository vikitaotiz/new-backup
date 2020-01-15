@extends('adminlte::page')

@section('title', 'HospitalNote')

@section('content_header')
    <div class="row">
        <div class="col-md-6">
            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
        </div>
        <div class="col-md-6">
            <strong>Edit Content (Feature)</strong>
        </div>
    </div>
@stop

@section('content')
    <div class="panel panel-primary">
        <div class="panel-body">
            <form action="{{route('offers.update', $offer->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" value="{{$offer->title}}" class="form-control" placeholder="Enter Title">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Image (Slider)</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>

                <div class="p-2">
                    @if ($offer->image)
                        <img src="{{asset('storage/'.$offer->image)}}" alt="{{$offer->title}}" width="80%" height="300">
                    @else
                        <h5>Image not provided</h5>
                    @endif
                </div>

                <div class="p-2">
                    <label>Content</label>
                    <textarea name="content" class="form-control" id="offer_area" placeholder="Enter Content">
                        {!! $offer->content !!}
                    </textarea>
                </div>
                <br>
                <div class="p-2">
                    <input type="submit" value="Update Content" class="btn btn-sm btn-primary btn-block">
                </div>
            </form>
        </div>
        
    </div>
@stop