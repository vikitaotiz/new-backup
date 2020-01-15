@extends('adminlte::page')

@section('title', 'HospitalNote')

@section('content_header')
    <div class="row">
        <div class="col-md-6">
            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
        </div>
        <div class="col-md-6">
            <h1>Landing Page</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="panel panel-primary">
        <div class="panel-body">
            <div class="p-2">
                <div class="col-md-6 panel panel-warning">
                    <div class="panel-body">
                        <a href="{{route('homepages.index')}}"><strong>Sliders</strong></a>
                    </div>
                </div>
                <div class="col-md-6 panel panel-warning">
                    <div class="panel-body">
                        <a href="{{route('abouts.index')}}"><strong>About Us</strong></a>
                    </div>
                </div>
            </div>
            <div class="p-2">
                <div class="col-md-4 panel panel-warning">
                    <div class="panel-body">
                        <a href="{{route('offers.index')}}"><strong>Features</strong></a>
                    </div>
                </div>
                <div class="col-md-4 panel panel-warning">
                    <div class="panel-body">
                        <a href="{{route('teams.index')}}"><strong>Our Team</strong></a>
                    </div>
                </div>
                <div class="col-md-4 panel panel-warning">
                    <div class="panel-body">
                        Contact
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop