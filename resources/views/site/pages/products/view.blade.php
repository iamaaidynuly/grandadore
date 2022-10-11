@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'Products')
{{--@push('css')
    <link rel="stylesheet" href="{{asset('css/product-detail.css')}}">
@endpush--}}
@push('css')
    <link rel="stylesheet" href="{{asset('css/breadcrumb.css')}}">
    <link rel="stylesheet" href="{{asset('css/product-detail.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontawesome.min.css')}}">
@endpush
@push('js')
    <script src="{{ asset('js/product-detail.js') }}"></script>
    <script>
        basketBundle.isProductView = true;
    </script>


@endpush

@section('content')
    <!-- New -->
    <div class="container-fluid">
        <div class="details__section">
            <div class="tablet-details d-none d-sm-flex d-lg-none flex-column">
                <div class="rating-elements" data-item-id="{{ $item->id }}" data-rate-value="{{ $item->getAvgRating() }}"></div>

                <span class="product__name-tablet">{{ $item->title }}</span>

                <span class="code-tablet">{{ t('Product.code') }} {{ $item->code }}</span>
            </div>

            <div class="swiper-container gallery-top">
                <div class="swiper-wrapper">
                    <div class="swiper-slid">
                        <img class="gallery-top-img" src="{{ $item->image ? asset('u/items/'.$item->image) : asset('images/no-image.jpg') }}"
                             alt="{{ $item->title }}" title="{{ $item->title }}">
                    </div>
                    @if(count($item_gallery))
                        @foreach($item_gallery as $galleryImage)
                            <div class="swiper-slide">
                                <img class="gallery-top-img"
                                     src="{{ asset('u/gallery/'.$galleryImage->image) }}"
                                     alt="{{ $galleryImage->alt }}"
                                     title="{{ $galleryImage->title }}">
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="swiper__details">
                    <div class="swiper-button-next">
                        <img src="{{ asset('images/001-left-arrow.png') }}" alt="" title="">
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev">
                        <img src="{{ asset('images/001-right-arrow.png') }}" alt="" title="">
                    </div>
                </div>
            </div>

            <div class="swiper-small-wrapper d-none d-lg-block">
                <div class="swiper-container gallery-thumbs">
                    <div class="swiper-wrapper">
                        @if($item->image)
                            <div class="swiper-slide">
                                <img class="img-small" src="{{ $item->image ? asset('u/items/'.$item->image) : asset('images/no-image.jpg') }}"
                                     alt="{{ $item->title }}" title="{{ $item->title }}">
                            </div>
                        @endif

                        @if(count($item_gallery))

                            @foreach($item_gallery as $galleryImage)
                                <div class="swiper-slide">
                                    <img class="img-small"
                                         src="{{ asset('u/gallery/'.$galleryImage->image) }}"
                                         alt="{{ $galleryImage->alt }}"
                                         title="{{ $galleryImage->title }}">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="details d-flex justify-content-start flex-column">
                <div class="d-flex">
                    <!--<div class="rating-elements rating-elements_display-none" data-item-id="{{ $item->id }}" data-rate-value="{{ $item->getAvgRating() }}"></div>-->
                </div>

                <h1 class="product__name">{{ $item->title }}</h1>

                <div class="item__code-price">
                    <span class="code">{{ t('Product.code') }} <span class="product-view-code">{{ $item->code }}</span></span>
                    <span class="prices__span">
                        {{ t('Product.price') }}
                        <span class="price">
                            {{ formatPrice(in_array($item->id, $basketService->getItemIds()) ? $basketService->getItemById($item->id)->getDiscountedPrice(true) : $item->getDiscountedPrice(false, true)) }}
                        </span>
                        @include('components.currency-symbol')
                        @if(isset($basketService) && $basketService->getItemById($item->id)!=null && $basketService->getItemById($item->id)->getPrice(true) != $basketService->getItemById($item->id)->getDiscountedPrice(true))
                            <span class="price-old ml-3">
                            {{ formatPrice(in_array($item->id, $basketService->getItemIds()) ? $basketService->getItemById($item->id)->getPrice(true) : $item->exchangedPrice) }}
                            @include('components.currency-symbol')
                        </span>
                        @endif
                    </span>
                    <p>В упаковке {{$item->count}} штук</p>
                </div>

                <div class="item__filter-group">
                    <div class="inp-groups">
                        @if(count($item->sizes))
                            <div class="filter__div mb-3 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center filter__button choose-size-red">

                                    @if(count($item->sizes)<2)
                                    @foreach($item->sizes as $size)
                                        <span style="text-transform: capitalize">{{ t('Product.size') }}  {{ $size->name }}</span>
                                    @endforeach
                                    @elseif(count($item->sizes)>=2)
                                        <span style="text-transform: capitalize">{{ t('Product.size') }}</span><i class="fas fa-chevron-down"></i>
                                    @endif
                                </div>
                                <div class="filter__content">
                                    @foreach($item->sizes as $size)
                                        @if(count($item->sizes)>1)
                                        <div class="filter-item{{ count($item->sizes)>1 ?'': 'active'  }}" data-price="{{ $size->exchangedPrice }}"
                                             data-size-id="{{ $size->id }}">
                                            {{ $size->name }}
                                        </div>
                                        @else
                                            <div class="filter-item{{ $loop->first ? ' active' : '' }}" data-price="{{ $size->exchangedPrice }}"
                                                 data-size-id="{{ $size->id }}">
                                                {{ $size->name }}
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="filter__div mb-3 d-flex flex-column">

                                <div class="filter__content">
                                            <div class="filter-item active " data-price="" data-size-id="">
                                            </div>
                                </div>
                            </div>
                        @endif


                        @if(count($colorFilters))
                            <div class="filter__div mb-3 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center filter__button choose-color-red">
                                    @if(count($colorFilters) == 1)
                                        @foreach($colorFilters as $colorFilter)
                                            {{ t('Product.color') }} {{ $colorFilter->name }}
                                        @endforeach
                                    @else
                                        <span>{{ t('Product.color') }}</span><i class="fas fa-chevron-down"></i>
                                    @endif

                                </div>
                                <div class="filter__content">
                                    @foreach($colorFilters as $colorFilter)

                                        <div class="d-flex changecolor align-items-center mb-3"
                                             data-color="#{{ $colorFilter->hex_color }}">
                                           @if(count($colorFilters) == 1)
                                                <div class="color mr-2 color-data-id" title="{{ $colorFilter->name }}"
                                                     style="background-color: {{ '#'.$colorFilter->hex_color }};" data-id="{{ $colorFilter->id }}">
                                                    <img class="colorCheck" src="{{ asset('images/001-checked.svg') }}"
                                                         alt="{{ $colorFilter->name }}" style="display: inline;" >
                                                </div>
                                            @else

                                                <div class="color mr-2 color-choose-self" title="{{ $colorFilter->name }}"
                                                     style="background-color: {{ '#'.$colorFilter->hex_color }};" data-id="{{ $colorFilter->id }}">
                                                    <img class="colorCheck" src="{{ asset('images/001-checked.svg') }}"
                                                         alt="{{ $colorFilter->name }}" >
                                                </div>
                                            @endif
                                            <span class="color__name d-none">{{ $colorFilter->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                                <div class="mr-2 color-data-id" title="" data-id="">

                                </div>
                        @endif
                    </div>


                    @if($item->exchangedPrice > 0)
                        <div class="counter__detail count-section">
                            <div class="amount-div">
                                <span class="text">{{ t('Product.count') }}</span>

                                <div class="amount ">
                                    <div class="down view-count-decrement">
                                        <img src="{{ asset('images/counterarrowleft.png') }}" alt="" title="">
                                    </div>
                                    <input id="quantity"
                                           class="amountInp quantity product-view-count"
                                           name="quantity"
                                           type="number"
                                           data-item-id="{{ $item->id }}"
                                           value="{{ $basketService->getItems()->contains('itemId', '=', $item->id) ? $basketService->getItems()->firstWhere('itemId', $item->id)['count'] : 1 }}"
                                           min="0">
                                    <div class="up view-count-increment">
                                        <img src="{{ asset('images/counterarrowright.png') }}" alt="" title="">
                                    </div>
                                </div>
                            </div>

                            <div class="detail__button-group">
                                <button class="add__item basket-action-trigger view-action-trigger"
                                        data-item-id="{{ $item->id }}" data-sizes-count="{{ count($item->sizes) }}">
                                    <span class="product_add">{{ t('Product.Add basket') }}</span>
                                    <span class="product_in_basket">{{ t('Product.In basket') }}</span>
                                    <img class="ml-3" src="{{ asset('images/shopping-cart.svg') }}" alt="" title="">
                                </button>

                                @if(authUser())
                                    <button class="add__favorite favorite-action-trigger"
                                            data-item-id="{{ $item->id }}">
                                        <img class="img-fluid" src="{{ asset('images/favoritee.png') }}" alt=""
                                             title="">
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                @if(count($options))
                    <p class="product__information p-0">Информация о технических характеристиках, комплекте поставки,
                        стране изготовления и внешнем виде товара*</p>

                    <div class="product__characteristics d-flex flex-column">
                        @foreach($options as $option)
                            <div class="d-flex justify-content-between">
                                <span class="character__name">{{ $option->name }}:</span>
                                <span class="character">{{ $option->value }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($brands)
                    @if($brands->logo_image)
                        <div class="product__logo">
                            <a href="{{ route('brand.view', ['url' => $brands->url]) }}">
                                <img src="{{ asset('u/brands/'.$brands->logo_image) }}" class="product__logo-img"
                                     alt="{{ $brands->title }}" title="{{ $brands->title }}">
                            </a>
                        </div>
                    @endif
                @endif

                <div class="product-socc">
                    <div class="soc-box"><a target="_blank" href="https://wa.me/?text={{request()->url()}}"><i
                                    class="fab fa-whatsapp"></i></a></div>
                    <div class="soc-box"><a target="_blank" href="https://telegram.me/share/url?url={{request()->url()}}"><i
                                    class="fab fa-telegram"></i></a></div>
                    <div class="soc-box"><a target="_blank" href="viber://forward?text={{request()->url()}}"><i
                                    class="fab fa-viber"></i></a></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row content">
            @if($item->description)
                <div class="col-12{{ $countOtziv > 0 ? ' col-lg-6' : 'col-lg-12' }} d-flex flex-column">
                    <span class="description__name">{{ t('Product.desc') }}</span>
                    <p class="create-otziv mt-3">{!! $item->description !!}</p>
                </div>
            @endif
{{--            <div class="col-12 col-lg-6 d-flex flex-column description_block">--}}
{{--                <div class="d-flex flex-row align-items-center border-bottom">--}}
{{--                    <span class="description__name"--}}
{{--                          style="border: none">{{ t('Product.review') }} ({{ $countOtziv }})</span>--}}
{{--                    <span class="create-otziv" id="product-detail-myBtn">Написать отзыв</span>--}}
{{--                </div>--}}
{{--                <div class="review_block d-flex flex-column">--}}
{{--                    @php $count=0; @endphp--}}
{{--                    @foreach($otziv as $value)--}}
{{--                        @if(isset($value->item) && $value->item->url == $url && $count<10)--}}
{{--                            @php $count++ ; @endphp--}}
{{--                            <div class="review d-flex flex-column">--}}
{{--                                <span class="review-text">{{ $value->otziv }}</span>--}}
{{--                                <div class="star-rating star-filter stars-otziv star-rating{{$value->star}}"--}}
{{--                                     data-star="4">--}}
{{--                                    <span class="fas fa-star rating" data-rating="1"></span>--}}
{{--                                    <span class="fas fa-star rating" data-rating="2"></span>--}}
{{--                                    <span class="fas fa-star rating" data-rating="3"></span>--}}
{{--                                    <span class="fas fa-star rating" data-rating="4"></span>--}}
{{--                                    <span class="fas fa-star rating" data-rating="5"></span>--}}
{{--                                </div>--}}
{{--                                <span class="review-date">{{ $value->name }} {{ $value->created_at }}</span>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                    <div class="all__reviews">--}}
{{--                        @if(count($otziv)>10)--}}
{{--                            @for($i = 9; $i <count($otziv) ; $i++)--}}
{{--                                <div class="review d-flex flex-column">--}}
{{--                                    <span class="review-text">{{ $otziv[$i]->otziv }}</span>--}}
{{--                                    <span class="review-date">{{ $otziv[$i]->name }} {{ $otziv[$i]->created_at }}</span>--}}
{{--                                </div>--}}
{{--                            @endfor--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                    @if($countOtziv > 10)--}}
{{--                        <button class="view__all-reviews">{{ t('Product.see all') }}</button>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>

    @if(count($similar_items))
        <div class="container-fluid similar">
            <h2>
                <a href="javascript:void(0)" class="name__catalog">
                    {{ t('Product.similar') }}
                </a>
            </h2>
            <div class="swiper-container catalog-container">
                <div class="swiper-wrapper">
                    @foreach($similar_items as $relatedItem)
                        <div class="swiper-slide swiper-slide-active">
                            @include('site.components.product-card', ['item' => $relatedItem])
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-next">
                    <img src="{{ asset('images/001-left-arrow.png') }}" alt="" title="">
                </div>
                <div class="swiper-button-prev">
                    <img src="{{ asset('images/001-right-arrow.png') }}" alt="" title="">
                </div>
            </div>
        </div>
    @endif

    <div id="product-detail-myModal" class="product-detial-modal">
        <div class="product-detail-modal-content">
            <form action="{{ route('product.otziv') }}" method="post">
                @csrf
                <div class="input-field mb-3">
                    <label for="name">ФИО
                        <span class="first-letter">*</span>
                    </label>
                    <input id="name" type="text" class="form-control nice-input" name="name"
                           value="@if(Auth::user()){{ Auth::user()->name }}@endif">
                </div>
                <input type="text" name="product" value="{{ $item->id }}" hidden>
                <div class="input-field mb-3">
                    <label for="email">Email
                        <span class="first-letter">* </span>
                    </label>
                    <input id="email" type="email" class="form-control nice-input" name="email" value="@if(Auth::user()){{ Auth::user()->email }}@endif">
                </div>

                <div class="input-field mb-3">

                    <div class="d-flex justify-content-between align-items-center">
                        <label for="textareea">Отзыв
                            <span class="first-letter">*</span>
                        </label>

                        <div class="star-rating rate-prod d-flex d-sm-none d-lg-flex justify-content-start otzivvs wrapper">
                            <span class="fas fa-star rating poxvovi-star rating2" data-rating="1"
                                  onclick="$('#rating').val($(this).attr('data-rating'))"></span>
                            <span class="fas fa-star rating rating2" data-rating="2"
                                  onclick="$('#rating').val($(this).attr('data-rating'))"></span>
                            <span class="fas fa-star rating rating2" data-rating="3"
                                  onclick="$('#rating').val($(this).attr('data-rating'))"></span>
                            <span class="fas fa-star rating rating2" data-rating="4"
                                  onclick="$('#rating').val($(this).attr('data-rating'))"></span>
                            <span class="fas fa-star rating rating2" data-rating="5"
                                  onclick="$('#rating').val($(this).attr('data-rating'))"></span>
                            <input type="hidden" name="rating" class="rating-value" value="{{ $item->getAvgRating() }}">
                        </div>
                    </div>


                    <textarea id="textareea" class="form-control nice-input otziv-textarea" name="otziv"></textarea>
                </div>

                <input type="text" id="rating" name="rating" value="1" hidden>

                <button class="btn btn-send" type="submit">Отправить</button>
            </form>
        </div>
    </div>

@endsection
