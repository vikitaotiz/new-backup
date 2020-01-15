<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
@yield('title', config('adminlte.title', 'AdminLTE 2'))
@yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css') }}">

    @if(config('adminlte.plugins.select2'))
        <!-- Select2 -->
        <link rel="stylesheet" href="{{asset('css/select2.css')}}">
        {{-- <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css"> --}}
    @endif

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}">

    @if(config('adminlte.plugins.datatables'))
        <!-- DataTables with bootstrap 3 style -->
        <link rel="stylesheet" href="{{asset('css/datatables.css')}}">
        {{-- <link rel="stylesheet" href="//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.css"> --}}
    @endif

    @yield('adminlte_css')


    <link rel="stylesheet" href="{{asset('css/fullcalendar.css')}}">

    {{-- <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/> --}}
    <link rel="stylesheet" href="{{asset('flatdatepicker/flatdatepicker.css')}}">
    {{-- <link rel="stylesheet" href="{{asset('css/fullcalendar_core.css')}}">
    <link rel="stylesheet" href="{{asset('css/fullcalendar_list.css')}}"> --}}
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @toastr_css

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        @media print {
            a[href]:after {
                display: none;
                visibility: hidden;
            }
            #addchat-bubble {
                display: none;
                visibility: hidden;
            }
            .dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate {
                display: none;
                visibility: hidden;
            }
            .invFixed{
                table-layout: auto; width: 100%;
            }
            .invFixed th::after{
                display: none!important;
            }
        }
        .collapse.in, .collapse{
            height: 600px;
            width: 700px;
            overflow-y: scroll;
            /* max-height:200px; */
            }
        #patient-wrapper {
            display: none;
        }
        .custom-arrow{
            font-size: 30px;
            margin-right: 10px;
            cursor: pointer;
        }
        .count{
            margin: -2px 0 0 0;
            display: table;
            float: right;
            font-size: 20px;
        }
        .table-custom thead tr th {
            position: relative;
            cursor: pointer;
        }
        .table-custom-header::after {
            opacity: 0.2;
            content: "\e150";
            position: absolute;
            bottom: 8px;
            right: 8px;
            display: block;
            font-family: 'Glyphicons Halflings';
        }
        .sorting_asc::after {
            content: "\e155";
            position: absolute;
            bottom: 8px;
            right: 8px;
            display: block;
            font-family: 'Glyphicons Halflings';
            opacity: 0.5;
        }
        .sorting_desc::after {
            content: "\e156";
            position: absolute;
            bottom: 8px;
            right: 8px;
            display: block;
            font-family: 'Glyphicons Halflings';
            opacity: 0.5;
        }
        span.select2.select2-container.select2-container--default {
            width: 100% !important;
        }
    </style>

    @if(\Illuminate\Support\Facades\Auth::check() && auth()->user()->role_id != 5 && auth()->user()->role_id != 1 && auth()->user()->role_id != 2)
    <!-- 1. Addchat css -->
    <link href="<?php echo asset('assets/addchat/css/addchat.min.css') ?>" rel="stylesheet">
    @endif
</head>
<body class="hold-transition @yield('body_class')">

@yield('body')

<!-- 2. AddChat widget -->
<div id="addchat_app"
    data-baseurl="<?php echo url('') ?>"
    data-csrfname="<?php echo 'X-CSRF-Token' ?>"
    data-csrftoken="<?php echo csrf_token() ?>"
></div>


{{--<script src="{{asset('js/app.js')}}"></script>--}}

<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>


<link rel="stylesheet" href="{{asset('summernote/summernote.css')}}">
{{-- <link rel="stylesheet" href="{{asset('summernote/cerulean.css')}}"> --}}
<script src="{{asset('summernote/summernote.js')}}"></script>


{{-- <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script> --}}
<script src="{{asset('js/moment.js')}}"></script>

{{-- <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script> --}}
<script src="{{asset('js/fullcalendar.js')}}"></script>
{{-- <script src="{{asset('js/fullcalendar_main.js')}}"></script>
<script src="{{asset('js/fullcalendar_core.js')}}"></script>
<script src="{{asset('js/fullcalendar_list.js')}}"></script> --}}

@if(config('adminlte.plugins.select2'))
    <!-- Select2 -->
    <script src="{{asset('js/select2.js')}}"></script>
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> --}}
@endif

@if(config('adminlte.plugins.datatables'))
    <!-- DataTables with bootstrap 3 renderer -->
    {{-- <script src="{{asset('js/datatables.js')}}"></script> --}}
    {{-- <script src="//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.js"></script> --}}
@endif

@if(config('adminlte.plugins.chartjs'))
    <!-- ChartJS -->
    <script src="{{asset('js/chart.js')}}"></script>

    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js"></script> --}}
@endif

<script src="{{asset('summernote/summer_scripts.js')}}"></script>
<script src="{{asset('flatdatepicker/flatdatepicker.js')}}"></script>

@toastr_js
@toastr_render

@if(\Illuminate\Support\Facades\Auth::check() && auth()->user()->role_id != 5 && auth()->user()->role_id != 1 && auth()->user()->role_id != 2)
<!-- 3. AddChat JS -->
<!-- Modern browsers -->
<script type="module" src="<?php echo asset('assets/addchat/js/addchat.min.js') ?>"></script>
<!-- Fallback support for Older browsers -->
<script nomodule src="<?php echo asset('assets/addchat/js/addchat-legacy.min.js') ?>"></script>
@endif

<script>
    function goBack() {
        window.history.back();
    }

    function printContent(el){
        var restorepage = document.body.innerHTML;
            var printcontent = document.getElementById(el).innerHTML;
            document.body.innerHTML = printcontent;
            window.print();
            document.body.innerHTML = restorepage;
    }

    // flatpickr("#appointment_date", {
    //     enableTime: true,
    //     altInput: true,
    // });

    $( document ).ready(function() {
        flatpickr("#end_time", {
            enableTime: true
        });

        flatpickr("#timing_from", {
            enableTime: true
        });

        flatpickr("#timing_to", {
            enableTime: true
        });

        flatpickr('#to');
        flatpickr('#from');
        flatpickr('#date_of_birth');
        flatpickr('#deadline');
        flatpickr('#due_date');

        $('#patients_list').select2({
            placeholder: "Select a patient",
            width: '100%',
            allowClear: true
        });

        $('#medication_id').select2({
            placeholder: "Select medication",
            width: '100%',
            allowClear: true
        });

        $('#user_id_test').select2({
            placeholder: "Select a patient",
            width: '100%',
            tags: 'true',
            allowClear: true
        });

        $.fn.modal.Constructor.prototype.enforceFocus = function() {};

        $('#patient_id').select2({
            placeholder: "Select a patient",
            width: '100%',
            allowClear: true
        });

        $('#company_id').select2({
            placeholder: "Select a company",
            width: '100%',
            allowClear: true
        });

        $('#product_service').select2({
            placeholder: "Select a product or service",
            width: '100%',
            allowClear: true
        });

        $('#user_id').select2({
            placeholder: "Select a patient",
            width: '100%',
            allowClear: true
        });

       $('#doctor_id').select2({
           placeholder: "Select staff/doctor",
           width: '100%',
           allowClear: true
       });

        $('#service_id').select2({
            placeholder: "Select service",
            width: '100%',
            allowClear: true
        });

       $('#addchat-bubble').addClass('no-print');
    });

    //  "initComplete": function(settings, json) {
    //     $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
    //  }
</script>

@yield('adminlte_js')

</body>
</html>
