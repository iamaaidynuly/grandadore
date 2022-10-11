<div class="order-container">
    <div class="order-header p-3">
        <div class="col">
            <h3>Заказ <u>&#8470;{{ $order->getFormattedId() }}</u></h3>
            <p class="mb-0 mt-2">
                <span class="order-date">{{ $order->calendar('created_at') }}</span>
            </p>
            @if($order->status == \App\Models\Order::STATUS_PENDING && $order->process != 0)
                <p class="mb-0 mt-1">
                    <u class="order-date">{{ $order->processType }}</u>
                </p>
            @endif
        </div>
        <div class="order-info-wrapper col text-right">
            <p class="mb-0">
                <span>Тип оплаты:</span>
                <b>{{ $order->payment_method == 'bank' ? 'оплата банковской картой' : 'оплата наличными' }}</b>
            </p>
            <p class="mb-0">
                <span>Сумма товаров:</span>
                <b>{{ formatPrice(exchangePrice($order->sum)) }}</b>
                @include('components.basket-currency-symbol')
            </p>
            @if($order->delivery_price)
                <p class="mb-0">
                    <span>Цена доставки:</span>
                    <b>{{ formatPrice(exchangePrice($order->delivery_price)) }}</b>
                    @include('components.basket-currency-symbol')
                </p>
            @else
                <p class="mb-0">
                    <span>Бесплатная доставка</span>
                </p>
            @endif
            <p class="mb-0">
                <span>Общая сумма заказа:</span>
                <b>{{ formatPrice(exchangePrice($order->total)) }}</b>
                @include('components.basket-currency-symbol')
            </p>
            <div style="color:#b90101">
                @if($order->status === \App\Models\Order::STATUS_NEW)
                    <span class="order-card-badge warning-badge">В ожидании подтверждения</span>
                @else
                    <span class="order-card-badge">{{ \App\Models\Order::PROCESS[$order->process] }}</span>
                @endif
                @if($order->status === \App\Models\Order::STATUS_PENDING && !$order->paid)
                    <span class="order-card-badge warning-badge">- ожидает оплаты</span>
                @endif
            </div>
            @if($order->payment_method == 'bank' && !$order->paid && $order->status == \App\Models\Order::STATUS_PENDING)
                <form action="{{ route('cabinet.payment') }}" method="post" class="mt-2">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ encrypt($order->id) }}">
                    <button type="submit" class="btn btn-dark btn-sm">Оплатить</button>
                </form>
            @elseif($order->paid)
                <p class="mb-0" style="color:green;">
                    @if($order->payment_method == 'bank')
                        <span>Оплачено онлайн</span>
                    @else
                        <span>Заказ оплачен</span>
                    @endif
                </p>
            @endif
        </div>
    </div>

    <div class="order-items-container d-flex">
        <div class="order-items-wrapper">
            @foreach($order->items as $item)
                @include('site.pages.cabinet.orders.order-item-card', ['orderItem' => $item])
            @endforeach
        </div>
    </div>
</div>
