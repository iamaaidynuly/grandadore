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
                        <li class="active"><span>02</span></li>
                        <li class="in-active"><span>03</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="d-flex mt-xl-5">
            @include('site.components.personal-infobar')
            <div class="personal-inforamtion w-100 ml-lg-5 mt-1">
                <div class="personal-inforamtion w-100 ml-lg-5">
                    <h1 class="mb-5">Ваш заказ принят</h1>
                    <div class=" text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" class="mb-4 mt-5" viewBox="0 0 28 28">
                            <g id="Group_5947" data-name="Group 5947" transform="translate(-936 -671)">
                                <rect id="Rectangle_1395" data-name="Rectangle 1395" width="11" height="11" transform="translate(936 671)" fill="#212121" opacity="0.4"/>
                                <rect id="Rectangle_1396" data-name="Rectangle 1396" width="10" height="11" transform="translate(954 671)" fill="#212121"/>
                                <rect id="Rectangle_1397" data-name="Rectangle 1397" width="11" height="10" transform="translate(936 689)" fill="#212121" opacity="0.63"/>
                                <rect id="Rectangle_1398" data-name="Rectangle 1398" width="10" height="10" transform="translate(954 689)" fill="#212121" opacity="0.8"/>
                            </g>
                        </svg>

                        <h4>Ваш заказ отправлен на подтверждение</h4>
                        <h2>Скоро наш оператор свяжется с вами</h2>
                    </div>

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
