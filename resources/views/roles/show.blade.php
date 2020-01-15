@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                     <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>{{$role->name}}</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('roles.edit', $role->id)}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-pencil"></i>  Edit role</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                   
                        <p>{{$role->description}}</p>
            </div>
         </div>
    
@endsection
