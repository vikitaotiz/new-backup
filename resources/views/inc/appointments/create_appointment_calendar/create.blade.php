<div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Create New Appointment</h4>
            </div>
            <div class="modal-body">

               <form action="{{route('appointments.store')}}" method="post">

                        {{csrf_field()}}

                        <div class="row" style="padding: 1%;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Service</label>

                                    @if($services->count() > 0)
                                        <select onchange="getDoctor()" name="service_id" id="service_id" class="form-control" required>
                                            <option selected disabled>Select service</option>
                                            @foreach ($services as $service)
                                                <option value="{{$service->id}}">{{$service->name}}</option>
                                            @endforeach
                                        </select>
                                    @else

                                        @if (auth()->user()->role_id == 5)
                                            <p>No Service Available.
                                        @else
                                            <p>No Service Available.
                                                <a href="{{route('services.create')}}">Create Service</a>
                                            </p>
                                        @endif
                                    @endif

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Doctor</label>
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
                                                        <input type="hidden" name="from" id="appointmentFromTime">
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

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">

                                    @if (auth()->user()->role_id == 5)
                                        <label>Patient Name. </label>
                                        <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                                        <select name="user_id" id="user_id" class="form-control" disabled>
                                            <option value="{{auth()->user()->id}}" id="test">
                                                {{auth()->user()->firstname}}
                                                {{auth()->user()->lastname}}
                                                - DOB : {{auth()->user()->date_of_birth}}
                                                    @if (auth()->user()->nhs_number)
                                                    - NHS : {{auth()->user()->nhs_number}}
                                                    @endif
                                            </option>
                                        </select>
                                    @else

                                       <label>Patient Name.
                                        <a href="#" data-toggle="modal" data-target="#addNewPatient">Create New</a>
                                        </label>
                                        <select name="user_id" id="user_id" class="form-control" required>
                                            @foreach ($patients as $patient)
                                                <option value="{{$patient->id}}">
                                                    {{$patient->firstname}} {{$patient->lastname}} -
                                                    DOB : {{$patient->date_of_birth}}
                                                    @if ($patient->nhs_number)
                                                    - NHS : {{$patient->nhs_number}}
                                                    @endif

                                                </option>
                                            @endforeach
                                        </select>

                                    @endif

                                </div>
                            </div>

                        </div><hr>
                         <div class="row" style="padding: 1%;">
                            <div class="form-group">
                                <label>Comment</label>
                                <textarea name="description" id="description" class="form-control" placeholder="Enter Comment...">{{old('description')}}</textarea>
                            </div>
                        </div>
                        <hr>

                         <div class="row" style="padding: 1%;">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>
                            </div>
                            <div class="col-md-6">
                                <input type="submit" class="btn btn-sm btn-success btn-block" value="Submit Appointment">
                            </div>
                        </div>
                    </form>
        </div>

        </div>
    </div>
