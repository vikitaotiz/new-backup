<table class="table table-bordered" id="appointments_table">
<thead>
<tr>
    <th>Patient Name</th>
    <th>Patient Email</th>
    <th>Name</th>
    <th>Appointment Date</th>
    {{-- <th>End Date</th> --}}
    <th>Status</th>
    <th>Doctor Assigned</th>
    <th>Description</th>
    <th>End Time</th>
    <th>Progress</th>
    <th>Color</th>
    <th>Service Id</th>
    <th>Doctor Id</th>
    <th>User Id</th>
    <th>Created On</th>
</tr>
</thead>
<tbody>
    @foreach ($appointments as $appointment)
        @if(isset($appointment->user))
            @if($appointment->user != null)
                @if(isset($appointment->user->email))
                <tr>
                    <td>{{$appointment->user->firstname . $appointment->user->lastname}}</td>
                    <td>{{$appointment->user->email}}</td>
                    <td><a href="{{route('appointments.show', $appointment->id)}}">{{$appointment->service->name}}</a></td>
                    <td>{{$appointment->appointment_date}}</td>
                    {{-- <td>{{$appointment->end_date}}</td> --}}
                    <td>{{$appointment->status}}</td>
                    <td>{{App\User::findOrfail($appointment->doctor_id)->firstname}}</td>
                    <td>{{$appointment->description}}</td>
                    <td>{{$appointment->end_time}}</td>
                    <td>{{$appointment->progress}}</td>
                    <td>{{$appointment->color}}</td>
                    <td>{{$appointment->service_id}}</td>
                    <td>{{$appointment->doctor_id}}</td>
                    <td>{{$appointment->user_id}}</td>
                    <td>{{$appointment->created_at}}</td>
                </tr>
                @endif
            @endif
        @endif
    @endforeach
</tbody>
</table>

