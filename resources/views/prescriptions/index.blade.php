@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>All Prescriptions</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('prescriptions.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New prescription</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                        @if(count($prescriptions) > 0)
                        <table class="table table-bordered" id="prescriptions_table">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Created On</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                    @foreach ($prescriptions as $prescription)
                                        <tr>
                                        <td>
                                            {{$prescription->user->firstname}}
                                            {{$prescription->user->lastname}}
                                        </td>
                                        <td>{{$prescription->created_at->format('g:i A D jS, M, Y')}}</td>
                                        <td>
                                            {{App\User::find($prescription->creator_id)->firstname}}
                                            {{App\User::find($prescription->creator_id)->lastname}}
                                        </td>

                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <a href="{{route('prescriptions.show', $prescription->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View</a>
                                                </div>
                                                <div class="col-md-8">
                                                    <form action="{{route('prescriptions.destroy', $prescription->id)}}" method="POST">
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
                        <h4>There are no prescriptions</h4>
                    @endif

                </div>
            </div>

@endsection
