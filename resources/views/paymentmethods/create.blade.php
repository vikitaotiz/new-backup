@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Create New Payment Method</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('roles.create')}}" class="btn btn-sm btn-primary">Create New Role</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('paymentmethods.store')}}" method="post">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <label>Payment Methods</label>
                                    <input type="text" name="name" class="form-control" value="{{old('name')}}" placeholder="Enter Payment Method e.g Cash, Bank...">
                                </div>
                                <div class="form-group">
                                    <label>Payment Method Details</label>
                                    <textarea name="details" class="form-control" value="{{old('details')}}" 
                                    placeholder="Cash Details Or For Bank, Type Bank Name and Branch, Account No. and Code..."></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Create Payment Method" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>
                </div>
            </div>
    
@endsection
