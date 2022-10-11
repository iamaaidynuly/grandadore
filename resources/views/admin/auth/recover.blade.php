@extends('admin.auth.layout')
@section('form_action', e(route('admin.password.recover', ['email'=>$email, 'token'=>$token])))
@section('content')
    <div class="form-group">
        <label for="auth-password">Адрес эл. почты</label>
        <input type="text" class="form-control" value="{{ $email }}" disabled>
    </div>
    <div class="form-group">
        <label for="auth-password">Новый пароль</label>
        <input type="password" name="new_password" autocomplete="new-password" aria-describedby="newPasswordHelp"
               class="form-control" placeholder="Новый пароль">
        @if ($errors->has('new_password'))
            <small id="newPasswordHelp" class="form-text">{!! $errors->first('new_password') !!}</small>
        @endif
    </div>
    <div class="form-group">
        <label for="auth-password">Новый пароль</label>
        <input type="password" name="new_password_confirmation" autocomplete="new-password-confirmation"
               class="form-control" placeholder="Повторите пароль">
    </div>
    <div class="auth-submit">
        <div class="auth-left"></div>
        <div class="auth-right">
            <button type="submit" class="btn btn-astudio">Восстановить</button>
        </div>
    </div>
@endsection
