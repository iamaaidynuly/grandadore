@extends('site.pages.cabinet.cabinet_layout', ['disableSmallBasket' => true])

@push('css')
    <link rel="stylesheet" href="{{ asset('css/basket.css') }}">
@endpush

@push('js')
    <script src="{{ asset('js/bootstrapselect.js') }}"></script>
    <script>
        basketCalculator.isBigBasket = true;
    </script>
@endpush

@section('cabinetContent')
    @if(count($basketService->getItems()))
        <div class="d-flex flex-column w-100">
            {{--            table-responsive-sm--}}
            <div class=" basket-table">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Артикул</th>
                        <th scope="col">Название товара</th>
                        <th scope="col">Цвет</th>
                        <th scope="col">Размер</th>
                        <th scope="col">Количество</th>
                        <th scope="col">Стоимость</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>

                    <tbody>
                    <? /** @var App\ValueObjects\BasketItem $basketItem */ ?>

                    @foreach($basketService->getItems() as $basketItem)
                        <tr class="basket-item-row" data-item-id="{{ $basketItem->getItemId() }}">
                            <th scope="row" class="colorblack">{{ $basketItem->getItem()->code }}</th>
                            <td class="colorblack">
                                <a target="_blank"
                                   href="{{ route('product.view', ['url' => $basketItem->getItem()->url]) }}">
                                    {{ $basketItem->getItemTitle() }}
                                </a>
                            </td>
                            <td class="gray gray_dis-none">
                                <span class="basket-price" data-price="{{ $basketItem->getDiscountedPrice(true) }}">
                                    {{ formatPrice($basketItem->getDiscountedPrice(true)) }}
                                </span>
                                @include('components.currency-symbol')
                            </td>
                            <td class="colors">
                                @if($basketItem->getColor() != null)
                                <div class="color"
                                     style="background-color: {{ '#'.$basketItem->getColor()->hex_color }}"></div>
                                @endif
                            </td>
                            <td class="sizes">
                                @if($basketItem->getSize() != null)
                                    <div class="basket_size"
                                         data-criterion="83">{{ $basketItem->getSize() ? $basketItem->getSize()->name : '' }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="amount input-group-prepend">
                                    <div class="down item-count-decrement">
                                        <img src="{{ asset('images/counterarrowleft.png') }}" alt="" title="">
                                    </div>

                                    <input class="amountInp quantity basket-item-count"
                                           type="number"
                                           min="1"
                                           name="quantity"
                                           value="{{ $basketItem->getCount() }}">
                                    <div class="up item-count-increment">
                                        <img src="{{ asset('images/counterarrowright.png') }}" alt="" title="">
                                    </div>
                                </div>
                            </td>
                            <td class="gray">
                                <span class="item-total-price">-</span>
                                @include('components.currency-symbol')
                            </td>
                            <td class="gray">
                                <i class="far fa-trash-alt remove-item"></i>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="order-info">
                {{--<div class="order-group">
                    <span class="text">Номер заказа</span>
                    <span class="text">Nº 146227</span>
                </div>--}}

                {{-- <div class="order-group">
                     <span class="text">Цена</span>
                     <span class="text-bold basket__price">7.500.000<span class="tenge">₸</span></span>
                 </div>

                 <div class="order-group">
                     <span class="text">Цена с учётом скидок</span>
                     <span class="text-bold discount__price">7.500.000<span class="tenge">₸</span></span>
                 </div>--}}
                @if(formatPrice($basketService->getBasketTotal(true, true)) < formatPrice($price->price))
                    <p>{{ $price->text }} {{formatPrice($price->price)}} @include('components.basket-currency-symbol')</p>
                @endif
                <div class="order-group">
                    <p class="m-0 border-top-grey">Сумма: <span
                            class="basket-total-amount"></span> @include('components.basket-currency-symbol')</p>
                </div>


                <button class="order-btn">
                    <a class="text-white text-decoration-none" @if(formatPrice($basketService->getBasketTotal(true, true)) > formatPrice($price->price)) href="{{ route('cabinet.order.create') }}" @endif>Оформить
                        заказ</a>
                </button>
            </div>

        </div>
        <div class="big-basket-container" style="display: none">
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
                            <tr class="basket-item-row2" data-item-id="{{ $basketItem->getItemId() }}">
                                <td>{{ $basketItem->getItem()->code }}</td>
                                <td>
                                    <a target="_blank"
                                       href="{{ route('product.view', ['url' => $basketItem->getItem()->url]) }}">{{ $basketItem->getItemTitle() }} {{ $basketItem->getSize() ? '('.$basketItem->getSize()->name.')' : '' }}</a>
                                </td>
                                <td class="td-grey">
                        <span class="basket-price"
                              data-price="{{ $basketItem->getDiscountedPrice(true) }}">{{ formatPrice($basketItem->getDiscountedPrice(true)) }}</span>
                                    @include('components.basket-currency-symbol')
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
                                    @include('components.basket-currency-symbol')
                                </td>
                                <td class="button-grey">
                                    <button class="btn remove-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17.869" height="22.001"
                                             viewBox="0 0 17.869 22.001">
                                            <g id="Group_6036" data-name="Group 6036"
                                               transform="translate(-1646.86 -388)">
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
                    <p class="m-0 border-top-grey">Сумма: <span
                            class="basket-total-amount"></span> @include('components.basket-currency-symbol')</p>
                    <a class="btn btn-grey mt-2" href="{{ route('cabinet.order.create') }}">Оформить заказ</a>
                </div>
            @else
                <h2 class="text-center">Товаров нет</h2>
            @endif
        </div>
    @else
        <h2 class="text-center">Корзина пуста</h2>
    @endif
@endsection
