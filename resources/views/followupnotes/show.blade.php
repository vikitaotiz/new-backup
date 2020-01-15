@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
                <div class="col-md-4">
                    <strong>Patient Name :
                        {{$consultation->user->firstname}}
                        {{$consultation->user->lastname}}
                    </strong>
                </div>
                <div class="col-md-4">
                    <a href="{{route('followupnotes.edit', $consultation->id)}}"
                       class="btn btn-sm btn-primary btn-block"><i class="fa fa-pencil"></i> Edit consultation</a>
                </div>
            </div>
        </div>
    </div>
    @include('inc.tabMenu', ['tabMenuPosition'=>5, 'patient_id'=>$consultation->user_id])
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    @if($consultation->user->doctor && count($consultation->user->doctor->companies))
                        @foreach($consultation->user->doctor->companies as $item)
                            @if($item->logo)
                                <img src="{{asset('/storage/'.$item->logo)}}" alt="Company Logo" width="100" height="60">
                            @else
                                <i class="fa fa-building"></i>
                            @endif
                            {{$item->name}}.
                        @endforeach
                    @else
                        @if($consultation->company->logo)
                            <img src="{{asset('/storage/'.$consultation->company->logo)}}" alt="Company Logo" width="100" height="60">
                        @else
                            <i class="fa fa-building"></i>
                        @endif
                        {{$consultation->company->name}}.
                    @endif
                    <small class="pull-right">Date: {{date('d/m/Y')}}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    @if($consultation->user->doctor && count($consultation->user->doctor->companies))
                        @foreach($consultation->user->doctor->companies as $item)
                            <strong>{{$item->name}}</strong><br>
                            Address: {{$item->address}}<br>
                            Phone: {{$item->phone}}<br>
                            Email: {{$item->email}}
                        @endforeach
                    @else
                        <strong>{{$consultation->company->name}}</strong><br>
                        Address: {{$consultation->company->address}}<br>
                        Phone: {{$consultation->company->phone}}<br>
                        Email: {{$consultation->company->email}}
                    @endif
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong>
                        <a href="{{route('patients.show', $consultation->user->id)}}">
                            {{$consultation->user->firstname}}
                            {{$consultation->user->lastname}}
                        </a>
                    </strong><br>
                    Address: {{$consultation->user->address}}<br>
                    Phone: {{$consultation->user->phone}}<br>
                    Email: {{$consultation->user->email}}
                </address>
            </div>

            <!-- /.col -->
        </div>
        <br>
        <hr>
        <strong>RE : Follow Up Consultation Note.</strong>
        <div class="row" style="margin: 2%;" id="invoice">

            <div class="col-md-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Patient Progress
                    </div>
                    <div class="panel-body">
                        {!! $consultation->patient_progress !!}
                    </div>
                </div>
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Patient Assessment
                    </div>
                    <div class="panel-body">
                        {!! $consultation->assessment !!}
                    </div>
                </div>
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Plan
                    </div>
                    <div class="panel-body">
                        {!! $consultation->plan !!}
                    </div>
                </div>
            </div>


        </div>
        <hr>
    </section>
    <!-- /.row -->
    <div class="row no-print" style="padding: 2%;">
        <div class="col-xs-12">
            <button onclick="printContent('invoice')" type="button" target="_blank" class="btn btn-info"><i
                        class="fa fa-print"></i> Print
            </button>

            {{-- <button type="button" class="btn btn-success pull-right"><i class="fa fa-envelope"></i>
                Mail Letter
            </button> --}}

        </div>
    </div>



@endsection
