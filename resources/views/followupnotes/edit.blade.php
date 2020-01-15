@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
                <div class="col-md-4">
                    <strong>Edit Follow up Consultation Note</strong>
                </div>
                <div class="col-md-4">
                    {{-- <a href="{{route('notes.create')}}" class="btn btn-sm btn-primary">Create New note</a> --}}
                </div>
            </div>
        </div>

        <div class="panel-body">

            @include('inc.tabMenu', ['tabMenuPosition'=>5, 'patient_id'=>$consultation->user_id])
            <form action="{{route('followupnotes.update', $consultation->id)}}" method="post">

                {{csrf_field()}} {{method_field('PATCH')}}

                <div style="padding: 1%;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Patient Name</label>
                                <select name="user_id" id="patient_id" class="form-control">
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}"
                                                @if ($user->id == $consultation->user_id)
                                                selected
                                                @endif>
                                            {{$user->firstname}} {{$user->lastname}} -
                                            DOB : {{$user->date_of_birth}} -
                                            NHS : {{$user->nhs_number}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Patient's Progress</label>
                                <textarea name="patient_progress" id="followupnote1" class="form-control"
                                          placeholder="Enter Patient's Progress...">{{$consultation->patient_progress}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Assessment</label>
                                <textarea name="assessment" id="followupnote2" class="form-control"
                                          placeholder="Enter assessment">{{$consultation->assessment}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Plan</label>
                                <textarea name="plan" id="followupnote3" class="form-control" 
                                placeholder="Enter plan">{{$consultation->plan}}</textarea>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="form-group">
                        <input type="submit" value="Update Follow Up Note" class="btn btn-sm btn-block btn-success">
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
