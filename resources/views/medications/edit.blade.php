@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Edit medication</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('medications.create')}}" class="btn btn-sm btn-primary">Create New medication</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    
                        <form action="{{route('medications.update', $medication->id)}}" method="post">
                                {{csrf_field()}} {{method_field('PUT')}}
                                <div class="form-group">
                                    <label>Medication Name</label>
                                    <input type="text" name="name" class="form-control" value="{{$medication->name}}" placeholder="Enter medication Name...">
                                </div>
                                <div class="form-group">
                                    <label>Medication Description</label>
                                    <textarea name="description" class="form-control" placeholder="Enter Medication Description...">{{$medication->description}}</textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Update Medication" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>

                </div>
            </div>
    
@endsection
