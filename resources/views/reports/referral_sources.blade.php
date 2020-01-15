@extends('adminlte::page')

@section('title', 'Referral Sources Marketing Reports')

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
                    <strong>Referral Sources Marketing Reports</strong>
                </div>
                <div class="col-md-4">
                    <div class="float-right"><a href="javascript:print()" class="btn btn-default"><i class="fa fa-print"></i> Print</a></div>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <form method="post" class="no-print" action="{{ route('reports.marketing.referral_sources') }}" id="appointment-form">
                @csrf
                <div class="form-row">
                    <div class="col-md-3">
                        <label for="appointment_date">From <span class="text-danger">*</span></label>
                        <input  class="form-control flatpickr flatpickr-input" onchange="setTo()" type="text" id="from" name="from" placeholder="Select From Date" readonly="readonly" required>
                    </div>
                    <div class="col-md-3">
                        <label for="end_date">To <span class="text-danger">*</span></label>
                        <input  class="form-control flatpickrTo flatpickr-input" type="text" id="to" name="to" placeholder="Select To Date" readonly="readonly" required>
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
                <div class="col-md-12">
                    <div class="success-result">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>Referral sources marketing report</h4>
                                    </div>
                                    <div class="col-md-4 text-right">

                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div style="width: 400px;height: 400px;display: table;margin: auto">
                                        <canvas id="referral" width="400" height="400"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script>
        flatpickr(".flatpickr", {
            altInput: true,
            allowInput: true,
            altFormat: 'd F, Y',
        });

        function setTo() {
            flatpickr(".flatpickrTo", {
                altInput: true,
                allowInput: true,
                altFormat: 'd F, Y',
                minDate: $('#from').val(),
            });
        }

        // Referral sources marketing report chart
        function referralPieChart(data) {
            var ctx = document.getElementById('referral');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Referral sources'],
                    datasets: [{
                        label: 'Referral sources marketing report',
                        data: [data],
                        backgroundColor: ['#f6ff48']
                    }]
                }
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
                success: function (data) {
                    $('.result').show();

                    if (data > 0) {
                        referralPieChart(data);
                        if (data === 1) {
                            var verb = 'is';
                        } else {
                            var verb = 'are';
                        }

                        $('.alert-danger').hide();
                        $('.success-result').show();
                        $('.alert-info').show();
                        $('#info').text('There ' + verb + ' ' + data + ' referrals from ' + formatDate($('#from').val()) + ' to ' + formatDate($('#to').val()) + ' for ' + $('#doctor_id option:selected').text());
                    } else {
                        $('.alert-danger').show();
                        $('.alert-info').hide();
                        $('.success-result').hide();
                        $('#info1').text('There are no referrals from ' + formatDate($('#from').val()) + ' to ' + formatDate($('#to').val()) + ' for ' + $('#doctor_id option:selected').text());
                    }
                }
            });
        });

        // Format date
        function formatDate(date) {
            var d = new Date(date);

            return d.toDateString();
        }

        // Format date
        function formatDateToStandard(date) {
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
    </script>
@endsection
