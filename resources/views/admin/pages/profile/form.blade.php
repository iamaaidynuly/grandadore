@extends('admin.layouts.app')
@section('content')
    <form action="{{ route('admin.profile.main')}}" method="post" autocomplete="off">
        @csrf @method('patch')
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="c-title">Имя</div>
                    <div class="little-p">
                        <input type="text" name="name" class="form-control" autocomplete="name" placeholder="Имя"
                               value="{{ old('name', $user->name) }}">
                    </div>
                </div>
                <div class="card">
                    <div class="c-title">Эл.почта</div>
                    <div class="little-p">
                        <input type="text" name="email" class="form-control" autocomplete="email" placeholder="Эл.почта"
                               value="{{ old('email', $user->email) }}">
                    </div>
                </div>
                <div class="card">
                    <div class="c-title">Текуший пароль</div>
                    <div class="little-p">
                        <input type="password" name="current_password" class="form-control" autocomplete="new-password"
                               placeholder="Текуший пароль">
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="c-title">Пароль</div>
                    <div class="little-p">
                        <input class="labelauty toggle-bottom-input" type="checkbox" name="change_password" value="1"
                               data-labelauty="Изменить пароль" {!! oldCheck('change_password', false) !!}>
                        <div class="bottom-input" style="max-height:100px;">
                            <input type="password" style="margin-top:8px;" autocomplete="new-password"
                                   name="new_password" class="form-control" placeholder="Новый пароль">
                            <input type="password" style="margin-top:8px;" autocomplete="new-password-confirmation"
                                   name="new_password_confirmation" class="form-control" placeholder="Повторите пароль">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 save-btn-fixed">
                <button type="submit"></button>
            </div>
        </div>
    </form>
@endsection
