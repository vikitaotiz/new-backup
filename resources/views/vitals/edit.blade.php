@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
                <div class="col-md-4">
                    <strong>Edit Vital Note</strong>
                </div>
                <div class="col-md-4">
                    {{-- <a href="{{route('roles.create')}}" class="btn btn-sm btn-primary">Create New Role</a> --}}
                </div>
            </div>
        </div>

        <div class="panel-body">
            @include('inc.tabMenu', ['tabMenuPosition'=>5, 'patient_id'=>$vital->user_id])
            <form action="{{route('vitals.update', $vital->id)}}" method="post">
                {{csrf_field()}} {{method_field('PUT')}}
                <div style="padding: 1%;">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Patient Name</label>
                                <select name="user_id" id="patient_id" class="form-control">
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}"
                                                @if($user->id == $vital->user_id)
                                                selected
                                                @endif>
                                            DOB : {{$user->date_of_birth}} -
                                            {{$user->firstname}} {{$user->lastname}}
                                            NHS : {{$user->nhs_number}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Height</label>
                                <input type="text" name="height" class="form-control" value="{{$vital->height}}"
                                       placeholder="Enter height...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Weight</label>
                                <input type="text" name="weight" class="form-control" value="{{$vital->weight}}"
                                       placeholder="Enter weight...">
                            </div>
                        </div>

                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Temperature</label>
                                <input type="text" name="temperature" class="form-control"
                                       value="{{$vital->temperature}}" placeholder="Enter temperature...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pulse Rate</label>
                                <input type="text" name="pulse_rate" class="form-control" value="{{$vital->pulse_rate}}"
                                       placeholder="Enter Pulse Rate...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Systolic BP</label>
                                <input type="text" name="systolic_bp" class="form-control"
                                       value="{{$vital->systolic_bp}}" placeholder="Enter Systolic BP...">
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Diastolic BP</label>
                                <input type="text" name="diastolic_bp" class="form-control"
                                       value="{{$vital->diastolic_bp}}" placeholder="Enter Diastolic BP...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Respiratory Rate</label>
                                <input type="text" name="respiratory_rate" class="form-control"
                                       value="{{$vital->respiratory_rate}}" placeholder="Enter Respiratory Rate...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Oxygen Saturation</label>
                                <input type="text" name="oxygen_saturation" class="form-control"
                                       value="{{$vital->oxygen_saturation}}" placeholder="Enter Oxygen Saturation...">
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>O2 Administered</label>
                                <input type="text" name="o2_administered" class="form-control"
                                       value="{{$vital->o2_administered}}" placeholder="Enter O2 Administered...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pain</label>
                                <input type="text" name="pain" class="form-control" value="{{$vital->pain}}"
                                       placeholder="Enter pain...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Head Circumference</label>
                                <input type="text" name="head_circumference" class="form-control"
                                       value="{{$vital->head_circumference}}" placeholder="Enter Head Circumference...">
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <input type="submit" class="btn btn-sm btn-success btn-block" value="Edit Vitals Note">
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection
