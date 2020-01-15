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
            /*width: 100% !important;*/
        }

        .date-picker {
            pointer-events: none;
            opacity: 0.5;
        }

        button.btn.btn-success.active {
            background-color: white;
            color: green;
        }
        .float-right{
            float: right!important;
        }
        .result {
            display: none;
        }

        .float-right{
            float: right!important;
        }
        .result {
            display: none;
        }
        #appointments-wrapper {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@stop

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
                <div class="col-md-4">
                    <strong>Edit Appointment</strong>
                </div>
                <div class="col-md-4">
                    {{-- <a href="{{route('clients.edit')}}" class="btn btn-sm btn-primary">Edit Client</a> --}}
                </div>
            </div>
        </div>

        <div class="panel-body">

            @include('inc.tabMenu', ['tabMenuPosition'=>3, 'patient_id'=>$appointment->user->id])

            <form action="{{route('appointments.update', $appointment->id)}}" method="post">

                {{csrf_field()}}
                @method('put')

                <div class="row" style="padding: 1%;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Service</label>

                            @if(count($services) > 0)
                                <select onchange="getDoctor()" name="service_id" id="service_id" class="form-control" required>
                                    <option disabled>Select service</option>
                                    @foreach ($services as $service)
                                        <option value="{{$service->id}}" @if($service->id == $appointment->service_id) selected @endif>{{$service->name}}</option>
                                    @endforeach
                                </select>
                            @else

                                @if (auth()->user()->role_id == 5)
                                    <p>No Service Available.
                                @else
                                    <p>No Service Available.
                                        <a href="{{route('services.create')}}">Create Service</a>
                                    </p>
                                @endif
                            @endif

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Doctor</label>
                            <select name="doctor_id" id="doctor_id" class="form-control" onchange="createNewFlatpickr()" required>
                                @foreach ($appointment->service->users as $user)
                                    <option value="{{$user->id}}" @if($user->id == $appointment->doctor_id) selected @endif>{{$user->firstname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="box border-info">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="date-picker col-md-5">
                                                <input type="hidden" name="from" id="appointmentFromTime" value="{{ date('h:iA', strtotime($appointment->from)) }}">
                                                <input  class="form-control flatpickr flatpickr-input" type="text" id="appointment_date" name="appointment_date" value="{{old('appointment_date')}}" placeholder="Select Appointment Date" data-id="inline" readonly="readonly" required>
                                            </div>
                                            <div class="col-md-7">
                                                <div style="padding: 7px!important;" class="box-header bg-success text-center">
                                                    <h3 class="box-title">Date: </h3>
                                                </div>
                                                <div class="table-responsive text-center" style="width: 100%">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center"><span class="date morning"></span>Morning</th>
                                                            <th class="text-center"><span class="date afternoon"></span>Afternoon</th>
                                                            <th class="text-center"><span class="date evening"></span>Evening</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="3" id="morning">
                                                                <div class="text-info">Please choose a date</div>
                                                            </td>
                                                            <td id="afternoon">
                                                            </td>
                                                            <td id="evening">
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">

                            @if (auth()->user()->role_id == 5)
                                <label>Patient Name. </label>
                                <select name="user_id" id="user_id" class="form-control" disabled>
                                    <option value="{{auth()->user()->id}}" id="test">
                                        {{auth()->user()->firstname}}
                                        {{auth()->user()->lastname}}
                                        - DOB : {{auth()->user()->date_of_birth}}
                                        @if (auth()->user()->nhs_number)
                                            - NHS : {{auth()->user()->nhs_number}}
                                        @endif
                                    </option>
                                </select>
                            @else

                                <select name="user_id" id="user_id" class="form-control" required>
                                    @foreach ($patients as $patient)
                                        <option value="{{$patient->id}}" @if($patient->id == $appointment->user_id) selected @endif>
                                            {{$patient->firstname}} {{$patient->lastname}} -
                                            DOB : {{$patient->date_of_birth}}
                                            @if ($patient->nhs_number)
                                                - NHS : {{$patient->nhs_number}}
                                            @endif

                                        </option>
                                    @endforeach
                                </select>

                            @endif

                        </div>
                    </div>

                </div><hr>
                <div class="row" style="padding: 1%;">
                    <div class="form-group">
                        <label>Comment</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Enter Comment...">{{old('description')}}</textarea>
                    </div>
                </div>
                <hr>

                <div class="row" style="padding: 1%;">
                    <div class="col-md-6">

                    </div>
                    <div class="col-md-6">
                        <input type="submit" class="btn btn-sm btn-success btn-block" value="Submit Appointment">
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        let Result = null;
        let fp = null;

        $(document).ready(function () {
            let dateStr = '{{ date('Y-m-d', strtotime($appointment->appointment_date)) }}';
            let date = new Date('{{ date('Y-m-d', strtotime($appointment->appointment_date)) }}');
            getAvailableDateTime(date.getMonth() + 1, date.getFullYear(), new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate());

            setTimeout(function () {
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
            }, 1000);
        })

        // Get availableDateTime for current doctor & month, year
        function getAvailableDateTime(month, year, days) {
            $.ajax({
                type: 'get',
                url: window.location.origin + '/timings',
                data: {
                    'doctor_id': $('#doctor_id option:selected').val(),
                    'service_id': $('#service_id option:selected').val(),
                    'timeZone': Intl.DateTimeFormat().resolvedOptions().timeZone,
                    'month': month,
                    'year': year,
                    'days': days,
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
                defaultDate: '{{ date('Y-m-d', strtotime($appointment->appointment_date)) }}',
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
                        url: window.location.origin + '/timings',
                        data: {
                            'doctor_id': $('#doctor_id option:selected').val(),
                            'service_id': $('#service_id option:selected').val(),
                            'timeZone': Intl.DateTimeFormat().resolvedOptions().timeZone,
                            'month': date.getMonth() + 1,
                            'year': date.getFullYear(),
                            'days': new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate(),
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
                        url: window.location.origin + '/timings',
                        data: {
                            'doctor_id': $('#doctor_id option:selected').val(),
                            'service_id': $('#service_id option:selected').val(),
                            'timeZone': Intl.DateTimeFormat().resolvedOptions().timeZone,
                            'month': date.getMonth() + 1,
                            'year': date.getFullYear(),
                            'days': new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate(),
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
                url: window.location.origin + '/doctor-by-service/' + $('#service_id').val(),
                success: function (response) {
                    $('#doctor_id').html('');
                    $('#doctor_id').append('<option selected disabled>Select doctor</option>');
                    for (var key in response) {
                        $('#doctor_id').append('<option value="' + response[key].id + '">' + response[key].firstname + ' ' + response[key].lastname + '</option>');
                    }
                }
            });
        }
    </script>

@stop
