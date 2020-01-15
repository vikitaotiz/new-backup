@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Edit Currency</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('roles.create')}}" class="btn btn-sm btn-primary">Create New Role</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('currencies.update', $currency->id)}}" method="post">
                                {{csrf_field()}} {{method_field('PUT')}}
                                <div class="form-group">
                                    <label>Currency Name</label>
                                    <input type="text" name="name" value="{{$currency->name}}" class="form-control" placeholder="Enter Currency Name...">
                                </div>
                                <div class="form-group">
                                    <label>Currency Symbol</label>
                                    <input type="text" name="symbol" value="{{$currency->symbol}}" class="form-control" placeholder="Enter Currency Symbol... e.g Kshs, USD etc">
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Update Currency" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>
                </div>
            </div>
    
@endsection
