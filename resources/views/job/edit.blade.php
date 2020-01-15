@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
                <div class="col-md-4 text-center">
                    <strong>Update SMS Scheduler</strong>
                </div>
                <div class="col-md-4">
                    <a href="{{route('jobs.index')}}" class="pull-right btn btn-sm btn-primary"><i class="fa fa-arrow-up"></i> Back To List</a>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <form action="{{ route('jobs.update', $job->id) }}" method="post">
                @csrf
                @method('put')
                <div class="form-group row">
                    <div class="col-md-3">
                        <select name="template_id" id="template_id" class="form-control" onchange="showTemplate()">
                            <option selected disabled>Select Template</option>
                            @foreach($smsTemplates as $smsTemplate)
                                <option value="{{ $smsTemplate->id }}" data-value="{{ $smsTemplate->body }}">{{ $smsTemplate->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="user_id" id="user_id" class="form-control">
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" @if($job->users && $job->users->contains('user_id', $patient->id)) selected @endif>{{ $patient->firstname }} {{ $patient->lastname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="doctor_id" id="doctor_id" class="form-control">
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" @if($job->doctors && $job->doctors->contains('user_id', $doctor->id)) selected @endif>{{ $doctor->firstname }} {{ $doctor->lastname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="company_detail_id" id="company_detail_id" class="form-control" onchange="showTemplate()">
                            <option selected disabled>Select business</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" @if($job->company_detail_id == $company->id) selected @endif>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12"><textarea name="message" class="form-control" id="message" rows="5" placeholder="Body">{{ $job->template }}</textarea></div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-md-2">Reminder period</label>
                    <span class="col-md-2 help-block">send reminder</span>
                    <div class="col-md-6">
                        <input type="number" min="1" name="reminder_period" class="form-control" value="@if($job->reminder_period){{$job->reminder_period}}@else{{ 1 }}@endif" placeholder="Reminder period">
                    </div>
                    <span class="col-md-2 help-block">day(s) before appointment</span>
                </div>
                <div class="form-group row">
                    <label class="col-md-1">Reminder time</label>
                    <span class="col-md-3 help-block">Your reminders will start sending during this timeframe.</span>
                    <div class="col-md-3">
                        <input type="text" name="reminder_time_from" class="flatpickr form-control" value="@if($job->reminder_time_from){{date('H:i', strtotime($job->reminder_time_from))}}@elseif(old('reminder_time_from')){{old('reminder_time_from')}}@endif" placeholder="Reminder time from">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="reminder_time_to" class="flatpickr form-control" value="@if($job->reminder_time_to){{date('H:i', strtotime($job->reminder_time_to))}}@elseif(old('reminder_time_to')){{old('reminder_time_to')}}@endif" placeholder="Reminder time to">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2">Default Reminder type</label>
                    <span class="col-md-2 help-block"></span>
                    <div class="col-md-6">
                        <select class="form-control" name="reminder_type" id="reminder_type">
                            <option selected disabled>Reminder type</option>
                            <option value="0" @if($job->reminder_type === 0) selected @endif>None</option>
                            <option value="1" @if($job->reminder_type === 1) selected @endif>SMS</option>
                            <option value="2" @if($job->reminder_type === 2) selected @endif>SMS & Email</option>
                        </select>
                    </div>
                    <span class="col-md-2 help-block">New client will have this reminder type by default.</span>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function () {
            flatpickr(".flatpickr", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
        });

        function showTemplate() {
            $('#message').val($('#template_id option:selected').data('value'));
        }
    </script>
@stop
