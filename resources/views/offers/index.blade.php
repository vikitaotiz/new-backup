@extends('adminlte::page')

@section('title', 'HospitalNote')

@section('content_header')
    <div class="row">
        <div class="col-md-4">
            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
        </div>
        <div class="col-md-4">
            <strong>Landing Page (Features/Homepage)</strong>
        </div>
        <div class="col-md-4">
            <a href="{{route('offers.create')}}" class="btn btn-sm btn-primary btn-block">
                Create Content
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="panel panel-primary">
        <div class="panel-body">
        @if ($offers->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <th>Title</th>
                    <th>Created By</th>
                    <th>Created On</th>
                </thead>
                <tbody>
                    @foreach ($offers as $offer)
                        <tr>
                            <td>
                                <a href="{{route('offers.show', $offer->id)}}">                        
                                    {{$offer->title}}
                                </a>
                            </td>
                            <td>{{$offer->user->firstname}}</td>
                            <td>{{$offer->created_at->diffForHumans()}}</td>
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