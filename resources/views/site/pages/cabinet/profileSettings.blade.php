@extends('site.pages.cabinet.cabinet_layout')

@push('css')
    <!-- <link rel="stylesheet" href="{{asset('css/profile.css')}}"> -->
    <link rel="stylesheet" href="{{asset('css/mydata.css')}}">
@endpush
@push('js')
    <script src="{{ asset('js/basket-calculator.js') }}"></script>
@endpush
@if(session()->has('message'))
    <div class="alert alert-success text-left mt-3" style="z-index: 999">
        {{ session()->get('message') }}
    </div>
@endif
@section('cabinetContent')
    <!-- New -->

    <div class="personal__form">
        <form class="row" action="{{ route('cabinet.profile.updateUserInfo') }}" method="post">
            @csrf
            <div class="mb-4 col-12 col-sm-6 col-lg-6">
                <label for="name" class="form-label">
                    ФИО
                    <span>*</span>
                </label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $user->name }}">
                @if($errors->has('name'))
                    <span class="input-alert">{{ $errors->first('name') }}</span>
                @endif
            </div>

            <div class="mb-4 col-12 col-sm-6 col-lg-6 offset-mr-lg-4">
                <label for="delivery_city_id" class="form-label">
                    Населенный пункт
                    <span>*</span>
                </label>
                <select class="form-control w-100" id="delivery_city_id" name="delivery_city_id">
                    <option value="">Не выбрано</option>
                    @foreach($regions as $region)
                        <optgroup label="{{ $region->title }}">
                            @foreach($region->cities as $city)
                                <option
                                        value="{{ $city->id }}"{{ (old('delivery_city_id') ?? $user->delivery_city_id) == $city->id ? ' selected' : '' }}>{{ $city->title }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @if($errors->has('delivery_city_id'))
                    <span class="input-alert">{{ $errors->first('delivery_city_id') }}</span>
                @endif
            </div>

            <div class="mb-4 col-12 col-sm-6 col-lg-6">
                <label for="inputphone" class="form-label">
                    Мобильный телефон
                    <span>*</span>
                </label>
                {{--<input type="tel" class="form-control" id="inputphone">--}}
                <u>{{ $user->formattedPhone }}</u>
                <button class="btn btn-grey btn-xs ml-1" type="button" data-toggle="modal" data-target="#phoneChangingModal">{{ $user->phone ? 'Поменять' : 'Привязать' }}</button>
            </div>

            <div class="mb-4 col-12 col-sm-6 col-lg-6 offset-mr-lg-4">
                <label for="inputemail" class="form-label">
                    Почта
                    <span>*</span>
                </label>
                <u>{{ $user->email }}</u>
                <button class="btn btn-grey btn-xs ml-1" type="button" data-toggle="modal" data-target="#emailChangingModal">{{ $user->email ? 'Поменять' : 'Привязать' }}</button>
                {{--<input type="email" class="form-control" id="inputemail">--}}
            </div>

            <div class="mb-4 col-12 mr-lg-1">
                <label for="city" class="form-label">
                    Адрес
                    <span>*</span>
                </label>
                <input type="text" class="form-control" id="city" name="city" value="{{ old('address') ?? $user->address }}">
                @if($errors->has('address'))
                    <span class="input-alert">{{ $errors->first('address') }}</span>
                @endif
            </div>

            <!-- <div class="mb-4 col-lg-1"></div> -->

            <div class="mb-4 col-12 col-sm-6 col-lg-4">
                <label for="inputpassword" class="form-label">
                    Старый пароль
                    <span>*</span>
                </label>
                <input type="password" class="form-control" id="inputpassword" name="old_pass">
            </div>

            <div class="mb-4 col-12 col-sm-6 col-lg-4">
                <label for="inputpassword2" class="form-label">
                    Новый пароль
                    <span>*</span>
                </label>
                <input type="password" class="form-control" id="inputpassword2" name="password1">
            </div>

            <div class="mb-4 col-12 col-sm-6 col-lg-4">
                <label for="inputpassword3" class="form-label">
                    Повторите новый пароль
                    <span>*</span>
                </label>
                <input type="password" class="form-control" id="inputpassword3" name="password2">
            </div>

            <div class="submit-col col-12 d-flex flex-column flex-sm-row align-items-start align-items-sm-center d-flex justify-content-end">
                <div class="detail" style="display: none">
                    <input id="inputcheckbox" class="inputcheckbox" type="checkbox">
                    <label for="inputcheckbox" class="consent p-0 m-0">
                        Согласен на обработку персональных данных
                    </label>
                </div>
                <button type="submit" class="form-btn">Сохранить</button>
            </div>
        </form>
    </div>
    <!-- New -->

    {{--<h1 class="mb-4" style="display: none">Личная информация</h1>
    <div class="row user-cabinet-row" style="display: none">
        <div class="col-12 col-md-6">
            <form method="post" action="{{ route('cabinet.profile.updateUserInfo') }}">
                @csrf
                <div class="bordered-container">
                    <div class="form-group">
                        <label for="name" class="col-form-label">
                            <span>ФИО</span>
                            <sup>*</sup>
                        </label>
                        <input type="text" class="form-control" id="name" value="{{ old('name') ?? $user->name }}"
                               name="name">
                        @if($errors->has('name'))
                            <span class="input-alert">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="delivery_city_id" class="col-form-label">
                            <span>Населенный пункт</span>
                            <sup>*</sup>
                        </label>
                        <select class="form-control w-100" id="delivery_city_id" name="delivery_city_id">
                            <option value="">Не выбрано</option>
                            @foreach($regions as $region)
                                <optgroup label="{{ $region->title }}">
                                    @foreach($region->cities as $city)
                                        <option
                                            value="{{ $city->id }}"{{ (old('delivery_city_id') ?? $user->delivery_city_id) == $city->id ? ' selected' : '' }}>{{ $city->title }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @if($errors->has('delivery_city_id'))
                            <span class="input-alert">{{ $errors->first('delivery_city_id') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="city" class="col-form-label">
                            <span>Адрес</span>
                            <sup>*</sup>
                        </label>
                        <input type="text" class="form-control" id="city" value="{{ old('address') ?? $user->address }}"
                               name="address">
                        @if($errors->has('address'))
                            <span class="input-alert">{{ $errors->first('address') }}</span>
                        @endif
                    </div>
                    <div class="form-group d-flex justify-content-end">
                        <button class="btn btn-grey" type="submit">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-6">
            <div class="bordered-container">
                <ul class="list-group">
                    <li>
                        <span>Номер телефона:</span>
                        <u>{{ $user->formattedPhone }}</u>
                        <button class="btn btn-grey btn-xs ml-1" type="button" data-toggle="modal" data-target="#phoneChangingModal">Сменить</button>
                    </li>
                    <li>
                        <span>Эл. почта:</span>
                        <u>{{ $user->email }}</u>
                        <button class="btn btn-grey btn-xs ml-1" type="button" data-toggle="modal" data-target="#emailChangingModal">Сменить</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>--}}

    <div class="modal fade" id="phoneChangingModal" tabindex="-1" role="dialog" aria-labelledby="phoneChangingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="phoneChangingModalLabel">{{ $user->phone ? 'Смена' : 'Привязка' }} номера телефона</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert" id="phoneAlert" style="font-size: 12px; display: none;"></div>
                    <div class="form-group">
                        <label for="phone">Новый номер телефона</label>
                        <input type="text" class="form-control masked-phone-inputs" id="cabinet-phone" value="" name="phone">
                    </div>
                    <div class="form-group" style="display: none;">
                        <label for="phoneCode">Код подтверждения</label>
                        <input type="text" class="form-control " id="phoneCode" value="">
                    </div>
                    <div class="form-group d-flex justify-content-end">
                        <button type="button" class="btn btn-grey btn-sm" id="phoneChangingTrigger">Отправить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="emailChangingModal" tabindex="-1" role="dialog" aria-labelledby="emailChangingModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailChangingModalLabel">{{ $user->email ? 'Смена' : 'Привязка' }} адреса эл. почты</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert" id="emailAlert" style="font-size: 12px; display: none;"></div>
                    <div class="form-group">
                        <label for="email">Новый адрес эл. почты</label>
                        <input type="text" class="form-control" id="cabinet-email" value="{{$user->email}}" name="email">
                    </div>
                    <div class="form-group" style="display: none;">
                        <label for="emailCode">Код подтверждения</label>
                        <input type="text" class="form-control " id="emailCode" value="">
                    </div>
                    <div class="form-group d-flex justify-content-end">
                        <button type="button" class="btn btn-grey btn-sm" id="emailChangingTrigger" data-user="{{$user->id}}"  >Отправить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
