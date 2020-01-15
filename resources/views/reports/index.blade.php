@extends('adminlte::page')

@section('title', 'Reports')

@section('content_header')
    <h1>Reports</h1>
@stop

@section('content')
    <div class="row">
        <!-- col -->
        <div class="col-lg-3 col-md-6 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>Appointments</h3>

                    <p>Appointments schedule</p>
                </div>
                <div class="icon">
                    <i class="ion ion-calendar"></i>
                </div>
                <a href="{{route('reports.appointments.schedule')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->

        <!-- col -->
        <div class="col-lg-3 col-md-6 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>Appointments</h3>

                    <p>Missed appointments</p>
                </div>
                <div class="icon">
                    <i class="ion ion-calendar"></i>
                </div>
                <a href="{{route('reports.appointments.missed')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->

        <!-- col -->
        <div class="col-lg-3 col-md-6 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-blue-gradient">
                <div class="inner">
                    <h3>Marketing</h3>

                    <p>Appointment types marketing report</p>
                </div>
                <div class="icon">
                    <i class="ion ion-briefcase"></i>
                </div>
                <a href="{{route('reports.marketing.appointments')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->

        <!-- col -->
        <div class="col-lg-3 col-md-6 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>Patients</h3>

                    <p>Patients by total invoiced</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-stalker"></i>
                </div>
                <a href="{{route('reports.patients.patients_by_total_invoiced')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- col -->
        <div class="col-lg-3 col-md-6 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>Marketing</h3>

                    <p>Referral sources marketing reports</p>
                </div>
                <div class="icon">
                    <i class="ion ion-briefcase"></i>
                </div>
                <a href="{{route('reports.marketing.referral_sources')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
@stop
