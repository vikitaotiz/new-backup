@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-1">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{route('tasks.index', ['patient_id' => $task->user->id])}}" class="btn btn-sm btn-info"><i class="fa fa-list"></i> Task List</a>
                </div>
                <div class="col-md-4">
                    <strong>{{$task->name}}</strong>
                </div>
                <div class="col-md-4">
                    <a href="{{route('tasks.edit', $task->id)}}" class="btn btn-sm btn-primary btn-block"><i
                                class="fa fa-pencil"></i> Edit task</a>
                </div>
            </div>
        </div>
    </div>
    @include('inc.tabMenu', ['tabMenuPosition'=>8, 'patient_id'=>$task->user_id])
    <section class="invoice" id="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    @if($task->user->doctor && count($task->user->doctor->companies))
                        @foreach($task->user->doctor->companies as $item)
                            <i class="fa fa-globe"></i> {{$item->name}}.
                        @endforeach
                    @else
                        <i class="fa fa-globe"></i> {{$company->name}}.
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
                    @if($task->user->doctor && count($task->user->doctor->companies))
                        @foreach($task->user->doctor->companies as $item)
                            <strong>{{$item->name}}</strong><br>
                            Address: {{$item->address}}<br>
                            Phone: {{$item->phone}}<br>
                            Email: {{$item->email}}
                        @endforeach
                    @else
                        <strong>{{$company->name}}.</strong><br>
                        Address : {{$company->address}}<br>
                        Phone: {{$company->phone}}<br>
                        Email: {{$company->email}}
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
                        {{App\User::find($task->user_id)->firstname}}
                        {{App\User::find($task->user_id)->lastname}}
                    </strong><br>
                    Address: {{App\User::find($task->user_id)->address}}<br>
                    Phone: {{App\User::find($task->user_id)->phone}}<br>
                    Email: {{App\User::find($task->user_id)->email}}
                </address>
            </div>

            <!-- /.col -->
        </div>
        <br>
        <hr>
        <div class="row" style="margin: 2%;">
            <strong>
                Task Note for
                {{App\User::findOrfail($task->user_id)->firstname}}
                {{App\User::findOrfail($task->user_id)->lastname}}
            </strong>
        </div>
        <hr>
        <div class="row" style="margin: 2%;">

            <table class="table table-bordered">
                <tr>
                    <th>Task Description:</th>
                    <td>{!! $task->description !!}</td>
                </tr>
                <tr>
                    <th>Task Status:</th>
                    <td>
                        @if ($task->status == 'open')
                            <strong>OPEN</strong>
                        @else
                            <strong>CLOSED</strong>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Due Date:</th>
                    <td>{{$task->deadline->format('D jS, M, Y')}}</td>
                </tr>
                <tr>
                    <th>Created By:</th>
                    <td>{{App\User::findOrfail($task->doctor_id)->firstname}}</td>
                </tr>

                <tr>
                    <th>Created On:</th>
                    <td>{{$task->created_at->format('D, M, jS, Y g:i A')}}</td>
                </tr>
            </table>
            <p></p>
        </div>

    </section>
    <!-- /.row -->
    <div class="row no-print" style="padding: 2%;">
        <div class="col-xs-12">
            <button onclick="printContent('invoice')" type="button" target="_blank" class="btn btn-default"><i
                        class="fa fa-print"></i> Print
            </button>

            {{-- <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a> --}}
            {{-- <button type="button" class="btn btn-success pull-right"><i class="fa fa-envelope"></i>
                Mail Letter
            </button> --}}

            @if($task->status == 'open')

                <a href="{{route('close_task', $task->id)}}" class="btn btn-warning pull-right"
                   style="margin-right: 5px;">
                    <i class="fa fa-window-close-o"></i> Close Task
                </a>

            @else
                <a href="{{route('open_task', $task->id)}}" class="btn btn-warning pull-right"
                   style="margin-right: 5px;">
                    <i class="fa fa-window-close-o"></i> Open Task
                </a>
            @endif
        </div>
    </div>



@endsection
