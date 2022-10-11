@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'personal-info')

@section('content')
    <div class="container ">
        <div class="w-100 steps-for-media position-relative">


            <div class="personal-info-bar-btn">
                <div class="btn-section">
                    <div class="personal-info-button">
                        <div class="toggle-wrap-personal-info">
                            <span class="toggle-bar"></span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="step-section">
                <div class="steps">
                    <ul>
                        <li class="active"><span>01</span></li>
                        <li><span>02</span></li>
                        <li><span>03</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="d-flex mt-xl-5">
            @include('site.components.personal-infobar')
            <div class="personal-inforamtion w-100 ml-lg-5 mt-1">
            <form>
                <div class="personal-inforamtion w-100 ml-lg-5">
                    <h1 class="mb-5">Личная информация</h1>

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">ФИО </label>
                            <div class="col-sm-9">
                                <input type="text"  class="form-control" id="name" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-sm-3 col-form-label">Мобильный телефон </label>
                            <div class="col-sm-9">
                                <input type="text"  class="form-control phonenumber"  id="phone">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-sm-3 col-form-label">Выберите метод доставки</label>
                            <div class="col-sm-9">
                               <select class="select2 " style="width: 100%">
                                   <option>1</option>
                                   <option>1</option>
                                   <option>1</option>
                               </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Точка самовывоза</label>
                            <div class="col-sm-9">
                                <select class="select211 " style="width: 100%">
                                    <option disabled>Выбрать</option>
                                    <option>Алмата 3 стреет</option>
                                </select>
                            </div>
                        </div>
                    <div class="form-group row">

                        <div class="offset-lg-3 col-sm-9 col-12">
                            <div class="row">
                            <div class="col-lg-8 col-12">
                                <iframe width="100%" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                                        src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=1%20Grafton%20Street,%20Dublin,%20Ireland+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
                            </div>
                            <div class="col-lg-4 col-12">
                                <div class="address-information">
                                    <p>Название : Алмата<p>
                                    <p>Телефон : +7 777 777 7777</p>
                                    <p>Адрес : Алмата 3 Стреет</p>
                                </div>
                                <div class="address-information">
                                    <p>Название : Алмата<p>
                                    <p>Телефон : +7 777 777 7777</p>
                                    <p>Адрес : Алмата 3 Стреет</p>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>


                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-3 col-form-label">Выберите метод оплаты</label>
                    <div class="col-sm-9">
                        <select class="select21" style="width: 100%">
                            <option disabled>Выбрать</option>
                            <option>Алмата 3 стреет</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-end align-items-end flex-column p-0">
                    <p class="m-0 border-top-grey">Сумма:  <span>12.002.400</span> <sub>₸</sub></p>
                    <button class="btn btn-grey mt-2">Заказать</button>
                </div>

            </form>
            </div>

        </div>
    </div>

@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/personal-info.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/select2/css/select2.min.css') }}">
@endpush
@section('js')
    <script src="{{ asset('assets/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/personal-info.js') }}"></script>

    <script>
        $('.select2').select2();
        $('.select21').select2();
        $('.select211').select2();
    </script>
@endsection
