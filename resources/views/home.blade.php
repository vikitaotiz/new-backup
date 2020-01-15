@extends('adminlte::page')

@section('title', 'HospitalNote')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">

        <!-- ./col -->

      @if (auth()->user()->role_id == 5)

        @include('inc.dashboard.patient-dash')

     @else

        @include('inc.dashboard.dash')

     @endif

        <!-- ./col -->
      </div>
@stop
