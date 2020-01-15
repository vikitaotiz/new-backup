<table class="table table-bordered" id="tasks_table">
    <thead>
    <tr>
        <th>Patient Name</th>
        <th>Patient Email</th>
        <th>Name</th>
        <th>Description</th>
        <th>Deadline</th>
        <th>Status</th>
        <th>Doctor Assigned</th>
        <th>Doctor Id</th>
        <th>User Id</th>
        <th>Created On</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($tasks as $task)
        @if(isset($task->user))
            @if($task->user != null)
                @if(isset($task->user->email))
                    <?php $doctor = App\User::find($task->doctor_id); ?>
                    <tr>
                        <td>{{ $task->user->firstname . $task->user->lastname }}</td>
                        <td>{{ $task->user->email }}</td>
                        <td><a href="{{route('tasks.show', $task->id)}}">{{$task->name}}</a></td>
                        <td>{{$task->description}}</td>
                        <td>{{$task->deadline}}</td>
                        <td>{{$task->status}}</td>
                        <td>{{isset($doctor) && $doctor != null ? $doctor->firstname : ''}}</td>
                        <td>{{$task->doctor_id}}</td>
                        <td>{{$task->user_id}}</td>
                        <td>{{isset($task->created_at) && $task->created_at != null ? $task->created_at->diffForHumans() : ''}}</td>
                    </tr>
                @endif
            @endif
        @endif
    @endforeach
    </tbody>
</table>

