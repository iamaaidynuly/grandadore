@extends('site.pages.cabinet.cabinet_layout', ['disableSmallBasket' => true])
@push('css')
    <link rel="stylesheet" href="{{asset('css/order.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.select.min.css')}}">
@endpush
@push('js')
    <script src="{{ asset('js/order.js') }}"></script>
    <script src="{{ asset('js/bootstrap.select.min.js') }}"></script>
@endpush
@section('cabinetContent')
    <!-- New -->
    <? /** @var App\Services\BasketService\BasketService $basketService */ ?>
    <script src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=ce752946-5050-4a17-9d27-624b2dc71d8b&lang=ru_RU"></script>
    <div class="paymentform container">
        <form method="post" action="{{ route('cabinet.order.newOrder') }}" id="order-form"
              class="paymentform w-100 position-relative" novalidate>
        @csrf
        <!-- ФИО -->
            <div class="form-group mx-0 row">
                <label for="name" class="d-flex align-items-center form-label col-12 col-lg-3">ФИО*</label>
                <input type="text"
                       class="form-control col-12 col-lg-9"
                       id="name"
                       name="name"
                       value="{{ old('name') ?? authUser()->name }}">
                @if($errors->has('name'))
                    <span class="input-alert">{{ $errors->first('name') }}</span>
                @endif
            </div>

            <!-- Мобильный телефон -->
            <div class="form-group mx-0 row">
                <label for="phone" class="d-flex align-items-center form-label col-12 col-lg-3">Мобильный
                    телефон*</label>
                <input type="tel"
                       class="form-control col-12 col-lg-9 masked-phone-inputs"
                       id="phone"
                       name="phone"
                       data-value="{{ old('phone') ?? authUser()->phoneWithoutCountryCode }}"
                       value="{{ old('phone') ?? authUser()->phoneWithoutCountryCode }}">
                @if($errors->has('phone'))
                    <span class="input-alert">{{ $errors->first('phone') }}</span>
                @endif
            </div>

            <!-- Метод доставки -->
            <div class="form-group mx-0 row">
                <label for="phone" class="form-label d-flex align-items-center form-label col-12 col-lg-3">Выберите
                    метод доставки*</label>

                <div class="select-wrap col-12 col-lg-9 form-dropdown d-flex align-items-center p-0">
                    <select required class="selectpicker w-100" id="select1" name="type_paynament">
                        <option>Выбрать</option>
                        @if(count($pickupPoints))
                            <option value="samovivoz">Самовывоз</option>
                        @endif
                        <option value="dostavka-do-dveri">Доставка до двери</option>
                    </select>
                </div>
            </div>
        @if(count($pickupPoints))
            <!-- Точка самовывоза -->
                <div class="form-group mx-0 row tochka-samovivoza">
                    <label for="phone" class="form-label d-flex align-items-center form-label col-12 col-lg-3">Точка
                        самовывоза</label>
                    <div class="select-wrap col-12 col-lg-9 form-dropdown d-flex align-items-center p-0"
                         style="padding-right: 8px !important;">
                        <select required class="w-100 latlng map-select" name="address_selected">
                            <option value="">Выбрать</option>
                            @foreach($pickupPoints as $point)
                                <option class="optionYandex" value="{{$point->lat . "||" .$point->lng."||".$point->id}}"
                                        data-x="{{ $point->lat }}"
                                        data-y="{{ $point->lng }}">{{$point->address}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="yandexMap mb-3"></div>
        @endif
        {{-- <div class="form-group row address">
             <div class="pickup-map-wrap col-12 col-lg-9 d-flex offset-3" id="map01">
                 <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3Aa2aeaf60dee70c2c5ae255d3ed89aabdc9d47881b22b25018114f62f599968cd&amp;source=constructor" width="100%" height="100%" frameborder="0"></iframe>
             </div>

             <div class="map-info d-flex flex-column mt-4 w-100 text-right">
                 <span class="text">Название : Алмата</span>
                 <a href="tel:+77777777777" class="text">Телефон: +7 777 777 7777</a>
                 <a href="https://yandex.ru/maps/11119/republic-of-tatarstan/house/derevnya_alma_ata_2/YUoYdwRlTEwFQFtufXp1dXtlYQ==/?ll=53.137340%2C54.645081&source=wizgeo&utm_medium=maps-desktop&utm_source=serp&z=18"  class="text">Адрес: Алмата 3 улица </a>
             </div>
         </div>--}}

        <!-- Регион -->
            <div class="form-group mx-0 row region">
                <label for="phone"
                       class="form-label d-flex align-items-center form-label col-12 col-lg-3">Регион</label>

                <div class="select-wrap col-12 col-lg-9 form-dropdown d-flex align-items-center p-0">
                    <select required class="selectpicker w-100" name="region" id="region">
                        <option class="test">Выбрать</option>
                        @foreach($deliverAll as $deliver)
                            <option value="{{$deliver->id}}">{{$deliver->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Населенный пункт -->
            <div class="form-group mx-0 row naselyonniy-punkt">
                <label for="phone" class="form-label d-flex align-items-center form-label col-12 col-lg-3">Населенный
                    пункт</label>

                <div class="select-wrap col-12 col-lg-9 form-dropdown d-flex align-items-center p-0 uniqal">
                </div>
            </div>
            <input type="text" id="type_paynament" name="type_paynament" value="" hidden>
            <!-- Адрес -->
            <div class="form-group mx-0 row address-input">
                <label for="address"
                       class="d-flex align-items-center form-label d-flex align-items-center form-label col-12 col-lg-3">Адрес*</label>
                <input type="text" class="dropdown form-dropdown d-flex w-100 col-12 col-lg-9" id="address"
                       name="address">
                @if($errors->has('address'))
                    <span class="input-alert">{{ $errors->first('address') }}</span>
                @endif
            </div>

            <!-- Метод оплаты-->
            <div class="form-group mx-0 row method-oplati">
                <label for="phone" class="form-label d-flex align-items-center form-label col-12 col-lg-3">Выберите
                    метод оплаты</label>

                <div class="select-wrap col-12 col-lg-9 form-dropdown d-flex align-items-center p-0">
                    <select required class="selectpicker w-100" name="payment_method" id="selectmethod"
                            style="border: 1px solid red">
                        <option value="">Выбрать</option>
                        <option value="cash">Наличными на месте</option>
                        <option value="bank">Онлайн оплата</option>
                    </select>
                </div>
            </div>

            <div class="form-group mx-0 row sled-shag">
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="form-btn">Отправить</button>
                </div>
            </div>
        </form>
    </div>
    <!-- New -->

    {{--   <? /** @var App\Services\BasketService\BasketService $basketService */ ?>
       <form method="post" action="{{ route('cabinet.order.newOrder.deliver') }}" style="display: none">
           @csrf
           <h1 class="mb-4">Информация о заказе</h1>
           <div class="form-group row">
               <label for="name" class="col-sm-3 col-form-label">ФИО заказчика</label>
               <div class="col-sm-9">
                   <input type="text" class="form-control" id="name" name="name"
                          value="{{ old('name') ?? authUser()->name }}">
                   @if($errors->has('name'))
                       <span class="input-alert">{{ $errors->first('name') }}</span>
                   @endif
               </div>
           </div>
           <div class="form-group row">
               <label for="phone" class="col-sm-3 col-form-label">Номер телефона заказчика</label>
               <div class="col-sm-9">
                   <input type="text" class="form-control masked-phone-inputs" id="phone" name="phone"
                          data-value="{{ old('phone') ?? authUser()->phoneWithoutCountryCode }}">
                   @if($errors->has('phone'))
                       <span class="input-alert">{{ $errors->first('phone') }}</span>
                   @endif
               </div>
           </div>

           <div class="form-group row">
               <label for="delivery" class="col-sm-3 col-form-label">
                   <span>Населенный пункт</span>
               </label>
               <div class="col-sm-9">
                   <select required class="form-control" id="delivery" name="city_id">
                       <option value="">Не выбрано</option>
                       @foreach($regions as $region)
                           <optgroup label="{{ $region->title }}">
                               @foreach($region->cities as $city)
                                   <option data-min-price="{{ $city->min_price }}" data-price="{{ $city->price }}" value="{{ $city->id }}"{{ (old('delivery_city_id') ?? $user->delivery_city_id) == $city->id ? ' selected' : '' }}>{{ $city->title }}</option>
                               @endforeach
                           </optgroup>
                       @endforeach
                   </select>
                   @if($errors->has('delivery_city_id'))
                       <span class="input-alert">{{ $errors->first('delivery_city_id') }}</span>
                   @endif
               </div>

           </div>
           <div class="form-group row">
               <label for="city" class="col-sm-3 col-form-label">
                   <span>Адрес</span>
               </label>
               <div class="col-sm-9">
                   <input type="text" class="form-control" id="city" value="{{ old('address') ?? $user->address }}"
                          name="address">
                   @if($errors->has('address'))
                       <span class="input-alert">{{ $errors->first('address') }}</span>
                   @endif
               </div>
           </div>
           <div class="form-group row">
               <label for="payment_method" class="col-sm-3 col-form-label">Метод оплаты</label>
               <div class="col-sm-9">
                   <select required class="form-control w-100" id="payment_method" name="payment_method">
                       <option value="cash">Наличными на месте</option>
                       <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : null }}>Безналичная оплата</option>
                   </select>
               </div>
           </div>
           <div class="d-flex justify-content-end align-items-end flex-column p-0">
               <div class="border-top-grey">
                   <div class="non-free-delivery" style="display:none;">
                       <span>Доставка: </span>
                       <span class="delivery-price"></span>
                       <sub class="currency-icon">₸</sub>
                   </div>
                   <input type="text" id="payment_method77" value="" hidden>
                   <div class="free-delivery" style="display:none;">
                       <u>Бесплатная доставка</u>
                   </div>
               </div>
               <p class="m-0">Сумма заказа: <span class="order-total" data-total="{{ $basketService->getBasketTotal() }}"></span> <sub>₸</sub></p>
               <button class="btn btn-grey mt-2">Заказать</button>
           </div>
       </form>--}}
@endsection

@push('js')
    <script src="{{ asset('js/basket-calculator.js') }}"></script>
    <script src="{{ asset('js/order.js') }}"></script>
    <script>
        $('#delivery').change(function () {
            basketCalculator.calculateOrderForm();
        });

        $(document).ready(function () {
            basketCalculator.calculateOrderForm();
        });

        $('body').on('change', '#select1', function () {
            $('#type_paynament').val($('#select1').val())
        })


        $('body').on('change', '#selectmethod', function () {
            $('.payment_method77').val($('#selectmethod').val())
        })


        function change(id) {
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('cabinet.order.naselionniPunk') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id,
                },
                success: function (data) {
                    $('.uniqal').html(data)
                }
            });
        }

        $('body').on('change', '#region', function () {
            change($(this).val())
        })

        $('body').on('change', '.latlng', function () {
            yandex($(this).val())
        })

        function yandex(x) {
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('yandex') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    x: x,
                },
                success: function (data) {
                    $('.yandexMap').html(data);
                }
            });
        }


    </script>
@endpush


















