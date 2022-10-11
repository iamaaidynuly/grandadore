@extends('site.layouts.main')
@section('title', 'About')

@section('content')
    <div class="container mb-5 my-5">
        <div class="row">
            @if($banner_about->content->image)
                <div class="col-12">
                    <img class="img-fluid" src="{{ asset('u/banners/'.$banner_about->content->image) }}"
                         alt="{{ $banner_about->content->title }}">
                </div>
            @endif
            <div class="col-12 mt-3 mt-sm-4 mt-lg-5 editor-content_block">
                <div class="editor-content">
                    {!! $banner_about->content->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('css/news.css') }}">
@endpush


@section('js')
{{--    <script>alert("ok");</script>--}}
@endsection
