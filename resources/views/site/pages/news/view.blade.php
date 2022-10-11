@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'News_view')

@section('content')
    <div class="container-fluid">
        <div class="title-block">
            <h1 class="title">
                <a href="javascript:void(0)">{{ $item->title }}</a>
            </h1>
            <div class="detail">
                <p class="date">
                    <a href="javascript:void(0)">{{ $item->created_at->calendar() }}</a>
                </p>
                <div class="d-flex align-items-center views__box">
                    <i class="fas fa-eye views__icon"></i>
                    <p class="m-0 views__count">{{ $item->views_count }}</p>
                </div>
            </div>
        </div>

        <div class="pictures__block">
            <div class="picture">
                <img src="{{ asset('u/news/'.$item->image) }}" alt="{{ $item->title }}" title="{{ $item->title }}">
            </div>
            @if(count($gallery))
                <div class="gallery row" id="lightgallery-news">
                    @foreach($gallery as $image)
                        <div class="gallery-item col-4">
                            <a data-fancybox="gallery" href="{{ asset('u/gallery/'.$image->image) }}">
                                <img class="img-fluid" src="{{ asset('u/gallery/thumbs/'.$image->image) }}" alt="" title="">
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="text__information">
            <p class="text__p">{!! $item->description !!}</p>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('css/news_view.css') }}">
@endpush
@push('js')
    <script src="{{ asset('js/news_view.js') }}"></script>
@endpush
