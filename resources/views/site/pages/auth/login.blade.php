@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'Login')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
    <div class="container" >
        <div class="d-flex justify-content-center align-items-center flex-column w-100 my-5 ">
            <div class="login-section ">
                <form action="" class="w-100 d-contents" action="{{ route('login.post') }}" method="post">
                    @csrf
{{--                    <img src="{{ asset('images/logo.svg') }}" class="header__logo">--}}
                    <div class="w-100">
                        <div class="input-group my-3">

                            <input type="text" class="form-control registration__input" id="inlineFormInputGroup"
                                   placeholder="Эл. почта" name="email" value="{{ old('email') }}">
                            @if($errors->has('email'))
                                <span class="input-alert">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="input-group  mb-3">

                            <input type="password" class="form-control registration__input" id="inlineFormInputGroup" placeholder="Пароль" name="password">
                            @if($errors->has('password'))
                                <span class="input-alert">{{ $errors->first('password') }}</span>
                            @endif
                            @if($errors->has('global'))
                                <p class="text-center input-alert mt-3" style="color: red">
                                    {{ $errors->first('global') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class=" btn login-btn">Войти</button>
                </form>
                <div class="forgot-pass-text">
                    <a href="{{ url('reset') }}">Забыли пароль?</a>
                </div>
                <div class="or-text mt-4">
                    <span> или</span>
                </div>
                <div class="login-btn btn">
                    <a href="{{ url('register') }}"><span>Регистрация</span> </a>
                </div>
            </div>
        </div>
    </div>



@endsection
