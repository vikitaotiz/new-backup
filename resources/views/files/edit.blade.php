@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Edit Patient File</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('roles.create')}}" class="btn btn-sm btn-primary">Create New Role</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    @include('inc.tabMenu', ['tabMenuPosition'=>4, 'patient_id'=>$file->user_id])
                    <form method="post" action="{{route('files.update', $file->id)}}" enctype="multipart/form-data">
                        {{csrf_field()}} {{method_field('PATCH')}}
                        

                        <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                    <div class="form-group">
                                            <label>Select Patient</label>
                                            <select name="user_id" id="user_id" class="form-control">
                                                @foreach ($users as $user)
                                                    <option value="{{$user->id}}"
                                                        @if($user->id == $file->user_id)
                                                            selected
                                                        @endif>
                                                        DOB : {{$user->date_of_birth}}
                                                        - {{$user->firstname}} {{$user->firstname}}.
                                                        @if ($user->nhs_number)
                                                            NHS : {{$user->nhs_number}}
                                                        @endif
                                                        
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div><br>
                                <div class="row">                                
                                    <div class="col-md-12">
                                            <label>Name of the file.</label>
                                            <input type="text" name="name" class="form-control" value="{{$file->name}}" placeholder="Enter Document Name...">
                                    </div>
                                </div><br>

                                <div class="row">
                                    <div class="col-md-12">
                                        @if (@is_array(getimagesize('storage/'.$file->filename)))
                                            <img src="{{asset('storage/'.$file->filename)}}" alt="file" width="500" height="350">
                                        @else
                                            <a href="{{asset('storage/'.$file->filename)}}" target="_blank">Document (Click to download)</a>
                                        @endif
                                    </div>
                                </div><br>

                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="file" name="filename" class="form-control" accept="image/jpeg,image/jpg,image/gif,image/png,application/pdf,image/x-eps">
                                    </div>
                                </div><br>
                                <div class="row">
                                     <div class="form-group">
                                            <input type="submit" class="btn btn-sm btn-success btn-block" value="Submit File">
                                        </div>
                                </div>
                        </div>
                    </form>
            </div>
    
@endsection
