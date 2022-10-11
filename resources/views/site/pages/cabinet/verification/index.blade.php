@extends('site.pages.cabinet.cabinet_layout')

@section('cabinetContent')
    <div class="align-items-center d-flex flex-column mt-4 w-100">
        <div class="login-section">
            <form method="POST" action="{{ route('cabinet.phoneVerification.verify') }}" class="w-100 d-contents">
                @csrf
                <p class="sub-text mb-0">Код для подтверждения отправлен на указанный номер</p>
                <div class="input-group my-2">
                    <input type="text" class="form-control" placeholder="Введите полученный код" name="code"
                           value="{{ old('code') }}">
                    @if($errors->has('code'))
                        <span class="input-alert">{{ $errors->first('code') }}</span>
                    @endif
                </div>
                <button type="submit" class=" btn login-btn">Подтвердить</button>
            </form>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush
