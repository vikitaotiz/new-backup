@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>All Doctors Timings</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('doctortimings.create')}}" class="btn btn-sm btn-primary btn-block">
                                <i class="fa fa-plus"></i> Create New Doctor Timing</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                        @if(count($timings) > 0)
                        <table class="table table-bordered" id="timings_table">
                            <thead>
                                <tr>
                                    {{-- <th>#ID</th> --}}
                                    <th>Staff/Doctor Name</th>
                                    <th>Status</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Created On</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                    @foreach ($timings as $timing)
                                        <tr>
                                        {{-- <td>{{$timing->id}}</td> --}}
                                        <td>{{$timing->user->firstname}} {{$timing->user->lastname}}</td>
                                        <td>
                                            @if ($timing->status == 'active')
                                                <span style="text-decoration: underline;">Available.</span>
                                            @else
                                                    <span style="text-decoration: underline;">Not Available.</span>
                                            @endif
                                        </td>
                                        <td>{{$timing->from->format('D, jS, M, Y')}}</td>
                                        <td>{{$timing->to->format('D, jS, M, Y')}}</td>
                                        <td>{{$timing->created_at->format('D, jS, M, Y')}}</td>
                                        <td>
                                            {{$timing->creator->firstname}}
                                            {{$timing->creator->lastname}}
                                        </td>

                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <a href="{{route('doctortimings.show', $timing->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View</a>
                                                </div>
                                                <div class="col-md-8">
                                                    <form action="{{route('doctortimings.destroy', $timing->id)}}" method="POST">
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
                        <h4>There are no doctors timings</h4>
                    @endif

                </div>
            </div>

@endsection
