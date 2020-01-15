@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>All Notes</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('notes.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New note</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <a href="{{route('initialnotes.index')}}">Initial Consultation Notes</a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{route('initialnotes.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New Initial Consultation Note</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    @if($initialnotes > 0)
                                        <h4>No. of Notes : {{$initialnotes}}</h4>
                                    @else
                                       There are no initial consultation notes yet.
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-md-8">
                                           <a href="{{route('followupnotes.index')}}">Follow up Consultation Notes</a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{route('followupnotes.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New Follow Up Consultation Note</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                   @if($followupnotes > 0)
                                        <h4>{{$followupnotes}}</h4>
                                    @else
                                       There are no follow up consultation notes yet.
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-md-8">
                                           <a href="{{route('vitals.index')}}">Vitals Notes</a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{route('vitals.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New Vitals Note</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    @if($vitals > 0)
                                        <h4>{{$vitals}}</h4>
                                    @else
                                       There are no vital notes yet.
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

@endsection
