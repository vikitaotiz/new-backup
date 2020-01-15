
                                        <div class="row">
                                                <div class="col-md-6">
                                                    <h4>Patient's Appointments</h4>
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="javascript:void(0)" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Create Appointment</a>
                                                    {{-- <a href="{{route('appointments.create')}}" class="btn btn-success btn-sm">Create Appointment</a> --}}
                                                </div>
                                            </div>

                                            @if (count($patient->appointments) > 0)
                                                <table class="table table-bordered" id="appointments_table">
                                                    <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Appointment Date</th>
                                                        {{-- <th>End Date</th> --}}
                                                        <th>Status</th>
                                                        <th>Doctor Assigned</th>
                                                        <th>Created On</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($patient->appointments as $appointment)
                                                            <tr>
                                                                <td><a href="{{route('appointments.show', $appointment->id)}}">@if($appointment->service){{$appointment->service->name}}@endif</a></td>
                                                                <td>{{$appointment->appointment_date/*->format('D, jS, M, Y')*/}}</td>
                                                                {{-- <td>{{$appointment->end_date}}</td> --}}
                                                                <td>{{$appointment->status}}</td>
                                                                <td>@if($appointment->doctor){{$appointment->doctor->firstname}}@endif</td>
                                                                <td>{{$appointment->created_at->diffForHumans()}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                Patient has No Appointments yet.
                                            @endif
