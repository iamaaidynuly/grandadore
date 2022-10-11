@extends('site.layouts.main', ['headerSidebar' => true, 'disableSmallBasket' => isset($disableSmallBasket) ? $disableSmallBasket : false])
@push('css')
    <link rel="stylesheet" href="{{asset('css/breadcrumb.css')}}">
    <link rel="stylesheet" href="{{ asset('css/personal-info.css') }}">
@endpush
@section('content')

    <div class="container-fluid">

        {{--<div class="personal-tablet d-lg-none">
            <div class="dropdown d-flex justify-content-start">
                <button class="dropdown-toggle personal-link active d-flex justify-content-start align-items-center" type="button" id="dropdownMenuButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <div class="icon-wrap d-flex justify-content-center align-items-center">
                        <img class="personal-user" src="images/personal-user.svg" alt="" title="">
                    </div>
                    <span>Мои данные</span>
                </button>

                <div class="dropdown-menu tablet-dropdown" aria-labelledby="dropdownMenuButton3" style="position: absolute; transform: translate3d(0px, 27px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-start">
                    <a href="#" class="dropdown-item personal-link d-flex justify-content-start">
                        <div class="icon-wrap d-flex justify-content-center align-items-center">
                            <img class="personal-heart" src="images/001-heart.svg" alt="" title="">
                        </div>
                        <span>Избранные</span>
                    </a>

                    <a class="dropdown-item personal-link d-flex justify-content-start" href="#">
                        <div class="icon-wrap d-flex justify-content-center align-items-center">
                            <img class="personal-shopping" src="images/001-shopping55.svg" alt="" title="">
                        </div>
                        <span>Моя корзина</span>
                    </a>

                    <a class="dropdown-item personal-link d-flex justify-content-start" href="#">
                        <div class="icon-wrap d-flex justify-content-center align-items-center">
                            <img class="personal-filing" src="images/001-filing-cabinet.svg" alt="" title="">
                        </div>
                        <span>Мои покупки</span>
                    </a>

                    <a class="dropdown-item personal-link d-flex justify-content-start">
                        <div class="icon-wrap d-flex justify-content-center align-items-center">
                            <img class="personal-logout" src="images/001-log-out.svg" alt="" title="">
                        </div>
                        <span>Выход</span>
                    </a>

                </div>
            </div>
        </div>--}}

        <div class="d-flex page">

            @include('site.components.personal-infobar')
            @yield('cabinetContent')

        </div>
    </div>

    {{--<div class="container">
        <div class="personal-info-bar-btn">
            <div class="btn-section">
                <div class="personal-info-button">
                    <div class="toggle-wrap-personal-info">
                        <span class="toggle-bar"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex mt-5">
            @include('site.components.personal-infobar')
            <div class="personal-inforamtion ml-lg-3">
                @yield('cabinetContent')
            </div>
        </div>
    </div>--}}
@endsection
@section('js')
    <script src="{{ asset('assets/select2/js/select2.full.min.js') }}"></script>
    <script>
        window.phoneChangingCodeUrl = '{{ route('cabinet.phoneVerification.sendPhoneChangingCode') }}';
        window.phoneChangingUrl = '{{ route('cabinet.phoneVerification.change') }}';
        window.emailChangingCodeUrl = '{{ route('cabinet.emailVerification.sendEmailChangingCode') }}';
        window.emailChangingUrl = '{{ route('cabinet.emailVerification.change') }}';
    </script>
    <script src="{{ asset('js/personal-info.js') }}"></script>
@endsection
