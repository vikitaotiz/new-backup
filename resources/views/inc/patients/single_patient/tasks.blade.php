<div class="row">
        <div class="col-md-6">
            <h4>Patient's Tasks</h4>
        </div>
        <div class="col-md-6">
            {{-- <a href="{{route('tasks.create')}}" class="btn btn-success btn-sm">Create New Task</a> --}}
            <a href="{{route('patienttask.create', $patient->id)}}" class="btn btn-success btn-sm">Create New Task</a>
        </div>
    </div>

    @if (count($patient->tasks) > 0)
        <table class="table table-bordered" id="tasks_table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Doctor Assigned</th>
                <th>Created On</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($patient->tasks as $task)
                    <tr>
                        <td>
                            {{-- <a href="{{route('tasks.show', $task->id)}}">{{$task->name}}</a> --}}
                            <a data-toggle="collapse" href="#collapseTask{{$task->id}}" aria-expanded="false" aria-controls="collapseExample">
                                    {{$task->name}}</a>
                            
                                    <div class="collapse" id="collapseTask{{$task->id}}">
                                        <div class="mt-5">
                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <strong>{{$task->name}}</strong>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <a href="{{route('tasks.show', $task->id)}}">Click To View More</a>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <a href="{{route('tasks.edit', $task->id)}}">Click To Edit Task Details</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel-body">
                                                    <table class="table">
                                                        <tr>
                                                            <th>Task Description: </th>
                                                            <td>{!! $task->description !!}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Task Status: </th>
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
                                                            <td>
                                                                {{$task->doctor ? $task->doctor->firstname .' '. $task->doctor->lastname : ''}}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="panel-footer">
                                                     {{$task->created_at->format('D jS, M, Y')}}
                                                </div>
                                            </div>
                                        </div>
                                      </div> 
                        </td>
                        <td>{{$task->deadline->format('D, jS, M, Y')}}</td>
                        <td>{{$task->status}}</td>
                        <td>{{$task->doctor ? $task->doctor->firstname .' '. $task->doctor->lastname : ''}}</td>
                        <td>{{$task->created_at->diffForHumans()}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        Patient has No tasks yet.
    @endif
