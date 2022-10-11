@extends('site.pages.cabinet.cabinet_layout', ['disableSmallBasket' => true])
<style>
    .personal__panel{
      display: none!important;
    }
</style>

@push('css')
    <link rel="stylesheet" href="{{ asset('css/basket.css') }}">
@endpush

@push('js')
    <script src="{{ asset('js/bootstrapselect.js') }}"></script>
    <script>
        basketCalculator.isBigBasket = true;
    </script>
@endpush
@if(session('remove'))
    @foreach($basketService->getItems()->toArray() as $basket)


        @php  $basketService->delete($basket['itemId']); @endphp
    @endforeach
@endif

@section('cabinetContent')
    @if(count($basketService->getItems()))

    <form action="{{ route('fastOrder') }}" method="post" class="w-100 input-field" id="fast_form">
        @csrf
    <div class="d-flex flex-column w-100">

        <div class="table-responsive-sm basket-table">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Артикул*</th>
                    <th scope="col">Название товара*</th>
                    <th scope="col">Цена*</th>
                    <th scope="col">Количество*</th>
                    <th scope="col">Стоимость*</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <? /** @var App\ValueObjects\BasketItem $basketItem */ ?>

                @if(isset($basketService) && $basketService != null && $basketService != "")
                @foreach($basketService->getItems() as $basketItem)

                    <tr class="basket-item-row" data-item-id="{{ $basketItem->getItemId() }}">
                        <th scope="row" class="colorblack">{{ $basketItem->getItem()->code }}</th>
                        <td class="colorblack">
                            <a target="_blank" href="{{ route('product.view', ['url' => $basketItem->getItem()->url]) }}">
                                {{ $basketItem->getItemTitle() }} {{ $basketItem->getSize() ? '('.$basketItem->getSize()->name.')' : '' }}
                            </a>
                        </td>
                        <td class="gray price-styless">
                        <span class="basket-price" data-price="{{ $basketItem->getDiscountedPrice(true) }}">
                            {{ formatPrice($basketItem->getDiscountedPrice(true)) }}
                        </span>
                            @include('components.currency-symbol')
                        </td>
                        <td>
                            <div class="amount input-group-prepend">
                                <div class="down item-count-decrement">
                                    <img src="{{ asset('images/counterarrowleft.png') }}" alt="" title="">
                                </div>

                                <input class="amountInp quantity basket-item-count"
                                       type="number"
                                       min="1"
                                       value="{{ $basketItem->getCount() }}">

                                <div class="up item-count-increment">
                                    <img src="{{ asset('images/counterarrowright.png') }}" alt="" title="">
                                </div>
                            </div>
                        </td>
                       <td class="gray price-styless">
                            <span class="item-total-price">-</span>
                            @include('components.currency-symbol')
                        </td>
                        <td class="gray">
                            <i class="far fa-trash-alt remove-item"></i>
                        </td>
                    </tr>

                    <input type="text" name="id[]" value="{{$basketItem->getItemId()}}" hidden>

                    <input type="text" name="count[]" value="{{$basketItem->getCount()}}" hidden>
                @endforeach
                @endif
                </tbody>
            </table>
        </div>
        @if(!empty($basketService->getItems()->toArray()))
        <div class="row">
            {{--<div class="order-group">
                <span class="text">Номер заказа</span>
                <span class="text">Nº 146227</span>
            </div>--}}

            {{--<div class="order-group">
                <span class="text">Цена</span>
                <span class="text-bold basket__price">7.500.000<span class="tenge">₸</span></span>
            </div>

            <div class="order-group">
                <span class="text">Цена с учётом скидок</span>
                <span class="text-bold discount__price">7.500.000<span class="tenge">₸</span></span>
            </div>--}}
@if(isset($user))
            <div class="order-group col-12 d-flex justify-content-end" style="font-weight: 700">
                <p class="m-0 border-top-grey">Сумма: <span class="basket-total-amount"></span> <sub>₸</sub></p>
            </div>

            <div class="form-group col-12 col-sm-6 col-lg-4">
                <label for="name">Имя Фамилия <span class="first-letter">*</span></label>
                <input type="text" name="name" class="form-control nice-input" id="name" aria-describedby="nameHelp" value="{{ $user->name }}">
            </div>

            <div class="form-group col-12 col-sm-6 col-lg-4">
                <label for="city">Город <span class="first-letter">*</span></label>
                <input type="text" name="gorod" class="form-control nice-input" id="city" aria-describedby="nameHelp"  value="{{ $user->address }}">
            </div>

            <div class="form-group col-12 col-sm-6 col-lg-4">
                <label for="exampleInputEmail1">Эл. почта<span class="first-letter">*</span></label>
                <input type="email" name="email" class="form-control nice-input" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $user->email }}">
            </div>

            <div class="form-group col-12 col-sm-6 col-lg-4">
                <label for="address">Адрес доставки<span class="first-letter">*</span></label>
                <input type="text" name="dostavka" class="form-control nice-input" id="address" aria-describedby="emailHelp">
            </div>

            <div class="form-group col-12 col-sm-6 col-lg-4">
                <label for="phone">Номер<span class="first-letter">*</span></label>
                <input type="number" name="nomer" class="form-control nice-input" id="phone" aria-describedby="phone" value="{{ $user->phone }}">
            </div>


            <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="order__button">Отправить заказ</button>
            </div>
            @else
                <div class="order-group col-12 d-flex justify-content-end" style="font-weight: 700">
                    <p class="m-0 border-top-grey">Сумма: <span class="basket-total-amount"></span> <sub>₸</sub></p>
                </div>

                <div class="form-group col-12 col-sm-6 col-lg-4">
                    <label for="name">Имя Фамилия <span class="first-letter">*</span></label>
                    <input type="text" name="name" class="form-control nice-input" id="name" aria-describedby="nameHelp">
                </div>

                <div class="form-group col-12 col-sm-6 col-lg-4">
                    <label for="city">Город <span class="first-letter">*</span></label>
                    <input type="text" name="gorod" class="form-control nice-input" id="city" aria-describedby="nameHelp">
                </div>

                <div class="form-group col-12 col-sm-6 col-lg-4">
                    <label for="exampleInputEmail1">Эл. почта<span class="first-letter">*</span></label>
                    <input type="email" name="email" class="form-control nice-input" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>

                <div class="form-group col-12 col-sm-6 col-lg-4">
                    <label for="address">Адрес доставки<span class="first-letter">*</span></label>
                    <input type="text" name="dostavka" class="form-control nice-input" id="address" aria-describedby="emailHelp">
                </div>

                <div class="form-group col-12 col-sm-6 col-lg-4">
                    <label for="phone">Номер<span class="first-letter">*</span></label>
                    <input type="number" name="nomer" class="form-control nice-input" id="phone" aria-describedby="phone">
                </div>


                <div class="col-12 d-flex justify-content-end">
                    <script>
                        function onSubmit(token) {
                            if (document.getElementById("fast_form").checkValidity()) {
                                document.getElementById("fast_form").submit();
                            } else {
                                document.getElementById("fast_form").reportValidity()
                            }
                        }
                    </script>
                    <button type="submit" class="order__button g-recaptcha"
                            data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"
                            data-callback='onSubmit'
                            data-action='submit'>Отправить заказ</button>
                </div>
            @endif
        </div>
@endif
    </div>
    </form>
    @else
        <div class="d-flex justify-content-center flex-grow-1">
            <h2 class="text-center">Корзина пуста</h2>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="basket-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <p class="text-center h5">Спасибо, Ваш заказ оформлен</p>
                    </div>
                    <div class="modal-footer m-0 p-0">
                        <a href="/" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
{{--    <div class="big-basket-container" style="display: none">
        @if(count($basketService->getItems()))
            <h1 class="mb-xl-3 text-lg-left text-center p-lg-0 p-3">Моя корзина</h1>
            <div class="personal-table table-responsive-xxxl">
                <table class="table">
                    <thead>
                    <tr>
                        <td>Артикул</td>
                        <td>Название товара</td>
                        <td>Цена</td>
                        <td>Количество</td>
                        <td colspan="2">Стоимость</td>
                    </tr>
                    </thead>
                    <tbody>
                    <? /** @var App\ValueObjects\BasketItem $basketItem */ ?>
                    @foreach($basketService->getItems() as $basketItem)
                        <tr class="basket-item-row" data-item-id="{{ $basketItem->getItemId() }}">
                            <td>{{ $basketItem->getItem()->code }}</td>
                            <td>
                                <a target="_blank"
                                   href="{{ route('product.view', ['url' => $basketItem->getItem()->url]) }}">{{ $basketItem->getItemTitle() }} {{ $basketItem->getSize() ? '('.$basketItem->getSize()->name.')' : '' }}</a>
                            </td>
                            <td class="td-grey">
                        <span class="basket-price"
                              data-price="{{ $basketItem->getDiscountedPrice() }}">{{ formatPrice($basketItem->getDiscountedPrice()) }}</span>
                                <sub>₸</sub>
                            </td>
                            <td>
                                <div class="input-group-prepend">
                                    <button class="btn btn-minus item-count-decrement">-</button>
                                    <input class="form-control quantity basket-item-count" min="1" name="quantity"
                                           value="{{ $basketItem->getCount() }}" type="number">
                                    <button class="btn  btn-plus item-count-increment">+</button>
                                </div>
                            </td>
                            <td class="td-grey">
                                <span class="item-total-price">-</span>
                                <sub>₸</sub>
                            </td>
                            <td class="button-grey">
                                <button class="btn remove-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17.869" height="22.001"
                                         viewBox="0 0 17.869 22.001">
                                        <g id="Group_6036" data-name="Group 6036" transform="translate(-1646.86 -388)">
                                            <path id="Path_1206" data-name="Path 1206"
                                                  d="M222.913,154.7a.515.515,0,0,0-.515.515v9.738a.515.515,0,0,0,1.031,0v-9.738A.515.515,0,0,0,222.913,154.7Zm0,0"
                                                  transform="translate(1435.921 241.268)" fill="#9b9b9b"/>
                                            <path id="Path_1207" data-name="Path 1207"
                                                  d="M104.913,154.7a.515.515,0,0,0-.515.515v9.738a.515.515,0,0,0,1.031,0v-9.738A.515.515,0,0,0,104.913,154.7Zm0,0"
                                                  transform="translate(1547.842 241.268)" fill="#9b9b9b"/>
                                            <path id="Path_1208" data-name="Path 1208"
                                                  d="M1.46,6.549V19.243A2.843,2.843,0,0,0,2.216,21.2a2.537,2.537,0,0,0,1.841.8h9.749a2.537,2.537,0,0,0,1.841-.8,2.843,2.843,0,0,0,.756-1.961V6.549a1.968,1.968,0,0,0-.5-3.871H13.259V2.034A2.024,2.024,0,0,0,11.219,0H6.644A2.024,2.024,0,0,0,4.6,2.034v.644H1.965a1.968,1.968,0,0,0-.5,3.871ZM13.806,20.969H4.057a1.632,1.632,0,0,1-1.566-1.726V6.594H15.372v12.65a1.632,1.632,0,0,1-1.566,1.726ZM5.634,2.034a.993.993,0,0,1,1.01-1h4.575a.993.993,0,0,1,1.01,1v.644h-6.6ZM1.965,3.708H15.9a.927.927,0,0,1,0,1.855H1.965a.927.927,0,0,1,0-1.855Zm0,0"
                                                  transform="translate(1646.863 388.001)" fill="#9b9b9b"/>
                                            <path id="Path_1209" data-name="Path 1209"
                                                  d="M163.913,154.7a.515.515,0,0,0-.515.515v9.738a.515.515,0,1,0,1.03,0v-9.738A.515.515,0,0,0,163.913,154.7Zm0,0"
                                                  transform="translate(1491.881 241.268)" fill="#9b9b9b"/>
                                        </g>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end align-items-end flex-column p-0">
                <p class="m-0 border-top-grey">Сумма: <span class="basket-total-amount"></span> <sub>₸</sub></p>
                <a class="btn btn-grey mt-2" href="{{ route('cabinet.order.create') }}">Оформить заказ</a>
            </div>
        @else
            <h2 class="text-center">Товаров нет</h2>
        @endif
    </div>--}}
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function() {
            $('#basket-modal').modal('show');
        });
    </script>



@endsection




