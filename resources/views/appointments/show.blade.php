@extends('adminlte::page')

@section('content')

    <div class="panel" style="background: {{$appointment->color}};">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-1">
                    <button onclick="goBack()" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>

                <div class="col-md-3">
                    @if ($appointment->progress == 'pending')
                        <strong style="color: white;">Current Progress: Pending</strong>
                    @elseif($appointment->progress == 'arrived')
                        <strong style="color: white;">Current Progress: Arrived</strong>
                    @elseif($appointment->progress == 'send_in')
                        <strong style="color: white;">Current Progress: Send In</strong>
                    @elseif($appointment->progress == 'left')
                        <strong style="color: white;">Current Progress: Left</strong>
                    @elseif($appointment->progress == 'did_not_attend')
                        <strong style="color: white;">Current Progress: Did Not Attend</strong>
                    @else
                        <strong style="color: white;">No Progress</strong>
                    @endif
                </div>
                <div class="col-md-4">
                    <strong style="color: #fff;">{{$appointment->name}}</strong>
                </div>
                <div class="col-md-4">
                    <a href="{{route('appointments.edit', $appointment->id)}}" class="btn btn-sm btn-default btn-block"><i
                                class="fa fa-pencil"></i> Edit appointment</a>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-tabs-custom">
        @include('inc.tabMenu', ['tabMenuPosition'=>3, 'patient_id'=>$appointment->user->id])
    </div>
    <section class="invoice" id="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    @if($appointment->doctor && count($appointment->doctor->companies))
                        @foreach($appointment->doctor->companies as $item)
                            @if($item->logo)
                                <img src="{{asset('/storage/'.$item->logo)}}" alt="Company Logo" width="50" height="30">
                            @else
                                <i class="fa fa-building"></i>
                            @endif
                                {{$item->name}}.
                        @endforeach
                    @else
                        @if($company->logo)
                            <img src="{{asset('/storage/'.$company->logo)}}" alt="Company Logo" width="50" height="30">
                        @else
                            <i class="fa fa-building"></i>
                        @endif
                        {{$company->name}}.
                    @endif

                    <small class="pull-right">Date: {{date('Y, M, jS, D')}}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <div>
                    {{-- <img src="{{asset('/storage/'.$company->logo)}}" alt="Company Logo" width="100" height="100"> --}}
                </div>
                From
                @if($appointment->doctor && count($appointment->doctor->companies))
                    @foreach($appointment->doctor->companies as $item)
                        <address>
                            <strong>{{$item->name}}.</strong><br>
                            Address : {{$item->address}}<br>
                            Phone: {{$item->phone}}<br>
                            Email: {{$item->email}}
                        </address>
                    @endforeach
                @else
                <address>
                    <strong>{{$company->name}}.</strong><br>
                    Address : {{$company->address}}<br>
                    Phone: {{$company->phone}}<br>
                    Email: {{$company->email}}
                </address>
                @endif
            </div>
            <div class="col-sm-4 invoice-col">
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong>
                        <a href="{{route('patients.show',$appointment->user_id)}}">
                            {{$appointment->user->firstname}} {{$appointment->user->lastname}}
                        </a>
                    </strong><br>
                    Address: {{$appointment->user->address ?? 'Not Set'}}<br>
                    Phone: {{$appointment->user->phone ?? 'Not Set'}}<br>
                    Email: {{$appointment->user->email ?? 'Not Set'}}
                </address>
            </div>

            <!-- /.col -->
        </div>
        <br>
        <hr>
        <div class="row" style="margin: 2%;">
            <strong>
                Appointment Note for
                {{$appointment->user->firstname}} {{$appointment->user->lastname}}
            </strong>
        </div>
        <hr>
        <div class="row" style="margin: 2%;">

            <table class="table table-bordered">
                <tr>
                    <th>Appointment Description:</th>
                    <td>{!! $appointment->description !!}</td>
                </tr>
                <tr>
                    <th>Appointment Status:</th>
                    <td>
                        @if ($appointment->status == 'close')
                            <strong>CLOSED</strong>
                        @else
                            <strong>OPEN</strong>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Appointment Date:</th>
                    <td>{{date('D, M j, Y g:i A', strtotime($appointment->appointment_date))}}</td>
                </tr>

                @if ($appointment->status == 'close')
                    <tr>
                        <th>Slot Duration (Hours) :</th>
                        <td>
                            {{Carbon\Carbon::parse($appointment->appointment_date)->diffInHours($appointment->end_time)}}</td>
                    </tr>
                @endif


                <tr>
                    <th>Created By:</th>
                    <td>{{$appointment->creator->firstname}}</td>
                </tr>

                <tr>
                    <th>Created On:</th>
                    <td>{{$appointment->created_at->format('Y M, jS, D,  g:i A')}}</td>
                </tr>
            </table>
            <p></p>
        </div>
        <hr>
        <!-- /.row -->


    </section>


    <div class="row no-print" style="padding: 2%;">
        <div class="col-xs-12">

            <button onclick="printContent('invoice')" type="button" target="_blank" class="btn btn-info"><i
                        class="fa fa-print"></i> Print
            </button>

            {{-- <button type="button" class="btn btn-success pull-right"><i class="fa fa-envelope"></i>
                Mail Letter
            </button> --}}

            @if ($appointment->status == 'open')
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#appointment_progress">
                    Change Appointment Status
                </button>
            @endif


            @if($appointment->status == 'open')

                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#update">
                    Close Appointment
                </button>

            @else
                <a href="{{route('open', $appointment->id)}}" class="btn btn-warning pull-right"
                   style="margin-right: 5px;">
                    <i class="fa fa-window-close-o"></i> Open Appointment
                </a>
            @endif

        </div>
    </div>



    <div class="modal modal-success fade" id="update">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Close Appointment</h4>
                </div>
                <div class="modal-body">
                    <form action="{{route('close', $appointment->id)}}" method="POST">
                        {{csrf_field()}}

                        <div class="form-group">
                            <label>Appointment Completed on : </label>
                            <input type="date" name="end_time" id="end_time" class="form-control"
                                   placeholder="Enter appointment ending time...">
                        </div>
                        <hr>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default btn-block" data-dismiss="modal">
                                        Cancel
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" class="btn btn-sm btn-warning btn-block"
                                           value="Close Appointment">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <div class="modal modal-success fade" id="appointment_progress">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Change Appointment Status. (
                        @if ($appointment->progress == 'pending')
                            <strong style="color: white;">Current Progress: Pending</strong>
                        @elseif($appointment->progress == 'arrived')
                            <strong style="color: white;">Current Progress: Arrived</strong>
                        @elseif($appointment->progress == 'send_in')
                            <strong style="color: white;">Current Progress: Send In</strong>
                        @elseif($appointment->progress == 'left')
                            <strong style="color: white;">Current Progress: Left</strong>
                        @elseif($appointment->progress == 'did_not_attend')
                            <strong style="color: white;">Current Progress: Did Not Attend</strong>
                        @else
                            <strong style="color: white;">No Progress</strong>
                        @endif
                )</h4>
                </div>
                <div class="modal-body">

                    <form action="{{route('appointment.progress', $appointment->id)}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Appointment Progress</label>
                            <select name="progress" id="progress" class="form-control">
                                <option value="pending">Pending</option>
                                <option value="arrived">Arrived</option>
                                <option value="send_in">Send In</option>
                                <option value="left">Left</option>
                                <option value="did_not_attend">Did Not Attend</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-sm btn-block btn-primary" value="Change Status">
                        </div>
                    </form>

                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection
