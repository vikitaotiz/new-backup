
                                        <h4>User's Appointments</h4>
                                        @if (count($user->doctorAppointments) > 0)
                                            <table class="table table-bordered" id="appointments_table">
                                                <thead>
                                                    <th>#ID</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Status</th>
                                                    <th>Doctor Assigned</th>
                                                    <th>Created On</th>
                                                </thead>
                                                <tbody>
                                                    @foreach ($user->doctorAppointments as $appointment)
                                                        <tr>
                                                            <td><a href="{{route('appointments.show', $appointment->id)}}">{{$appointment->id}}</a></td>
                                                            <td>{{date('d M, Y', strtotime($appointment->appointment_date))}} {{ $appointment->from }}</td>
                                                            <td>{{date('d M, Y', strtotime($appointment->appointment_date))}} {{ $appointment->to }}</td>
                                                            <td>{{$appointment->status}}</td>
                                                            <td>{{App\User::findOrfail($appointment->doctor_id)->firstname}}</td>
                                                            <td>{{$appointment->created_at->diffForHumans()}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            user has No Appointments yet.
                                        @endif
