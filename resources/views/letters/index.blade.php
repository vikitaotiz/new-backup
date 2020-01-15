@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>All Letters</strong>
                        </div>
                        <div class="col-md-4">
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
                                            <a href="{{route('prescriptions.index')}}">Prescription Letters</a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{route('prescriptions.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New Prescription Letter</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    @if($prescriptions > 0)
                                        <h4>{{$prescriptions}}</h4>
                                    @else
                                       There are no Prescription letters yet.
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
                                            <a href="{{route('referrals.index')}}">Referral Letters</a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{route('referrals.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New Referral Letter</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    @if($referrals > 0)
                                        <h4>{{$referrals}}</h4>
                                    @else
                                       There are no referral letters yet.
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
                                           <a href="{{route('sicknotes.index')}}">Sick Note Letters</a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{route('sicknotes.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New Sick Note Letter</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                   @if($sicknotes > 0)
                                        <h4>{{$sicknotes}}</h4>
                                    @else
                                       There are no sick note letters yet.
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
                                           <a href="{{route('certificates.index')}}">Certificate Letters</a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{route('certificates.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New Certificate Letter</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    @if($certificates > 0)
                                        <h4>{{$certificates}}</h4>
                                    @else
                                       There are no Certificate Letters yet.
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
@endsection
