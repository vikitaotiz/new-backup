@extends('adminlte::page')

@section('title', 'Appointment types marketing report')

@section('css')
    <style>
        .float-right{
            float: right!important;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="{{ asset('css/Chart.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="panel panel-success">
        <div class="no-print panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
                <div class="col-md-4">
                    <strong>Appointment types marketing report</strong>
                </div>
                <div class="col-md-4">
                    <div class="float-right"><a href="javascript:print()" class="btn btn-default"><i class="fa fa-print"></i> Print</a></div>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <form method="post" class="no-print" action="{{ route('reports.marketing.appointments') }}" id="appointment-form">
                @csrf
                <div class="form-row">
                    <div class="col-md-3">
                        <label for="appointment_date">From <span class="text-danger">*</span></label>
                        <input  class="form-control flatpickr flatpickr-input" onchange="setTo()" type="text" id="appointment_date" name="appointment_date" placeholder="Select From Date" readonly="readonly" required>
                    </div>
                    <div class="col-md-3">
                        <label for="end_date">To <span class="text-danger">*</span></label>
                        <input  class="form-control flatpickrTo flatpickr-input" type="text" id="end_date" name="end_date" placeholder="Select To Date" readonly="readonly" required>
                    </div>
                    <div class="col-md-2">
                        <br>
                        <button type="submit" class="btn btn-danger"> Create report</button>
                    </div>
                </div>
            </form>

            <div class="row">
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
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4>Appointment types summery</h4>
                                </div>
                                <div class="col-md-4 text-right">

                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div style="width: 400px;height: 400px;display: table;margin: 10px auto;">
                                    <canvas id="pieChart" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4>Appointment types by month</h4>
                                </div>
                                <div class="col-md-4 text-right">

                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div style="width: 100%;height: 400px;display: table;margin: auto">
                                    <canvas id="barChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4>Online bookings</h4>
                                </div>
                                <div class="col-md-4 text-right">

                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div style="width: 400px;height: 400px;display: table;margin: auto">
                                    <canvas id="bookings" height="400"></canvas>
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
        let appointments = [<?php echo $data?>];
        var date = new Date(), y = date.getFullYear(), m = date.getMonth();
        var firstDay = new Date(y, m, 1);
        var lastDay = new Date(y, m + 1, 0);

        function setTo() {
            flatpickr(".flatpickrTo", {
                altInput: true,
                allowInput: true,
                altFormat: 'd F, Y',
                minDate: $('#appointment_date').val(),
            });
        }

        // A $( document ).ready() block.
        $( document ).ready(function() {
            flatpickr(".flatpickr", {
                defaultDate: formatDateToStandard(firstDay),
                altInput: true,
                allowInput: true,
                altFormat: 'd F, Y',
            });

            flatpickr(".flatpickrTo", {
                defaultDate: formatDateToStandard(lastDay),
                altInput: true,
                allowInput: true,
                altFormat: 'd F, Y',
                minDate: $('#appointment_date').val(),
            });

            barChart(appointments);
            pieChartByCurrentMonth();
            bookingPieChart();
        });

        // Online bookings
        function bookingPieChart() {
            var ctx = document.getElementById('bookings');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Online bookings', 'Others'],
                    datasets: [{
                        label: 'Appointment types summery',
                        data: [{{ $onlineAppointment }}, {{ $otherAppointment }}],
                        backgroundColor: ['#f6ff48','#ff3428']
                    }]
                }
            });
        }

        // Get appointment by current month
        function pieChartByCurrentMonth() {
            $.ajax({
                type: 'post',
                url: '{{ route('reports.marketing.appointments') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    appointment_date: formatDateToStandard(firstDay),
                    end_date: formatDateToStandard(lastDay),
                },
                success: function (data) {
                    pieChart(data);

                    if (data[2] > 0) {
                        if (data[2] === 1) {
                            var verb = 'is';
                        } else {
                            var verb = 'are';
                        }

                        $('.alert-danger').hide();
                        $('.alert-info').show();
                        $('#info').text('There ' + verb + ' ' + data[2] + ' appointment from ' + formatDate(formatDateToStandard(firstDay)) + ' to ' + formatDate(formatDateToStandard(lastDay)));
                    } else {
                        $('.alert-danger').show();
                        $('.alert-info').hide();
                        $('#info1').text('There are no appointments from ' + formatDate(formatDateToStandard(firstDay)) + ' to ' + formatDate(formatDateToStandard(lastDay)));
                    }

                }
            });
        }

        // Generate bar chart
        function barChart(appointments) {
            Chart.defaults.groupableBar = Chart.helpers.clone(Chart.defaults.bar);

            var helpers = Chart.helpers;
            Chart.controllers.groupableBar = Chart.controllers.bar.extend({
                calculateBarX: function (index, datasetIndex) {
                    // position the bars based on the stack index
                    var stackIndex = this.getMeta().stackIndex;
                    return Chart.controllers.bar.prototype.calculateBarX.apply(this, [index, stackIndex]);
                },

                hideOtherStacks: function (datasetIndex) {
                    var meta = this.getMeta();
                    var stackIndex = meta.stackIndex;

                    this.hiddens = [];
                    for (var i = 0; i < datasetIndex; i++) {
                        var dsMeta = this.chart.getDatasetMeta(i);
                        if (dsMeta.stackIndex !== stackIndex) {
                            this.hiddens.push(dsMeta.hidden);
                            dsMeta.hidden = true;
                        }
                    }
                },

                unhideOtherStacks: function (datasetIndex) {
                    var meta = this.getMeta();
                    var stackIndex = meta.stackIndex;

                    for (var i = 0; i < datasetIndex; i++) {
                        var dsMeta = this.chart.getDatasetMeta(i);
                        if (dsMeta.stackIndex !== stackIndex) {
                            dsMeta.hidden = this.hiddens.unshift();
                        }
                    }
                },

                calculateBarY: function (index, datasetIndex) {
                    this.hideOtherStacks(datasetIndex);
                    var barY = Chart.controllers.bar.prototype.calculateBarY.apply(this, [index, datasetIndex]);
                    this.unhideOtherStacks(datasetIndex);
                    return barY;
                },

                calculateBarBase: function (datasetIndex, index) {
                    this.hideOtherStacks(datasetIndex);
                    var barBase = Chart.controllers.bar.prototype.calculateBarBase.apply(this, [datasetIndex, index]);
                    this.unhideOtherStacks(datasetIndex);
                    return barBase;
                },

                getBarCount: function () {
                    var stacks = [];

                    // put the stack index in the dataset meta
                    Chart.helpers.each(this.chart.data.datasets, function (dataset, datasetIndex) {
                        var meta = this.chart.getDatasetMeta(datasetIndex);
                        if (meta.bar && this.chart.isDatasetVisible(datasetIndex)) {
                            var stackIndex = stacks.indexOf(dataset.stack);
                            if (stackIndex === -1) {
                                stackIndex = stacks.length;
                                stacks.push(dataset.stack);
                            }
                            meta.stackIndex = stackIndex;
                        }
                    }, this);

                    this.getMeta().stacks = stacks;
                    return stacks.length;
                },
            });

            var data = {
                labels: JSON.parse('<?php echo $labels; ?>'),
                datasets: appointments
            };

            var ctx = document.getElementById("barChart").getContext("2d");
            new Chart(ctx, {
                type: 'groupableBar',
                data: data,
                options: {
                    scales: {
                        yAxes: [{
                            stacked: true,
                        }]
                    },
                    responsive: true,
                    maintainAspectRatio: false
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
                    pieChart(data);

                    if (data[2] > 0) {
                        if (data[2] === 1) {
                            var verb = 'is';
                        } else {
                            var verb = 'are';
                        }

                        $('.alert-danger').hide();
                        $('.alert-info').show();
                        $('#info').text('There ' + verb + ' ' + data[2] + ' appointment from ' + formatDate($('#appointment_date').val()) + ' to ' + formatDate($('#end_date').val()) + ' for ' + $('#doctor_id option:selected').text());
                    } else {
                        $('.alert-danger').show();
                        $('.alert-info').hide();
                        $('#info1').text('There are no appointments from ' + formatDate($('#appointment_date').val()) + ' to ' + formatDate($('#end_date').val()));
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

        // Create pie chart
        function pieChart(data) {
            var colors = ['#f6ff48','#ff3428','#00ff67','#15fff7','#2a88ff',
                '#ff8675','#e552ff','#ff7c00', '#a6ff0f', '#8b6cff'];
            var colorSet = [];
            $.each(data[0], function (i, v) {
                if(i > 9){
                    i = i % 9;
                    i--;
                }
                colorSet.push(colors[i]);
            });
            var ctx = document.getElementById('pieChart');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data[0],
                    datasets: [{
                        label: 'Appointment types summery',
                        data: data[1],
                        backgroundColor: colorSet
                    }]
                }
            });
        }
    </script>
@endsection
