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
                    <strong>Create Initial Consultations Note for {{$user->firstname}} {{$user->lastname}}</strong>
                </div>
                <div class="col-md-4">
                    {{-- <a href="{{route('notes.create')}}" class="btn btn-sm btn-primary">Create New note</a> --}}
                </div>
            </div>
        </div>

        <div class="panel-body">
            @include('inc.tabMenu', ['tabMenuPosition'=>5, 'patient_id'=>$user->id])
            <form action="{{route('patient_store_initialnote.store')}}" method="post">

                {{csrf_field()}}

                <input type="hidden" name="user_id" value="{{$user->id}}">

                <div style="padding: 2%;">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Presenting Complaint</label>
                                <textarea name="complain" class="form-control" id="initialnote"
                                          placeholder="Enter main complaint...">{{old('complain')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>History of Presenting Complaint</label>
                            <textarea name="history_presenting_complaint" id="initialnote1" class="form-control"
                                      placeholder="Enter subjective history...">{{old('history_presenting_complaint')}}</textarea>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Past Medical History</label>
                            <textarea name="past_medical_history" id="initialnote2" class="form-control"
                                      placeholder="Enter medical and surgical history...">{{old('past_medical_history')}}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Family History</label>
                            <textarea name="family_history" id="initialnote3" class="form-control"
                                      placeholder="Enter family history...">{{old('family_history')}}</textarea>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Social History</label>
                            <textarea name="social_history" id="initialnote4" class="form-control"
                                      placeholder="Enter social history...">{{old('social_history')}}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Drug History</label>
                            <textarea name="drug_history" id="initialnote5" class="form-control"
                                      placeholder="Enter medication history...">{{old('drug_history')}}</textarea>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Drug Allergies</label>
                            <textarea name="drug_allergies" id="initialnote6" class="form-control"
                                      placeholder="Enter medication allergy...">{{old('drug_allergies')}}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Examination</label>
                            <textarea name="examination" id="initialnote7" class="form-control"
                                      placeholder="Enter observation and examination findings...">{{old('examination')}}</textarea>
                        </div>
                    </div>
                    <hr>


                    <div class="row">
                        <div class="col-md-6">
                            <label>Diagnosis</label>
                            <textarea name="diagnosis" id="initialnote8" class="form-control"
                                      placeholder="Enter diagnosis or impression...">{{old('diagnosis')}}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Treatment</label>
                            <textarea name="treatment" id="initialnote9" class="form-control"
                                      placeholder="Enter treatment plan...">{{old('treatment')}}</textarea>
                        </div>
                    </div>
                    <hr>


                    <div class="form-group">
                        <input type="submit" value="Create Initial Consultation Note"
                               class="btn btn-sm btn-block btn-success">
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
