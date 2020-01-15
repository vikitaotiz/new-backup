@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Edit Tax</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('taxs.create')}}" class="btn btn-sm btn-primary">Create New tax</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('taxes.update', $tax->id)}}" method="post">

                                {{csrf_field()}} {{method_field('PUT')}}
                                
                                <div class="form-group">
                                    <label>Tax Name</label>
                                    <input type="text" name="name" class="form-control" value="{{$tax->name}}" placeholder="Enter tax Name...">
                                </div>
                                <div class="form-group">
                                    <label>Tax Rate (%)</label>
                                    <input type="number" class="form-control" name="rate" value="{{$tax->rate}}" placeholder="Enter Tax Rate...">
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Update Tax" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>
                </div>
            </div>
    
@endsection
