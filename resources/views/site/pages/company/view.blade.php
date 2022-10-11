@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'Company item')
@section('content')

    <div class="container">
        <div class="company-item-bg-image mt-2"
             style="background-image: url('{{ asset('u/users/thumbs/'.$company->image) }}');">
            <div class="company-description">
                <div class="company-short-info">
                    @if($company->logo)
                        <div class="company-item-image">
                            <img src="{{ asset('u/users/thumbs/'.$company->logo) }}" alt="">
                        </div>
                    @endif
                    <div class="items">
                        <h1>{{ $company->name }}</h1>
                    </div>
                    @if(count($companyCategories))
                        <div class="items">
                            @foreach($companyCategories as $category)
                                <p><a target="_blank"
                                      href="{{ route('products.category.list', ['url' => $category->url]) }}">{{ $category->name }}</a>
                                </p>
                            @endforeach
                        </div>
                    @endif
                    @if($company->phone)
                        <div class="items">
                            <div class="info">
                                <span>Номер телефона </span>
                                <a href="tel:{{ $company->phone }}">{{ $company->phone }}</a>
                            </div>
                        </div>
                    @endif
                    @if($company->email)
                        <div class="items">
                            <div class="info">
                                <span>Эл.почта</span>
                                <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                            </div>
                        </div>
                    @endif
                    @if($company->work_hours)
                        <div class="items">
                            <div class="info">
                                <span>Часы работы</span>
                                <p>{{ $company->work_hours }}</p>
                            </div>
                        </div>
                    @endif
                    @if($company->website)
                        <div class="items">
                            <a href="{{ $company->website }}" target="_blank" rel="nofollow">
                                {{ $company->website }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if($company->description)
            <div>
                {!! $company->description !!}
            </div>
        @endif
        @if(count($galleryImages))
            <div class="row my-3 " id="lightgallery">
                @foreach($galleryImages as $image)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-6 mt-2 pr-0">
                        <div class="item" data-src="{{ asset('u/gallery/'.$image->image) }}">
                            <img src="{{ asset('u/gallery/thumbs/'.$image->image) }}" class="w-100"
                                 alt="{{ $image->alt }}" title="{{ $image->title }}">
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        @include('site.components.prduct-title', ['title' => 'Последние товары продавца'])
        <div class="row">
            @foreach($company->companyItems as $item)
                <div class="col-xl-3 col-md-3 col-6 mt-2">
                    @include('site.components.product-card', ['item'=> $item])
                </div>
            @endforeach
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/company.css') }}">
@endpush

@section('js')
    <script src="{{asset('js/company.js')}}"></script>
@endsection
