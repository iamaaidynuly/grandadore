@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'Login')

@section('content')
    @if (session()->has('success'))
        <div class="w-50 d-flex justify-content-center">
            {!! session()->get('success')!!}
        </div>
    @endif

    <div class="container-fluid">
        <div class="registration__block">
            <div>
                <h1 class="register">{{ t('Register Page.register') }}</h1>

                <div class="d-flex flex-row reg-soc-group">
                    <div class="reg-mail reg-login-soc-icon">
                        <i class="fas fa-at"></i>
                    </div>

                    <div class="reg-facebook reg-login-soc-icon">
                        <i class="fab fa-facebook-square"></i>
                    </div>

                    <div class="reg-gmail reg-login-soc-icon">
                        <i class="fab fa-google"></i>
                    </div>
                </div>
                <h1 class="register mt-3"><a style="text-decoration: none; color: #0a0302"
                                             href="{{ url('login') }}">{{ t('login.authorize')  }}</a></h1>
            </div>

            <form class="registration__form" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="w-100">
                    @if($errors->has('message'))
                        <span class="input-alert" style="color: red">{{ $errors->first('email') }}</span>
                    @endif
                    <div class="form-group">
                        <input type="text" class="form-control registration__input" name="email_or_phone" id="email"
                               placeholder="{{ t('Register Page.Email') }}" value="{{ old('email_or_phone') }}">
                        @if($errors->has('email_or_phone'))
                            <span class="input-alert" style="color: red">{{ $errors->first('email_or_phone') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control registration__input" name="password" id="password"
                               placeholder="{{ t('Register Page.Password') }}">
                        @if($errors->has('password'))
                            <span class="input-alert" style="color: red">{{ $errors->first('password') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control registration__input" name="password_confirmation"
                               id="password_confirmation" placeholder="{{ t('Register Page.Confirm password') }}">
                        @if($errors->has('password_confirmation'))
                            <span class="input-alert" style="color: red">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                </div>

                @php($oferta =\App\Models\Page::getStaticPage('oferta'))
                <div class="button-check">
                    @if(session()->has('message'))
                        <div class="justify-content-center" style="color: red">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    <div class="form-group form-check mt-4">
                        @if($oferta)
                            <label class="form-check-label" for="ofertaCheck">
                                {{ t('Register Page.Oferta text') }} <a
                                        href="{{ route('page', ['url' => $oferta->url]) }}"
                                        target="_blank">{{ t('Register Page.Oferta href') }}</a>
                            </label>
                            <input type="checkbox" class="form-check-input" id="ofertaCheck" name="agree"
                                   value="1"{{ old('agree') ? ' checked' : '' }}>
                        @endif
                        @if($errors->has('agree'))
                            <span class="input-alert">{{ t('Register Page.Oferta unchecked') }}</span>
                        @endif
                    </div>

                    <button type="button" class="btn registr-btn">{{ t('Register Page.register') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registration.css') }}">
@endpush

@push('js')
    <script>
        let ofertaCheck = document.querySelector('#ofertaCheck');
        const but = document.querySelector('.registr-btn');
        const checkLabel = document.querySelector('.form-check-label');

        if (ofertaCheck.checked == true) {
            but.type = 'submit';
        } else {
            but.type = 'button';
        }

        ofertaCheck.addEventListener('change', function () {
            if (ofertaCheck.checked == true) {
                but.type = 'submit';
            } else {
                but.type = 'button';
            }
        })

        const register = document.querySelector('.registr-btn');
        register.addEventListener('click', function () {
            if (ofertaCheck.checked == true) {
                checkLabel.style.color = 'black'
            } else {
                checkLabel.style.color = 'red'
            }
        })
    </script>
@endpush
