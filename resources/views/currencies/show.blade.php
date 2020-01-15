@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                     <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>{{$currency->name}}</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('currencies.edit', $currency->id)}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-pencil"></i>  Edit Currency</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                   
                        <p><strong>Currency Symbol:</strong> {{$currency->symbol}}</p>
            </div>
         </div>
    
@endsection
