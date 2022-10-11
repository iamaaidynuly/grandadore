<div class="order-container">
    <div class="order-header p-3">
        <div class="col">
            <h3>Заказ <u>&#8470;{{ $order->getFormattedId() }}</u></h3>
            <p class="mb-0">
                <span class="order-date">{{ $order->calendar('created_at') }}</span>
            </p>
            @if($order->status == \App\Models\Order::STATUS_PENDING && $order->process != 0)
                <p class="mb-0 mt-1">
                    <u class="order-date">{{ \App\Models\Order::STATUS_PENDING }}</u>
                </p>
            @endif
            @if($order->payment_method == 'bank' && !$order->paid && $order->status == \App\Models\Order::STATUS_PENDING)
                <form action="{{ route('cabinet.payment') }}" method="post" class="mt-2">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ encrypt($order->id) }}">
                    <button type="submit" class="btn btn-grey btn-sm">Оплатить</button>
                </form>
            @endif
        </div>
        <div class="order-info-wrapper col text-right">
            <p class="mb-0">
                <span>Тип оплаты:</span>
                <b>{{ $order->payment_method == 'bank' ? 'оплата банковской картой' : 'оплата наличными' }}</b>
            </p>
            <p class="mb-0">
                <span>Сумма товаров:</span>
                <b>{{ formatPrice($order->sum) }}</b>
                <sub>₸</sub>
            </p>
        </div>
        {{--   <div class="order-info-wrapper col text-right">
               <p class="mb-0">
                   <span>Тип оплаты:</span>
                   <b>{{ $order->payment_method == 'bank' ? 'оплата банковской картой' : 'оплата наличными' }}</b>
               </p>
               <p class="mb-0">
                   <span>Сумма товаров:</span>
                   <b>{{ formatPrice($order->sum) }}</b>
                   <sub>₸</sub>
               </p>
               @if($order->delivery_price)
                   <p class="mb-0">
                       <span>Цена доставки:</span>
                       <b>{{ formatPrice($order->delivery_price) }}</b>
                       <sub>₸</sub>
                   </p>
               @endif
               <p class="mb-0">
                   <span>Общая сумма заказа:</span>
                   <b>{{ formatPrice($order->total) }}</b>
                   <sub>₸</sub>
               </p>
               @if($order->payment_method == 'bank' && !$order->paid && $order->status == \App\Models\Order::STATUS_PENDING)
                   <form action="{{ route('cabinet.payment') }}" method="post" class="mt-2">
                       @csrf
                       <input type="hidden" name="order_id" value="{{ encrypt($order->id) }}">
                       <button type="submit" class="btn btn-grey btn-sm">Оплатить</button>
                   </form>
               @elseif($order->paid)
                   <p class="mb-0">
                       <span>Заказ оплачен</span>
                   </p>
               @endif
           </div>
       </div>
       <div class="order-items-container d-flex">
           <div class="order-items-headings">
               <ul>
                   <li class="item-image"></li>
                   <li style="margin-bottom: 70px;">
                       <span>Название</span>
                   </li>
                   <li style="margin-bottom: 55px;">
                       <span>Код</span>
                   </li>
                   <li style="margin-bottom: 55px;">
                       <span>Количество</span>
                   </li>
                   <li>
                       <span>Цена</span>
                   </li>
               </ul>
           </div>
           <div class="order-items-wrapper">--}}
        @foreach($order->items as $item)
            @php $items = \App\Models\Items::where('id', $item->items_id)->get(); @endphp
            @foreach($items as $item)
                @include('site.pages.cabinet.orders.order-item-card', ['item' => $item , 'order'=>$order])
            @endforeach
        @endforeach
    </div>
</div>

