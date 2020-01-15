@extends('adminlte::page')

@section('content')
   
    <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>All Vital Notes</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('vitals.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New vital</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                        @if(count($vitals) > 0)
                        <table class="table table-bordered" id="vitals_table">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Created On</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($vitals as $vital)
                                        <tr>
                                        <td>
                                            {{$vital->user->firstname}}
                                            {{$vital->user->lastname}}
                                        </td>
                                        <td>{{$vital->created_at->format('D, M, jS, Y g:i A')}}</td>
                                        <td>{{App\User::find($vital->creator_id)->firstname}}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <a href="{{route('vitals.show', $vital->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View</a>
                                                </div>
                                                <div class="col-md-8">
                                                    <form action="{{route('vitals.destroy', $vital->id)}}" method="POST">
                                                        {{csrf_field()}}{{method_field('DELETE')}}
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    @else
                        <h4>There are no vitals</h4>
                    @endif
                </div>
            </div>
    
@endsection
