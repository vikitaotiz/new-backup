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
                    <strong>Create Follow up Consultations Note {{$user->firstname}} {{$user->lastname}}</strong>
                </div>
                <div class="col-md-4">
                    {{-- <a href="{{route('notes.create')}}" class="btn btn-sm btn-primary">Create New note</a> --}}
                </div>
            </div>
        </div>

        <div class="panel-body">
            @include('inc.tabMenu', ['tabMenuPosition'=>5, 'patient_id'=>$user->id])
            <form action="{{route('patient_store_followupnote.store')}}" method="post">

                {{csrf_field()}}

                <input type="hidden" name="user_id" value="{{$user->id}}">

                <div style="padding: 1%;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Patient Name</label>
                                <select name="user_id" id="patient_id" class="form-control" disabled>
                                    <option value="{{$user->id}}">
                                        {{$user->firstname}} {{$user->lastname}} -
                                        DOB : {{$user->date_of_birth}} -
                                        NHS : {{$user->nhs_number}}
                                    </option>
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
                                          placeholder="Enter Patient's Progress...">{{old('patient_progress')}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Assessment</label>
                                <textarea name="assessment" id="followupnote2" class="form-control"
                                          placeholder="Enter assessment">{{old('assessment')}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Plan</label>
                                <textarea name="plan" id="followupnote3" class="form-control"
                                          placeholder="Enter plan">{{old('plan')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="form-group">
                        <input type="submit" value="Create Follow Up Note" class="btn btn-sm btn-block btn-success">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
