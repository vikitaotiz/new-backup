@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
                <div class="col-md-4 text-center">
                    <strong>All SMS</strong>
                </div>
                <div class="col-md-4">

                </div>
            </div>
        </div>

        <div class="panel-body">

            @if (count($allSms) > 0)
                <table class="table table-bordered">
                    <thead>
                    <th>Patient</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Message</th>
                    <th>Sent at</th>
                    <th>Actions</th>
                    </thead>
                    <tbody>
                    @foreach ($allSms as $sms)
                        <tr>
                            <td>@if(isset($sms->user)){{ $sms->user->firstname ?? '' }} {{ $sms->user->lastname ?? '' }}@endif</td>
                            <td>{{ $sms->from }}</td>
                            <td>{{ $sms->to }}</td>
                            <td>{{ $sms->message }}</td>
                            <td>{{ $sms->created_at->diffForHumans() }}</td>
                            <td>
                                <form action="{{route('messages.destroy', $sms->id)}}" method="POST">
                                    {{csrf_field()}} {{method_field('DELETE')}}
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                There are no sms.
            @endif

        </div>
    </div>

@endsection
