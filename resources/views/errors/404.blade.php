@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message')
    <p>
        Whoops. We are sorry, but something went wrong there. <br>
        If you use the back button on your browser, you could try again. <br>
        If you keep seeing this page, please contact our support team. <br>
        They'll be happy to help you out.
    </p>
@stop
