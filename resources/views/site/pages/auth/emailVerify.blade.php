@extends('site.pages.cabinet.cabinet_layout')
@if(session()->has('message'))
    @push('js')
        <script>
            location.reload();
        </script>
    @endpush
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
@section('cabinetContent')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-12">
                <div class="card">
                    <div class="card-body">
                        @if(session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('На ваш адрес электронной почты была отправлена новая ссылка для подтверждения.') }}
                            </div>
                        @endif

                        {{ __('Для доступа к возможностям личного кабинета вам нужно подтвердить ваш адрес эл. почты.') }}
                        {{ __('Если вы не получили сообщение по эл. почте:') }}
                        <form class="d-inline" method="POST" action="{{ route('cabinet.emailVerification.resend') }}">
                            @csrf
                            <button type="submit"
                                    class="btn btn-link p-0 m-0 align-baseline">{{ __('нажмите здесь для запроса нового') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
