@extends('adminlte::page')

@section('content')
   
    <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>All Currencies</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('currencies.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New currencie</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                        @if(count($currencies) > 0)
                        <table class="table table-bordered" id="currencies_table">
                            <thead>
                                <tr>
                                    {{-- <th>#ID</th> --}}
                                    <th>Name</th>
                                    <th>Symbol</th>
                                    <th>Created On</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                    @foreach ($currencies as $currency)
                                        <tr>
                                        {{-- <td>{{$currency->id}}</td> --}}
                                        <td>{{$currency->name}}</td>
                                        <td>{{$currency->symbol}}</td>
                                        <td>{{$currency->created_at->format('Y, M, D')}}</td>
                                        <td>{{App\User::find($currency->user_id)->firstname}}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <a href="{{route('currencies.show', $currency->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View</a>
                                                </div>
                                                <div class="col-md-8">
                                                    <form action="{{route('currencies.destroy', $currency->id)}}" method="POST">
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
                        <h4>There are no currencies</h4>
                    @endif
                </div>
            </div>
    
@endsection
