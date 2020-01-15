@extends('adminlte::page')

@section('css')
    <style>
        .service {
            display: none;
        }
    </style>
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
@stop


@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success">Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Edit User</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('users.create')}}" class="btn btn-sm btn-primary">Create New User</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                        <form action="{{route('users.update', $user->id)}}" method="post" enctype="multipart/form-data">

                            {{csrf_field()}}

                            {{method_field('PUT')}}

                            @if(auth()->user()->role_id == 6 && isset(auth()->user()->company))
                                <input type="hidden" name="company_id" value="{{ auth()->user()->company->id }}">
                            @endif

                            <div class="form-group">
                                <label for="username">User name</label>
                                <input type="text" name="username" class="form-control" value="{{ $user->username }}" placeholder="User name" required>
                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                <span class="help-block">
                                    <strong>User will need this username along with password during login.</strong>
                                </span>
                            </div>

                                <input type="hidden" name="role_id" value="{{$user->role_id}}">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>First Name</label>
                                        <input type="text" name="firstname" class="form-control" value="{{$user->firstname}}" placeholder="Enter First Name...">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input type="text" name="lastname" class="form-control" value="{{$user->lastname}}" placeholder="Enter Last Name...">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>User Email</label>
                                            <input type="email" name="email" class="form-control" value="{{$user->email}}" placeholder="Enter User Email...">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>GMC Number</label>
                                            <input type="text" name="gmc_no" class="form-control" value="{{$user->gmc_no}}" placeholder="Enter User GMC Number...">
                                        </div>
                                    </div>

                                </div> <hr>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="tel" name="phone" class="form-control" value="{{$user->phone}}" placeholder="Enter Phone...">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Profile Photo</label>
                                                <input type="file" class="form-control" name="profile_photo">
                                            </div>
                                        </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="address" class="form-control" value="{{$user->address}}" placeholder="Enter Address...">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <input type="text" name="date_of_birth" id="date_of_birth" value="{{$user->date_of_birth}}" class="form-control" placeholder="Select Date of Birth">
                                        </div>
                                    </div>

                                </div> <hr>


                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select name="gender" id="gender_id" class="form-control">
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>User Password</label>
                                            <input type="password" name="password" class="form-control" placeholder="Enter User Password...">
                                        </div>
                                    </div>

                                    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>User Role</label>
                                            <select onchange="getService()" name="role_id" id="role_id" class="form-control">
                                                @foreach ($roles as $role)
                                                    <option value="{{$role->id}}"
                                                        @if ($role->id == $user->role_id)
                                                            selected
                                                        @endif>{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @elseif(auth()->user()->role_id == 5)
                                        <div class="form-group">
                                            <label>User Role</label>
                                            <select name="role_id" id="role_id" class="form-control" disabled>
                                                @foreach ($roles as $role)
                                                    <option value="{{$role->id}}"
                                                            @if ($role->id == $user->role_id)
                                                            selected
                                                        @endif>{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @elseif(auth()->user()->role_id == 6 && $user->role_id != 6)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>User Role</label>
                                                <select onchange="getService()" name="role_id" id="role_id" class="form-control">
                                                    @foreach ($roles as $role)
                                                        <option value="{{$role->id}}"
                                                                @if ($role->id == $user->role_id)
                                                                selected
                                                            @endif>{{$role->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Select Company</label>
                                                <select name="company_id" class="form-control" required>
                                                    <option selected disabled>Select Company</option>
                                                    @foreach ($companies as $company)
                                                        <option value="{{$company->id}}" @if(isset($user->companies) && count($user->companies) && $user->companies->contains($company->id)) selected @endif>{{$company->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="service col-md-6" @if($user->role_id == 3)style="display: block"@endif>
                                        <div class="form-group">
                                            <label>Select Service </label>
                                            @if($services->count() > 0)
                                            <select name="service_id[]" id="service_id" class="form-control" multiple="multiple">
                                                @foreach ($services as $service)
                                                    <option value="{{$service->id}}" @if($user->role_id == 3) @foreach($user->services as $serv) @if($service->id == $serv->id) selected @endif  @endforeach @endif>{{$service->name}}</option>
                                                @endforeach
                                            </select>
                                            @else
                                                @if (!auth()->user()->role_id == 5)
                                                    <p>No Service Available.
                                                @endif
                                                <p>No Service Available.
                                                    <a href="{{route('services.create')}}">Create Service</a>
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="service col-md-6" @if($user->role_id == 3)style="display: block"@endif>
                                        <div class="form-group">
                                            <label>Appointment slot size</label>
                                            <input class="form-control" type="number" id="slot" name="slot" min="1" value="@if($user->role_id == 3){{ $user->slot }}@else{{old('slot')}}@endif" placeholder="eg. 15 minutes">
                                        </div>
                                    </div>
                                </div> <hr>


                                <div class="row" style="padding: 1%;">
                                        <div class="form-group">
                                            <label>More Information</label>
                                            <textarea class="form-control" name="more_info" id="more_info" placeholder="Enter more information about the user...">
                                                    {{$user->more_info}}
                                            </textarea>
                                        </div>
                                </div> <hr>

                               <div class="row" style="padding: 1%;">
                                    <div class="form-group" >
                                        <input type="submit" value="Submit User" class="btn btn-sm btn-success btn-block">
                                    </div>
                               </div>

                            </form>
                </div>
            </div>

@endsection

@section('js')
    <script src="{{ asset('js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#service_id').select2();
        });

        function getService() {
            if ($('#role_id').val() == 3) {
                $('.service').show();
            } else {
                $('.service').hide();
            }
        }
    </script>

@endsection
