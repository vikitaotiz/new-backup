@extends('adminlte::page')

@section('content')
   
    <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Open Tasks</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('tasks.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New task</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                        @if(count($tasks) > 0)
                        <table class="table table-bordered" id="tasks_table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>User Assigned</th>
                                    <th>Created On</th>
                                    {{-- <th>Created By</th> --}}
                                    <th>Staff Assigned</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($tasks as $task)
                                    <tr>
                                        <td>{{$task->name}}</td>
                                        <td>
                                            @if ($task->status == 'open')
                                               <strong>OPEN</strong>
                                            @else
                                                <strong>CLOSED</strong>
                                            @endif
                                        </td>
                                        <td>
                                            {{App\User::find($task->user_id)->firstname ?? 'N/A'}}
                                            {{App\User::find($task->user_id)->lastname ?? 'N/A'}}
                                        </td>
                                        <td>{{$task->created_at->format('D, M, jS, Y g:i A')}}</td>
                                        {{-- <td>{{App\User::find($task->user_id)->name ?? 'N/A'}}</td> --}}
                                        <td>{{App\User::find($task->doctor_id)->firstname ?? 'N/A'}}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <a href="{{route('tasks.show', $task->id)}}" class="btn btn-primary btn-sm">
                                                            <i class="fa fa-edit"></i> View</a>
                                                </div>
                                                <div class="col-md-8">
                                                    <form action="{{route('tasks.destroy', $task->id)}}" method="POST">
                                                        {{csrf_field()}}{{method_field('DELETE')}}
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you Sure?')">
                                                                <i class="fa fa-trash"></i> Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                     </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    @else
                        <h4>There are no tasks</h4>
                    @endif
    
                </div>
            </div>
    
@endsection
