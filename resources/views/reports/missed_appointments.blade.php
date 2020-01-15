@extends('adminlte::page')

@section('title', 'Reports Missed appointments')

@section('css')
    <style>
        .float-right{
            float: right!important;
        }
        .result {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@stop

@section('content')
    <div class="panel panel-success">
        <div class="no-print panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
                <div class="col-md-4">
                    <strong>Reports Missed appointments</strong>
                </div>
                <div class="col-md-4">
                    <div class="float-right"><a href="javascript:print()" class="btn btn-default"><i class="fa fa-print"></i> Print</a></div>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <form class="no-print" method="post" action="{{ route('reports.appointments.missed') }}" id="appointment-form">
                @csrf
                <div class="form-row">
                    <div class="col-md-3">
                        <label for="appointment_date">From <span class="text-danger">*</span></label>
                        <input  class="form-control flatpickr flatpickr-input" onchange="setTo()" type="text" id="appointment_date" name="appointment_date" placeholder="Select Appointment Date" readonly="readonly" required>
                    </div>
                    <div class="col-md-3">
                        <label for="end_date">To </label>
                        <input  class="form-control flatpickrTo flatpickr-input" type="text" id="end_date" name="end_date" placeholder="Select Appointment Date" readonly="readonly">
                    </div>
                    <div class="col-md-4">
                        <label for="doctor_id">Practitioners <span class="text-danger">*</span></label>
                        <select class="form-control" id="doctor_id" name="doctor_id" required>
                            <option selected="selected" value="all">All Practitioners</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                            @endforeach
                            {{--<optgroup label="Groups">
                                <option selected="selected" value="all">All Practitioners</option>
                                <option value="active">Active Practitioners</option>
                                <option value="inactive">Inactive Practitioners</option>
                            </optgroup>
                            <optgroup label="Active Practitioners">

                            </optgroup>--}}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <br>
                        <button type="submit" class="btn btn-danger"> Create report</button>
                    </div>
                </div>
            </form>

            <div class="result row">
                <div class="col-md-12">
                    <div class="panel-body">
                        <div class="alert alert-info">
                            <h4 id="info"></h4>
                        </div>
                        <div class="alert alert-danger">
                            <h4 id="info1"></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="success-result">
                        <h3>Summary</h3>
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>Appointment type</h4>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <h4>Count</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row" id="type">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="success-result">
                        <h3>&nbsp;</h3>
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>Practitioner</h4>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <h4>Count</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row" id="practitioner">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="success-result">
                        <h3 id="date"></h3>
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr class="r-table__row-header">
                                <th>Appointment</th>
                                <th>Patient</th>
                                <th>Practitioner</th>
                                <th>Reason</th>
                            </tr>
                            </thead>
                            <tbody id="appointment">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
         flatpickr("#appointment_date", {            
            enableTime: true,
            altInput: true,
            allowInput: true,
            altFormat: 'd F, Y',
            time_24hr: true,
            defaultHour: '00',
            defaultMinute: '00',
        });

        function setTo() {
            flatpickr(".flatpickrTo", {
                altInput: true,
                allowInput: true,
                altFormat: 'd F, Y',
                minDate: $('#appointment_date').val(),
            });
        }

        $('#appointment-form').submit(function (event) {
            event.preventDefault();

            $.ajax({
                type: 'post',
                url: $(this).attr('action'),
                contentType: false,
                cache: false,
                processData: false,
                data: new FormData(this),
                success: function (response) {
                    let data = response.data.appointments;
                    let services = response.data.services;
                    let doctors = response.data.doctors;

                    $('#practitioner').html('');
                    $('#appointment').html('');
                    $('#type').html('');
                    $('.result').show();

                    if ($('#end_date').val() == "") {
                        $('#date').text(formatDate($('#appointment_date').val()));
                    } else {
                        $('#date').text(formatDate($('#appointment_date').val()) + ' to ' + formatDate($('#end_date').val()));
                    }

                    if (data.length > 0) {
                        if (data.length === 1) {
                            var verb = 'is';
                        } else {
                            var verb = 'are';
                        }

                        $('.alert-danger').hide();
                        $('.success-result').show();
                        $('.alert-info').show();

                        if ($('#end_date').val() == "") {
                            $('#info').text('There ' + verb + ' ' + data.length + ' missed appointment(s) on ' + formatDate($('#appointment_date').val()) + ' for ' + $('#doctor_id option:selected').text());
                        } else {
                            $('#info').text('There ' + verb + ' ' + data.length + ' missed appointment(s) from ' + formatDate($('#appointment_date').val()) + ' to ' + formatDate($('#end_date').val()) + ' for ' + $('#doctor_id option:selected').text());
                        }

                        data.forEach(function (value, index, array) {
                            $('#appointment').append(
                                '<tr class="r-table__row r-table__row--last">\n' +
                                '<th class="r-table__cell r-table__cell--row-header xl-no-wrap rounded-bottom-left border-color" data-label="Time" scope="row">' + formatDate(value.appointment_date) + ' ' + value.from +' — '+ value.to +'</th>\n' +
                                '<td class="r-table__cell" data-label="Patient"><a href="javascript:void(0)">'+ value.user.firstname +' '+ value.user.lastname +'</a></td>\n' +
                                '<td class="r-table__cell break-word" data-label="Practitioner">'+ value.doctor.firstname +' '+ value.doctor.lastname +'</td>' +
                                '<td class="r-table__cell pad-b3 lg-pad-b0 r-table__cell--last" data-label="Type"><button type="button" class="btn btn-danger">Did not attend</button></td>' +
                                '</tr>'
                            );
                        });

                        for (var key in services) {
                            $('#type').append(
                                '<div class="col-md-8">\n' +
                                '<h4>' + key + '</h4>\n' +
                                '</div>\n' +
                                '<div class="col-md-4 text-right">\n' +
                                '<h4>' + services[key] + '</h4>\n' +
                                '</div>'
                            );
                        }

                        for (var key in doctors) {
                            $('#practitioner').append(
                                '<div class="col-md-8">\n' +
                                '<h4>' + key + '</h4>\n' +
                                '</div>\n' +
                                '<div class="col-md-4 text-right">\n' +
                                '<h4>' + doctors[key] + '</h4>\n' +
                                '</div>'
                            );
                        }
                    } else {
                        $('.alert-danger').show();
                        $('.success-result').hide();
                        $('.alert-info').hide();

                        if ($('#end_date').val() == "") {
                            $('#info1').text('There are no missed appointments on ' + formatDate($('#appointment_date').val()) + ' for ' + $('#doctor_id option:selected').text());
                        } else {
                            $('#info1').text('There are no appointments from ' + formatDate($('#appointment_date').val()) + ' to ' + formatDate($('#end_date').val()) + ' for ' + $('#doctor_id option:selected').text());
                        }
                    }

                }
            });
        });

        // Format date
        function formatDate(date) {
            var d = new Date(date);

            return d.toDateString();
        }
    </script>
@endsection
