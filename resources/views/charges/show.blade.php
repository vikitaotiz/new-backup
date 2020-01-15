@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                     <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>{{$charge->name}}</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('charges.edit', $charge->id)}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-pencil"></i>  Edit charge</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                    <strong>Charges per hour : {{$charge->amount}}</strong>
                   
                    <p>{{$charge->description}}</p>
                    
            </div>
         </div>
    
@endsection
