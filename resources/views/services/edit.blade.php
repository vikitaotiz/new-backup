@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Edit Service</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('services.create')}}" class="btn btn-sm btn-primary">Create New service</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    
                        <form action="{{route('services.update', $service->id)}}" method="post">
                                {{csrf_field()}} {{method_field('PUT')}}
                                <div class="form-group">
                                    <label>Service Name</label>
                                    <input type="text" name="name" class="form-control" value="{{$service->name}}" placeholder="Enter Service Name...">
                                </div>
                                <div class="form-group">
                                    <label>Service Description</label>
                                    <textarea name="description" class="form-control" placeholder="Enter Service Description...">{{$service->description}}</textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Update Service" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>

                </div>
            </div>
    
@endsection
