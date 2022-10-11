@extends('site.layouts.main', ['headerSidebar' => false])
@section('title', 'Home page')

@push('css')
    <link rel="stylesheet" href="{{asset('css/homepage.css')}}">
    <link rel="stylesheet" href="{{asset('css/swiper.min.css')}}">
@endpush

@section('js')
    <script src="public/js/swiper.min.js"></script>
    <script src="{{ asset('js/home.js') }}"></script>
@endsection
{{--@dd(Session::has('success'))--}}
{{--    @if(Session::has('success'))--}}
{{--<div class="alert alert-success text-left mt-3" style="z-index: 999">--}}
{{--    hellooooo--}}
{{--</div>--}}
{{--    @endif--}}
@if(session()->has('message'))
    @push('js')
        <script>
            location.reload();
        </script>
    @endpush
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('content')



    @if(count($slider))
        @include('site.components.slider', ['slider' => $slider])
    @endif

    <h1 class="home-main-text">Интернет магазин Grandadore.com</h1>

    @if(count($short_links))
        <div class="container-fluid indent d-none d-lg-block">
            <div class="row">
                @foreach($short_links as $short_link)
                    @if($short_link->image)
                        <div class="col-3 d-flex flex-column">
                            <div class="circle d-flex justify-content-center align-items-center">
                                <a class="text-decoration-none d-flex justify-content-center align-items-center"
                                   href="@if($short_link->url) {{ $short_link->url }} @else javascript:void(0) @endif">
                                    <img class="img-fluid" src="{{ asset('u/short_links/'.$short_link->image) }}"
                                         alt="{{ $short_link->title }}" title="{{ $short_link->title }}">
                                </a>
                            </div>
                            <span class="service-name">{{ $short_link->title }}</span>
                            <span class="service-title" style="display: none">По казахстану и снг*</span>
                            <span class="service-information">{!! $short_link->text !!}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
    {{--new block--}}
    @if(count($categoriesChunks))
        @php $categoryFirstEnd = 0 @endphp
        @foreach($categoriesChunks as $index => $categoryParents)
            @if($categoryParents->parent_id == null && $categoryParents->in_home == 1)
                @php $categoryFirstEnd++ @endphp
                <div class="container-fluid indent">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="javascript:void(0)" class="name__catalog">{{ $categoryParents->name }}</a>
                        <span class="d-lg-none">
                            <a href="{{ url('/products/category/'.$categoryParents->url) }}" class="all__items">
                                Посмотреть все
                            </a>
                        </span>
                    </div>
                    @endif
                    <div class="indent catalog__block mb-2 mb-sm-3 mb-lg-4 d-flex justify-content-start">
                        @php $categoryWithImageCount = 0 @endphp
                        @foreach($categoryParents->nestedChildren as $row => $category)
                            @if( $categoryWithImageCount < 10 && $category->in_home == 1)
                                @php $categoryWithImageCount++ @endphp
                                <div class="d-flex flex-column item__catalog">
                                    <a href="{{ route('products.category.list',['url'=>$category->url]) }}">
                                        <img class="img-fluid"
                                             src="{{ asset('u/categories/'.$category->image) }}"
                                             alt="{{ $category->name }}"
                                             title="{{ $category->name }}">
                                    </a>
                                    <span>
                                        <h3>
                                        <a href="{{ route('products.category.list',['url'=>$category->url]) }}"
                                           class="item__name">
                                            {{ $category->name }}
                                        </a>
                                            </h3>
                                    </span>
                                </div>
                            @endif
                            {{--@foreach($category->children as $val)
                                    @if($categoryWithImageCount < 10 && $val->in_home == 1)
                                        @php $categoryWithImageCount++ @endphp
                                        <div class="d-flex flex-column item__catalog">
                                            <a href="{{ route('products.category.list',['url'=>$val->url]) }}">
                                                <img class="img-fluid"
                                                     src="{{ asset('u/categories/'.$val->image) }}"
                                                     alt="{{ $val->name }}"
                                                     title="{{ $val->name }}">
                                            </a>
                                            <span>
                                        <a href="{{ route('products.category.list',['url'=>$val->url]) }}"
                                           class="item__name">
                                            {{ $val->name }}
                                        </a>
                                    </span>
                                        </div>
                                    @endif
                            @endforeach--}}
                        @endforeach
                    </div>
                </div>

                @if($categoryFirstEnd == 1 && $big_banner->content)
                    @include('site.components.two-banner', ['banner' => $big_banner->content])
                @endif


                @endforeach
            @endif

            @if(count($brands))
                <div class="container-fluid indent">
                    @if($brandPage)
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('page', ['url' => $brandPage->url]) }}"
                               class="name__catalog">{{ $brandPage->title }}</a>
                            <span class="d-lg-none">
                        <a href="{{ route('page', ['url' => $brandPage->url]) }}" class="all__items">Посмотреть все</a>
                    </span>
                        </div>
                    @endif
                    <div
                        class="indent mb-2 mb-sm-3 mb-lg-4 catalog__block d-flex justify-content-start align-items-center">
                        @foreach($brands as $brand)
                            @if($brand->image)
                                <a href="{{ route('brand.view', ['url' => $brand->url]) }}"
                                   class="brand__item d-flex flex-column align-items-center text-decoration-none">
                                    <img class="img-fluid" src="{{ asset('u/brands/'.$brand->image) }}"
                                         alt="{{ $brand->title }}" title="{{ $brand->title }}">
                                    <span class="span__brand-name">
                                <span class="brand__name">
                                    {{ $brand->title }}
                                </span>
                            </span>
                                    @if($brand->logo_image)
                                        <div
                                            class="brand__logo-wrapper d-flex justify-content-center align-items-center">
                                            <img class="img-fluid" src="{{ asset('u/brands/'.$brand->logo_image) }}"
                                                 alt="{{ $brand->title }}" title="{{ $brand->title }}">
                                        </div>
                                    @endif
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            @if($search)
                <div class="container-fluid indent">
                    <div class="d-flex align-items-center">
                        <span class="popular__search-title">Популярные поиски</span>
                        <div class="line"></div>
                    </div>

                    <div class="popular__searchs-block">
                        @foreach($search as $item)
                            <form class="" action="{{ route('products.search') }}" method="get">
                                <input type="hidden" name="searchQuery" value="{{ $item->title }}">
                                <button style="border: 0; background-color: white" class="popular__search__link-span"
                                        type="submit"><a class="popular__search-link"><h2>{{ $item->title }}</h2></a></button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($home_banners->main_banner)
                <div class="container-fluid indent d-flex flex-column">
                    @if($home_banners->main_banner->title)
                        <div>
                            <h1 class="paragraph__name">{{ $home_banners->main_banner->title }}</h1>
                        </div>
                    @endif

                    @if($home_banners->main_banner->content)
                        <div class="mt-2">
                            <div class="text__information mt-3">
                                {!! $home_banners->main_banner->content !!}
                            </div>
                        </div>
                    @endif
                </div>
            @endif

@endsection
