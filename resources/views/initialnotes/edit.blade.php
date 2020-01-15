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
                    <strong>Edit Initial Consultations Note</strong>
                </div>
                <div class="col-md-4">
                    {{-- <a href="{{route('notes.create')}}" class="btn btn-sm btn-primary">Create New note</a> --}}
                </div>
            </div>
        </div>

        <div class="panel-body">
            @include('inc.tabMenu', ['tabMenuPosition'=>5, 'patient_id'=>$consultation->user_id])
            <form action="{{route('initialnotes.update', $consultation->id)}}" method="post">

                {{csrf_field()}} {{method_field('PATCH')}}
                <div style="padding: 2%;">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Patient Name</label>
                                <select name="user_id" id="patient_id" class="form-control">
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}"
                                                @if($user->id == $consultation->user_id)
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Presenting Complaint</label>
                                <textarea name="complain" class="form-control" id="initialnote"
                                          placeholder="Enter main complaint...">{{$consultation->complain}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>History of Presenting Complaint</label>
                            <textarea name="history_presenting_complaint" class="form-control" id="initialnote1"
                                      placeholder="Enter subjective history...">{{$consultation->history_presenting_complaint}}</textarea>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Past Medical History</label>
                            <textarea name="past_medical_history" class="form-control" id="initialnote2"
                                      placeholder="Enter medical and surgical history...">{{$consultation->past_medical_history}}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Family History</label>
                            <textarea name="family_history" class="form-control" id="initialnote3"
                                      placeholder="Enter family history...">{{$consultation->family_history}}</textarea>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Social History</label>
                            <textarea name="social_history" class="form-control" id="initialnote4"
                                      placeholder="Enter social history...">{{$consultation->social_history}}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Drug History</label>
                            <textarea name="drug_history" class="form-control" id="initialnote5"
                                      placeholder="Enter medication history...">{{$consultation->drug_history}}</textarea>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Drug Allergies</label>
                            <textarea name="drug_allergies" class="form-control" id="initialnote6"
                                      placeholder="Enter medication allergy...">{{$consultation->drug_allergies}}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Examination</label>
                            <textarea name="examination" class="form-control" id="initialnote7"
                                      placeholder="Enter observation and examination findings...">{{$consultation->examination}}</textarea>
                        </div>
                    </div>
                    <hr>


                    <div class="row">
                        <div class="col-md-6">
                            <label>Diagnosis</label>
                            <textarea name="diagnosis" class="form-control" id="initialnote8"
                                      placeholder="Enter diagnosis or impression...">{{$consultation->diagnosis}}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Treatment</label>
                            <textarea name="treatment" class="form-control" id="initialnote9"
                                      placeholder="Enter treatment plan...">{{$consultation->treatment}}</textarea>
                        </div>
                    </div>
                    <hr>


                    <div class="form-group">
                        <input type="submit" value="Update Initial Consultation Note"
                               class="btn btn-sm btn-block btn-success">
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
