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

    <div class="panel panel-success" id="appointments-wrapper">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                    <a href="{{route('appointments.calendar')}}" class="btn btn-sm btn-info"><i class="fa fa-calendar"></i> Calendar View</a>
                </div>
                <div class="col-md-4">
                    <strong>All Appointments</strong>
                </div>
                <div class="col-md-4">
                    <a href="javascript:void(0)" class="btn btn-sm btn-primary btn-block" data-toggle="modal" data-target="#myModal"><i class="fa fa-calendar-plus-o"></i> Create New appointment</a>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <form class="no-print mb-5" method="get" @submit.prevent="getAppointments" id="appointment-form">
                <div class="form-row">
                    <div class="col-md-3">
                        <label for="appointment_date">From <span class="text-danger">*</span></label>
                        <input  class="form-control flatpickrFilter flatpickr-input" v-model="params.appointment_date" onchange="setTo()" id="appointment_from" type="text" name="appointment_date" placeholder="Select Appointment Date" readonly="readonly" required>
                    </div>
                    <div class="col-md-3">
                        <label for="end_date">To </label>
                        <input  class="form-control flatpickrFilterTo flatpickr-input" v-model="params.end_date" type="text" name="end_date" placeholder="Select Appointment Date" readonly="readonly">
                    </div>
                    <div class="col-md-4">
                        <label for="doctor_id">Practitioners <span class="text-danger">*</span></label>
                        <select class="form-control" v-model="params.doctor_id" name="doctor_id" required>
                            <option selected="selected" value="all">All Practitioners</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <br>
                        <button type="submit" class="btn btn-danger"> Filter</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-1">
                    <select v-model="params.perPage" class="form-control" @change="getAppointments()">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-md-5"></div>
                <div class="col-md-6">
                    <div class="pull-right">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="" class="pull-right">Search: </label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" v-model="params.keyword" class="form-control" @change="keyTyping" @keyup="keyTyping">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-hover table-striped table-custom" id="appointments_table" v-if="appointments.length > 0">
                <thead>
                <tr>
                    <th @click="filterParam('sort', 'service')" :class="{ 'table-custom-header' : params.sort_by != 'service', 'sorting_asc' : params.sort_by == 'service' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'service' && params.order_by == 'desc' }">Service Name</th>
                    <th @click="filterParam('sort', 'patient')" :class="{ 'table-custom-header' : params.sort_by != 'patient', 'sorting_asc' : params.sort_by == 'patient' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'patient' && params.order_by == 'desc' }">Patient</th>
                    <th @click="filterParam('sort', 'progress')" :class="{ 'table-custom-header' : params.sort_by != 'progress', 'sorting_asc' : params.sort_by == 'progress' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'progress' && params.order_by == 'desc' }">Progress</th>
                    <th @click="filterParam('sort', 'status')" :class="{ 'table-custom-header' : params.sort_by != 'status', 'sorting_asc' : params.sort_by == 'status' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'nhs_number' && params.order_by == 'desc' }">Status</th>
                    <th @click="filterParam('sort', 'appointment_date')" :class="{ 'table-custom-header' : params.sort_by != 'appointment_date', 'sorting_asc' : params.sort_by == 'appointment_date' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'appointment_date' && params.order_by == 'desc' }">Appointment Date</th>
                    <th>Staff Assigned</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="appointment in appointments">
                        <td>@{{appointment.service_name}}</td>
                        <td>
                            <a :href="base_url + '/patients/' + appointment.user_id">
                                @{{appointment.patient_name ? appointment.patient_name : 'N/A'}}
                            </a>
                        </td>
                        <td v-if="appointment.status == 'open'">
                                {{-- <a data-toggle="modal" data-target="#appointment_progress" style="cursor: pointer;"
                                data-toggle="tooltip" title="Click to change appointment progress."> --}}
                            <a :onclick="'changeAppointmentProgress(' + appointment.id + ',\'' + appointment.progress +'\')'" style="cursor: pointer;"
                               data-toggle="tooltip" title="Click to change appointment progress.">
                                <strong v-if="appointment.progress == 'pending'">Pending</strong>
                                <strong v-else-if="appointment.progress == 'arrived'">Arrived</strong>
                                <strong v-else-if="appointment.progress == 'send_in'">Send In</strong>
                                <strong v-else-if="appointment.progress == 'left'">Left</strong>
                                <strong v-else-if="appointment.progress == 'did_not_attend'">Did Not Attend</strong>
                                <strong v-else>No Progress</strong>
                            <!-- <input type="hidden" name="appointmentId" value=""> -->
                            </a>
                        </td>
                        <td v-else>
                            @{{appointment.progress}}
                        </td>
                        <td>@{{appointment.status}}</td>
                        <td>@{{moment(appointment.appointment_date).format('MMM D, YYYY')}} @{{ appointment.from }} - @{{ appointment.to }}</td>
                        <td>
                            @{{appointment.doctor_name ? appointment.doctor_name : ''}}
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-md-4">
                                    <a :href="base_url + '/appointments/' + appointment.id" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View</a>
                                </div>
                                <div class="col-md-8">
                                    <form :action="base_url + '/appointments/' + appointment.id" method="POST">
                                        {{csrf_field()}}{{method_field('DELETE')}}
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you Sure?')"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <h4 v-else>There are no appointments</h4>
            <div class="row">
                <div class="col-md-6">
                    <p v-if="counts && counts > params.perPage && (params.pageNo * params.perPage < counts)">Showing @{{ (params.pageNo * params.perPage) - (params.perPage - 1) }} to @{{ params.pageNo * params.perPage }} of @{{ counts }} entries</p>
                    <p v-if="counts && counts > params.perPage && (((params.pageNo * params.perPage) - (params.perPage - 1)) < counts && params.pageNo * params.perPage > counts)">Showing @{{ (params.pageNo * params.perPage) - (params.perPage - 1) }} to @{{ counts }} of @{{ counts }} entries</p>
                    <p v-if="counts && counts < params.perPage">Showing @{{ (params.pageNo * params.perPage) - (params.perPage - 1) }} to @{{ counts }} of @{{ counts }} entries</p>
                    <p v-if="counts && counts === params.perPage">Showing @{{ (params.pageNo * params.perPage) - (params.perPage - 1) }} to @{{ counts }} of @{{ counts }} entries</p>
                </div>
                <div class="col-md-6">
                    <div class="pull-right">
                        <span class="fa fa-angle-left custom-arrow" style="font-size: 25px" @click="filterParam('page', 'previous')"></span>
                        <span class="fa fa-angle-right custom-arrow" style="font-size: 25px" @click="filterParam('page', 'next')"></span>
                        <span class="count"></span>
                    </div>
                </div>
            </div>
        </div>


        {{-- Chane Appointment Progress --}}
        <div class="modal modal-success fade" id="appointment_progress">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Change Appointment Progress.

                            {{--@if ($appointment->progress == 'pending')
                                <strong style="color: white;">Current Progress: Pending</strong>
                            @elseif($appointment->progress == 'arrived')
                                <strong style="color: white;">Current Progress: Arrived</strong>
                            @elseif($appointment->progress == 'send_in')
                                <strong style="color: white;">Current Progress: Send In</strong>
                            @elseif($appointment->progress == 'left')
                                <strong style="color: white;">Current Progress: Left</strong>
                            @elseif($appointment->progress == 'did_not_attend')
                                <strong style="color: white;">Current Progress: Did Not Attend</strong>
                            @else
                              <strong style="color: white;">No Progress</strong>
                            @endif--}}

                        </h4>
                    </div>
                    <div class="modal-body">

                        {{-- <form action="{{route('appointment.progress', $appointment->id)}}" method="POST"> --}}
                        <form action="" method="POST" id="changeAppointmentProgressForm">
                            @csrf
                            <div class="form-group">
                                <label>Appointment Progress</label>
                                <select name="progress" id="progress" class="form-control">
                                    <option value="pending">Pending</option>
                                    <option value="arrived">Arrived</option>
                                    <option value="send_in">Send In</option>
                                    <option value="left">Left</option>
                                    <option value="did_not_attend">Did Not Attend</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="submit" class="btn btn-sm btn-block btn-primary" value="Change Status">
                            </div>
                        </form>

                    </div>

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Create New Appointment</h4>
                    </div>
                    <div class="modal-body">

                        <form action="{{route('appointments.store')}}" method="post">

                            {{csrf_field()}}

                            <div class="row" style="padding: 1%;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Select Service</label>

                                        @if(count($services) > 0)
                                            <select onchange="getDoctor()" name="service_id" id="service_id" class="form-control" required>
                                                <option selected disabled>Select service</option>
                                                @foreach ($services as $service)
                                                    <option value="{{$service->id}}">{{$service->name}}</option>
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
                                            <option selected disabled>Select service first</option>
                                            {{--@foreach ($users as $user)
                                                <option value="{{$user->id}}">{{$user->firstname}} {{$user->lastname}}</option>
                                            @endforeach--}}
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
                                                            <input type="hidden" name="from" id="appointmentFromTime">
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
                                            <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
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

                                            <label>Patient Name.
                                                <a href="#" data-toggle="modal" data-target="#addNewPatient">Create New</a>
                                            </label>
                                            <select name="user_id" id="user_id" class="form-control" required>
                                                @foreach ($patients as $patient)
                                                    <option value="{{$patient->id}}">
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
                                    <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" class="btn btn-sm btn-success btn-block" value="Submit Appointment">
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        {{-- Create Patient on the appointment --}}
        <div class="modal fade" id="addNewPatient" role="dialog">
            @include('inc.appointments.create_user.create_user_ajax')
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('/js/vue.min.js') }}"></script>
    <script>
        let Result = null;
        let fp = null;

        // Get availableDateTime for current doctor & month, year
        function getAvailableDateTime(month, year, days) {
            $.ajax({
                type: 'get',
                url: 'timings',
                data: {
                    'doctor_id': $('#doctor_id option:selected').val(),
                    'service_id': $('#service_id option:selected').val(),
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

        function changeAppointmentProgress(id, progress) {
            $("#progress > option").each(function() {
                if ($(this).val() === progress) {
                    $(this).prop('selected', true);
                }
            });

            $('#appointment_progress').modal('show');
            $('#appointment_progress').find('form').attr('action', '{{ url('progress') }}/' + id);
        }

        function setTo() {
            flatpickr(".flatpickrFilterTo", {
                altInput: true,
                allowInput: true,
                altFormat: 'd F, Y',
                minDate: $('#appointment_from').val(),
            });
        }

        $(document).ready(function () {
            flatpickr(".flatpickrFilter", {
                altInput: true,
                allowInput: true,
                altFormat: 'd F, Y',
            });
        })

        // New vue instance
        new Vue({
            el: '#appointments-wrapper',
            data: {
                base_url: window.location.origin,
                appointments: [],
                counts: 0,
                params: {
                    keyword: '',
                    appointment_date: '',
                    end_date: '',
                    doctor_id: 'all',
                    pageNo: 1,
                    perPage: 10,
                    sort_by: '',
                    order_by: '',
                    _token: '{{ csrf_token() }}'
                },
                keywordTyping: ''
            },
            methods: {
                filterParam(key, val) {
                    _this = this;

                    if (key == 'page') {
                        if (val == 'next') {
                            if (_this.params.pageNo * _this.params.perPage > _this.counts) {
                                return false;
                            }

                            _this.params.pageNo += 1;
                        } else {
                            if (_this.params.pageNo == 1) {
                                return false;
                            }

                            _this.params.pageNo -= 1;
                        }
                    } else if (key == 'sort') {
                        if (_this.params.order_by == '') {
                            _this.params.order_by = 'asc';
                        } else {
                            if (_this.params.order_by == 'asc') {
                                _this.params.order_by = 'desc';
                            } else {
                                _this.params.order_by = 'asc';
                            }
                        }

                        _this.params.sort_by = val;
                    }

                    _this.getAppointments();
                },
                getAppointments() {
                    _this = this;

                    // Submit the form using AJAX.
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('appointments.all') }}',
                        data: _this.params,
                        success: function(response) {
                            _this.counts = response.data.counts;
                            _this.appointments = response.data.appointments;
                            $('#appointments-wrapper').fadeIn();
                        }
                    });
                },
                keyTyping: function(){
                    let THIS = this;
                    clearInterval(THIS.keywordTyping);
                    THIS.keywordTyping = setInterval(function () {
                        clearInterval(THIS.keywordTyping);
                        THIS.getAppointments();
                    }, 1000)
                },
            },
            mounted: function () {
                _this = this;
                this.getAppointments();
                setInterval(function () {
                    _this.getAppointments();
                }, 5000);
            }
        })
    </script>

@stop
