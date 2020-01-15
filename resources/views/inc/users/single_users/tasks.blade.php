<h4>User's Tasks</h4>
@if (count($user->tasks) > 0)
    <table class="table table-bordered" id="tasks_table">
        <thead>
            <th>#ID</th>
            <th>Name</th>
            <th>Deadline</th>
            <th>Status</th>
            <th>Doctor Assigned</th>
            <th>Created On</th>
        </thead>
        <tbody>
            @foreach ($user->tasks as $task)
                <tr>
                    <td>{{$task->id}}</td>
                    <td><a href="{{route('tasks.show', $task->id)}}">{{$task->name}}</a></td>
                    <td>{{$task->deadline}}</td>
                    <td>{{$task->status}}</td>
                    <td>{{$task->user ? $task->user->firstname.' '.$task->user->lastname : ''}}</td>
                    <td>{{$task->created_at->diffForHumans()}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    user has No tasks yet.
@endif