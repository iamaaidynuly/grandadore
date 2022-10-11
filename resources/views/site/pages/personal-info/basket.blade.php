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
                <h1 class="mb-xl-5 text-lg-left text-center p-lg-0 p-3">Моя корзина</h1>
                <div class="personal-table table-responsive-xxxl">
                    <table class="table">
                        <thead>
                        <tr>
                            <td>Артикул</td>
                            <td>Название товара</td>
                            <td>Цена</td>
                            <td> Количество</td>
                            <td colspan="2"> Стоимость</td>
                        </tr>
                        </thead>
                        <tbody>
                        @for($i=0; $i<10; $i++)
                            <tr>
                                <td>1004856</td>
                                <td>Женская обувь Tommy Hilfiger</td>
                                <td class="td-grey">1.500.300 <sub>₸</sub></td>
                                <td>
                                    <div class="input-group-prepend">
                                        <button class="btn btn-minus">
                                            -
                                        </button>
                                        <input class="form-control quantity" min="0" name="quantity" value="1"
                                               type="number">
                                        <button class="btn  btn-plus">
                                            +
                                        </button>
                                    </div>
                                </td>
                                <td class="td-grey"> 1.500.300 <sub>₸</sub></td>
                                <td class="button-grey">
                                    <button class="btn "><i class="fa fa-trash-alt color-grey"></i></button>
                                </td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end align-items-end flex-column p-0">
                    <p class="m-0 border-top-grey">Цена: <span>12.002.400</span> <sub>₸</sub></p>
                    <p class="m-0">Цена с учетом скидок: <span>12.002.400</span> <sub>₸</sub></p>
                    <button class="btn btn-grey mt-2">Оформить заказ</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/personal-info.css') }}">
@endpush
@section('js')
    <script src="{{ asset('js/personal-info.js') }}"></script>
@endsection
