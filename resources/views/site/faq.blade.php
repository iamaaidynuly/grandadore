@extends('site.layouts.main', ['headerSidebar' => false])
@section('title', 'Home page')

@push('css')
    <link rel="stylesheet" href="{{asset('css/homepage.css')}}">
    <link rel="stylesheet" href="{{asset('css/answer.css')}}">
@endpush

@section('js')
    <script src="public/js/swiper.min.js"></script>
    <script src="{{ asset('js/home.js') }}"></script>
@endsection


@section('content')

    <div class="container-fluid mt-3 mt-sm-4 mt-lg-5 mb-3 mb-sm-4 mb-lg-5">
        @foreach($supportAll as $support)
            @if($support->title != "" && $support->answer != "")
        <div class="answer-block d-flex flex-column mb-3 mb-sm-4">
            <div class="vopros-text">{{$support->title}}</div>
            <div class="otvet-text"><?=$support->answer?></div>
        </div>
            @endif
        @endforeach
    </div>
@endsection
