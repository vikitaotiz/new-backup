@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Edit Payment Method</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('roles.create')}}" class="btn btn-sm btn-primary">Create New Role</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('paymentmethods.update', $paymentmethod->id)}}" method="post">
                                
                                {{csrf_field()}} {{method_field('PATCH')}}

                                <div class="form-group">
                                    <label>Payment Methods</label>
                                    <input type="text" name="name" class="form-control" value="{{$paymentmethod->name}}" placeholder="Enter Payment Method e.g Cash, Bank...">
                                </div>
                                <div class="form-group">
                                    <label>Payment Method Details</label>
                                    <textarea name="details" class="form-control" placeholder="Enter Role Details...">
                                        {{$paymentmethod->details}}
                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Update Payment Method" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>
                </div>
            </div>
    
@endsection
