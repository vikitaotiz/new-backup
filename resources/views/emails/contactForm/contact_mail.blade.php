@component('mail::message')

<strong>Email : {{$data['email']}}</strong><br>
<strong>Name : {{$data['name']}}</strong><br>
<strong>Subject : {{$data['subject']}}</strong><br>
<hr>
<p>Message</p>
<hr>
<p>
    {{$data['message']}}
</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
