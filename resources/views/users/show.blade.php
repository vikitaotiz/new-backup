@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{asset('css/clockpicker.css')}}">
    <style>
        .form-group {
            /*margin-bottom: 35px!important;*/
        }
        .form-group-cld{
            padding-bottom: 15px;
        }
        .form-group-avl{
            width: 100%;
            display: inline-block;
            margin: 0!important;
            padding: 0;
            min-height: 40px;
        }
        .list-label{
            margin-top: 7px!important;
        }
        .custom-ul{
            margin-left: -56px;
            width: 106.4%;
            margin-bottom: -11px;
        }
        .custom-li{
            display: inline-block;
            background-color: #cccc;
            border-top: 1px solid white;
            border-bottom: 1px solid white;
        }
        .time{
            display: none;
        }
        .unavailable{
            display: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@stop

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                     <div class="row">
                        <div class="col-md-2">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>{{$user->firstname}} {{$user->lastname}} : {{$user->role ? $user->role->name : ''}}</strong>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                @if(!$user->email == 'admin@admin.com')

                                <div class="col-md-4">
                                        <strong>Available</strong>
                                    </div>
                                @endif

                                <div class="col-md-4">
                                    <a href="{{route('users.edit', $user->id)}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-pencil"></i> Edit Client</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div></div>

                <div class="">

                    <div class="row">
                            <div class="col-md-3">

                              <!-- Profile Image -->
                              <div class="box box-success">
                                <div class="box-body box-profile">
                                    @if($user->profile_photo)
                                        <img class="profile-user-img img-responsive img-circle" src="{{asset('/storage/'.$user->profile_photo)}}" alt="{{$user->name}}'s profile picture">
                                    @else
                                        <img class="profile-user-img img-responsive img-circle" src="{{asset('img/user.png')}}" alt="{{$user->name}}'s profile picture">
                                    @endif

                                  <h3 class="profile-username text-center">{{$user->name}}</h3>


                                  <ul class="list-group list-group-unbordered">

                                     <li class="list-group-item"><strong>Phone : </strong><a>{{$user->phone}}</a></li>
                                     <li class="list-group-item"><strong>Address : </strong><a>{{$user->address}}</a></li>
                                     <li class="list-group-item"><strong>Role : </strong><a>{{App\Role::find($user->role_id)->name ?? 'No Role'}}</a></li>
                                     <li class="list-group-item"><strong>Email : </strong><a>{{$user->email}}</a></li>
                                     <li class="list-group-item"><strong>GMC Number : </strong><a>{{$user->gmc_no}}</a></li>
                                     <li class="list-group-item"><strong>Created On : </strong><a>{{$user->created_at->format('D jS, M, Y')}}</a></li>

                                  </ul>

                                  {{-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> --}}
                                </div>
                                <!-- /.box-body -->
                              </div>
                              <!-- /.box -->


                              <!-- /.box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-md-9">

                              <div class="nav-tabs-custom">

                                <ul class="nav nav-tabs nav-tabs-success">
                                  <li class="active"><a href="#more_details" data-toggle="tab">More Details</a></li>
                                  {{-- <li><a href="#patients" data-toggle="tab">Patients</a></li> --}}
                                  @if($user->role_id != 6)
                                  <li><a href="#appointments" data-toggle="tab">Appointments</a></li>
                                  <li><a href="#tasks" data-toggle="tab">Tasks</a></li>
                                  <li><a href="#invoices" data-toggle="tab">Invoices</a></li>
                                  <li><a href="#availability" data-toggle="tab">Availability</a></li>
                                  <li><a href="#slot" data-toggle="tab">Appointment slot size</a></li>
                                  <li><a href="#services" data-toggle="tab">Services</a></li>
                                  @endif
                                </ul>

                                <div class="tab-content">

                                  <div class="active tab-pane" id="more_details">

                                    @include('inc.users.single_users.more_details')

                                  @if($user->role_id != 6)
                                  <!-- /.tab-pane -->
                                  <div class="tab-pane" id="appointments">

                                        @include('inc.users.single_users.appointments')

                                  </div>

                                  <div class="tab-pane" id="tasks">

                                        @include('inc.users.single_users.tasks')

                                  </div>

                                  <div class="tab-pane" id="invoices">

                                        @include('inc.users.single_users.invoices')

                                  </div>

                                    <div class="tab-pane" id="availability">
                                        <form action="{{ route('timings.store') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <div class="panel">
                                            <div class="panel-body">
                                                <div class="box-body">

                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <div class="form-group border-bottom form-group-avl">
                                                                <label for="" class="col-sm-2 control-label list-label">
                                                                    <input type="checkbox" name="status_sunday" @if(count($user->timing)) @foreach($user->timing as $timing) @if($timing->day == 1 && $timing->status) checked @endif @endforeach @endif> Sunday
                                                                </label>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group clockpicker">
                                                                        <input type="text" name="sunday_opening" class="form-control" placeholder="00:00" @if(count($user->timing)) value="@foreach($user->timing as $timing) @if($timing->day == 1 && $timing->from != null) {{ date('H:i', strtotime($timing->from)) }} @endif @endforeach" @endif>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1 text-center">
                                                                    <span style="line-height: 30px;display: inline-block">to</span>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group clockpicker">
                                                                        <input type="text" name="sunday_closing" class="form-control" placeholder="00:00" @if(count($user->timing)) value="@foreach($user->timing as $timing) @if($timing->day == 1 && $timing->to != null) {{ date('H:i', strtotime($timing->to)) }} @endif @endforeach" @endif>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group" style="margin: 0">
                                                                        @if($services->count() > 0)
                                                                            <select name="sunday_services[]" class="services form-control" multiple="multiple">
                                                                                @foreach ($services as $service)
                                                                                    <option value="{{$service->id}}" @if(count($user->timing)) @foreach($user->timing()->where('day', 1)->get() as $timing) @if(in_array($service->id, explode(', ', $timing->services))) selected @endif @endforeach @endif>{{$service->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        @else
                                                                            <p>No Service Available.
                                                                                <a href="{{route('services.create')}}">Create Service</a>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    <button id="sunday" type="button" onclick="sunday_added(this);" class="btn btn-default">add break</button>
                                                                </div>
                                                            </div>
                                                            <ul class="custom-ul dreak_fields">
                                                                @foreach($user->timing as $timing)
                                                                    @if($timing->day == 1)
                                                                        @foreach($timing->break as $break)
                                                                            <li class="list-group-item custom-li">
                                                                                <input type="hidden" name="sunday_update_id[]" value="{{ $break->id }}">
                                                                                <div class="form-group border-bottom">
                                                                                    <label for="" class="col-sm-3 control-label list-label">
                                                                                        Break
                                                                                    </label>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="input-group clockpicker">
                                                                                            <input type="text" class="form-control" name="sunday_update_from[]" placeholder="00:00" value="{{ date('H:i', strtotime($break->from)) }}">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-1">to</div>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="input-group clockpicker">
                                                                                            <input type="text" class="form-control" name="sunday_update_to[]" placeholder="00:00" value="{{ date('H:i', strtotime($break->to)) }}">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-2">
                                                                                        <button type="button" onclick="if (confirm('Are you sure want to delete this data?')) {removeBreak({{ $break->id }}); removeThisBreak(this);}" class="btn btn-default">Remove</button>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="form-group border-bottom form-group-avl">
                                                                <label for="" class="col-sm-2 control-label list-label">
                                                                    <input type="checkbox" name="status_monday" @if(count($user->timing)) @foreach($user->timing as $timing) @if($timing->day == 2 && $timing->status) checked @endif @endforeach @endif> Monday
                                                                </label>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group clockpicker">
                                                                        <input type="text" name="monday_opening" class="form-control" placeholder="00:00" @if(count($user->timing)) value="@foreach($user->timing as $timing) @if($timing->day == 2 && $timing->from != null) {{ date('H:i', strtotime($timing->from)) }} @endif @endforeach" @endif>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1 text-center">
                                                                    <span style="line-height: 30px;display: inline-block">to</span>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group clockpicker">
                                                                        <input type="text" name="monday_closing" class="form-control" placeholder="00:00" @if(count($user->timing)) value="@foreach($user->timing as $timing) @if($timing->day == 2 && $timing->to != null) {{ date('H:i', strtotime($timing->to)) }} @endif @endforeach" @endif>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group" style="margin: 0">
                                                                        @if($services->count() > 0)
                                                                            <select name="monday_services[]" class="services form-control" multiple="multiple">
                                                                                @foreach ($services as $service)
                                                                                    <option value="{{$service->id}}" @if(count($user->timing)) @foreach($user->timing()->where('day', 2)->get() as $timing) @if(in_array($service->id, explode(', ', $timing->services))) selected @endif @endforeach @endif>{{$service->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        @else
                                                                            <p>No Service Available.
                                                                                <a href="{{route('services.create')}}">Create Service</a>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    <button id="monday" type="button" class="btn btn-default" onclick="sunday_added(this);">add break</button>
                                                                </div>
                                                            </div>
                                                            <ul class="custom-ul dreak_fields">
                                                                @foreach($user->timing as $timing)
                                                                    @if($timing->day == 2)
                                                                        @foreach($timing->break as $break)
                                                                            <li class="list-group-item custom-li">
                                                                                <input type="hidden" name="monday_update_id[]" value="{{ $break->id }}">
                                                                                <div class="form-group border-bottom">
                                                                                    <label for="" class="col-sm-3 control-label list-label">
                                                                                        Break
                                                                                    </label>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="input-group clockpicker">
                                                                                            <input type="text" class="form-control" name="monday_update_from[]" placeholder="00:00" value="{{ date('H:i', strtotime($break->from)) }}">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-1">to</div>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="input-group clockpicker">
                                                                                            <input type="text" class="form-control" name="monday_update_to[]" placeholder="00:00" value="{{ date('H:i', strtotime($break->to)) }}">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-2">
                                                                                        <button type="button" onclick="if (confirm('Are you sure want to delete this data?')) {removeBreak({{ $break->id }}); removeThisBreak(this);}" class="btn btn-default">Remove</button>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="form-group border-bottom form-group-avl">
                                                                <label for="" class="col-sm-2 control-label list-label">
                                                                    <input type="checkbox" name="status_tuesday" @if(count($user->timing)) @foreach($user->timing as $timing) @if($timing->day == 3 && $timing->status) checked @endif @endforeach @endif> Tuesday
                                                                </label>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group clockpicker">
                                                                        <input type="text" name="tuesday_opening" class="form-control" placeholder="00:00" @if(count($user->timing)) value="@foreach($user->timing as $timing) @if($timing->day == 3 && $timing->from != null) {{ date('H:i', strtotime($timing->from)) }} @endif @endforeach" @endif>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1 text-center">
                                                                    <span style="line-height: 30px;display: inline-block">to</span>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group clockpicker">
                                                                        <input type="text" name="tuesday_closing" class="form-control" placeholder="00:00" @if(count($user->timing)) value="@foreach($user->timing as $timing) @if($timing->day == 3 && $timing->to != null) {{ date('H:i', strtotime($timing->to)) }} @endif @endforeach" @endif>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group" style="margin: 0">
                                                                        @if($services->count() > 0)
                                                                            <select name="tuesday_services[]" class="services form-control" multiple="multiple">
                                                                                @foreach ($services as $service)
                                                                                    <option value="{{$service->id}}" @if(count($user->timing)) @foreach($user->timing()->where('day', 3)->get() as $timing) @if(in_array($service->id, explode(', ', $timing->services))) selected @endif @endforeach @endif>{{$service->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        @else
                                                                            <p>No Service Available.
                                                                                <a href="{{route('services.create')}}">Create Service</a>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    <button id="tuesday" type="button" class="btn btn-default" onclick="sunday_added(this);">add break</button>
                                                                </div>
                                                            </div>
                                                            <ul class="custom-ul dreak_fields">
                                                                @foreach($user->timing as $timing)
                                                                    @if($timing->day == 3)
                                                                        @foreach($timing->break as $break)
                                                                            <li class="list-group-item custom-li">
                                                                                <input type="hidden" name="tuesday_update_id[]" value="{{ $break->id }}">
                                                                                <div class="form-group border-bottom">
                                                                                    <label for="" class="col-sm-3 control-label list-label">
                                                                                        Break
                                                                                    </label>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="input-group clockpicker">
                                                                                            <input type="text" class="form-control" name="tuesday_update_from[]" placeholder="00:00" value="{{ date('H:i', strtotime($break->from)) }}">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-1">to</div>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="input-group clockpicker">
                                                                                            <input type="text" class="form-control" name="tuesday_update_to[]" placeholder="00:00" value="{{ date('H:i', strtotime($break->to)) }}">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-2">
                                                                                        <button type="button" onclick="if (confirm('Are you sure want to delete this data?')) {removeBreak({{ $break->id }}); removeThisBreak(this);}" class="btn btn-default">Remove</button>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </li>

                                                        <li class="list-group-item">
                                                            <div class="form-group border-bottom form-group-avl">
                                                                <label for="" class="col-sm-2 control-label list-label">
                                                                    <input type="checkbox" name="status_wednesday" @if(count($user->timing)) @foreach($user->timing as $timing) @if($timing->day == 4 && $timing->status) checked @endif @endforeach @endif> Wednesday
                                                                </label>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group clockpicker">
                                                                        <input type="text" name="wednesday_opening" class="form-control" placeholder="00:00" @if(count($user->timing)) value="@foreach($user->timing as $timing) @if($timing->day == 4 && $timing->from != null) {{ date('H:i', strtotime($timing->from)) }} @endif @endforeach" @endif>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1 text-center">
                                                                    <span style="line-height: 30px;display: inline-block">to</span>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group clockpicker">
                                                                        <input type="text" name="wednesday_closing" class="form-control" placeholder="00:00" @if(count($user->timing)) value="@foreach($user->timing as $timing) @if($timing->day == 4 && $timing->to != null) {{ date('H:i', strtotime($timing->to)) }} @endif @endforeach" @endif>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group" style="margin: 0">
                                                                        @if($services->count() > 0)
                                                                            <select name="wednesday_services[]" class="services form-control" multiple="multiple">
                                                                                @foreach ($services as $service)
                                                                                    <option value="{{$service->id}}" @if(count($user->timing)) @foreach($user->timing()->where('day', 4)->get() as $timing) @if(in_array($service->id, explode(', ', $timing->services))) selected @endif @endforeach @endif>{{$service->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        @else
                                                                            <p>No Service Available.
                                                                                <a href="{{route('services.create')}}">Create Service</a>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    <button id="wednesday" type="button" class="btn btn-default" onclick="sunday_added(this);">add break</button>
                                                                </div>
                                                            </div>
                                                            <ul class="custom-ul dreak_fields">
                                                                @foreach($user->timing as $timing)
                                                                    @if($timing->day == 4)
                                                                        @foreach($timing->break as $break)
                                                                            <li class="list-group-item custom-li">
                                                                                <input type="hidden" name="wednesday_update_id[]" value="{{ $break->id }}">
                                                                                <div class="form-group border-bottom">
                                                                                    <label for="" class="col-sm-3 control-label list-label">
                                                                                        Break
                                                                                    </label>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="input-group clockpicker">
                                                                                            <input type="text" class="form-control" name="wednesday_update_from[]" placeholder="00:00" value="{{ date('H:i', strtotime($break->from)) }}">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-1">to</div>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="input-group clockpicker">
                                                                                            <input type="text" class="form-control" name="wednesday_update_to[]" placeholder="00:00" value="{{ date('H:i', strtotime($break->to)) }}">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-2">
                                                                                        <button type="button" onclick="if (confirm('Are you sure want to delete this data?')) {removeBreak({{ $break->id }}); removeThisBreak(this);}" class="btn btn-default">Remove</button>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="form-group border-bottom form-group-avl">
                                                                <label for="" class="col-sm-2 control-label list-label">
                                                                    <input type="checkbox" name="status_thursday" @if(count($user->timing)) @foreach($user->timing as $timing) @if($timing->day == 5 && $timing->status) checked @endif @endforeach @endif> Thursday
                                                                </label>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group clockpicker">
                                                                        <input type="text" name="thursday_opening" class="form-control" placeholder="00:00" @if(count($user->timing)) value="@foreach($user->timing as $timing) @if($timing->day == 5 && $timing->from != null) {{ date('H:i', strtotime($timing->from)) }} @endif @endforeach" @endif>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1 text-center">
                                                                    <span style="line-height: 30px;display: inline-block">to</span>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group clockpicker">
                                                                        <input type="text" name="thursday_closing" class="form-control" placeholder="00:00" @if(count($user->timing)) value="@foreach($user->timing as $timing) @if($timing->day == 5 && $timing->to != null) {{ date('H:i', strtotime($timing->to)) }} @endif @endforeach" @endif>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group" style="margin: 0">
                                                                        @if($services->count() > 0)
                                                                            <select name="thursday_services[]" class="services form-control" multiple="multiple">
                                                                                @foreach ($services as $service)
                                                                                    <option value="{{$service->id}}" @if(count($user->timing)) @foreach($user->timing()->where('day', 5)->get() as $timing) @if(in_array($service->id, explode(', ', $timing->services))) selected @endif @endforeach @endif>{{$service->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        @else
                                                                            <p>No Service Available.
                                                                                <a href="{{route('services.create')}}">Create Service</a>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    <button id="thursday" type="button" class="btn btn-default" onclick="sunday_added(this);">add break</button>
                                                                </div>
                                                            </div>
                                                            <ul class="custom-ul dreak_fields">
                                                                @foreach($user->timing as $timing)
                                                                    @if($timing->day == 5)
                                                                        @foreach($timing->break as $break)
                                                                            <li class="list-group-item custom-li">
                                                                                <input type="hidden" name="thursday_update_id[]" value="{{ $break->id }}">
                                                                                <div class="form-group border-bottom">
                                                                                    <label for="" class="col-sm-3 control-label list-label">
                                                                                        Break
                                                                                    </label>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="input-group clockpicker">
                                                                                            <input type="text" class="form-control" name="thursday_update_from[]" placeholder="00:00" value="{{ date('H:i', strtotime($break->from)) }}">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-1">to</div>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="input-group clockpicker">
                                                                                            <input type="text" class="form-control" name="thursday_update_to[]" placeholder="00:00" value="{{ date('H:i', strtotime($break->to)) }}">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-2">
                                                                                        <button type="button" onclick="if (confirm('Are you sure want to delete this data?')) {removeBreak({{ $break->id }}); removeThisBreak(this);}" class="btn btn-default">Remove</button>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="form-group border-bottom form-group-avl">
                                                                <label for="" class="col-sm-2 control-label list-label">
                                                                    <input type="checkbox" name="status_friday" @if(count($user->timing)) @foreach($user->timing as $timing) @if($timing->day == 6 && $timing->status) checked @endif @endforeach @endif> Friday
                                                                </label>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group clockpicker">
                                                                        <input type="text" name="friday_opening" class="form-control" placeholder="00:00" @if(count($user->timing)) value="@foreach($user->timing as $timing) @if($timing->day == 6 && $timing->from != null) {{ date('H:i', strtotime($timing->from)) }} @endif @endforeach" @endif>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1 text-center">
                                                                    <span style="line-height: 30px;display: inline-block">to</span>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group clockpicker">
                                                                        <input type="text" name="friday_closing" class="form-control" placeholder="00:00" @if(count($user->timing)) value="@foreach($user->timing as $timing) @if($timing->day == 6 && $timing->to != null) {{ date('H:i', strtotime($timing->to)) }} @endif @endforeach" @endif>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group" style="margin: 0">
                                                                        @if($services->count() > 0)
                                                                            <select name="friday_services[]" class="services form-control" multiple="multiple">
                                                                                @foreach ($services as $service)
                                                                                    <option value="{{$service->id}}" @if(count($user->timing)) @foreach($user->timing()->where('day', 6)->get() as $timing) @if(in_array($service->id, explode(', ', $timing->services))) selected @endif @endforeach @endif>{{$service->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        @else
                                                                            <p>No Service Available.
                                                                                <a href="{{route('services.create')}}">Create Service</a>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    <button id="friday" type="button" class="btn btn-default" onclick="sunday_added(this);">add break</button>
                                                                </div>
                                                            </div>
                                                            <ul class="custom-ul dreak_fields">
                                                                @foreach($user->timing as $timing)
                                                                    @if($timing->day == 6)
                                                                        @foreach($timing->break as $break)
                                                                            <li class="list-group-item custom-li">
                                                                                <input type="hidden" name="friday_update_id[]" value="{{ $break->id }}">
                                                                                <div class="form-group border-bottom">
                                                                                    <label for="" class="col-sm-3 control-label list-label">
                                                                                        Break
                                                                                    </label>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="input-group clockpicker">
                                                                                            <input type="text" class="form-control" name="friday_update_from[]" placeholder="00:00" value="{{ date('H:i', strtotime($break->from)) }}">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-1">to</div>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="input-group clockpicker">
                                                                                            <input type="text" class="form-control" name="friday_update_to[]" placeholder="00:00" value="{{ date('H:i', strtotime($break->to)) }}">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-2">
                                                                                        <button type="button" onclick="if (confirm('Are you sure want to delete this data?')) {removeBreak({{ $break->id }}); removeThisBreak(this);}" class="btn btn-default">Remove</button>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="form-group border-bottom form-group-avl">
                                                                <label for="" class="col-sm-2 control-label list-label">
                                                                    <input type="checkbox" name="status_saturday" @if(count($user->timing)) @foreach($user->timing as $timing) @if($timing->day == 7 && $timing->status) checked @endif @endforeach @endif> Saturday
                                                                </label>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group clockpicker">
                                                                        <input type="text" name="saturday_opening" class="form-control" placeholder="00:00" @if(count($user->timing)) value="@foreach($user->timing as $timing) @if($timing->day == 7 && $timing->from != null) {{ date('H:i', strtotime($timing->from)) }} @endif @endforeach" @endif>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1 text-center">
                                                                    <span style="line-height: 30px;display: inline-block">to</span>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="input-group clockpicker">
                                                                        <input type="text" name="saturday_closing" class="form-control" placeholder="00:00" @if(count($user->timing)) value="@foreach($user->timing as $timing) @if($timing->day == 7 && $timing->to != null) {{ date('H:i', strtotime($timing->to)) }} @endif @endforeach" @endif>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group" style="margin: 0">
                                                                        @if($services->count() > 0)
                                                                            <select name="saturday_services[]" class="services form-control" multiple="multiple">
                                                                                @foreach ($services as $service)
                                                                                    <option value="{{$service->id}}" @if(count($user->timing)) @foreach($user->timing()->where('day', 7)->get() as $timing) @if(in_array($service->id, explode(', ', $timing->services))) selected @endif @endforeach @endif>{{$service->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        @else
                                                                            <p>No Service Available.
                                                                                <a href="{{route('services.create')}}">Create Service</a>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    <button id="saturday" type="button" class="btn btn-default" onclick="sunday_added(this);">add break</button>
                                                                </div>
                                                            </div>
                                                            <ul class="custom-ul dreak_fields">
                                                                @foreach($user->timing as $timing)
                                                                    @if($timing->day == 7)
                                                                        @foreach($timing->break as $break)
                                                                            <li class="list-group-item custom-li">
                                                                                <input type="hidden" name="saturday_update_id[]" value="{{ $break->id }}">
                                                                                <div class="form-group border-bottom">
                                                                                    <label for="" class="col-sm-3 control-label list-label">
                                                                                        Break
                                                                                    </label>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="input-group clockpicker">
                                                                                            <input type="text" class="form-control" name="saturday_update_from[]" placeholder="00:00" value="{{ date('H:i', strtotime($break->from)) }}">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-1">to</div>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="input-group clockpicker">
                                                                                            <input type="text" class="form-control" name="saturday_update_to[]" placeholder="00:00" value="{{ date('H:i', strtotime($break->to)) }}">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-2">
                                                                                        <button type="button" onclick="if (confirm('Are you sure want to delete this data?')) {removeBreak({{ $break->id }}); removeThisBreak(this);}" class="btn btn-default">Remove</button>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                    </ul>

                                                </div>

                                            </div>
                                            <button type="submit" class="btn btn-info btn-block">Submit</button>
                                        </div>
                                        </form>

                                        <div class="row" style="margin: 2%;">
                                            <div class="panel-heading">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</a>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <strong>One off availability/unavailability</strong>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <a onclick="createAvailability()" href="javascript:void(0)" class="btn btn-sm btn-success btn-block" data-toggle="modal" data-target="#myModal"><i class="fa fa-calendar-plus-o"></i> Create one off availability/unavailability</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th>Type</th>
                                                    <th>From time</th>
                                                    <th>To time</th>
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($user->availabilities as $availability)
                                                <tr>
                                                    <td>{{ date('d F, Y', strtotime($availability->from)) }}</td>
                                                    <td>
                                                        @if($availability->type)
                                                            {{ date('d F, Y', strtotime($availability->from)) }}
                                                        @else
                                                            {{ date('d F, Y', strtotime($availability->to)) }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($availability->type)
                                                            One-off availability
                                                        @else
                                                            Unavailable block
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($availability->type)
                                                            {{ $availability->from_time }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($availability->type)
                                                            {{ $availability->to_time }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <a onclick="editAvailability('{{ $availability->id }}', '{{ route('availabilities.update', $availability->id) }}')" href="javascript:void(0)" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <form action="{{route('availabilities.destroy', $availability->id)}}" method="POST">
                                                                    {{csrf_field()}}{{method_field('DELETE')}}
                                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you Sure?')"><i class="fa fa-trash"></i> Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            <p></p>
                                        </div><hr>
                                        <!-- /.row -->
                                    </div>

                                    <div class="tab-pane" id="slot">
                                        <form action="{{ route('users.timings.update', $user->id) }}" method="post">
                                            {{csrf_field()}}
                                            @method('put')

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Appointment slot size</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="number" id="slot" name="slot" min="1" value="{{$user->slot}}" placeholder="eg. 15 minutes" required>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-success btn-block">Submit</button>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="services">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</a>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Services</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <a href="{{route('users.edit', $user->id)}}" class="btn btn-sm btn-success btn-primary btn-block"><i class="fa fa-pencil"></i> Add new services</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Service name</th>
                                                <th>Service description</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($user->services as $service)
                                            <tr>
                                                <td>{{ $service->name }}</td>
                                                <td>{{ $service->description }}</td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                  <!-- /.tab-pane -->
                                  @endif
                                </div>
                                <!-- /.tab-content -->
                              </div>
                              <!-- /.nav-tabs-custom -->
                            </div>
                            <!-- /.col -->
                          </div>


                        </div>


{{-- Create Patient on the appointment --}}

<div class="modal fade" id="changeAvailabilityStatus" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                @if ($user->availability == 1)
                                    <div class="modal-header bg-success">
                                        @else
                                            <div class="modal-header bg-danger">
                                                @endif
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Change Availability Status for {{$user->firstname}} {{$user->lastname}} :
                                                    <strong>{{$user->availability ? 'Available' : 'Not Available'}}</strong></h4>
                                            </div>
                                            <div class="modal-body">

                                                <form action="{{route('change_timing_status', $user->id)}}" method="post">

                                                    {{csrf_field()}}

                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select name="availability" class="form-control">
                                                                    <option value=1>Available</option>
                                                                    <option value=0>Not Available</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                                    <div class="row" style="padding: 2%;">
                                                        <div class="col-md-6">
                                                            <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="submit" class="btn btn-sm btn-success btn-block" value="Change Status">
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>

                                    </div>

                            </div>
                        </div>
                    </div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Create one off availability/unavailability</h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('availabilities.store') }}" method="post" id="availability-form">
                    {{csrf_field()}}
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>From *</label>
                                <input class="form-control flatpickr flatpickr-input" type="text" id="from" name="from" value="{{ old('from') }}" placeholder="Select Availability From Date" readonly="readonly" required>
                            </div>
                        </div>

                        <div class="unavailable col-md-12">
                            <div class="form-group">
                                <label>To *</label>
                                <input  class="form-control newflatpickr flatpickr-input" type="text" id="to" name="to" value="{{ old('to') }}" placeholder="Select Availability To Date" readonly="readonly">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Type *</label>
                                <select onchange="toogleAvailability()" name="type" id="type" class="form-control" required>
                                    <option selected disabled>Select Type</option>
                                    <option value="1">One-off Availability</option>
                                    <option value="0">Unavailable Block</option>
                                </select>
                            </div>
                        </div>

                        <div class="time col-md-12">
                            <div class="form-group">
                                <label>From time *</label>
                                <div class="input-group clockpickerNew">
                                    <input type="text" class="form-control" id="from_time" name="from_time" placeholder="00:00" value="{{ old('from_time') }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                </div>
                            </div>
                        </div>

                        <div class="time col-md-12">
                            <div class="form-group">
                                <label>To time *</label>
                                <div class="input-group clockpickerNew">
                                    <input type="text" class="form-control" id="to_time" name="to_time" placeholder="00:00" value="{{ old('to_time') }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                </div>
                            </div>
                        </div>
                    </div><hr>
                    <div class="row" style="padding: 2%;">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-sm btn-success btn-block">Submit one off availability/unavailability</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
</div>

@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/clockpicker.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function() {
            $('.services').select2({
                placeholder: "Select services",
                width: '100%',
                tags: 'true',
                allowClear: true
            });
        });

        $('.clockpicker').clockpicker({
            donetext: 'Done'
        });

        $('.clockpickerNew').clockpicker({
            placement: 'top',
            align: 'left',
            donetext: 'Done'
        });

        let fpNew = null;

        let fp = $('.flatpickr').flatpickr({
            minDate: 'today',
            altInput: true,
            allowInput: true,
            altFormat: 'd F, Y',
            onChange: function(selectedDates, dateStr, instance){
                if (fpNew) {
                    fpNew.destroy();
                }
                fpNew = $('.newflatpickr').flatpickr({
                    minDate: dateStr,
                    altInput: true,
                    allowInput: true,
                    altFormat: 'd F, Y',
                });
            },
        });

        function sunday_added(trigger) {
            const day = trigger.id;
            var trigger = $(trigger);
            var parent = trigger.closest('li');
            var cTarget = parent.find('.dreak_fields');
            var breakItem =
                '<li class="list-group-item custom-li">'+
                '<div class="form-group border-bottom">'+
                '<label for="" class="col-sm-3 control-label list-label">'+
                'Break'+
                '</label>'+

                '<div class="col-sm-3">'+
                '<div class="input-group clockpicker">\n' +
                '<input type="text" class="form-control" name="' + day + '_from[]" placeholder="00:00">\n' +
                '<span class="input-group-addon">\n' +
                '<span class="glyphicon glyphicon-time"></span>\n' +
                '</span>\n' +
                '</div>'+
                '</div>'+
                '<div class="col-md-1">'+
                'to'+
                '</div>'+
                '<div class="col-sm-3">'+
                '<div class="input-group clockpicker">\n' +
                '<input type="text" class="form-control"name="' + day + '_to[]" placeholder="00:00">\n' +
                '<span class="input-group-addon">\n' +
                '<span class="glyphicon glyphicon-time"></span>\n' +
                '</span>\n' +
                '</div>'+
                '</div>'+

                '<div class="col-sm-2">'+
                '<button onclick="removeThisBreak(this);" class="btn btn-default">Remove</button>'+
                '</div>'+
                '</div>'+
                '</li>';
            cTarget.append(breakItem);
            $('.clockpicker').clockpicker({
                donetext: 'Done'
            });
        }

        function removeThisBreak(trigger) {
            var trigger = $(trigger);
            var parent = trigger.closest('li');
            parent.remove();
        }

        function removeBreak(id) {
            $.ajax({
                type: 'post',
                url: window.location.origin + '/day_breaks/' + id,
                data: {
                    '_token': '{{ csrf_token() }}',
                    '_method': 'DELETE'
                },
                success: function(){
                    console.log('success');
                }
            });
        }

        function toogleAvailability() {
            if ($('#type').val() == 1) {
                $('.time').show();
                $('.unavailable').hide();
            } else {
                $('.time').hide();
                $('.unavailable').show();
            }
        }

        function editAvailability(id, route) {
            $.ajax({
                type: 'get',
                url: window.location.origin + '/availabilities/' + id + '/edit',
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                success: function(data){
                    $('#availability-form').trigger('reset');
                    $('#availability-form').attr('action', route);
                    $('#availability-form').append('<input type="hidden" name="_method" value="PUT">');

                    fp.setDate(data.from);
                    if (fpNew != null && fpNew !== 'undefined') {
                        fpNew.setDate(data.to);
                    }

                    $('#from').val(data.from);
                    $('#to').val(data.to);
                    if (data.from_time != null && data.to_time != null) {
                        $('#from_time').val(data.from_time.slice(0, 5));
                        $('#to_time').val(data.to_time.slice(0, 5));
                    }

                    $("#type > option").each(function() {
                        if ($(this).val() == data.type) {
                            $(this).prop('selected', true);
                        }
                    });

                    if (data.type == 1) {
                        $('.time').show();
                        $('.unavailable').hide();
                    } else {
                        $('.time').hide();
                        $('.unavailable').show();
                    }

                    $('.modal-title').text('Update one off availability/unavailability');
                    $('#myModal').modal('show');
                }
            });
        }

        function createAvailability() {
            $('#availability-form').attr('action', '{{ route('availabilities.store') }}');
            $('input[name=_method]').remove();
            $('.modal-title').text('Create one off availability/unavailability');
            $('#availability-form').trigger('reset');
        }
    </script>
@stop
