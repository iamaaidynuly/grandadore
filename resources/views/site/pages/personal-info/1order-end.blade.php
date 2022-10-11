@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'personal-info')

@section('content')
    <div class="container">
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
            <div class="personal-inforamtion w-100 ml-lg-5 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28">
                    <g id="Group_5947" data-name="Group 5947" transform="translate(-936 -671)">
                        <rect id="Rectangle_1395" data-name="Rectangle 1395" width="11" height="11" transform="translate(936 671)" fill="#212121" opacity="0.4"/>
                        <rect id="Rectangle_1396" data-name="Rectangle 1396" width="10" height="11" transform="translate(954 671)" fill="#212121"/>
                        <rect id="Rectangle_1397" data-name="Rectangle 1397" width="11" height="10" transform="translate(936 689)" fill="#212121" opacity="0.63"/>
                        <rect id="Rectangle_1398" data-name="Rectangle 1398" width="10" height="10" transform="translate(954 689)" fill="#212121" opacity="0.8"/>
                    </g>
                </svg>

                <h1 class="mb-5">Личная информация</h1>
               <h2> Ваш заказ отправлен на подтверждение
                   Скоро наш оператор свяжется с вами</h2>
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
