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
                        {{$vital->user->firstname}}
                        {{$vital->user->lastname}}
                    </strong>
                </div>
                <div class="col-md-4">
                    <a href="{{route('vitals.edit', $vital->id)}}" class="btn btn-sm btn-primary btn-block"><i
                                class="fa fa-pencil"></i> Edit vital</a>
                </div>
            </div>
        </div>
    </div>
    @include('inc.tabMenu', ['tabMenuPosition'=>5, 'patient_id'=>$vital->user_id])
    <section class="invoice" id="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    @if($vital->user->doctor && count($vital->user->doctor->companies))
                        @foreach($vital->user->doctor->companies as $item)
                            @if($item->logo)
                                <img src="{{asset('/storage/'.$item->logo)}}" alt="Company Logo" width="50" height="30">
                            @else
                                <i class="fa fa-building"></i>
                            @endif
                            {{$item->name}}.
                        @endforeach
                    @else
                        @if($vital->company->logo)
                            <img src="{{asset('/storage/'.$consultation->company->logo)}}" alt="Company Logo" width="50" height="30">
                        @else
                            <i class="fa fa-building"></i>
                        @endif
                        {{$vital->company->name}}.
                    @endif

                    <small class="pull-right">Date: {{date('D, M, jS, Y')}}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    @if($vital->user->doctor && count($vital->user->doctor->companies))
                        @foreach($vital->user->doctor->companies as $item)
                            <strong>{{$item->name}}</strong><br>
                            Address: {{$item->address}}<br>
                            Phone: {{$item->phone}}<br>
                            Email: {{$item->email}}
                        @endforeach
                    @else
                        <strong>{{$vital->company->name}}</strong><br>
                        Address: {{$vital->company->address}}<br>
                        Phone: {{$vital->company->phone}}<br>
                        Email: {{$vital->company->email}}
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
                        <a href="{{route('patients.show', $vital->user->id)}}">
                            {{$vital->user->firstname}}
                            {{$vital->user->lastname}}
                        </a>
                    </strong><br>
                    Address: {{$vital->user->address}}<br>
                    Phone: {{$vital->user->phone}}<br>
                    Email: {{$vital->user->email}}
                </address>
            </div>

            <!-- /.col -->
        </div>
        <br>
        <hr>

        <div class="row" style="margin: 2%;">
            <table class="table table-bordered">
                <tr>
                    <th>Temperature:</th>
                    <td>{{$vital->temperature}}</td>
                </tr>

                <tr>
                    <th>Pulse Rate:</th>
                    <td>{{$vital->pulse_rate}}</td>
                </tr>

                <tr>
                    <th>Systolic BP:</th>
                    <td>{{$vital->systolic_bp}}</td>
                </tr>

                <tr>
                    <th>Diastolic BP:</th>
                    <td>{{$vital->diastolic_bp}}</td>
                </tr>

                <tr>
                    <th>Respiratory Rate:</th>
                    <td>{{$vital->respiratory_rate}}</td>
                </tr>

                <tr>
                    <th>Oxygen Saturation:</th>
                    <td>{{$vital->oxygen_saturation}}</td>
                </tr>

                <tr>
                    <th>O2 Administered:</th>
                    <td>{{$vital->o2_administered}}</td>
                </tr>

                <tr>
                    <th>Pain:</th>
                    <td>{{$vital->pain}}</td>
                </tr>

                <tr>
                    <th>Head Circumference:</th>
                    <td>{{$vital->head_circumference}}</td>
                </tr>

                <tr>
                    <th>Height:</th>
                    <td>{{$vital->height}}</td>
                </tr>

                <tr>
                    <th>Weight:</th>
                    <td>{{$vital->weight}}</td>
                </tr>

                <tr>
                    <th>Created On:</th>
                    <td>{{$vital->created_at->format('D, M, jS, Y g:i A')}}</td>
                </tr>
            </table>
        </div>
        <hr>

    </section>
    <!-- /.row -->
    <div class="row no-print" style="padding: 2%;">
        <div class="col-xs-12">
            <button onclick="printContent('invoice')" type="button" target="_blank" class="btn btn-default"><i
                        class="fa fa-print"></i> Print
            </button>
            {{-- <button type="button" class="btn btn-success pull-right"><i class="fa fa-envelope"></i>
                Mail Letter
            </button> --}}

        </div>
    </div>



@endsection
