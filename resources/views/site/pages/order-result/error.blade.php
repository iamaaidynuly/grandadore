@extends('site.layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="w-100 alert alert-danger mt-3">
                <h3 class="text-center">{{ $errorText }}</h3>
            </div>
        </div>
    </div>
@endsection
