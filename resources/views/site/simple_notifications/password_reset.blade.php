@extends('site.notifications.layout')
@section('content')
    <p>{{ __('mail.reset.line 1') }}</p>
    <p>{{ __('mail.reset.line 2') }} <a href="{{ $url }}">{{ $url }}</a></p>
@endsection