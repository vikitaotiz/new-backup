<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Book</title>
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
        .eventBody{
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
        .user-info {
            display: none;
        }
        .flatpickr-calendar {
            width: 100%!important;
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
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css') }}">
</head>
<body>

<div class="container">
    <div class="well">
        @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Success!</strong> {{ Session::get('success') }}
            </div>
            <a href="{{ url('/') }}" class="btn btn-success">Back to site</a>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h2>Appointment Book</h2>
        <form class="form-horizontal" action="{{route('appointment.book')}}" method="post">

            {{csrf_field()}}

            <div class="appointment">
                <div class="row" style="padding: 1%;">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Service <span class="text-danger">*</span></label>

                            @if($services->count() > 0)
                                <select @if(request('id')) onchange="getDoctor({{ request('id') }})" @else onchange="getDoctor()" @endif name="service_id" id="service_id" class="form-control" required>
                                    <option selected disabled>Select service</option>
                                    @foreach ($services as $service)
                                        <option value="{{$service->id}}">{{$service->name}}</option>
                                    @endforeach
                                </select>
                            @else
                                <p>No Service Available.</p>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Doctor <span class="text-danger">*</span></label>
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
                                                <input type="hidden" name="from" id="from">
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


                {{--<div class="row" style="padding: 1%;">
                    <label>Choose Appointment Color <span class="text-danger">*</span></label>
                    <input type="color" name="color" id="color" class="form-control" required>
                </div><hr>--}}

                <div class="col-md-12">
                    <div class="row" style="padding: 1%;">
                        <div class="form-group">
                            <label>Comment </label>
                            <textarea name="description" id="description" class="form-control" placeholder="Enter Comment...">{{old('description')}}</textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row" style="padding: 1%;">
                    <div class="col-md-6">

                    </div>
                    <div class="col-md-6">
                        <input type="button" class="btn btn-sm btn-success btn-block" onclick="validate()" value="Continue">
                    </div>
                </div>
            </div>

            <div class="user-info">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Your Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form class="form-horizontal">
                        <div class="box-body">
                            <p style="margin-bottom: 15px!important;">
                                Please make sure you fill out all information requested. Required fields are marked *
                            </p>
                            <div class="form-group">
                                <label for="" class="col-sm-2 ">First Name <span>*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="firstname" class="form-control" placeholder="First Name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 ">Last Name <span>*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 ">Gender <span>*</span></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" value="male" checked>Male
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" value="female">Female
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 ">Date of Birth <span>*</span></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <select type="text" name="dob_day" class="form-control" required>
                                                @php
                                                    for ($d=1; $d<=31; $d++) {
                                                        echo '  <option value="' . $d . '">' . $d . '</option>' . PHP_EOL;
                                                    }
                                                @endphp
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <select type="text" name="dob_month" class="form-control" required>
                                                @php
                                                    for ($m=1; $m<=12; $m++) {
                                                        echo '  <option value="' . $m . '">' . date('M', mktime(0,0,0,$m)) . '</option>';
                                                    }
                                                @endphp
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <select type="text" name="dob_year" class="form-control" required>
                                                @php
                                                    $cutoff = 1970;
                                                    $now = date('Y');
                                                        for ($y=$now; $y>=$cutoff; $y--) {
                                                           echo '  <option value="' . $y . '">' . $y . '</option>';
                                                       }
                                                @endphp
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12" style="border-bottom: 1px solid #D9D9E0">
                                    <h4>
                                        Your Contact Details
                                    </h4>
                                </div>
                                <div class="col-sm-12">
                                    <p>
                                        We need this to confirm your booking.
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 ">Email Address <span>*</span></label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control" placeholder="Enter Your Email" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 ">Mobile Number <span>*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="phone" class="form-control" placeholder="Enter Mobile Number" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12" style="border-bottom: 1px solid #D9D9E0">
                                    <h4>
                                        Extra information
                                    </h4>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 ">More info</label>
                                <div class="col-sm-10">
                                    <textarea type="text" name="more_info" class="form-control" placeholder="Enter Your Comments"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="checkbox">
                                        <label><input type="checkbox"> Remember me</label>
                                        <p><i>Don’t check this if you are using a public computer.</i></p>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Book appointment <i class="fa fa-check"></i></button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.js"></script>
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
        $('#from').val('');

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

        fp = flatpickr(".flatpickr", {
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
        $('#from').val($(_this).text());

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
    function getDoctor(id) {
        if (id) {
            var url = window.location.origin + '/doctor-by-service/' + $('#service_id').val() + '?id=' + id;
        } else {
            var url = window.location.origin + '/doctor-by-service/' + $('#service_id').val();
        }

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
            url: url,
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

    function validate() {
        var service_id = $("#service_id option:selected").val();
        var doctor_id = $("#doctor_id option:selected").val();
        var appointment_date = $('input[name="appointment_date"]').val();
        var from = $('input[name="from"]').val();

        if($.trim(service_id).length > 0 && $.trim(doctor_id).length > 0 &&
            $.trim(appointment_date).length > 0 &&
            $.trim(from).length > 0){
            $('.user-info').show();
            $('.appointment').hide();
            return true;
        } else {
            alert("Please fill out all necessary fields.");
            return false;
        }
    }
</script>
</body>
</html>
