@extends('adminlte::page')

@section('title', $patientTreatmentNote->template->print_title)

@section('css')
    <style>
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 100px;
            border-radius: 100%;
        }

        .bg-info {
            background-color: #00c0ef
        }
    </style>
@stop

@section('content')
    <div class="text-center">
        @if($patientTreatmentNote->user->doctor)
            @if(count($patientTreatmentNote->user->doctor->companies))
                @foreach($patientTreatmentNote->user->doctor->companies as $company)
                <img src="{{asset('/storage/'.$company->logo)}}" alt="Company logo" class="center">
                <h1>{{ $company->name }}</h1>
                <h4>{{ $company->address }}</h4>
                @endforeach
            @endif
        @endif
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    @if($patientTreatmentNote->user)
                    <h2>{{ ucwords($patientTreatmentNote->user->title) }} {{ $patientTreatmentNote->user->firstname }} {{ $patientTreatmentNote->user->lastname }}</h2>
                    @endif
                </div>
                <div class="no-print col-md-4 text-center">
                    <a href="javascript:print()" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                </div>
                <div class="col-md-4">
                    <div class="pull-right">
                        <h2>{{ $patientTreatmentNote->template->title }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body">
            @include('inc.tabMenu', ['tabMenuPosition' => $patientTreatmentNote->type == 'note' ? 5 : 7, 'patient_id'=>$patientTreatmentNote->user_id])
            <div class="row">
                <div class="col-md-6">
                    <table class="table no-border table-striped table-hover">
                        @if($patientTreatmentNote->template->is_show_patients_dob)
                            <tr>
                                <th>Date of birth</th>
                                <td>{{ $patientTreatmentNote->user->date_of_birth }}</td>
                            </tr>
                        @endif

                        @if($patientTreatmentNote->template->is_show_patients_occupation)
                            <tr>
                                <th>Occupation</th>
                                <td>{{ $patientTreatmentNote->user->occupation }}</td>
                            </tr>
                        @endif

                        @if($patientTreatmentNote->template->is_show_patients_referral_source)
                            <tr>
                                <th>Reference no.</th>
                                <td>{{ $patientTreatmentNote->user->referral_source }}</td>
                            </tr>
                        @endif

                        @if($patientTreatmentNote->template->is_show_patients_address)
                            <tr>
                                <th>Address</th>
                                <td>{{ $patientTreatmentNote->user->address }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table no-border table-striped table-hover">
                        @if($patientTreatmentNote->template->is_show_patients_nhs_number)
                            @if($patientTreatmentNote->user->doctor)
                                <tr>
                                    <th>Practitioner</th>
                                    <td>{{ $patientTreatmentNote->user->doctor->firstname .' '. $patientTreatmentNote->user->doctor->lastname }}</td>
                                </tr>
                            @endif
                        @endif

                        @if($patientTreatmentNote->appointment_id)
                            <tr>
                                <th>Appointment</th>
                                <td>{{date('d M Y', strtotime($patientTreatmentNote->appointment->appointment_date))}}</td>
                            </tr>
                        @endif

                        <tr>
                            <th>Created</th>
                            <td>{{ date('d-m-Y', strtotime($patientTreatmentNote->created_at)) }}</td>
                        </tr>

                        <tr>
                            <th>Last updated</th>
                            <td>{{ date('d-m-Y', strtotime($patientTreatmentNote->updated_at)) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table no-border">
                        @foreach($patientTreatmentNote->template->sections as $section)
                            <tr>
                                <td colspan="2" style="word-break: break-all">
                                    <div class="alert alert-info">{{ $section->title }}</div>
                                </td>
                            </tr>

                            @foreach($section->questions as $question)
                                <tr>
                                    <th style="word-break: break-all;" width="200px">{{ $question->title }}</th>
                                    @foreach($patientTreatmentNote->notes as $note)
                                        @if($note->question_id == $question->id)
                                            @if($question->type == 4)
                                                <td style="word-break: break-all">
                                                    <div class="row">
                                                        @foreach(explode(",",$note->answer) as $imageSrc)
                                                            <div style="width:250px; border: 1px solid #dddddd; float: left;">
                                                                <img src="{{asset('/').$imageSrc}}" style="width:100%">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            @elseif($question->type == 3)
                                                <td style="word-break: break-all">
                                                    <div class="row">
                                                        @foreach(explode(",",$note->answer) as $item)
                                                            <span class="badge bg-info">{{$item}}</span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            @else
                                                <td style="word-break: break-all">{!! nl2br($note->answer) !!}</td>
                                            @endif
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
