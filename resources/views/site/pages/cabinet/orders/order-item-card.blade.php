
<div class="order-item-container">
        <div class="order-item-card ">
            @if($image = ($orderItem->item->image ?? $orderItem->item->image ?? null))
                <a href="/product/{{ $orderItem['item']['url'] }}"><div class="order-item-image" style="background-image: url(' {{ asset('/u/items/small/' . $image) }}')"></div></a>
{{--                 style="background-image: url('{{ $orderItem->item->image ? asset('u/items/small/'.$orderItem->item->image) : asset('images/no-image.jpg') }}')"></div>--}}
            <h4 class="order-item-name"><a href="">{{ $orderItem->name }}</a></h4>
            @endif
            @if(!isset($orderItem->item->image))
                    @php($url = isset($orderItem['item']['url']) ? $orderItem['item']['url'] : 0)
                <a href="/product/{{ $url }}"><div class="order-item-image" style="background-image: url(' {{ asset('/images/no-image.jpg') }}')"></div></a>
                {{--                 style="background-image: url('{{ $orderItem->item->image ? asset('u/items/small/'.$orderItem->item->image) : asset('images/no-image.jpg') }}')"></div>--}}
                <h4 class="order-item-name"><a href="">{{ $orderItem->name }}</a></h4>
            @endif

            @if($code = ($orderItem->code ?? $orderItem->item->code ?? null))
                <div class="order-item-code">
                    <span>Код: {{ $code }}</span>
                </div>
            @endif
            <div class="order-item-count">
                <span>Кол-во: {{ $orderItem->count }}</span>
            </div>
            <div class="order-item-total">
                <span>Сумма: {{ exchangePrice($orderItem->sum) }}</span>
                @include('components.basket-currency-symbol')
            </div>
        </div>
</div>

