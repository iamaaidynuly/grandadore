@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'Brand')

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column flex-lg-row brand__main">
            @if($brand->image)
                <div class="about__image">
                    <img src="{{ asset('u/brands/'.$brand->image) }}" class="brand__image" alt="{{ $brand->title }}" title="{{ $brand->title }}">
                </div>
            @endif
            <div class="about__details d-flex flex-column justify-content-between">
                @if($brand->logo_image)
                    <div class="logo-wrap">
                        <img class="w-100 h-100" src="{{ asset('u/brands/'.$brand->logo_image) }}" alt="{{ $brand->title }}" title="{{ $brand->title }}">
                    </div>
                @endif
                <h1 class="brand__name">{{ $brand->title }}</h1>
                <div class="about__details__text">
                    <p>{!! $brand->description !!}</p>
                </div>
            </div>
        </div>
    </div>

    @if(count($gallery))
        <div class="container-fluid">
            <div class="gallery d-flex flex-row" id="lightgallery-news">
                @foreach($gallery as $image)
                    <div class="gallery-item">
                        <a data-fancybox="gallery" href="{{ asset('u/gallery/'.$image->image) }}">
                            <img class="img-fluid" src="{{ asset('u/gallery/thumbs/'.$image->image) }}" alt="" title="">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(count($item_brands))
        <div class="container-fluid">
            <p class="catalog__name">{{ t('Products.brands') }}</p>
            <div class="items">
                @foreach($item_brands as $item)
                    @if($item->items[0])
                        @include('site.components.product-card', ['item' => $item->items[0]])
                    @endif
                @endforeach
            </div>
        </div>
    @endif

@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('css/brand.css') }}">
@endpush
@push('js')
    <script src="{{ asset('js/brand.js') }}"></script>
@endpush
