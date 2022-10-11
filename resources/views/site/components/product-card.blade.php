<div class="product-card card-item position-relative" data-id="{{ $item->id }}" data-price="{{ $item->price }}">
    <div class="mycard d-flex justify-content-center align-items-center flex-column text-center">
        <div class="position-relative w-100">
            <a class="card-image-wrapper" data-id="{{ $item->id }}" data-price="{{ $item->price }}"
               href="{{ route('product.view', ['url' => $item->url]) }}">
                <img class="animatable-image"
                     src="{{ $item->image ? asset('u/items/'.$item->image) : asset('images/no-image.jpg') }}"
                     alt="{{ $item->title }}" title="{{ $item->title }}">
            </a>
            @if($item->new || $item->top || $item->sale)
                <div class="novinki-top d-flex align-items-center position-absolute">
                    <span class="novinki-top-text" {{ $item->new==0 ? "hidden":"" }}>Новинки</span>
                    <span class="novinki-top-text" {{ $item->top==0 ? "hidden":"" }}>Топ</span>
                    <span class="novinki-top-text" {{ $item->sale==0 ? "hidden":"" }}>Скидка</span>
                </div>
            @endif
            <div class="card__buttons d-flex flex-column">
                @if(\Illuminate\Support\Facades\Auth::check())
                    <div
                            class="card-btn d-flex justify-content-center align-items-center mb-2 card-lovely favorite-action-trigger"
                            data-item-id="{{ $item->id }}">

                        <div class="check-heart"><i class="far fa-heart" style="color: #fff; font-size: 20px"></i></div>
                        <img src="{{ asset('images/hearth.svg') }}" alt="{{ $item->title }}" title="{{ $item->title }}">
                    </div>
                @endif
{{--                <div class="card-shop basket-action-trigger card-btn d-flex justify-content-center align-items-center"--}}
{{--                     data-item-id="{{ $item->id }}">--}}
{{--                    <img class="basket-img-img" src="{{ asset('images/basket.svg') }}" alt="{{ $item->title }}"--}}
{{--                         title="{{ $item->title }}">--}}
{{--                    <div class="check-img"><i class="fas fa-check" style="color: #fff"></i></div>--}}
{{--                </div>--}}
            </div>
        </div>
        <h3><a class="item__name" href="{{ route('product.view', ['url' => $item->url]) }}">{{ $item->title }}</a></h3>
        <div class="rating-elements" data-item-id="{{ $item->id }}" data-rate-value="{{ $item->getAvgRating() }}"></div>

        <div class="prices d-flex flex-column flex-sm-row justify-content-center align-items-center">
            <p class="price m-0 mr-sm-2">{{ formatPrice(($item->exchangedPrice*(100-$item->delivery_price)/100)) }}
                @include('components.currency-symbol')</p>
            @if($item->exchangedPrice != $item->exchangedPrice*(100-$item->delivery_price)/100)
                <p class="price-old m-0">{{ formatPrice($item->exchangedPrice) }}@include('components.currency-symbol')</p>
            @endif
        </div>
    </div>
</div>


