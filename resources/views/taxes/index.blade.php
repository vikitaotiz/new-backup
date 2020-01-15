@extends('adminlte::page')

@section('content')
   
    <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>All taxs</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('taxes.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New tax</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                        @if(count($taxes) > 0)
                        <table class="table table-bordered" id="taxes_table">
                            <thead>
                                <tr>
                                    {{-- <th>#ID</th> --}}
                                    <th>Name</th>
                                    <th>Rate</th>
                                    <th>Created On</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                    @foreach ($taxes as $tax)
                                        <tr>
                                        {{-- <td>{{$tax->id}}</td> --}}
                                        <td>{{$tax->name}}</td>
                                        <td>{{$tax->rate}} %</td>
                                        <td>{{$tax->created_at->format('Y, M, D')}}</td>
                                        <td>{{App\User::find($tax->user_id)->name}}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <a href="{{route('taxes.show', $tax->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View</a>
                                                </div>
                                                <div class="col-md-8">
                                                    <form action="{{route('taxes.destroy', $tax->id)}}" method="POST">
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
                        <h4>There are no taxs</h4>
                    @endif
                </div>
            </div>
    
@endsection
