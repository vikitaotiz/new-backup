@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Create New Doctor Timing.</strong>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    
                        <form action="{{route('doctortimings.store')}}" method="post" style="padding: 1%;">
                                {{csrf_field()}}
                                <div class="row">
                                    <label>Select Doctor</label>
                                    <select name="user_id" id="user_id">
                                        @foreach ($users as $user)
                                            <option value="{{$user->id}}">{{$user->firstname}} {{$user->lastname}}</option>
                                        @endforeach
                                    </select>
                                </div><hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <Label>From</Label>
                                        <input type="text" name="from" id="timing_from" placeholder="Enter From date" class="form-control" value="{{old('from')}}">
                                    </div>

                                    <div class="col-md-6">
                                        <Label>To</Label>
                                        <input type="text" name="to" id="timing_to" placeholder="Enter To date" class="form-control" value="{{old('to')}}">
                                    </div>
                                </div><hr>

                                <div class="row">
                                    <Label>Status</Label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div><hr>

                                <div class="row">
                                    <input type="submit" value="Submit Doctor Timing" class="btn btn-sm btn-success btn-block">
                                </div>
                            </form>
                </div>
            </div>
    
@endsection
