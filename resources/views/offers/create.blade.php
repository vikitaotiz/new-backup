@extends('adminlte::page')

@section('title', 'HospitalNote')

@section('content_header')
    <div class="row">
        <div class="col-md-6">
            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
        </div>
        <div class="col-md-6">
            <strong>Create Content (Feature)</strong>
        </div>
    </div>
@stop

@section('content')
    <div class="panel panel-primary">
        <div class="panel-body">
            <form action="{{route('offers.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Enter Title">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Image (Service)</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>
                <div class="p-2">
                    <label>Content</label>
                    <textarea name="content" class="form-control" id="offer_area" placeholder="Enter Content"></textarea>
                </div>
                <br>
                <div class="p-2">
                    <input type="submit" value="Submit Content" class="btn btn-sm btn-primary btn-block">
                </div>
            </form>
        </div>
        
    </div>
@stop