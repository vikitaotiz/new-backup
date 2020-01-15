@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Create New Service</strong>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('services.store')}}" method="post">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <label>Service Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter Service Name...">
                                </div>
                                <div class="form-group">
                                    <label>Service Description</label>
                                    <textarea name="description" class="form-control" placeholder="Enter Service Description..."></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Submit Service" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>
                </div>
            </div>
    
@endsection
