@extends('admin.layouts.app')
@section('title', 'Редактирование '.($item->type == 0 ? 'пользователя' : 'бутика'))
@section('content')

    <form action="{{ route('admin.users.update', ['id' => $item->id]) }}" method="post"
          enctype="multipart/form-data">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="row">
            <div class="col-12 col-lg-7">
                <div class="card p-2">
                    <label for="name">Имя</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Имя"
                           value="{{ $item->name ?? old('name')}}">
                </div>
                @labelauty(['id'=>'active', 'label'=>'Неактивно|Активно', 'checked' => $item->active])@endlabelauty
                <div class="card p-2">
                    <label for="email">Эл.почта</label>
                    <input type="text" max="255" id="email" name="email" class="form-control"
                           placeholder="Эл.почта" value="{{ $item->email ?? old('email') }}">
                </div>
                <div class="card p-2">
                    <label for="email">Часы работы</label>
                    <input type="text" max="255" id="work_hours" name="work_hours" class="form-control"
                           placeholder="Часы работы" value="{{ $item->work_hours ?? old('work_hours') }}">
                </div>
                <div class="card p-2">
                    <label for="email">Вебсайт</label>
                    <input type="text" max="255" id="website" name="website" class="form-control"
                           placeholder="Вебсайт" value="{{ $item->website ?? old('website') }}">
                </div>
                <div class="card p-2">
                    <label for="description">Описание</label>
                    <textarea class="ckeditor" name="description" id="description" cols="30" rows="10">{{ $item->description ?? old('description') }}</textarea>
                </div>
                <div class="card p-2">
                    <label for="password">Пароль</label>
                    <input class="form-control" type="password" max="255" id="password" name="password"
                           placeholder="Пароль" value="{{ old('password') }}">
                </div>
                <div class="card p-2">
                    <label for="confirm_password">Повтарите Пароль</label>
                    <input class="form-control" type="password" max="255" id="confirm_password"
                           name="password_confirmation" placeholder="Пароль" value="{{ old('confirm_password') }}">
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="c-title">Логотип (200х200)</div>
                    @if(!empty($item->logo))
                        <div class="p-2 text-center">
                            <img src="{{ asset('u/users/thumbs/'.$item->logo) }}" alt="" class="img-responsive">
                        </div>
                    @endif
                    <div class="c-body">
                        @file(['name'=>'logo'])@endfile
                    </div>
                </div>
                <div class="card">
                    <div class="c-title">Изображение (1200х680)</div>
                    @if(!empty($item->image))
                        <div class="p-2 text-center">
                            <img src="{{ asset('u/users/thumbs/'.$item->image) }}" alt="" class="img-responsive">
                        </div>
                    @endif
                    <div class="c-body">
                        @file(['name'=>'image'])@endfile
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 save-btn-fixed">
                <button type="submit"></button>
            </div>
        </div>
    </form>
@endsection
@push('js')
    @ckeditor
@endpush
