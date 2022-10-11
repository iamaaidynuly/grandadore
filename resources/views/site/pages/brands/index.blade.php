@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'Brands')

@section('content')
    @if(count($brands))
        <div class="container-fluid">
            <div class="d-flex justify-content-start flex-wrap brand__catalog">
                @foreach($brands as $brand)
                    @if($brand->image)
                        <a href="{{ route('brand.view', ['url' => $brand->url]) }}" class="brand-card text-decoration-none">
                            <div class="brand-image-wrapper">
                                <img class="brand-image" src="{{ asset('u/brands/'.$brand->image) }}" alt="{{ $brand->title }}" title="{{ $brand->title }}">
                                @if($brand->logo_image)
                                    <div class="brand-logo-div">
                                        <img class="img-fluid" src="{{ asset('u/brands/'.$brand->logo_image) }}" alt="{{ $brand->title }}" title="{{ $brand->title }}">
                                    </div>
                                @endif
                            </div>
                            <p class="brand-name">
                                <span>{{ $brand->title }}</span>
                            </p>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('css/brands.css') }}">
@endpush
