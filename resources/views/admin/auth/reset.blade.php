@extends('admin.auth.layout')
@section('form_action', route('admin.password.reset'))
@section('content')
    @if (session('reset_link_sent'))
        <div class="form-text root success">Письмо со ссылкой восстановления доступа отправлено.</div>
    @endif
    <div class="form-group">
        <label for="auth-email">Адрес эл.почты</label>
        <input type="text" name="email" autocomplete="admin-email" aria-describedby="emailHelp" class="form-control"
               id="auth-email" placeholder="Адрес эл.почты" value="{{ old('email') }}">
        @if ($errors->has('email'))
            <small id="emailHelp" class="form-text">{!! $errors->first('email') !!}</small>
        @endif
    </div>
    <div class="auth-submit">
        <div class="auth-left">
            <a href="{!! route('admin.login') !!}" class="auth-link">&Lt; Назад</a>
        </div>
        <div class="auth-right">
            <button type="submit" class="btn btn-astudio">Продолжить</button>
        </div>
    </div>
@endsection
