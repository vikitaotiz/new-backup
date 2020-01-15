@extends('adminlte::page')

@section('css')
    <style>
        .date.morning {
            width: 10px;
            height: 10px;
            top: 37px;
            content: " ";
            display: block;
            background: #6ED0F6;
            margin-left: 46%;
        }

        .date.afternoon {
            width: 10px;
            height: 10px;
            border-radius: 100%;
            top: 37px;
            content: " ";
            display: block;
            background: #F49AC1;
            margin-left: 46%;
        }

        .date.evening {
            width: 10px;
            height: 10px;
            top: 37px;
            content: " ";
            display: block;
            background: #F2DB4E;
            margin-left: 46%;
        }

        .eventBody {
            width: 100%;
            position: absolute;
            left: 0;
            bottom: -8px;
        }

        .event {
            width: 3px;
            height: 3px;
            border-radius: 150px;
            display: inline-block;
            background: #6ED0F6;
            margin: 0 2px;
        }

        .event.afternoon {
            background: #F49AC1;
        }

        .event.evening {
            background: #F2DB4E;
        }

        button.btn.btn-success {
            margin: 5px 0;
        }

        .flatpickr-calendar {
            width: 100% !important;
        }

        .date-picker {
            pointer-events: none;
            opacity: 0.5;
        }

        button.btn.btn-success.active {
            background-color: white;
            color: green;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('css/fullcalendar.css') }}">
@stop

@section('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.js"></script>
    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button class="btn btn-sm btn-success" onclick="goBack()" style="margin-left: 3%;"><i
                            class="fa fa-arrow-left"></i> Back
                    </button>
                    <a href="{{route('appointments.index')}}" class="btn btn-sm btn-info"><i class="fa fa-list"></i>
                        Full List View</a>
                </div>
                <div class="col-md-4">
                    <strong>Appointments (Calendar)</strong>
                </div>
                <div class="col-md-4">

                    @if(auth()->user()->role_id == 5)
                        @if(\App\Service::all()->count() > 0)
                            <button data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-success btn-block">
                                <i class="fa fa-calendar-plus-o"></i> Create New Appontment
                            </button>
                        @else
                            There are no services available <a href="#">Contact Admin.</a>
                        @endif
                    @else
                        <button data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-success btn-block">
                            <i class="fa fa-calendar-plus-o"></i> Create New Appontment
                        </button>
                    @endif

                </div>
            </div>
            <br>
        </div>
    </div>
    <div class="row">
        {{--<div class="col-md-4">
            <div class="box box-success">
                @if(count($appointments) > 0)
                    <table class="table table-bordered dataTables_wrapper form-inline dt-bootstrap" id="patients_table">
                        <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Patient Name</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($appointments as $appointment)
                            <tr>
                                <td style="background: {{$appointment->color}}; color: #fff">
                                    {{$appointment->service->name}}</td>
                                <td>
                                    <a href="{{route('appointments.show', $appointment->id)}}"
                                       style="color: {{$appointment->color}}; text-decoration: none;">
                                        {{$appointment->user->firstname ?? 'N/A'}}
                                        {{$appointment->user->lastname ?? 'N/A'}}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <h4>There are no appointments</h4>
                @endif
            </div>
        </div>--}}
        <div class="col-md-12">
            <div class="box box-success" id="calendar">
                {!! $calendar->calendar() !!}
                {!! $calendar->script() !!}
            </div>
        </div>
    </div>



    {{-- Create Appointment --}}
    <div class="modal fade" id="myModal" role="dialog">
        @include('inc.appointments.create_appointment_calendar.create')
    </div>


    {{-- Create Patient on the appointment --}}
    <div class="modal fade" id="addNewPatient" role="dialog">
        @include('inc.appointments.create_user.create_user_ajax')
    </div>

@endsection

@section('js')
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/fullcalendar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        let Result = null;
        let fp = null;

        // Get availableDateTime for current doctor & month, year
        function getAvailableDateTime(month, year, days) {
            $.ajax({
                type: 'get',
                url: 'timings',
                data: {
                    'service_id': $('#service_id option:selected').val(),
                    'doctor_id': $('#doctor_id option:selected').val(),
                    'month': month,
                    'year': year,
                    'days': days,
                    'timeZone': Intl.DateTimeFormat().resolvedOptions().timeZone,
                },
                success: function (data) {
                    initializeFlatpickr(data);
                }
            });
        }

        // Get availableDateTime for changed doctor
        function createNewFlatpickr() {
            $('#appointmentFromTime').val('');

            // Clear timing
            $('#morning').replaceWith('<td colspan="3" id="morning"><div class="text-info">Please choose a date</div></td>');
            $('#afternoon').html('');
            $('#evening').html('');

            // Destroy previous flatpickr instance
            if (fp){
                fp.destroy();
            }

            let date = new Date();
            getAvailableDateTime(date.getMonth() + 1, date.getFullYear(), new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate());
        }

        // Create a new instance of flatpkr
        function initializeFlatpickr(data) {
            Result = data;
            let disabledDates = [];

            fp = flatpickr("#appointment_date", {
                inline: true,
                minDate: 'today',
                altInput: true,
                allowInput: true,
                altFormat: 'd F, Y',
                onDayCreate: function (dObj, dStr, fp, dayElem) {
                    if (formatDate($(dayElem).attr("aria-label")) in Result) {
                        var eventBody = "<span class='eventBody'>";
                        if (Result[formatDate($(dayElem).attr("aria-label"))]['morning'].length > 0) {
                            eventBody += "<span class='event'></span>";
                        }

                        if (Result[formatDate($(dayElem).attr("aria-label"))]['afternoon'].length > 0) {
                            eventBody += "<span class='event afternoon'></span>";
                        }

                        if (Result[formatDate($(dayElem).attr("aria-label"))]['evening'].length > 0) {
                            eventBody += "<span class='event evening'></span>";
                        }

                        eventBody += "</span>";
                        dayElem.innerHTML += eventBody;
                    } else {
                        disabledDates.push(formatDate($(dayElem).attr("aria-label")));
                    }
                },
                onMonthChange: function (selectedDates, dateStr, instance) {
                    disabledDates = [];
                    $('.date-picker').css({"pointer-events": "none", "opacity": "0.5"});
                    $('#morning').replaceWith('<td colspan="3" id="morning"><div class="text-info">Please choose a date</div></td>');
                    $('#afternoon').html('');
                    $('#evening').html('');


                    let date = new Date(instance.currentYear, instance.currentMonth);

                    $.ajax({
                        type: 'get',
                        url: 'timings',
                        data: {
                            'service_id': $('#service_id option:selected').val(),
                            'doctor_id': $('#doctor_id option:selected').val(),
                            'month': date.getMonth() + 1,
                            'year': date.getFullYear(),
                            'days': new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate(),
                            'timeZone': Intl.DateTimeFormat().resolvedOptions().timeZone,
                        },
                        success: function (response) {
                            Result = response;

                            instance.redraw();
                            fp.set('disable', disabledDates);
                            $('.date-picker').css({"pointer-events": "all", "opacity": "1"});
                        }
                    });
                },
                onYearChange: function (selectedDates, dateStr, instance) {
                    disabledDates = [];
                    $('.date-picker').css({"pointer-events": "none", "opacity": "0.5"});
                    $('#morning').replaceWith('<td colspan="3" id="morning"><div class="text-info">Please choose a date</div></td>');
                    $('#afternoon').html('');
                    $('#evening').html('');


                    let date = new Date(instance.currentYear, instance.currentMonth);

                    $.ajax({
                        type: 'get',
                        url: 'timings',
                        data: {
                            'service_id': $('#service_id option:selected').val(),
                            'doctor_id': $('#doctor_id option:selected').val(),
                            'month': date.getMonth() + 1,
                            'year': date.getFullYear(),
                            'days': new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate(),
                            'timeZone': Intl.DateTimeFormat().resolvedOptions().timeZone,
                        },
                        success: function (response) {
                            Result = response;

                            instance.redraw();
                            fp.set('disable', disabledDates);
                            $('.date-picker').css({"pointer-events": "all", "opacity": "1"});
                        }
                    });
                },
                onChange: function (selectedDates, dateStr, instance) {
                    $('.box-title').text(dateStr);

                    if (dateStr in Result) {
                        let [morning, afternoon, evening] = Array(3).fill('');

                        Result[dateStr]['morning'].forEach(function (value, index, array) {
                            morning += '<button type="button" class="btn btn-success" onclick="setFrom(this)">' + value + '</button><br>';
                        });
                        Result[dateStr]['afternoon'].forEach(function (value, index, array) {
                            afternoon += '<button type="button" class="btn btn-success" onclick="setFrom(this)">' + value + '</button><br>';
                        });
                        Result[dateStr]['evening'].forEach(function (value, index, array) {
                            evening += '<button type="button" class="btn btn-success" onclick="setFrom(this)">' + value + '</button><br>';
                        });

                        $('#morning').replaceWith('<td id="morning">' + morning + ' </td>');
                        $('#afternoon').html(afternoon);
                        $('#evening').html(evening);
                    } else {
                        $('#morning').replaceWith('<td colspan="3" id="morning"><div class="text-danger">Doctor not available on selected date!</div></td>');
                        $('#afternoon').html('');
                        $('#evening').html('');
                    }
                },
            });

            fp.set('disable', disabledDates);
            $('.date-picker').css({"pointer-events": "all", "opacity": "1"});
        }

        // Set from
        function setFrom(_this) {
            $('#appointmentFromTime').val($(_this).text());

            // remove classes from all
            $("button").removeClass("active");
            // add class to the one we clicked
            $(_this).addClass("active");
        }

        // Format date
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        }

        // Get doctor by service
        function getDoctor() {
            // Clear timing
            $('#morning').replaceWith('<td colspan="3" id="morning"><div class="text-info">Please choose a date</div></td>');
            $('#afternoon').html('');
            $('#evening').html('');

            // Destroy previous flatpckr instance if exists
            if (fp) {
                fp.destroy();
            }

            // Make ajax call to get doctor by service
            $.ajax({
                type: 'get',
                url: 'doctor-by-service/' + $('#service_id').val(),
                success: function (response) {
                    $('#doctor_id').html('');
                    $('#doctor_id').append('<option selected disabled>Select doctor</option>');

                    if (jQuery.isEmptyObject(response)) {
                        alert('No doctor found for this service!');
                    } else {
                        for (var key in response) {
                            $('#doctor_id').append('<option value="' + response[key].id + '">' + response[key].firstname + ' ' + response[key].lastname + '</option>');
                        }
                    }
                }
            });
        }

        function addEvent(date) {
            console.log(date._d);
            $('#myModal').modal('show');
        }
    </script>
@stop

