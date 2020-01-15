@extends('adminlte::page')

@section('content')

        <div class="panel panel-success">
                <div class="panel-heading">
                     <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>{{$file->user->firstname}} {{$file->user->lastname}}</strong>
                        </div>
                        <div class="col-md-4">
                            @if(auth()->user()->role_id != 5)
                            <a href="{{route('files.edit', $file->id)}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-pencil"></i>  Edit File</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    @include('inc.tabMenu', ['tabMenuPosition'=>4, 'patient_id'=>$file->user->id])
                    <div class="row">
                        <div class="col-md-4">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <strong>Name : </strong> {{$file->name}}
                                </li>
                                <li class="list-group-item">
                                    <strong>Patient Name : </strong> {{$file->user->firstname}} {{$file->user->lastname}}
                                </li>
                                 <li class="list-group-item">
                                    <strong>Created On : </strong> {{$file->created_at->format('g:i A D jS, M, Y')}}
                                </li>

                                 <li class="list-group-item">
                                    <strong>Created By : </strong> {{$file->creator->firstname}} {{$file->creator->lastname}}
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-8">
                            @if (@is_array(getimagesize('storage/'.$file->filename)))
                                <img src="{{asset('storage/'.$file->filename)}}" alt="file" width="500" height="350">
                            @else
                                <a href="{{asset('storage/'.$file->filename)}}" target="_blank">Document (Click to download)</a>
                            @endif
                        </div>
                    </div>
            </div>

         </div>

@endsection
