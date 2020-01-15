@extends('adminlte::page')

@section('content')
            <div class="panel panel-success">

                <div class="panel-heading" style="padding: 1%;">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>All Payment Methods</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('paymentmethods.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New paymentmethod</a>
                        </div>
                    </div>                
                </div>
                <div class="panel-body">

                        @if(count($paymentmethods) > 0)
                        <table class="table table-bordered" id="paymentmethods_table">
                            <thead>
                                <tr>
                                    {{-- <th>#ID</th> --}}
                                    <th>Name</th>
                                    <th>Created On</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                    @foreach ($paymentmethods as $paymentmethod)
                                        <tr>
                                        {{-- <td>{{$paymentmethod->id}}</td> --}}
                                        <td>{{$paymentmethod->name}}</td>
                                        <td>{{$paymentmethod->created_at->format('D jS, M, Y')}}</td>
                                        <td>
                                            {{App\User::find($paymentmethod->user_id)->firstname}}
                                            {{App\User::find($paymentmethod->user_id)->lastname}}
                                        </td>

                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <a href="{{route('paymentmethods.show', $paymentmethod->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View</a>
                                                </div>
                                                <div class="col-md-8">
                                                    <form action="{{route('paymentmethods.destroy', $paymentmethod->id)}}" method="POST">
                                                        {{csrf_field()}}{{method_field('DELETE')}}
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>

                                        </tr>
                                    @endforeach

                            </tbody>

                        </table>
                    @else
                        <h4>There are no payment methods</h4>
                    @endif

                </div>
            </div>
@endsection
