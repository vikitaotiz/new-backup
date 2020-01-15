@extends('adminlte::page')

@section('title', 'Reports Patients by total invoiced')

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
                    <strong>Reports Patients by total invoiced</strong>
                </div>
                <div class="col-md-4">
                    <div class="float-right"><a href="javascript:print()" class="btn btn-default"><i class="fa fa-print"></i> Print</a></div>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <form class="no-print" method="post" action="{{ route('reports.patients.patients_by_total_invoiced') }}" id="appointment-form">
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
                        <h3 id="date"></h3>
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr class="r-table__row-header">
                                <th>Name</th>
                                <th>Spent</th>
                                <th>Email</th>
                                <th>Phone #</th>
                                <th>Address</th>
                            </tr>
                            </thead>
                            <tbody id="patients">

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
                    $('#patients').html('');
                    $('.result').show();

                    if (data.length > 0) {
                        if (data.length === 1) {
                            var verb = 'is';
                        } else {
                            var verb = 'are';
                        }

                        $('.alert-danger').hide();
                        $('.success-result').show();
                        $('.alert-info').show();
                        $('#info').text('There ' + verb + ' ' + data.length + ' patients with an invoice from ' + formatDate($('#from').val()) + ' to ' + formatDate($('#to').val()) + ' for ' + $('#doctor_id option:selected').text());

                        data.forEach(function (value, index, array) {
                            $('#patients').append(
                                '<tr class="r-table__row r-table__row--last">\n' +
                                '<td class="r-table__cell" data-label="Patient"><a href="javascript:void(0)">'+ value.user.firstname +' '+ value.user.lastname +'</a></td>\n' +
                                '<td class="r-table__cell break-word" data-label="Practitioner">' + value.quantity * value.charge.amount + ' (' + value.currency.symbol + ')</td>' +
                                '<td class="r-table__cell pad-b3 lg-pad-b0 r-table__cell--last" data-label="Email">'+ value.user.email +'</td>' +
                                '<td class="r-table__cell pad-b3 lg-pad-b0 r-table__cell--last" data-label="Phone">'+ value.user.phone +'</td>' +
                                '<td class="r-table__cell pad-b3 lg-pad-b0 r-table__cell--last" data-label="Address">'+ value.user.address +'</td>' +
                                '</tr>'
                            );
                        });
                    } else {
                        $('.alert-danger').show();
                        $('.success-result').hide();
                        $('.alert-info').hide();
                        $('#info1').text('There are no patients with an invoice from ' + formatDate($('#from').val()) + ' to ' + formatDate($('#to').val()) + ' for ' + $('#doctor_id option:selected').text());
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
