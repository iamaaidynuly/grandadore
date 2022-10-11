<? /** @var App\ValueObjects\BasketItem $basketItem */ ?>
<p style="text-align: center">Учитывайте, цена за одну упаковку!</p>
<div class="card section-shop basket-item-row del{{$basketItem->getItem()->id}}"
     data-item-id="{{ $basketItem->getItem()->id }}">
    <div class="card-image">
        <a href="{{ route('product.view', ['url' => $basketItem->getItem()->url]) }}">
            <img class="img-fluid" src="{{ $basketItem->getItem()->image ? asset('u/items/'.$basketItem->getItem()->image) : asset('images/no-image.jpg') }}"
                 alt="{{ $basketItem->getItem()->title }}"
                 title="{{ $basketItem->getItem()->title }}">
        </a>
    </div>

    <div class="card-body">
        <p class="card-name"><a href="{{ route('product.view', ['url' => $basketItem->getItem()->url]) }}"> {{ $basketItem->getItem()->title }}</a></p>

        @if(isset($basketItem->getColor()->name))
            <p class=" m-0 item-total-price">{{$basketItem->getColor()->name}}</p>
        @endif
        <div class="prices d-flex flex-column flex-sm-row justify-content-center align-items-center">

            <p class="price m-0 mr-sm-2 basket-price" data-price="{{ $basketItem->getDiscountedPrice(true) }}">
                {{ $basketItem->getDiscountedPrice(true) }}
                @include('components.currency-symbol')
            </p>
            <p class=" m-0 item-total-price">
                {{ formatPrice($basketItem->getSum(true)) }}
            </p>
            @include('components.currency-symbol')
        </div>

        <div class="amount">
            <div class="down item-count-decrement">
                <i class="fas fa-arrow-left"></i>
            </div>

            <input class="amountInp quantity basket-item-count"
                   type="number"
                   value="{{ $basketItem->getCount() }}"
                   data-item-id="{{ $basketItem->getItem()->id }}">

            <div class="up item-count-increment">
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </div>

    <div class="trash remove-item" title="add js for current item delete from basket if not exist">
        <i class="far fa-trash-alt " data-item-id="{{ $basketItem->getItem()->id }}"></i>
    </div>
</div>

