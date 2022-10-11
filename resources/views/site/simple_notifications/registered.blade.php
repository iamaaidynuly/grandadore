@extends('site.notifications.layout')
@section('content')
    <p>{{ __('mail.register.line 1') }}</p>
    <p>{{ __('mail.register.line 2') }} <a href="{{ $url }}">{{ $url }}</a></p>
@endsection