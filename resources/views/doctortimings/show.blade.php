@extends('adminlte::page')

@section('content')

        @if ($timing->status == 'active')
            <div class="panel panel-success">
        @else
        <div class="panel panel-danger">
        @endif
            {{-- <div class="panel panel-success"> --}}
                <div class="panel-heading">
                     <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>{{$timing->user->firstname}} {{$timing->user->lastname}}
                                is @if ($timing->status == 'active')
                                        <span style="text-decoration: underline;">Available.</span>
                                    @else
                                        <span style="text-decoration: underline;">Not Available.</span>
                                @endif
                            </strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('doctortimings.edit', $timing->id)}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-pencil"></i>  Edit Doctor Timing</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                <b>FROM</b> <a class="pull-right">{{$timing->from->format('D, jS, M, Y')}}</a>
                                </li>
                                <li class="list-group-item">
                                <b>TO</b> <a class="pull-right">{{$timing->to->format('D, jS, M, Y')}}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                   
            </div>
         </div>
    
@endsection
