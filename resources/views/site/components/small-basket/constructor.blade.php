<?php /** @var App\Services\BasketService\BasketService $basketService */ ?>
@php $ids = []; @endphp
@if(count($basketItems = $basketService->getItems()))
    <div class="basket__body d-flex flex-column">
        @foreach($basketItems as $basketItem)
            @php $ids[$basketItem->toArray()['itemId']][] = $basketItem->toArray()['count']; @endphp
            @include('site.components.small-basket.item', ['basketItem' => $basketItem,'ids'=>$ids])
        @endforeach

    </div>
    <div class="order__details d-flex justify-content-between card-shop-footer">
        <div class="order-group d-flex flex-column">
            {{--<span class="ordertext">Номер заказа</span>--}}
            <span class="ordertext">Итого</span>
        </div>

        <div class="order-group d-flex flex-column">
            {{--<span class="orderNumber">Nº 146227</span>--}}
            <span class="finalPrice basket-total-amount">
                {{ formatPrice($basketService->getBasketTotal(true, true)) }}
                @include('components.currency-symbol')
            </span>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Быстрый заказ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{--<div class="modal-body">
                    <form action="{{ route('fastOrder') }}" method="post">
                    <div class="form-group">
                        <label for="name">Имя Фамилия </label>
                        <input type="text" name="name" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Имя Фамилия">
                    </div>
                    <div class="form-group">
                        <label for="name">город </label>
                        <input type="text" name="gorod" class="form-control" id="name" aria-describedby="nameHelp" placeholder="место рождения">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Майл</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Майл">
                    </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">адрес доставки</label>
                            <input type="email" name="dostavka" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="адрес доставки">
                        </div>с
                    <div class="form-group">
                        <label for="phone">Номер</label>
                        <input type="number" name="nomer" class="form-control" id="phone" aria-describedby="phone" placeholder="Номер">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                    </form>
                </div>--}}

            </div>
        </div>
    </div>
    <div class="basket__mini-buttons">
        <a type="button" class="order__button mr-3" href="{{ route('fastOrderView') }}">Быстрый заказ</a>
        @if(!\Illuminate\Support\Facades\Auth::check())
            <a href="{{ route('registerFor') }}" class="order__button" title="для оформления пройдите регистрацию">Заказать</a>
        @endif
        @if(authUser())
            <a href="{{ route('cabinet.profile.basket') }}" class="order__button shop-page-btn ">Перейти к оформлению</a>
        @endif
    </div>
@else
    <h5 class="text-center my-3">Корзина пуста</h5>
@endif

<script>
    function delBask(val) {
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('del.order.bask') }}',
            data: {
                "_token": "{{ csrf_token() }}",
                'itemId': val,

            },
            success: function (data) {
                $(".del" + val).remove();
                data = JSON.stringify(data);
                location.reload();
            }
        });
    }
</script>
