@extends('adminlte::page')

@section('content')
   
    <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>All Sick Note Letters</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('sicknotes.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New sicknote</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                        @if(count($sicknotes) > 0)
                        <table class="table table-bordered" id="sicknotes_table">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Created On</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                    @foreach ($sicknotes as $sicknote)
                                        <tr>
                                        <td>
                                            {{$sicknote->user->firstname}}
                                            {{$sicknote->user->lastname}}
                                        </td>
                                        <td>{{$sicknote->created_at->diffForHumans()}}</td>
                                        <td>{{App\User::find($sicknote->creator_id)->firstname}}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <a href="{{route('sicknotes.show', $sicknote->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View</a>
                                                </div>
                                                <div class="col-md-8">
                                                    <form action="{{route('sicknotes.destroy', $sicknote->id)}}" method="POST">
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
                        <h4>There are no sicknotes</h4>
                    @endif
                </div>
            </div>
    
@endsection
