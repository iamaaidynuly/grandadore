@extends('admin.auth.layout')
@section('form_action', route('admin.login'))
@section('content')
    @if($errors->has('root'))
        <div class="form-text root">{!! $errors->first('root') !!}</div>
    @endif
    <div class="form-group">
        <label for="auth-email">Адрес эл.почты</label>
        <input type="text" name="email" autocomplete="admin-email" aria-describedby="emailHelp" class="form-control"
               id="auth-email" placeholder="Адрес эл.почты" value="{{ old('email') }}">
        @if($errors->has('email'))
            <small id="emailHelp" class="form-text">{!! $errors->first('email') !!}</small>
        @endif
    </div>
    <div class="form-group">
        <label for="auth-password">Пароль</label>
        <input type="password" name="password" autocomplete="admin-password" aria-describedby="passwordHelp"
               class="form-control" id="auth-password" placeholder="Пароль">
        @if($errors->has('password'))
            <small id="passwordHelp" class="form-text">{!! $errors->first('password') !!}</small>
        @endif
    </div>
    <div class="auth-submit">
        <div class="auth-left">
            <a href="{!! route('admin.password.reset') !!}" class="auth-link">Забыли пароль?</a>
        </div>
        <div class="auth-right">
            <button type="submit" class="btn btn-astudio">Войти</button>
        </div>
    </div>
@endsection
