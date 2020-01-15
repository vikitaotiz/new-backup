@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Edit Role</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('roles.create')}}" class="btn btn-sm btn-primary">Create New Role</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    
                        <form action="{{route('roles.update', $role->id)}}" method="post">
                                {{csrf_field()}} {{method_field('PUT')}}
                                <div class="form-group">
                                    <label>Role Name</label>
                                    <input type="text" name="name" class="form-control" value="{{$role->name}}" placeholder="Enter Role Name...">
                                </div>
                                <div class="form-group">
                                    <label>Role Description</label>
                                    <textarea name="description" class="form-control" placeholder="Enter Role Description...">{{$role->description}}</textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Submit Role" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>

                </div>
            </div>
    
@endsection
