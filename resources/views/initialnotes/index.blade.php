@extends('adminlte::page')

@section('content')
   
    <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>All Initial Consultation Notes</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('initialnotes.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New consultation</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                        @if(count($consultations) > 0)
                        <table class="table table-bordered" id="consultations_table">
                            <thead>
                                <tr>
                                    <th>Patient name</th>
                                    <th>Presenting Complain</th>
                                    <th>Created On</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                    @foreach ($consultations as $consultation)
                                        <tr>
                                       <td>
                                            {{$consultation->user->firstname}}
                                            {{$consultation->user->lastname}}
                                        </td>
                                        <td>{!! str_limit($consultation->complain, $limit = 50, $end = '...') !!}</td>
                                        <td>{{$consultation->created_at->format('g:i A D jS, M, Y')}}</td>
                                        <td>{{App\User::find($consultation->creator_id)->firstname}}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <a href="{{route('initialnotes.show', $consultation->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View</a>
                                                </div>
                                                <div class="col-md-8">
                                                    <form action="{{route('initialnotes.destroy', $consultation->id)}}" method="POST">
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
                        <h4>There are no initial consultation notes</h4>
                    @endif
                </div>
            </div>
    
@endsection
