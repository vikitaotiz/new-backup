@extends('adminlte::page')

@section('title', 'HospitalNote')

@section('content_header')
    <div class="row">
        <div class="col-md-4">
            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
        </div>
        <div class="col-md-4">
            <strong>Landing Page (Slider/Homepage)</strong>
        </div>
        <div class="col-md-4">
            <a href="{{route('homepages.create')}}" class="btn btn-sm btn-primary btn-block">
                Create Content
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="panel panel-primary">
        <div class="panel-body">
        @if ($homepages->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <th>Title</th>
                    <th>Created By</th>
                    <th>Created On</th>
                </thead>
                <tbody>
                    @foreach ($homepages as $homepage)
                        <tr>
                            <td>
                                <a href="{{route('homepages.show', $homepage->id)}}">                        
                                    {{$homepage->title}}
                                </a>
                            </td>
                            <td>{{$homepage->user->firstname}}</td>
                            <td>{{$homepage->created_at->diffForHumans()}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            No Content
        @endif
        </div>
        
    </div>
@stop