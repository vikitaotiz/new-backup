@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Create New Treatment Charge</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('charges.create')}}" class="btn btn-sm btn-primary">Create New charge</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('charges.store')}}" method="post">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Treatment Charge Name</label>
                                            <input type="text" name="name" class="form-control" placeholder="Enter charge Name...">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Charge Amount</label>
                                            <input type="number" name="amount" class="form-control" placeholder="Enter charge amount...">
                                        </div>
                                    </div>
                                </div><hr>
                                
                                <div class="form-group">
                                    <label>Charge Description</label>
                                    <textarea name="description" class="form-control" placeholder="Enter charge Description..."></textarea>
                                </div> <hr>
                                <div class="form-group">
                                    <input type="submit" value="Submit charge" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>
                </div>
            </div>
    
@endsection
