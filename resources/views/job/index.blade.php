@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
                <div class="col-md-4 text-center">
                    <strong>All SMS Scheduler</strong>
                </div>
                <div class="col-md-4">
                    <a href="{{route('jobs.create')}}" class="pull-right btn btn-sm btn-success"><i class="fa fa-plus"></i> Create</a>
                </div>
            </div>
        </div>

        <div class="panel-body">

            @if (count($jobs) > 0)
                <table class="table table-bordered">
                    <thead>
                    <th width="40%">Template</th>
                    <th>Business</th>
                    {{--<th>Patient ids</th>
                    <th>Doctor ids</th>--}}
                    <th>Reminder period</th>
                    <th>Reminder time</th>
                    <th>Default Reminder type</th>
                    <th>Created at</th>
                    <th>Actions</th>
                    </thead>
                    <tbody>
                    @foreach ($jobs as $job)
                        <tr>
                            <td>{{ $job->template }}</td>
                            <td>@if($job->company){{ $job->company->name }}@endif</td>
                            {{--<td>@if($job->users){{ $job->users->pluck('id') }}@endif</td>
                            <td>@if($job->doctors){{ $job->doctors->pluck('id') }}@endif</td>--}}
                            <td>{{ $job->reminder_period }} day(s)</td>
                            <td>{{ $job->reminder_time_from }} - {{ $job->reminder_time_to }}</td>
                            <td>
                                @if($job->reminder_type === 0)
                                    None
                                @elseif($job->reminder_type == 1)
                                    SMS
                                @elseif($job->reminder_type == 2)
                                    SMS & Email
                                @endif
                            </td>
                            <td>{{ $job->created_at->diffForHumans() }}</td>
                            <td>
                                <form action="{{route('jobs.destroy', $job->id)}}" method="POST">
                                    {{csrf_field()}} {{method_field('DELETE')}}
                                    <a href="{{ route('jobs.edit', $job->id) }}" type="submit" class="btn btn-sm btn-warning" ><i class="fa fa-edit"></i> Edit</a>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                There are no sms cron.
            @endif

        </div>
    </div>

@endsection
