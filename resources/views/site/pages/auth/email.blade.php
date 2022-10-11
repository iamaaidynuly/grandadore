@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'reset')

@section('content')
    <div class="container-fluid">
        <div class="registration__block">
            <div>
                <h1 class="register">{{ t('Reset Page.Reset Password') }}</h1>
            </div>

            <form class="registration__form" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="w-100">
                    <div class="form-group">
                        <input type="email" class="form-control registration__input" name="email" id="email" placeholder="{{ t('Reset Page.Email') }}" value="{{ old('email') }}">
                        @if($errors->has('email'))
                            <span class="input-alert">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="button-check">
                        <button type="submit" class="btn registr-btn">{{ t('Reset Page.Reset Button') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{--<div class="container">
        --}}{{--            <div class="login-section-bar">--}}{{--
        <div class="d-flex justify-content-center align-items-center flex-column w-100 my-5">
            <div class="login-section ">
                <form action="{{ route('password.email') }}" method="post" class="w-100 d-contents">
                    @csrf
                    <h2 class="color-grey" >Введите Эл. почту</h2>
                    <div class="login-input">
                        <div class="input-group my-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <svg id="Group_5700" data-name="Group 5700" xmlns="http://www.w3.org/2000/svg" width="13.701" height="13.701" viewBox="0 0 13.701 13.701">
                                        <path id="Path_11354" data-name="Path 11354" d="M11.7,2.007a6.851,6.851,0,1,0-9.688,9.688,6.855,6.855,0,0,0,8.1,1.187.412.412,0,0,0-.392-.725,6.027,6.027,0,1,1,3.166-5.306,5.945,5.945,0,0,1-.362,2.07,1.351,1.351,0,0,1-1.065.743A1.132,1.132,0,0,1,10.32,8.533V4.188a.412.412,0,1,0-.824,0v.421a3.468,3.468,0,1,0,.061,4.41,1.958,1.958,0,0,0,1.894,1.469,2.166,2.166,0,0,0,1.8-1.2A6.605,6.605,0,0,0,13.7,6.851,6.806,6.806,0,0,0,11.7,2.007ZM6.852,9.495A2.644,2.644,0,1,1,9.5,6.851,2.647,2.647,0,0,1,6.852,9.495Z" transform="translate(-0.001 0)" fill="#858585"/>
                                    </svg>
                                </div>
                            </div>
                            <input type="email" class="form-control" id="inlineFormInputGroup" placeholder="Эл. почта *" name="email">
                            @if($errors->has('email'))
                                <span class="input-alert">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn login-btn">Восстановить</button>
                </form>
                <div class="forgot-pass-text">
                    <a href="{{ route('register') }}">У Вас нет аккаунта ? Регистрация</a>
                </div>
            </div>
        </div>--}}

@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registration.css') }}">
@endpush
