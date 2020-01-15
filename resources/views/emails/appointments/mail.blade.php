@component('mail::message')
# Introduction

<p>Name : {{$appointment->user->firstname}} {{$appointment->user->lastname}}</p>

<hr>
<table class="table table-bordered">
        <tr>
            <th>Appointment Description: </th>
            <td>{!! $appointment->description !!}</td>
        </tr>
        <tr>
            <th>Appointment Status: </th>
            <td>
                @if ($appointment->status == 'close')
                  <strong>CLOSED</strong>
                @else 
                  <strong>OPEN</strong>
                @endif
            </td>
        </tr>
        <tr>
            <th>Appointment Date:</th>
            <td>{{$appointment->appointment_date->format('D, M j, Y g:i A')}}</td>
        </tr>

        @if ($appointment->status == 'close')
          <tr>
              <th>Slot Duration (Hours) :</th>
              <td>
                {{Carbon\Carbon::parse($appointment->appointment_date)->diffInHours($appointment->end_time)}}</td>
          </tr>
        @endif
        

        <tr>
            <th>Created By:</th>
            <td>{{$appointment->creator->firstname}}</td>
        </tr>

        <tr>
            <th>Created On:</th>
            <td>{{$appointment->created_at->format('Y M, jS, D,  g:i A')}}</td>
        </tr>
    </table>
<hr>

<a href="{{route('appointments.show', $appointment->id)}}" class="btn btn-sm btn-info">view more</a>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
